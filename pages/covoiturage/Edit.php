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
// BO
use covoiturage\classes\metier\Covoiturage as BO;
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
        if (!$this->getUser()->admin) {
            throw new Exception('Vous n\'êtes pas autorisé à visualiser cette page !');
        }
        $covoiturage = new BO(HRequete::getPOSTObligatoire('id'));
        echo '<div id="cov-trajet"><div class="panel panel-primary"><div class="panel-heading"><h3 class="panel-title">Edition d\'un trajet</h3></div><div class="panel-body">';
        echo BP::getEditForm($covoiturage);
        echo '</div></div>';
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
