<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\group;

use covoiturage\classes\abstraites\ServiceVue;
use covoiturage\classes\presentation\Group;
use covoiturage\utils\HRequete;
use Exception;
use covoiturage\classes\presentation\Covoiturage as CovoiturageBP;

/**
 * Description of Liste
 *
 * @author bruno
 */
class Trajet extends ServiceVue {

    public function executerService() {
        $group = new Group(HRequete::getPOST('id'));
        $numPage = intval (HRequete::getPOST('num_page', 1));
        $user = $this->getUser();
        if (!$group->isUserPresent($user) && !$user->admin) {
            throw new Exception('Vous n\'êtes pas autorisé à visualiser cette page !');
        }
        echo "<div id='cov-group-trajet'>";
        echo '<div class="panel panel-primary">
                <div class="panel-heading"><h3 class="panel-title">Nouveau trajet</h3></div>
                <div class="panel-body">' .
        CovoiturageBP::getForm($group) .
        '       </div>
              </div>';
        echo CovoiturageBP::getHtmlTableCond($group, $user, 10);
        if (!$user->admin) {
            echo CovoiturageBP::getHtmlTablePass($group, $user, 10);
        }
        echo "</div>";
    }

    public function getTitre() {
        $group = new Group(HRequete::getPOST('id'));
        return $group->nom . ' - Trajets';
    }

}
