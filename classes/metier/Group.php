<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\metier;

// DAO
use covoiturage\classes\dao\Group as DAO;

/**
 * Description of Group
 *
 * @author bruno
 */
class Group extends DAO {

    /**
     * L'utilisateur est-il présent dans le groupe
     * @param User $user
     * @return boolean
     */
    public function isUserPresent(User $user) {
        $userGroup = $this->getUserGroup($user);
        return $userGroup->existe();
    }

    /**
     * L'utilisateur est-il administrateur du groupe
     * @param User $user
     * @return boolean
     */
    public function isUserAdminGroup(User $user) {
        $userGroup = $this->getUserGroup($user);
        return $userGroup->group_admin;
    }

    /**
     * Obtenir l'utilisateur qui devrait être conducteur
     * pour le prochain trajet dans le groupe
     * @return User
     */
    public function getProchainConducteurPropose() {
        $userGroups = $this->getListeUserGroup();
        $conducteur = NULL;
        $creditMin = 0;
        foreach ($userGroups as $userGroup) {
            $user = $userGroup->getUser();
            $credit = $user->getScore($this);
            if ($conducteur === NULL || $credit < $creditMin) {
                $conducteur = $user;
                $creditMin = $credit;
            }
        }
        return $conducteur;
    }

    /**
     * Obtenir l'utilisateur qui a le plus souvent
     * été conducteur dans le groupe
     * @return User
     */
    public function getConducteurRecurrent() {
        $userGroups = $this->getListeUserGroup();
        $conducteur = NULL;
        $creditMin = 0;
        foreach ($userGroups as $userGroup) {
            $user = $userGroup->getUser();
            $credit = $user->getScore($this);
            if ($conducteur === NULL || $credit > $creditMin) {
                $conducteur = $user;
                $creditMin = $credit;
            }
        }
        return $conducteur;
    }

    /**
     * Obtenir le récapitulatif du groupe
     * Tableau de la forme [id_conducteur => [id_passager => nb de trajet]]
     * Soit une ligne par couple conducteur / passager
     * @return array
     */
    public function getRecapitulatif() {
        $recap = parent::getRecapitulatif();
        $recapFinal = [];
        foreach ($recap as $row) {
            $recapFinal[$row['conducteur_id']][$row['user_id']] = $row['nb'];
        }
        return $recapFinal;
    }

}
