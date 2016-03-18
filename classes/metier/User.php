<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\metier;

// DAO
use covoiturage\classes\dao\User as DAO;

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
}
