<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\covoiturage;
// BO
use covoiturage\classes\metier\Covoiturage as BO;
// Helpers
use covoiturage\utils\HRequete;
/**
 * Description of Delete
 *
 * @author bruno
 */
class Delete extends \covoiturage\classes\abstraites\Service {
    /**
     * Supprimer un trajet
     */
    public function executerService() {
        $trajet = new BO(HRequete::getPOSTObligatoire('id'));
        $trajet->supprimer();
        $this->setMessage('Suppression effectuée !');
    }
}
