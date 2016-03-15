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
use Exception;

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
        $user = $this->getUser();
        if (!$this->group->isUserAdminGroup($user) && !$user->admin) {
            throw new Exception('Vous n\'êtes pas autorisé à modifier ce groupe !');
        }
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
        }
    }

}
