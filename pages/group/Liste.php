<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\group;

use covoiturage\classes\abstraites\ServiceVue;
use covoiturage\classes\presentation\Group;

/**
 * Description of Liste
 *
 * @author bruno
 */
class Liste extends ServiceVue {

    public function executerService() {
        $groups = Group::getListe();
        $user = $this->getUser();

        echo "<div id='cov-group-list'>
                <div class='row'> ";

        foreach ($groups as $group) {
            if ($group->isUserPresent($user)) {
                echo $group->getTuile();
            }
        }
        if ($user->admin) {
            echo Group::getTuileAdd();
        }

        echo "  </div>
              </div>";
    }

    public function getTitre() {
        return 'Liste des groupes';
    }

}
