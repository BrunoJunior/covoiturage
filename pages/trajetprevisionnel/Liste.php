<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\trajetprevisionnel;

// Service de vue
use covoiturage\classes\abstraites\ServiceVue;
// BP
use covoiturage\classes\presentation\TrajetPrevisionnel as BP;
// BO
use covoiturage\classes\metier\Group as GroupBO;
// Helpers
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Liste
 *
 * @author bruno
 */
class Liste extends ServiceVue {

    /**
     * Affiche le contenu de la vue
     */
    public function executerService() {
        $group = new GroupBO(HRequete::getPOST('group_id'));
        $user = $this->getUser();
        echo BP::getHtmlTable($group, $user);
    }

    /**
     * Titre de la vue
     * @return string
     */
    public function getTitre() {
        return 'Liste des trajets prévisionnels';
    }

    /**
     * La vue ne doit pas retourner une page HTML complète
     * @return boolean
     */
    public function isComplete() {
        return FALSE;
    }

}
