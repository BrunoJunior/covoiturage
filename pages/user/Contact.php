<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\user;

use covoiturage\classes\abstraites\ServiceVue;
// BO
use covoiturage\classes\metier\User as BO;
// BP
use covoiturage\classes\presentation\User as BP;
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
    private $destinataire;

    /**
     * Récupération du groupe
     */
    protected function avantExecuterService() {
        $this->destinataire = new BO(HRequete::getPOSTObligatoire('id'));
        if ($this->getUser()->id == $this->destinataire->id) {
            throw new Exception('Vous ne pouvez vous contacter vous-même !');
        }
    }

    /**
     * Formulaire de contact
     */
    public function executerService() {
        echo BP::getContactForm($this->destinataire);
    }

    /**
     * Titre
     */
    public function getTitre() {
        return 'Envoyer un message à ' . $this->destinataire->toHtml();
    }
}
