<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\group;

use covoiturage\classes\abstraites\ServiceVue;
// BO
use covoiturage\classes\metier\Group as BO;
// BP
use covoiturage\classes\presentation\Group as BP;
// Helpers
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Contact
 *
 * @author bruno
 */
class Contact extends ServiceVue {

    /**
     * @var BO
     */
    private $group;

    /**
     * Récupération du groupe
     */
    protected function avantExecuterService() {
        $this->group = new BO(HRequete::getPOSTObligatoire('id'));
        if (!$this->getUser()->isDansGroupe($this->group)) {
            throw new Exception('Vous ne pouvez contacter ce groupe !');
        }
    }

    /**
     * Formulaire de contact
     */
    public function executerService() {
        echo BP::getContactForm($this->group);
    }

    /**
     * Titre
     */
    public function getTitre() {
        return $this->group->nom . ' - Envoyer un message';
    }

    /**
     * Validation des champs obligatoires
     * @return boolean
     */
    protected function isFormValidation() {
        return TRUE;
    }
}
