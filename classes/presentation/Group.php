<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\presentation;

use covoiturage\classes\metier\Group as GroupBO;
use covoiturage\pages\group\Edit;
use covoiturage\services\group\Remove;
use covoiturage\pages\group\Recap;
use covoiturage\pages\group\Trajet;
use covoiturage\utils\HSession;
use covoiturage\pages\user\Edit as EditUser;
use covoiturage\classes\metier\User;
use covoiturage\classes\presentation\UserGroup as UserGroupBP;
use covoiturage\services\group\Adduser;
use covoiturage\utils\HArray;

/**
 * Description of Group
 *
 * @author bruno
 */
class Group extends GroupBO {

    public function getTuile() {
        $prochainConducteur = $this->getProchainConducteurPropose();
        $conducteurRecurrent = $this->getConducteurRecurrent();
        $usergroups = $this->getListeUserGroup();
        $html = '<div class="col-md-3 col-sm-6 col-xs-12"><div class="cov-group">';
        $html .= '<h3>' . $this->nom . ' <span class="badge">' . count($usergroups) . '</span></h3> ';
        $conUser = HSession::getUser();
        $html .= '<hr /><div class="cov-group-users">';
        foreach ($usergroups as $usergroup) {
            $user = $usergroup->getUser();
            $credit = $user->getSommeCreditsTrajet($this);
            if ($conUser->admin) {
                $html .= '<a href="' . EditUser::getUrl($user->id) . '"><span class="glyphicon glyphicon-pencil"></span></a> ';
            }
            $html .= $user->toHtml() . '<span class="badge '.($credit < 0 ? 'bg-danger' : 'bg-success').'">' . $credit . '</span><span class="badge conducteur">' . $user->getNbVoyageConducteur($this) . '</span>';
            if (!empty($prochainConducteur) && $user->id == $prochainConducteur->id) {
                $html .= '<span class="glyphicon glyphicon-road"></span> ';
            }else if (!empty($conducteurRecurrent) && $user->id == $conducteurRecurrent->id) {
                $html .= '<span class="glyphicon glyphicon-star"></span> ';
            }
            $html .= '<br />';
        }
        $html .= '</div>';
        $html .= '<div class="cov-group-actions"><hr />';
        $html .= '<a class="btn btn-success" href="' . Recap::getUrl($this->id) . '" role="button" data-toggle="tooltip" title="Récapitulatif"><span class="glyphicon glyphicon-list"></span></a>';
        $html .= '<a class="btn btn-primary" href="' . Trajet::getUrl($this->id) . '" role="button" data-toggle="tooltip" title="Gérer les trajets"><span class="glyphicon glyphicon-road"></span></a>';
        if ($conUser->admin || $this->isUserAdminGroup($conUser)) {
            $html .= '<a class="btn btn-primary" href="' . Edit::getUrl($this->id) . '" data-toggle="tooltip" title="Editer"><span class="glyphicon glyphicon-pencil"></span></a>';
            $html .= '<button type="button" class="btn btn-danger group-remove" url="' . Remove::getUrl($this->id) . '" data-toggle="tooltip" title="Supprimer"><span class="glyphicon glyphicon-remove"></span></button>';
        }
        $html .= '</div></div></div>';
        return $html;
    }

    public static function getTuileAdd() {
        $user = HSession::getUser();
        if (!$user->admin) {
            return '';
        }
        $html = '<div class="col-md-3 col-sm-6 col-xs-12"><div class="cov-group add-group">';
        $html .= '<h3>Ajouter un groupe</h3>';
        $html .= '<hr />';
        $html .= '<a href="' . Edit::getUrl() . '"><span class="glyphicon glyphicon-plus-sign"></span></a>';
        $html .= '</div></div>';
        return $html;
    }

