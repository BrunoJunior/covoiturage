<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\presentation;

// BO
use covoiturage\classes\metier\UserGroup as BO;
// Services
use covoiturage\services\usergroup\Remove;
// Helpers
use covoiturage\utils\Html;

/**
 * Description of UserGroup
 *
 * @author bruno
 */
class UserGroup {

    /**
     * Obtenir une ligne pour un formulaire
     * @param BO $userGp
     * @return string
     */
    public static function getLigneForm(BO $userGp) {
        $user = $userGp->getUser();
        $html = '<div class="form-group">
                    <div class="col-sm-5">
                      <input name="prenom_user_' . $user->id . '" id="prenom_user_' . $user->id . '" class="form-control" placeholder="Prénom" value="' . $user->prenom . '" disabled="disabled" />
                    </div>
                    <div class="col-sm-5">
                      <input name="user_' . $user->id . '" id="user_' . $user->id . '" class="form-control" placeholder="Nom" value="' . $user->nom . '" disabled="disabled" />
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-danger cov-ug-remove" url="' . Remove::getUrl($userGp->id) . '" data-toggle="tooltip" title="Enlever du groupe">' . Html::getIcon('minus') . '</button>
                    </div>
                  </div>';
        return $html;
    }

}
