<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\group;

// Vue
use covoiturage\classes\abstraites\ServiceVue;
// BO
use covoiturage\classes\metier\Group as BO;
// BP
use covoiturage\classes\presentation\Covoiturage as CovoiturageBP;
// Helpers
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Liste
 *
 * @author bruno
 */
class Trajet extends ServiceVue {

    /**
     * Affichage des trajets
     * @throws Exception
     */
    public function executerService() {
        $group = new BO(HRequete::getPOST('id'));
        $user = $this->getUser();
        if (!$group->isUserPresent($user) && !$user->admin) {
            throw new Exception('Vous n\'êtes pas autorisé à visualiser cette page !');
        }
        echo '<div id="cov-group-trajet"><div class="panel panel-primary"><div class="panel-heading"><h3 class="panel-title">Nouveau trajet</h3></div><div class="panel-body">';
        echo CovoiturageBP::getForm($group);
        echo '</div></div>';
        echo CovoiturageBP::getHtmlTableCond($group, $user, 10);
        if (!$user->admin) {
            echo CovoiturageBP::getHtmlTablePass($group, $user, 10);
        }
        echo "</div>";
    }

    public function getTitre() {
        $group = new BO(HRequete::getPOST('id'));
        return $group->nom . ' - Trajets';
    }

}
