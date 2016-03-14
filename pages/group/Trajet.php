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
        if (!$group->isUserPresent($this->getUser()) && !$this->getUser()->admin) {
            throw new Exception('Vous n\'êtes pas autorisé à visualiser cette page !');
        }
        echo "<div id='cov-group-trajet'>";
        echo '<div class="panel panel-primary">
                <div class="panel-heading"><h4>Nouveau trajet</h4></div>
                <div class="panel-body">
                  FORMULAIRE TODO
                </div>
              </div>';
        echo '<div class="panel panel-info">
                <div class="panel-heading"><h4>Liste des trajets</h4></div>
                <div class="panel-body">
                  '.CovoiturageBP::getHtmlTable($group).'
                </div>
              </div>';
        echo "</div>";
    }

    public function getTitre() {
        $group = new Group(HRequete::getPOST('id'));
        return $group->nom . ' - Trajets';
    }

}
