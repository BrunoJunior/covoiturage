<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\presentation;

// BO
use covoiturage\classes\metier\Covoiturage as BO;
use covoiturage\classes\metier\Group as GroupBO;
use covoiturage\classes\metier\User as UserBO;
// Services
use covoiturage\services\covoiturage\Add;
use covoiturage\pages\covoiturage\Liste;
use covoiturage\services\covoiturage\Delete;
use covoiturage\pages\covoiturage\Edit;
// Helpers
use covoiturage\utils\HSession;
use covoiturage\utils\Html;

/**
 * Description of Covoiturage
 *
 * @author bruno
 */
class Covoiturage {

    /**
     * Obtenir l'entête du tableau de trajets
     * @param boolean $withConducteur
     * @param boolean $withPassagers
     * @return string
     */
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

    /**
     * Obtenir une ligne dans le tableau des trajet
     * @param BO $covoiturage
     * @param boolean $withConducteur
     * @param boolean $withPassagers
     * @return string
     */
    private static function getTr(BO $covoiturage, $withConducteur = TRUE, $withPassagers = TRUE) {
        $conducteur = $covoiturage->getConducteur();
        $passagers = $covoiturage->getListePassagers();
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
        $type = '<span class="cov-type glyphicon glyphicon-arrow-' . ($covoiturage->type == BO::TYPE_ALLER ? 'right' : 'left') . '"></span>';
        $html = '<tr><td class="hidden">' . $covoiturage->id . '</td><td>' . date('d/m/Y', strtotime($covoiturage->date)) . '</td><td class="center">' . $type . '</td>';
        if ($withConducteur) {
            $html .= '<td><div class="cov_passager_tuile bg-info"><span class="cov_passager_lib">' . $conducteur->toHtml() . '</span></div></td>';
        }
        if ($withPassagers) {
            $html .= '<td>' . $htmlPassagers . '</td>';
        }
        if (HSession::getUser()->admin) {
            $html .= '<td>
                        <button class="btn btn-danger cov-trajet-del" href="'.Delete::getUrl($covoiturage->id).'" role="button" data-toggle="tooltip" title="Supprimer"><span class="glyphicon glyphicon-trash"></span></button>
                        <a class="btn btn-primary" href="'.Edit::getUrl($covoiturage->id).'" role="button" data-toggle="tooltip" title="Modifier"><span class="glyphicon glyphicon-pencil"></span></button>
                      </td>';
        }
        $html .= '</tr>';
        return $html;
    }

    /**
     * Obtenir le tableau des trajets dont l'utilisateur est le conducteur
     * @param GroupBO $group
     * @param UserBO $user
     * @param int $nbMax
     * @param int $page
     * @return string
     */
    public static function getHtmlTableCond(GroupBO $group, UserBO $user, $nbMax = 0, $page = 1) {
        $covoiturages = $user->getListeCovoiturage($group, NULL, $nbMax, $page);
        $nbPages = $user->getListeCovoiturage($group, NULL, $nbMax, $page, BO::MODE_NBPAGES);
        $nbTotal = $user->getListeCovoiturage($group, NULL, $nbMax, $page, BO::MODE_COUNT);
        $lib = 'Mes trajets conducteur';
        if ($user->admin) {
            $lib = 'Trajets';
        }
        $html = '<div id="cov_list_trajets" data-refresh="' . Liste::getUrl(NULL, ['group_id' => $group->id, 'max' => $nbMax]) . '">';
        $html .= static::getHtmlTable($covoiturages, $lib, $nbTotal, $nbPages, $page, $user->admin);
        $html .= '</div>';
        return $html;
    }

    /**
     * Obtenir le tableau des trajets dont l'utilisateur est un passager
     * @param GroupBO $group
     * @param UserBO $user
     * @param int $nbMax
     * @param int $page
     * @return string
     */
    public static function getHtmlTablePass(GroupBO $group, UserBO $user, $nbMax = 0, $page = 1) {
        $covoiturages = $user->getListeCovoituragePassager($group, NULL, $nbMax, $page);
        $nbPages = $user->getListeCovoituragePassager($group, NULL, $nbMax, $page, BO::MODE_NBPAGES);
        $nbTotal = $user->getListeCovoituragePassager($group, NULL, $nbMax, $page, BO::MODE_COUNT);
        $html = '<div id="cov_list_passagers" data-refresh="' . Liste::getUrl(NULL, ['group_id' => $group->id, 'type' => 'passager', 'max' => $nbMax]) . '">';
        $html .= static::getHtmlTable($covoiturages, 'Mes trajets passager', $nbTotal, $nbPages, $page, TRUE, FALSE);
        $html .= '</div>';
        return $html;
    }

    /**
     * Obtenir un tableau de trajets
     * @param BO $covoiturages
     * @param string $label Libellé du bloc
     * @param int $nbTotal Nombre total de trajets
     * @param int $nbPage Nombre de pages
     * @param int $page Page demandée
     * @param boolean $withConducteur Afficher la colonne conducteur
     * @param boolean $withPassagers Afficher la colonne passagers
     * @return string
     */
    private static function getHtmlTable(BO $covoiturages, $label, $nbTotal, $nbPage = 0, $page = 1, $withConducteur = TRUE, $withPassagers = TRUE) {
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

    /**
     * Obtenir un formulaire d'ajout de trajet
     * @param GroupBO $group
     * @return string
     */
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
        $html .= '<div class="form-group">
                    <label for="cov_pass" class="col-sm-2 control-label">Passagers</label>
                    <div class="col-sm-10">';
        foreach ($userGList as $userGroup) {
            $user = $userGroup->getUser();
            if ($user->id != $connectUser->id || $connectUser->admin) {
                $html .= '<label class="checkbox-inline"> <input type="checkbox" id="cov_pass_cb_' . $user->id . '" name="cov_pass_cb_' . $user->id . '" value="' . $user->id . '"> ' . $user->toHtml() . ' </label>';
            }
        }
        $html .= '</div>';
        $html .= '</div>
                  <div class="form-group">
                    <div class="col-sm-2 col-xs-4">
                      <button type="submit" class="btn btn-success" value="' . BO::TYPE_ALLER . '" name="submit" id="submit_' . BO::TYPE_ALLER . '">Aller <span class="glyphicon glyphicon-arrow-right"></span></button>
                    </div>
                    <div class="col-sm-offset-3 col-sm-2 col-xs-4">
                      <button type="submit" class="btn btn-primary" value="' . BO::TYPE_ALLER . ',' . BO::TYPE_RETOUR . '" name="submit" id="submit">Aller <span class="glyphicon glyphicon-transfer"></span> Retour</button>
                    </div>
                    <div class="col-sm-offset-3 col-sm-2 col-xs-4">
                      <button type="submit" class="btn btn-danger pull-right" value="' . BO::TYPE_RETOUR . '" name="submit" id="submit_' . BO::TYPE_RETOUR . '"><span class="glyphicon glyphicon-arrow-left"></span> Retour</button>
                    </div>
                  </div>
                </form>';
        return $html;
    }

}
