<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\group;

// Vue
use covoiturage\classes\abstraites\ServiceVue;
// BP
use covoiturage\classes\presentation\Group as BP;
// BO
use covoiturage\classes\metier\Group as BO;

/**
 * Description of Liste
 *
 * @author bruno
 */
class Liste extends ServiceVue {

    /**
     * Affichage de la liste des groupes sous forme de tuiles
     */
    public function executerService() {
        $groups = BO::getListe();
        $user = $this->getUser();
        echo "<div id='cov-group-list'><div class='row'> ";
        foreach ($groups as $group) {
            if ($group->isUserPresent($user) || $user->admin) {
                echo BP::getTuile($group);
            }
        }
        echo BP::getTuileAdd();
        echo "</div></div>";
    }

    /**
     * Titre de la vue
     * @return string
     */
    public function getTitre() {
        return 'Liste des groupes';
    }

}
