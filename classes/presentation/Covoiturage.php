<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\presentation;

use covoiturage\classes\metier\Covoiturage as CovoiturageBO;
use covoiturage\classes\metier\Group as GroupBO;

/**
 * Description of Covoiturage
 *
 * @author bruno
 */
class Covoiturage extends CovoiturageBO {

    private static function getTh() {
        return '<thead><tr><th>Date</th><th>Conducteur</th><th>Passagers</th></tr></thead>';
    }

    private static function getTr(CovoiturageBO $covoiturage) {
        $conducteur = $covoiturage->getConducteur();
        $passagers = $covoiturage->getListePassagers();
        $htmlPassagers = '';
        if (!empty($passagers)) {
            foreach ($passagers as $passager) {
                $htmlPassagers .= $passager->getUser()->toHtml() . '<br />';
            }
        }
        return '<tr><td>' . $covoiturage->date . '</td><td>' . $conducteur->toHtml() . '</td><td>' . $htmlPassagers . '</td></tr>';
    }

    public static function getHtmlTable(GroupBO $group) {
        $covoiturages = $group->getListeCovoiturage();
        $html = '<div class="table-responsive"><table class="table">';
        $html .= static::getTh();
        foreach ($covoiturages as $covoiturage) {
            $html .= static::getTr($covoiturage);
        }
        $html .= '</table></div>';
        return $html;
    }

}
