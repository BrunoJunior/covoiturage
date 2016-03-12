<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\presentation;

use covoiturage\classes\metier\Group as GroupBO;
use covoiturage\pages\group\Edit;
use covoiturage\pages\group\Recap;
use covoiturage\pages\group\Trajet;

/**
 * Description of Group
 *
 * @author bruno
 */
class Group extends GroupBO {

    public function getTuile() {
        $usergroups = $this->getListeUserGroup();
        $html = '<div class="cov-group col-md-3 col-sm-6 col-xs-12">';
        $html .= '<h3>' . $this->nom . ' <span class="badge">' . count($usergroups) . '</span> <a class="btn btn-primary" href="'.Edit::getUrl($this->id).'"><span class="glyphicon glyphicon-pencil"></span></a></h3>';
        $html .= '<hr />';
        foreach ($usergroups as $usergroup) {
            $user = $usergroup->getUser();
            $html .= $user->prenom . ' ' . $user->nom . ' <span class="badge">' . $user->getNbVoyageConducteur() . '</span><br />';
        }
        $html .= '<hr />';
        $html .= '<a class="btn btn-success" href="'.Recap::getUrl($this->id).'" role="button"><span class="glyphicon glyphicon-list"></span> Récapitulatif</a> ';
        $html .= '<a class="btn btn-primary" href="'. Trajet::getUrl($this->id).'" role="button"><span class="glyphicon glyphicon-road"></span> Gérer les trajets</a>';
        $html .= '</div>';
        return $html;
    }

    public static function getTuileAdd() {
        $html = '<div class="cov-group add-group col-md-3 col-sm-6 col-xs-12">';
        $html .= '<h3>Ajouter un groupe</h3>';
        $html .= '<hr />';
        $html .= '<a href="'.Edit::getUrl().'"><span class="glyphicon glyphicon-plus-sign"></span></a>';
        $html .= '</div>';
        return $html;
    }

    public function getForm() {
        $html = '<form action="'.Edit::getUrl().'" class="form-horizontal" method="POST">
                    <input type="hidden" name="id" value="' . $this->id . '" />
                    <div class="form-group">
                      <label for="group_name" class="col-sm-2 control-label">Nom</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Nom du groupe" value="' . $this->nom . '">
                      </div>
                    </div>';

        $users = $this->getListeUserGroup();
        if (!empty($users)) {
            foreach ($users as $userGp) {
                $user = $userGp->getUser();
                $html .= '<div class="form-group">
                            <label for="user_'.$user->id.'" class="col-sm-2 control-label">Co-voitureur</label>
                                <div class="col-sm-4">
                                  <input name="user_'.$user->id.'" id="user_'.$user->id.'" class="form-control" placeholder="Nom" value="'.$user->nom.'" disabled="disabled" />
                                </div>
                                <div class="col-sm-4">
                                  <input name="prenom_user_'.$user->id.'" id="prenom_user_'.$user->id.'" class="form-control" placeholder="Prénom" value="'.$user->prenom.'" disabled="disabled" />
                                </div>
                                <div class="col-sm-2">
                                  <span class="glyphicon glyphicon-minus-sign"></span>
                                </div>
                          </div>';
            }
        }

        $html .= '<div class="form-group">
                      <label for="select-user1" class="col-sm-2 control-label">Co-voitureur existant</label>
                      <div class="col-sm-8">
                        <select id="select-user1" name="select-user1" class="form-control">
                        </select>
                      </div>
                      <div class="col-sm-2">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="user_nom1" class="col-sm-2 control-label">Nouveau co-voitureur</label>
                      <div class="col-sm-4">
                        <input name="user_nom1" id="user_nom1" class="form-control" placeholder="Nom" />
                      </div>
                      <div class="col-sm-4">
                        <input name="user_prenom1" id="user_prenom1" class="form-control" placeholder="Prénom" />
                      </div>
                      <div class="col-sm-2">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success" value="submit" name="submit" id="submit">'.($this->existe() ? 'Modifier' : 'Créer').'</button>
                      </div>
                    </div>
                </form>';
        return $html;
    }

}
