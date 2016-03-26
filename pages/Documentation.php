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
        echo '<h4>Connexion / Oubli de mot de passe</h4>';
        echo static::getImage('connexion', 'connexion', "En cas d'oubli du mot de passe, renseignez votre adresse email et cliquez sur le bouton \"J'ai oublié\"");
        echo '<h4>Inscription</h4>';
        echo static::getImage('inscription', 'inscription', "");
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
    private static function getImage($nom, $alt, $legend = '') {
        $root = Cache::get('', 'root');
        $html = '<p><img src="' . $root . 'resources/img/doc/' . $nom . '.jpg" class="img-responsive" alt="' . $alt . '" />';
        if (!empty($legend)) {
            $html .= '<span class="legend">'.$legend.'</span>';
        }
        $html .= '</p>';
        return $html;
    }

}
