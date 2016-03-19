<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\user;

use covoiturage\classes\abstraites\Service;
// BO
use covoiturage\classes\metier\User as BO;
// Helpers
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Contact
 *
 * @author bruno
 */
class Contact extends Service {

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
     * Prise en compte du formulaire de contact
     */
    public function executerService() {
        $titre = HRequete::getPOSTObligatoire('user_cont_titre');
        $message = HRequete::getPOSTObligatoire('user_cont_message');
        $ok = $this->destinataire->contacter($titre, strip_tags($message));
        if (!$ok) {
            throw new Exception('Erreur lors de l\'envoi du message !');
        }
        $this->setMessage('Message envoyé');
    }
}
