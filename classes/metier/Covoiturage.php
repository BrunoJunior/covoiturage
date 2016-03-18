<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\metier;

use Exception;

// DAO
use covoiturage\classes\dao\Covoiturage as DAO;

/**
 * Description of Covoiturage
 *
 * @author bruno
 */
class Covoiturage extends DAO {
    const TYPE_ALLER = 0;
    const TYPE_RETOUR = 1;

    /**
     * Ne pas créer si un trajet existe déjà pour un groupe un type et une date donnés
     * @throws Exception
     */
    protected function avantAjout() {
        if ($this->isDejaPresent()) {
            throw new Exception('Covoiturage déjà présent à cette date !');
        }
        if ($this->date > date('Y-m-d')) {
            throw new Exception('Vous ne pouvez renseigner de trajet à venir !');
        }
    }
}
