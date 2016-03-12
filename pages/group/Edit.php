<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\group;

use covoiturage\classes\abstraites\ServiceVue;
use covoiturage\utils\HRequete;
use covoiturage\classes\presentation\Group as GroupBP;
use covoiturage\classes\metier\User as UserBO;
use covoiturage\classes\metier\UserGroup as UserGroupBO;

/**
 * Description of Add
 *
 * @author bruno
 */
class Edit extends ServiceVue {

    /**
     * Groupe lié
     * @var GroupBP
     */
    private $group;

    /**
     * Execution du service
     */
    public function executerService() {
        $id = HRequete::getPOST('id');
        $this->group = new GroupBP($id);
        $this->traiterSubmit();
        echo $this->group->getForm();
    }

    /**
     * Titre
     * @return string
     */
    public function getTitre() {
        return 'Création d\'un nouveau groupe';
    }

    /**
     * Traitement du formulaire
     */
    private function traiterSubmit() {
        if (HRequete::isParamPostPresent('submit')) {
            $this->group->nom = HRequete::getPOST('group_name');
            $this->group->merger();
            $clesNom = HRequete::getListeClePostCommencant('user_nom');
            foreach ($clesNom as $cle) {
                $nom = HRequete::getPOST($cle);
                $prenom = HRequete::getPOST('user_prenom' . substr($cle, 8));
                $user = UserBO::chargerParNomEtPrenom($nom, $prenom);
                if (!$user->existe()) {
                    $user->merger();
                }
                $userGroup = UserGroupBO::chargerParGroupeEtUser($this->group, $user);
                if ($userGroup->existe()) {
                    throw new Exception('L\'utilisateur ' . $prenom . ' ' . $nom . ' est déjà présent dans le groupe !');
                }
                $userGroup->merger();
            }
        }
    }

}
