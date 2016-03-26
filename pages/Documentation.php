<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages;

// Vue
use covoiturage\classes\abstraites\ServiceVue;
use covoiturage\utils\Cache;

/**
 * Description of Documentation
 *
 * @author bruno
 */
class Documentation extends ServiceVue {

    /**
     * Documentation
     */
    public function executerService() {
        echo '<div id="doc">';
        echo '<h4>Connexion</h4>';
        echo static::getImage('connexion', 'connexion');
        echo '</div>';
    }

    /**
     * Titre de la page
     * @return string
     */
    public function getTitre() {
        return 'Documentation';
    }

    /**
     * La page n'est pas sécurisé
     * @return boolean
     */
    public function isSecurised() {
        return FALSE;
    }

    /**
     * Image de doc
     * @param string $nom
     * @param string $alt
     * @return string
     */
    private static function getImage($nom, $alt) {
        $root = Cache::get('', 'root');
        return '<img src="' . $root . 'resources/img/doc/' . $nom . '.jpg" class="img-responsive" alt="' . $alt . '" />';
    }

}
