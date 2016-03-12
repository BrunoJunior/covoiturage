<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\group;

use covoiturage\classes\abstraites\Service;
use covoiturage\classes\presentation\Group;
use covoiturage\utils\HRequete;

/**
 * Description of Liste
 *
 * @author bruno
 */
class Recap extends Service {

    public function executerService() {
        echo "<div id='cov-group-recap'>
                <div class='row'> ";

        echo "  </div>
              </div>";
    }

    public function getTitre() {
        $group = new Group(HRequete::getPOST('id'));
        return $group->nom . ' - Récapitulatif';
    }

}
