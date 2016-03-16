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
use covoiturage\classes\metier\User as UserBO;
use covoiturage\classes\metier\UserGroup as UserGroupBO;
use \covoiturage\pages\covoiturage\Liste;

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
        $group = $covoiturage->getGroup();
        $htmlPassagers = '';
        if (!empty($passagers)) {
            foreach ($passagers as $passager) {
                $user = $passager->getUser();
                $htmlPassagers .= '<div class="cov_passager_tuile bg-primary" data-param-id="'.$passager->id.'"><span class="cov_passager_lib">'.$user->toHtml().'</span>';
                if (HSession::getUser()->admin || UserGroupBO::chargerParGroupeEtUser($group, HSession::getUser())->group_admin) {
                    $htmlPassagers .= '<button type="button" class="btn btn-danger cov_remove_pass" data-toggle="tooltip" title="Enlever du trajet"><span class="glyphicon glyphicon-trash"></span></button>';
                }
                $htmlPassagers .= '</div>';
            }
        }
        $type = '<span class="cov-type glyphicon glyphicon-arrow-' . ($covoiturage->type == CovoiturageBO::TYPE_ALLER ? 'right' : 'left') . '"></span>';
        return '<tr><td>' . date('d/m/Y', strtotime($covoiturage->date)) . '</td><td class="center">' . $type . '</td><td>' . $conducteur->toHtml() . '</td><td>' . $htmlPassagers . '</td></tr>';
    }

    public static function getHtmlTableCond(GroupBO $group, UserBO $user) {
        $covoiturages = $user->getListeCovoiturage($group);
        $lib = 'Mes trajets conducteur';
        if ($user->admin) {
            $lib = 'Trajets';
        }
        $html = '<div id="cov_list_trajets" data-refresh="'.Liste::getUrl(NULL, ['group_id' => $group->id]).'">';
        $html .= static::getHtmlTable($covoiturages, $lib);
        $html .= '</div>';
        return $html;
    }
    
    public static function getHtmlTablePass(GroupBO $group, UserBO $user) {
        $covoiturages = $user->getListeCovoituragePassager($group);
        return static::getHtmlTable($covoiturages, 'Mes trajets passager');
    }

    private static function getHtmlTable($covoiturages, $label) {
        $html = '<div class="panel panel-info">
                <div class="panel-heading"><h3 class="panel-title">'.$label.' <span class="badge">'.count($covoiturages).'</span></h3></div>
                <div class="panel-body"><div class="table-responsive"><table class="table">';
        $html .= static::getTh() . '<tbody>';
        foreach ($covoiturages as $covoiturage) {
            $html .= static::getTr($covoiturage);
        }
        $html .= '</tbody></table></div></div></div>';
        return $html;
    }

    public static function getForm(GroupBO $group) {
        $connectUser = HSession::getUser();
        $userGList = $group->getListeUserGroup();
        $html = '<form action="' . Add::getUrl() . '" class="form-horizontal" method="POST">
                  <input type="hidden" id="cov_passagers" name="cov_passagers" />
                  <input type="hidden" id="group_id" name="group_id" value="' . $group->id . '" />';
        if ($connectUser->admin) {
            $html .= '<div class="form-group">
                    <label for="cov_conducteur" class="col-sm-2 control-label">Conducteur</label>
                    <div class="col-sm-10">
                      <select id="cov_conducteur" name="cov_conducteur" class="form-control">';
            foreach ($userGList as $userGroup) {
                $user = $userGroup->getUser();
                $selected = ($user->id == $connectUser->id) ? 'selected' : '';
                $html .= '<option value="' . $user->id . ' ' . $selected . '">' . $user->toHtml() . '</option>';
            }
            $html .= '      </select>
                    </div>
                  </div>';
        }
        $html .= '<div class="form-group">
                    <label for="cov_date" class="col-sm-2 control-label">Date</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="cov_date" name="cov_date">
                    </div>
                  </div>';
//        $html .= '<div class="form-group">
//                    <label class="col-sm-2 control-label">Passagers</label>
//                    <div id="cov_passagers_visu" class="col-sm-8"></div>
//                    <div class="cov_passager_tuile hidden bg-primary" id="cov_passager_tuile_hidden" data-param-id=""><span class="cov_passager_lib"></span><button type="button" class="btn btn-danger cov_remove_pass" data-toggle="tooltip" title="Enlever du trajet"><span class="glyphicon glyphicon-trash"></span></button></div>
//                  </div>';
        $html .= '<div class="form-group">
                    <label for="cov_pass" class="col-sm-2 control-label">Passagers</label>
                    <div class="col-sm-10">';
//        $html .= '      <select id="cov_pass" name="cov_pass" class="form-control">
//                            <option value="" selected>Choisir un passager</option>';
        foreach ($userGList as $userGroup) {
            $user = $userGroup->getUser();
            if ($user->id != $connectUser->id || $connectUser->admin) {
                $html .= '<label class="checkbox-inline"> <input type="checkbox" id="cov_pass_cb_'.$user->id.'" name="cov_pass_cb_'.$user->id.'" value="'.$user->id.'"> ' . $user->toHtml() . ' </label>';
//                $html .= '<option value="' . $user->id . '">' . $user->toHtml() . '</option>';
            }
        }
//        $html .= '      </select>';
        $html .= '</div>';
//        $html .= '  <div class="col-xs-2 col-sm-1">
//                        <button type="button" class="btn btn-success" id="cov_add_pass" data-toggle="tooltip" title="Ajouter au trajet"><span class="glyphicon glyphicon-plus"></span></button>
//                    </div>';
        $html .= '</div>
                  <div class="form-group">
                    <div class="col-sm-2 col-xs-4">
                      <button type="submit" class="btn btn-success" value="' . CovoiturageBO::TYPE_ALLER . '" name="submit" id="submit_' . CovoiturageBO::TYPE_ALLER . '">Aller <span class="glyphicon glyphicon-arrow-right"></span></button>
                    </div>
                    <div class="col-sm-offset-3 col-sm-2 col-xs-4">
                      <button type="submit" class="btn btn-primary" value="' . CovoiturageBO::TYPE_ALLER . ',' . CovoiturageBO::TYPE_RETOUR . '" name="submit" id="submit">Aller <span class="glyphicon glyphicon-transfer"></span> Retour</button>
                    </div>
                    <div class="col-sm-offset-3 col-sm-2 col-xs-4">
                      <button type="submit" class="btn btn-danger pull-right" value="' . CovoiturageBO::TYPE_RETOUR . '" name="submit" id="submit_' . CovoiturageBO::TYPE_RETOUR . '"><span class="glyphicon glyphicon-arrow-left"></span> Retour</button>
                    </div>
                  </div>
                </form>';
        return $html;
    }

}
