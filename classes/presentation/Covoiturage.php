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
        $type = '<span class="cov-type glyphicon glyphicon-arrow-' . ($covoiturage->type == CovoiturageBO::TYPE_ALLER ? 'right' : 'left') . '"></span>';
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
                    <label for="cov_date" class="col-sm-2 control-label">Date</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="cov_date" name="cov_date">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-8 col-sm-2 col-xs-6">
                      <button type="submit" class="btn btn-success" value="'.CovoiturageBO::TYPE_ALLER.'" name="submit_'.CovoiturageBO::TYPE_ALLER.'" id="submit_'.CovoiturageBO::TYPE_ALLER.'">Aller <span class="glyphicon glyphicon-arrow-right"></span></button>
                    </div>
                    <div class="col-sm-2 col-xs-6">
                      <button type="submit" class="btn btn-danger" value="'.CovoiturageBO::TYPE_RETOUR.'" name="submit_'.CovoiturageBO::TYPE_RETOUR.'" id="submit_'.CovoiturageBO::TYPE_RETOUR.'"><span class="glyphicon glyphicon-arrow-left"></span> Retour</button>
                    </div>
                  </div>
                </form>';
        return $html;
    }

}
