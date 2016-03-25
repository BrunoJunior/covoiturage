<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\presentation;

use covoiturage\classes\metier\Group as GroupBO;
use covoiturage\services\trajetprevisionnel\Proposer;
use covoiturage\classes\metier\TrajetPrevisionnel as BO;
use covoiturage\classes\metier\User as UserBO;
use covoiturage\utils\Html;

use covoiturage\services\trajetprevisionnel\Valider as ValiderTrajet;
use covoiturage\services\passagerprevisionnel\Valider as ValiderPassager;
use covoiturage\services\trajetprevisionnel\Delete as DeleteTrajet;
use covoiturage\services\passagerprevisionnel\Delete as DeletePassager;
use covoiturage\pages\trajetprevisionnel\Liste;
use covoiturage\services\trajetprevisionnel\Clear;

/**
 * Description of TrajetPrevisionnel
 *
 * @author bruno
 */
class TrajetPrevisionnel {

    /**
     * Obtenir la modal pour proposer un trajet
     * @param GroupBO $group
     */
    public static function getModal(GroupBO $group) {
        $html = '<div class="modal fade" id="cov-prev-' . $group->id . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <form action="' . Proposer::getUrl() . '" class="form-horizontal" method="POST">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="myModalLabel">Proposer un trajet</h4>
                            </div>
                            <div class="modal-body">
                            <input type="hidden" name="group_id" value="' . $group->id . '" />
                            <div class="form-group">
                                <label for="prev_date-' . $group->id . '" class="col-sm-2 control-label required">Date</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="prev_date-' . $group->id . '" name="prev_date" required="required" value="' . date('d/m/Y') . '">
                                </div>
                            </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                              <button type="submit" class="btn btn-success trajp-submit" value="submit" name="submit" id="submit-'.$group->id.'">Proposer</button>
                            </div>
                          </div>
                        </div>
                    </form>
                </div>';
        return $html;
    }

    /**
     * Obtenir le html pour l'affichage des trajets prévisionnels
     * @param UserBO $user
     */
    public static function getHtmlTable(GroupBO $group, UserBO $user) {
        $trajets = $user->getListeTrajetsPrevisionnels($group->id);
        $html = '<div id="trajp-liste" data-refresh="' . Liste::getUrl(NULL, ['group_id' => $group->id]) . '">
                    <div class="panel panel-info">
                        <div class="panel-heading"><h3 class="panel-title">Mes trajets prévisionnels <span class="badge">' . $user->getListeTrajetsPrevisionnels($group->id, BO::MODE_COUNT) . '</span></h3></div>
                        <div class="panel-body">
                            <table class="table">';
        $html .= static::getTh() . '<tbody>';
        foreach ($trajets as $trajet) {
            $html .= static::getTr($trajet);
        }
        $html .= '</tbody></table></div><div class="panel-footer text-right"><button class="btn btn-danger trajp-clear" href="' . Clear::getUrl(NULL,['group_id' => $group->id]) . '" role="button" data-toggle="tooltip" title="Supprimer les trajets passés" data-confirm="Êtes-vous sûr ?">'.Html::getIcon('eraser').' Nettoyer les trajets passés</button></div></div></div>';
        return $html;
    }

    /**
     * Obtenir l'entête du tableau de trajets prévisionnels
     * @return string
     */
    private static function getTh() {
        $html = '<thead><tr><th class="hidden">id</th><th class="trajp-date">Date</th><th class="center trajp-type">Type</th><th>Passagers</th><th class="center trajp-action">Actions</th></tr></thead>';
        return $html;
    }

    /**
     * Obtenir l'icône suivant le type de trajet
     * @param BO $trajet
     * @return string
     */
    private static function getIcone(BO $trajet) {
        switch ($trajet->type) {
            case BO::TYPE_ALLER:
                return Html::getIcon("arrow-right", "trajp-type");
            case BO::TYPE_RETOUR:
                return Html::getIcon("arrow-left", "trajp-type");
            case BO::TYPE_ALLER_RETOUR:
                return Html::getIcon("exchange", "trajp-type");
        }
    }

    /**
     * Obtenir une ligne dans le tableau des trajets prévisionnels
     * @param BO $trajet
     * @return string
     */
    private static function getTr(BO $trajet) {
        $passagers = $trajet->getListePassagers();
        $htmlPassagers = '';
        if (!empty($passagers)) {
            foreach ($passagers as $passager) {
                $user = $passager->getUser();
                $htmlPassagers .= '<div class="trajp-passager-tuile bg-info" data-param-id="' . $passager->id . '"><span class="trajp-passager-lib">' . $user->toHtml() . '</span>';
                $htmlPassagers .= '<button class="btn btn-success trajp-pass-valid" href="' . ValiderPassager::getUrl($passager->id) . '" role="button" data-toggle="tooltip" title="Valider le passager">'.Html::getIcon('check').'</button>';
                $htmlPassagers .= '<button class="btn btn-danger trajp-pass-delete" href="' . DeletePassager::getUrl($passager->id) . '" role="button" data-toggle="tooltip" title="Supprimer le passager" data-confirm="Êtes-vous sûr ?">'.Html::getIcon('trash').'</button></div>';
            }
        }
        $html = '<tr><td class="hidden">' . $trajet->id . '</td><td>' . $trajet->date . '</td><td class="center">' . static::getIcone($trajet) . '</td>';
        $html .= '<td>' . $htmlPassagers . '</td>';
        $html .= '<td class="center">
                    <button class="btn btn-success trajp-valid" href="' . ValiderTrajet::getUrl($trajet->id) . '" role="button" data-toggle="tooltip" title="Valider le trajet">'.Html::getIcon('check').'</button>
                    <button class="btn btn-danger trajp-delete" href="' . DeleteTrajet::getUrl($trajet->id) . '" role="button" data-toggle="tooltip" title="Supprimer le trajet" data-confirm="Êtes-vous sûr ?">'.Html::getIcon('trash').'</button>
                  </td>';
        $html .= '</tr>';
        return $html;
    }

}
