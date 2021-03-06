<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\trajetprevisionnel;

use covoiturage\classes\abstraites\Service;
use covoiturage\classes\metier\TrajetPrevisionnel as BO;
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Delete
 *
 * @author bruno
 */
class Delete extends Service {

    /**
     * Validation d'un trajet prévisionnel
     */
    public function executerService() {
        $trajet = new BO(HRequete::getPOSTObligatoire('id'));
        $idConducteur = $trajet->conducteur_id;
        if ($this->getUser()->id != $idConducteur) {
            throw new Exception("Vous n'êtes pas autorisé à exécuter ce service !");
        }
        $trajet->supprimer();
        $this->setMessage("Trajet supprimé");
    }

}
