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
use covoiturage\utils\Html;

/**
 * Description of Covoiturage
 *
 * @author bruno
 */
class Covoiturage extends CovoiturageBO {

    private static function getTh($withConducteur = TRUE, $withPassagers = TRUE) {
        $html = '<thead><tr><th class="hidden">id</th><th class="cov-date">Date</th><th class="center cov-type">Type</th>';
        if ($withConducteur) {
            $html .= '<th class="' . ($withPassagers ? 'cov-conducteur' : '') . '">Conducteur</th>';
        }
        if ($withPassagers) {
            $html .= '<th>Passagers</th>';
        }
        if (HSession::getUser()->admin) {
            $html .= '<th>Actions</th>';
        }
        $html .= '</tr></thead>';
        return $html;
    }

    private static function getTr(CovoiturageBO $covoiturage, $withConducteur = TRUE, $withPassagers = TRUE) {
        $conducteur = $covoiturage->getConducteur();
        $passagers = $covoiturage->getListePassagers();
        $group = $covoiturage->getGroup();
        if ($withPassagers) {
            $htmlPassagers = '';
            if (!empty($passagers)) {
                foreach ($passagers as $passager) {
                    $user = $passager->getUser();
                    $htmlPassagers .= '<div class="cov_passager_tuile bg-info" data-param-id="' . $passager->id . '"><span class="cov_passager_lib">' . $user->toHtml() . '</span>';
                    $htmlPassagers .= '</div>';
                }
            }
        }
        $type = '<span class="cov-type glyphicon glyphicon-arrow-' . ($covoiturage->type == CovoiturageBO::TYPE_ALLER ? 'right' : 'left') . '"></span>';
        $html = '<tr><td class="hidden">' . $covoiturage->id . '</td><td>' . date('d/m/Y', strtotime($covoiturage->date)) . '</td><td class="center">' . $type . '</td>';
        if ($withConducteur) {
            $html .= '<td><div class="cov_passager_tuile bg-info"><span class="cov_passager_lib">' . $conducteur->toHtml() . '</span></div></td>';
        }
        if ($withPassagers) {
            $html .= '<td>' . $htmlPassagers . '</td>';
        }
        if (HSession::getUser()->admin) {
            $html .= '<td>
                        <button class="btn btn-danger" url="" role="button" data-toggle="tooltip" title="Supprimer"><span class="glyphicon glyphicon-trash"></span></button>
                        <button class="btn btn-primary" url="" role="button" data-toggle="tooltip" title="Modifier"><span class="glyphicon glyphicon-pencil"></span></button>
                      </td>';
        }
        $html .= '</tr>';
        return $html;
    }

    public static function getHtmlTableCond(GroupBO $group, UserBO $user, $nbMax = 0, $page = 1) {
        $covoiturages = $user->getListeCovoiturage($group, NULL, $nbMax, $page);
        $nbPages = $user->getListeCovoiturage($group, NULL, $nbMax, $page, static::MODE_NBPAGES);
        $nbTotal = $user->getListeCovoiturage($group, NULL, $nbMax, $page, static::MODE_COUNT);
        $lib = 'Mes trajets conducteur';
        if ($user->admin) {
            $lib = 'Trajets';
        }
        $html = '<div id="cov_list_trajets" data-refresh="' . Liste::getUrl(NULL, ['group_id' => $group->id, 'max' => $nbMax]) . '">';
        $html .= static::getHtmlTable($covoiturages, $lib, $nbTotal, $nbPages, $page, $user->admin);
        $html .= '</div>';
        return $html;
    }

    public static function getHtmlTablePass(GroupBO $group, UserBO $user, $nbMax = 0, $page = 1) {
        $covoiturages = $user->getListeCovoituragePassager($group, NULL, $nbMax, $page);
        $nbPages = $user->getListeCovoituragePassager($group, NULL, $nbMax, $page, static::MODE_NBPAGES);
        $nbTotal = $user->getListeCovoituragePassager($group, NULL, $nbMax, $page, static::MODE_COUNT);
        $html = '<div id="cov_list_passagers" data-refresh="' . Liste::getUrl(NULL, ['group_id' => $group->id, 'type' => 'passager', 'max' => $nbMax]) . '">';
        $html .= static::getHtmlTable($covoiturages, 'Mes trajets passager', $nbTotal, $nbPages, $page, TRUE, FALSE);
        $html .= '</div>';
        return $html;
    }

    private static function getHtmlTable($covoiturages, $label, $nbTotal, $nbPage = 0, $page = 1, $withConducteur = TRUE, $withPassagers = TRUE) {
        $htmlPagination = Html::getBlocPagination(3, $nbPage, $page);
        $html = '<div class="panel panel-info">
                <div class="panel-heading"><h3 class="panel-title">' . $label . ' <span class="badge">' . $nbTotal . '</span></h3></div>
                <div class="panel-body">' . $htmlPagination . '<table class="table">';
        $html .= static::getTh($withConducteur, $withPassagers) . '<tbody>';
        foreach ($covoiturages as $covoiturage) {
            $html .= static::getTr($covoiturage, $withConducteur, $withPassagers);
        }
        $html .= '</tbody></table></div></div>';
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
                $html .= '<label class="checkbox-inline"> <input type="checkbox" id="cov_pass_cb_' . $user->id . '" name="cov_pass_cb_' . $user->id . '" value="' . $user->id . '"> ' . $user->toHtml() . ' </label>';
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
