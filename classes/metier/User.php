<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\metier;

// DAO
use covoiturage\classes\dao\User as DAO;
// Helpers
use covoiturage\utils\HMail;
use covoiturage\utils\HSession;
use DateTime;
use Exception;

/**
 * Description of User
 *
 * @author bruno
 */
class User extends DAO {

    /**
     * Nb de fois conducteur
     * @param Group $group
     * @return int
     */
    public function getNbVoyageConducteur($group = NULL) {
        return $this->getListeCovoiturage($group, NULL, 0, 1, static::MODE_COUNT, FALSE);
    }

    /**
     * Le mot de passe envoyé est-il correcte
     * @param string $password
     * @return boolean
     */
    public function checkPassword($password) {
        if (empty($this->password) && empty($password)) {
            return TRUE;
        }
        return password_verify($password, $this->password);
    }

    /**
     * Définir le mot de passe
     * @param string $password
     */
    public function setPassword($password) {
        $this->token = '';
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Affichage simpel d'un utilisateur
     * Prénom Nom
     * @return string
     */
    public function toHtml() {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * L'utilisateur fait-il parti du groupe
     * @param Group $group
     * @return boolean
     */
    public function isDansGroupe(Group $group) {
        return $group->isUserPresent($this);
    }

    /**
     * Combien de fois un utilisateur a-t-il été conducteur d'un autre
     * @param Group $group
     * @param User $user
     * @return int
     */
    public function getNbConducteurDe(Group $group, User $user) {
        return $this->getListeCovoiturage($group, $user, 0, 1, static::MODE_COUNT);
    }

    /**
     * Combien de fois un utilisateur a-t-il été passager d'un autre
     * @param Group $group
     * @param User $user
     * @return int
     */
    public function getNbPassagerDe(Group $group, User $user) {
        return $this->getListeCovoituragePassager($group, $user, 0, 1, static::MODE_COUNT);
    }

    /**
     * Score de l'utilsiateur dans un groupe
     * @param Group $group
     * @return type
     */
    public function getScore(Group $group) {
        return $this->getCreditsConducteur($group->id) - $this->getCreditsPassager($group->id);
    }
    
    /**
     * Vérifier si l'adresse email est déjà utilisée
     * @throws Exception
     */
    private function checkEmail() {
        $user = static::chargerParEmail($this->email);
        if ($user->existe() && $this->id != $user->id) {
            throw new Exception('Cette adresse email est déjà utilisée !');
        }
    }

    /**
     * Interdiction de création si email déjà utilisé
     */
    protected function avantAjout() {
        $this->checkEmail();
    }

    /**
     * Interdiction de modification si email déjà utilisé
     */
    protected function avantModification() {
        $this->checkEmail();
    }

    /**
     * Contacter un membres
     * @param string $sujet
     * @param string $message
     * @throws Exception
     * @return boolean Description
     */
    public function contacter($sujet, $message) {
        $connectedUser = HSession::getUser();
        if (!$connectedUser->existe()){
            $connectedUser->email = 'no-reply@co-voiturage.bdesprez.com';
            $connectedUser->prenom = 'Co-voiturage';
            $connectedUser->nom = '[Mot de passe]';
        }
        if (empty($connectedUser->email)) {
            throw new Exception('Veuillez configurer votre adresse email !');
        }
        if ($connectedUser->id == $this->id) {
            throw new Exception('Vous ne pouvez vous contacter vous-même !');
        }
        if (empty($this->email)) {
            throw new Exception('Cet utilisateur n\'a pas renseigné son adresse email !');
        }
        return HMail::envoyer($connectedUser, $this->email, $sujet, $message);
    }

    /**
     * Essaie d'obtenir un token pour envoyer un oubli de mot de passe
     * @return string
     * @throws Exception
     */
    public function getNewToken() {
        if (!empty($this->lastforgot)) {
            $dtForgot = DateTime::createFromFormat('Y-m-d H:i:s', $this->lastforgot);
            $dtNow = new DateTime('now');
            $diff = $dtNow->getTimestamp() - $dtForgot->getTimestamp();
            if ($diff < (86400)) {
                throw new Exception('Vous devez attendre 24h avant de pouvoir générer un nouveau mot de passe !');
            }
        }
        $this->token = HMail::getToken();
        $this->lastforgot = date('Y-m-d H:i:s');
        $this->merger();
        return $this->token;
    }

    /**
     * Vérification du token passé en paramètre
     * @param string $token
     * @throws Exception
     */
    public function checkToken($token) {
        if (empty($this->token) || empty($token) || $this->token != $token) {
            throw new Exception('Erreur d\'authentification');
        }
    }

    /**
     * Obtenir le token de vérification
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * Supprimer les associations Groupe - Utilisateur
     * avant la suppression de l'utilisateur
     */
    protected function avantSuppression() {
        $userGroups = $this->getListeUserGroup();
        foreach ($userGroups as $userGroup) {
            $userGroup->supprimer();
        }
    }
}
