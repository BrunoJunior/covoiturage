<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\group;

use covoiturage\classes\abstraites\Service;
use covoiturage\classes\presentation\Group;

/**
 * Description of Liste
 *
 * @author bruno
 */
class Liste extends Service {

    public function executerService() {
        $groups = Group::getListe();

        echo "<div id='cov-group-list'>
                <div class='row'> ";

        foreach ($groups as $group) {
            echo $group->getTuile();
        }
        echo Group::getTuileAdd();

        echo "  </div>
              </div>";
    }

    public function getTitre() {
        return 'Liste des groupes';
    }

}
