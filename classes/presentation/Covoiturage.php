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
use covoiturage\utils\HSession;

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
                $htmlPassagers .= '<div class="cov_passager_tuile bg-primary">' . $passager->getUser()->toHtml() . '</div>';
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

    public static function getForm(GroupBO $group) {
        $html = '<form action="' . Add::getUrl() . '" class="form-horizontal" method="POST">
                  <input type="hidden" id="cov_passagers" name="cov_passagers" />
                  <input type="hidden" id="group_id" name="group_id" value="' . $group->id . '" />';
        $html .= '<div class="form-group">
                    <label for="cov_date" class="col-sm-2 control-label">Date</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="cov_date" name="cov_date">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Passagers</label>
                    <div id="cov_passagers_visu" class="col-sm-8"></div>
                    <div class="cov_passager_tuile hidden bg-primary" id="cov_passager_tuile_hidden" data-param-id=""><span class="cov_passager_lib"></span><button type="button" class="btn btn-danger cov_remove_pass" data-toggle="tooltip" title="Enlever du trajet"><span class="glyphicon glyphicon-trash"></span></button></div>
                  </div>
                  <div class="form-group">
                    <label for="cov_pass" class="col-sm-2 control-label">Passager</label>
                    <div class="col-sm-9 col-xs-10">
                      <select id="cov_pass" name="cov_pass" class="form-control">
                                <option value="" selected>Choisir un passager</option>';
        $userGList = $group->getListeUserGroup();
        foreach ($userGList as $userGroup) {
            $user = $userGroup->getUser();
            if ($user->id != HSession::getUser()->id) {
                $html .= '<option value="' . $user->id . '">' . $user->prenom . ' ' . $user->nom . '</option>';
            }
        }
        $html .= '      </select>
                    </div>
                    <div class="col-xs-2 col-sm-1">
                        <button type="button" class="btn btn-success" id="cov_add_pass" data-toggle="tooltip" title="Ajouter au trajet"><span class="glyphicon glyphicon-plus"></span></button>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2 col-xs-6">
                      <button type="submit" class="btn btn-success" value="' . CovoiturageBO::TYPE_ALLER . '" name="submit" id="submit_' . CovoiturageBO::TYPE_ALLER . '">Aller <span class="glyphicon glyphicon-arrow-right"></span></button>
                    </div>
                    <div class="col-sm-offset-8 col-sm-2 col-xs-6">
                      <button type="submit" class="btn btn-danger pull-right" value="' . CovoiturageBO::TYPE_RETOUR . '" name="submit" id="submit_' . CovoiturageBO::TYPE_RETOUR . '"><span class="glyphicon glyphicon-arrow-left"></span> Retour</button>
                    </div>
                  </div>
                </form>';
        return $html;
    }

}
