<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\group;

// Service traitement
use covoiturage\classes\abstraites\Service;
// BO
use covoiturage\classes\metier\Group as BO;
use covoiturage\classes\metier\User as UserBO;
use covoiturage\classes\metier\UserGroup as UserGroupBO;
// BP
use covoiturage\classes\presentation\UserGroup as UserGroupBP;
// Helpers
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Adduser
 *
 * @author bruno
 */
class Adduser extends Service {

    /**
     * Ajout d'un utilisateur dans un groupe
     * @throws Exception
     */
    public function executerService() {
        $group = new BO(HRequete::getPOST('id'));
        $user = $this->getUser();
        if (!$user->admin && !$group->isUserAdminGroup($user)) {
            throw new Exception("Vous n'êtes pas autorisé à effectuer cette action !");
        }
        $idNewUser = HRequete::getPOST('user_id');
        if (empty($idNewUser)) {
            $nomNewUser = HRequete::getPOSTObligatoire('user_nom1');
            $prenomNewUser = HRequete::getPOSTObligatoire('user_prenom1');
            $newUser = UserBO::chargerParNomEtPrenom($nomNewUser, $prenomNewUser);
            if ($newUser->existe()) {
                throw new Exception("Ce covoitureur existe déjà ! Veuillez utiliser la liste de sélection !");
            }
            $newUser->setPassword($newUser->nom . '.' . $newUser->prenom);
            $newUser->merger();
        } else {
            $newUser = new UserBO($idNewUser);
            if (!empty($idNewUser) && !$newUser->existe()) {
                throw new Exception("Utilisateur inconnu !");
            }
        }
        if ($newUser->isDansGroupe($group)) {
            throw new Exception("Ce covoitureur est déjà présent dans le groupe !");
        }
        $userGroup = new UserGroupBO();
        $userGroup->group_admin = FALSE;
        $userGroup->group_id = $group->id;
        $userGroup->user_id = $newUser->id;
        $userGroup->merger();
        $this->addResponseItem('form-group', UserGroupBP::getLigneForm($userGroup));
    }

}
