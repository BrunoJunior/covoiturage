<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\passagerprevisionnel;

use covoiturage\classes\abstraites\Service;
use covoiturage\utils\HRequete;
use covoiturage\classes\metier\PassagerPrevisionnel as BO;
use Exception;

/**
 * Description of Valider
 *
 * @author bruno
 */
class Valider extends Service {

    /**
     * Validation d'un passager d'un trajet prévisionnel
     */
    public function executerService() {
        $passager = new BO(HRequete::getPOSTObligatoire('id'));
        $idConducteur = $passager->getTrajetPrevisionnel()->conducteur_id;
        if ($this->getUser()->id != $idConducteur) {
            throw new Exception("Vous n'êtes pas autorisé à exécuter ce service !");
        }
        $passager->valider();
        $this->setMessage("Passager validé");
    }

}
