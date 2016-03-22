<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\metier;

use Exception;

// DAO
use covoiturage\classes\dao\TrajetPrevisionnel as DAO;

/**
 * Description of TrajetPrevisionnel
 *
 * @author bruno
 */
class TrajetPrevisionnel extends DAO {
    const TYPE_ALLER = 0;
    const TYPE_RETOUR = 1;
    const TYPE_ALLER_RETOUR = 2;


    /**
     * Supprimer les passager avant la suppression du trajet
     */
    protected function avantSuppression() {
        $passagers = $this->getListePassagers();
        foreach ($passagers as $passager) {
            $passager->supprimer();
        }
    }
}
