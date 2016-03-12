<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages;

use covoiturage\classes\abstraites\Service;

/**
 * Description of 404
 *
 * @author bruno
 */
class Err404 extends Service {
    public function executerService() {
        echo '<div id="404" class="bg-danger text-center">';
        echo '<h3>Page inconnue !</h3>';
        echo '</div>';
    }

    public function getTitre() {
        return 'Erreur 404';
    }

}
