<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\presentation;

use covoiturage\classes\metier\Group as GroupBO;

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
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title" id="myModalLabel">Proposer un trajet</h4>
                        </div>
                        <div class="modal-body">
                          ...
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                          <button type="button" class="btn btn-primary">Proposer</button>
                        </div>
                      </div>
                    </div>
                </div>';
        return $html;
    }

}