    public function getForm() {
        $panelIdent = '<div class="panel panel-primary">
                        <div class="panel-heading">
                          <h3 class="panel-title">Identification</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                              <label for="group_name" class="col-sm-2 control-label">Nom</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Nom du groupe" value="' . $this->nom . '">
                              </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-10 col-sm-2">
                                  <button type="submit" class="btn btn-success" value="submit" name="submit" id="submit">' . ($this->existe() ? 'Modifier' : 'Créer') . '</button>
                                </div>
                              </div>
                        </div>
                    </div>';

        $panelListeCov = '';
        $users = $this->getListeUserGroup();
        if (!empty($users)) {
            $panelListeCov .= '<div class="panel panel-primary">
                        <div class="panel-heading">
                          <h3 class="panel-title">Covoitureurs</h3>
                        </div>
                        <div class="panel-body">';
            foreach ($users as $userGp) {
                $panelListeCov .= UserGroupBP::getLigneForm($userGp);
            }
            $panelListeCov .= '</div></div>';
        }

        $panelAjoutCov = '<div class="panel panel-success">
                    <div class="panel-heading">
                      <h3 class="panel-title">Ajouter des covoitureurs</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                          <label for="select_user1" class="col-sm-2 control-label">Co-voitureur existant</label>
                          <div class="col-sm-8">
                            <select id="select_user1" name="select_user1" class="form-control">
                                <option value="" selected>Choisir un covoitureur</option>';
        $userList = User::getListe();
        foreach ($userList as $user) {
            $panelAjoutCov .= '<option value="' . $user->id . '">' . $user->prenom . ' ' . $user->nom . '</option>';
        }
        $panelAjoutCov .= '</select>
                              </div>
                              <div class="col-sm-2">
                                <button type="button" class="btn btn-success cov-ug-remove" url="' . Adduser::getUrl($this->id) . '" data-toggle="tooltip" title="Enlever du groupe"><span class="glyphicon glyphicon-plus"></span></button>
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
                                <button type="button" class="btn btn-success cov-ug-remove" url="' . Adduser::getUrl($this->id) . '" data-toggle="tooltip" title="Enlever du groupe"><span class="glyphicon glyphicon-plus"></span></button>
                              </div>
                            </div>
                        </div>
                    </div>';

        $html = '<form action="' . \covoiturage\services\group\Edit::getUrl() . '" class="form-horizontal" method="POST">
                    <input type="hidden" name="id" value="' . $this->id . '" />';
        $html .= $panelIdent;
        if ($this->existe()) {
            $html .= $panelListeCov;
            $html .= $panelAjoutCov;
        }
        $html .= '</form>';
        return $html;
    }

    public function getRecapitulatifHtml() {
        $recap = $this->getRecapitulatif();
        $userGroups = $this->getListeUserGroup();
        $html = '<div class="panel panel-info">
                <div class="panel-heading"><h3 class="panel-title">Récapitulatif</h3></div>
                <div class="panel-body"><div class="table-responsive"><table class="table">
                <thead><tr><th></th>';
        foreach ($userGroups as $userGroup) {
            $html .= '<th>' . $userGroup->getUser()->toHtml() . '</th>';
        }
        $html .= '</tr></thead><tbody>';
        foreach ($userGroups as $userGroupRow) {
            $html .= '<tr><td>'.$userGroupRow->getUser()->toHtml().'</td>';
            foreach ($userGroups as $userGroupCol) {
                $class = 'bg-info';
                $valeur = '';
                if ($userGroupCol->id == $userGroupRow->id) {
                    $class = 'bg-primary';
                } else {
                    $pos = HArray::getVal(HArray::getVal($recap, $userGroupRow->user_id, []), $userGroupCol->user_id, 0);
                    $neg = HArray::getVal(HArray::getVal($recap, $userGroupCol->user_id, []), $userGroupRow->user_id, 0);
                    $valeur = $pos - $neg;
                    if ($valeur > 0) {
                        $class = 'bg-success';
                    } else if ($valeur < 0) {
                        $class = 'bg-danger';
                    }
                }
                $html .= '<td class="' . $class . '">' . $valeur . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table></div></div></div>';
        return $html;
    }
}
