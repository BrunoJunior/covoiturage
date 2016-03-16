<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\metier;

use covoiturage\classes\dao\Passager as PassagerDAO;
use Exception;

/**
 * Description of Passager
 *
 * @author bruno
 */
class Passager extends PassagerDAO {
    protected function avantAjout() {
        $idConducteur = $this->getCovoiturage()->conducteur_id;
        if ($this->user_id == $idConducteur) {
            throw new Exception('Le conducteur ne peut être passager !');
        }
    }
}
