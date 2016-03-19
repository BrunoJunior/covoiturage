<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\group;

use covoiturage\classes\abstraites\Service;
// BO
use covoiturage\classes\metier\Group as BO;
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
     * Prise en compte du formulaire de contact
     */
    public function executerService() {
        $titre = HRequete::getPOSTObligatoire('group_cont_titre');
        $message = HRequete::getPOSTObligatoire('group_cont_message');
        $ok = $this->group->contacter($titre, strip_tags($message));
        if (!$ok) {
            throw new Exception('Erreur lors de l\'envoi du message !');
        }
        $this->setMessage('Message envoyé');
    }
}
