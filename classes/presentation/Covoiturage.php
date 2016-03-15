<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\presentation;

use covoiturage\classes\metier\Covoiturage as CovoiturageBO;
use covoiturage\classes\metier\Group as GroupBO;
use covoiturage\services\covoiturage\Add;

/**
 * Description of Covoiturage
 *
 * @author bruno
 */
class Covoiturage extends CovoiturageBO {

    private static function getTh() {
        return '<thead><tr><th class="cov-date">Date</th><th class="center cov-type">Type</th><th class="cov-conducteur">Conducteur</th><th>Passagers</th></tr></thead>';
    }

    private static function getTr(CovoiturageBO $covoiturage) {
        $conducteur = $covoiturage->getConducteur();
        $passagers = $covoiturage->getListePassagers();
        $htmlPassagers = '';
        if (!empty($passagers)) {
            foreach ($passagers as $passager) {
                $htmlPassagers .= $passager->getUser()->toHtml() . ', ';
            }
        }
        $type = '<span class="cov-type glyphicon glyphicon-arrow-' . ($covoiturage->type == 0 ? 'right' : 'left') . '"></span>';
        return '<tr><td>' . date('d/m/Y', strtotime($covoiturage->date)) . '</td><td class="center">' . $type . '</td><td>' . $conducteur->toHtml() . '</td><td>' . $htmlPassagers . '</td></tr>';
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

    public static function getForm() {
        $html = '<form action="' . Add::getUrl() . '" class="form-horizontal" method="POST">';
        $html .= '<div class="form-group">
                    <label for="group_name" class="col-sm-2 control-label">Date</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="cov_date" name="cov_date">
                    </div>
                  </div>';
        $html .= '</form>';
        return $html;
    }

}
