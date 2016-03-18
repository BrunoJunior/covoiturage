<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\covoiturage;

// Service de vue
use covoiturage\classes\abstraites\ServiceVue;
// BP
use covoiturage\classes\presentation\Covoiturage as BP;
// Helpers
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Liste
 *
 * @author bruno
 */
class Edit extends ServiceVue {

    /**
     * Affiche le contenu de la vue
     */
    public function executerService() {

    }

    /**
     * Titre de la vue
     * @return string
     */
    public function getTitre() {
        return 'Edition d\'un trajet';
    }

    /**
     * La vue ne doit pas retourner une page HTML complète
     * @return boolean
     */
    public function isComplete() {
        return TRUE;
    }

}
