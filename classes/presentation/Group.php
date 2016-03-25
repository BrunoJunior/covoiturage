<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\presentation;

// BO
use covoiturage\classes\metier\User as UserBO;
use covoiturage\classes\metier\Group as BO;
use covoiturage\classes\presentation\TrajetPrevisionnel as TrajetPrevisionnelBP;
// Services
// Vues
use covoiturage\pages\user\Edit as EditUserVue;
use covoiturage\pages\group\Edit as EditVue;
use covoiturage\pages\group\Recap as RecapVue;
use covoiturage\pages\group\Trajet as TrajetVue;
use covoiturage\pages\group\Contact as ContactVue;
use covoiturage\pages\user\Contact as ContactUserVue;
// Traitements
use covoiturage\services\group\Edit;
use covoiturage\services\group\Remove;
use covoiturage\services\group\Adduser;
use covoiturage\services\group\Contact;
// Helpers
use covoiturage\utils\HSession;
use covoiturage\utils\HArray;
use covoiturage\utils\Html;

/**
 * Description of Group
 *
 * @author bruno
 */
class Group {

    /**
     * Obtenir une tuile pour un groupe
     * @param BO $group
     * @return string
     */
    public static function getTuile(BO $group) {
        $prochainConducteur = $group->getProchainConducteurPropose();
        $conducteurRecurrent = $group->getConducteurRecurrent();
        $usergroups = $group->getListeUserGroup();
        $html = TrajetPrevisionnelBP::getModal($group);
        $html .= '<div class="col-md-4 col-sm-6 col-xs-12"><div class="cov-group">';
        $html .= '<h3>' . $group->nom . ' <span class="badge">' . count($usergroups) . '</span></h3> ';
        $conUser = HSession::getUser();
        $html .= '<hr /><div class="cov-group-users">';
        foreach ($usergroups as $usergroup) {
            $user = $usergroup->getUser();
            $credit = $user->getScore($group);
            if ($conUser->admin) {
                $html .= '<a class="btn btn-warning" role="button" href="' . EditUserVue::getUrl($user->id) . '">'.Html::getIcon('pencil').'</a> ';
            } 
            if ($conUser->id != $user->id) {
                $html .= '<a class="btn btn-primary" role="button" href="' . ContactUserVue::getUrl($user->id) . '" data-toggle="tooltip" title="Contacter">'.Html::getIcon('envelope').'</a> ';
            }
            $html .= '<span class="user">' . $user->toHtml() . '</span><span class="badge ' . ($credit < 0 ? 'bg-danger' : 'bg-success') . '">' . $credit . '</span><span class="badge conducteur">' . $user->getNbVoyageConducteur($group) . '</span>';
            if (!empty($prochainConducteur) && $user->id == $prochainConducteur->id) {
                $html .= Html::getIcon('road').' ';
            } else if (!empty($conducteurRecurrent) && $user->id == $conducteurRecurrent->id) {
                $html .= Html::getIcon('trophy').' ';
            }
            $html .= '<br />';
        }
        $html .= '</div>';
        $html .= '<div class="cov-group-actions"><hr />';
        $html .= '<a class="btn btn-success" href="' . RecapVue::getUrl($group->id) . '" role="button" data-toggle="tooltip" title="Récapitulatif">'.Html::getIcon('balance-scale').'</a>';
        $html .= '<a class="btn btn-primary" href="' . TrajetVue::getUrl($group->id) . '" role="button" data-toggle="tooltip" title="Gérer les trajets">'.Html::getIcon('road').'</a>';
        $html .= '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cov-prev-'.$group->id.'"><span data-toggle="tooltip" title="Proposer un trajet">'.Html::getIcon('share-alt').'</span></button>';
        $html .= '<a class="btn btn-primary" href="' . ContactVue::getUrl($group->id) . '" role="button" data-toggle="tooltip" title="Contacter le groupe">'.Html::getIcon('envelope').'</span></a>';
        if ($conUser->admin || $group->isUserAdminGroup($conUser)) {
            $html .= '<a class="btn btn-warning" href="' . EditVue::getUrl($group->id) . '" data-toggle="tooltip" title="Editer">'.Html::getIcon('pencil').'</a>';
            $html .= '<button type="button" class="btn btn-danger group-remove" url="' . Remove::getUrl($group->id) . '" data-toggle="tooltip" title="Supprimer" data-confirm="Êtes-vous sûr ?">'.Html::getIcon('trash').'</button>';
        }
        $html .= '</div></div></div>';
        return $html;
    }

    /**
     * Obtenir la tuile d'ajout de groupe
     * @return string
     */
    public static function getTuileAdd() {
        $html = '<div class="col-md-4 col-sm-6 col-xs-12"><div class="cov-group add-group">';
        $html .= '<h3>Ajouter un groupe</h3>';
        $html .= '<hr />';
        $html .= '<a href="' . EditVue::getUrl() . '">'.Html::getIcon('plus-circle').'</a>';
        $html .= '</div></div>';
        return $html;
    }

    /**
     * Obtenir le formulaire d'édition d'un groupe
     * @param BO $group
     * @return string
     */
    public static function getForm(BO $group) {
        $panelIdent = '<div class="panel panel-primary">
                        <div class="panel-heading">
                          <h3 class="panel-title">Identification</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                              <label for="group_name" class="col-sm-2 control-label">Nom</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Nom du groupe" value="' . $group->nom . '">
                              </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                  <button type="submit" class="btn btn-success pull-right" value="submit" name="submit" id="submit">' . ($group->existe() ? 'Modifier' : 'Créer') . '</button>
                                </div>
                              </div>
                        </div>
                    </div>';

        $panelListeCov = '';
        $users = $group->getListeUserGroup();
        if (!empty($users)) {
            $panelListeCov .= '<div class="panel panel-primary">
                        <div class="panel-heading">
                          <h3 class="panel-title">Covoitureurs</h3>
                        </div>
                        <div class="panel-body">';
            foreach ($users as $userGp) {
                $panelListeCov .= UserGroup::getLigneForm($userGp);
            }
            $panelListeCov .= '</div></div>';
        }

        $panelAjoutCov = '<div class="panel panel-success">
                    <div class="panel-heading">
                      <h3 class="panel-title">Ajouter des covoitureurs</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                          <label for="user_id" class="col-sm-2 control-label">Co-voitureur existant</label>
                          <div class="col-sm-8">
                            <select id="user_id" name="user_id" class="form-control">
                                <option value="" selected>Choisir un covoitureur</option>';
        $userList = UserBO::getListe();
        foreach ($userList as $user) {
            $panelAjoutCov .= '<option value="' . $user->id . '">' . $user->toHtml() . '</option>';
        }
        $panelAjoutCov .= '</select>
                              </div>
                              <div class="col-sm-2">
                                <button type="button" class="btn btn-success cov-ug-add" url="' . Adduser::getUrl($group->id) . '" data-toggle="tooltip" title="Ajouter au groupe">'.Html::getIcon('user-plus').'</button>
                              </div>
                            </div>
                        </div>
                    </div>';

        $html = '<form action="' . Edit::getUrl() . '" class="form-horizontal" method="POST">
                    <input type="hidden" name="id" value="' . $group->id . '" />';
        $html .= $panelIdent;
        if ($group->existe()) {
            $html .= $panelListeCov;
            $html .= $panelAjoutCov;
        }
        $html .= '</form>';
        return $html;
    }

    /**
     * Obtenir la visualisation du récapitulatif du groupe
     * @param BO $group
     * @return string
     */
    public static function getRecapitulatifHtml(BO $group) {
        $recap = $group->getRecapitulatif();
        $userGroups = $group->getListeUserGroup();
        $html = '';

        $users = [];
        foreach ($userGroups as $userGroup) {
            $actUser = $userGroup->getUser();
            $users[] = ['user' => $actUser, 'label' => $actUser->toHtml()];
        }
        foreach ($users as $row) {
            $rowUser = $row['user'];
            $html .= '<div class="panel panel-info">
                <div class="panel-heading"><h3 class="panel-title">' . $row['label'] . '</h3></div>
                <div class="panel-body">';

            foreach ($userGroups as $userGroupRow) {
                if ($userGroupRow->user_id == $rowUser->id) {
                    continue;
                }
                $pos = HArray::getVal(HArray::getVal($recap, $rowUser->id, []), $userGroupRow->user_id, 0);
                $neg = HArray::getVal(HArray::getVal($recap, $userGroupRow->user_id, []), $rowUser->id, 0);
                $valeur = $pos - $neg;
                $classe = $valeur >= 0 ? 'bg-success' : 'bg-danger';
                $html .= '<div class="col-md-3 col-sm-6 col-xs-12"><div class="cov-recap">';
                $html .= '<h5>' . $userGroupRow->getUser()->toHtml() . '</h5><hr />';
                $html .= '<span class="badge ' . $classe . '">' . $valeur . '</span><hr />';
                $html .= '<span class="explication">*';
                if ($valeur > 0) {
                    $html .= $userGroupRow->getUser()->toHtml() . ' doit ' . $valeur . ' trajets à ' . $rowUser->toHtml();
                } else if ($valeur < 0) {
                    $html .= $rowUser->toHtml() . ' doit ' . abs($valeur) . ' trajets à ' . $userGroupRow->getUser()->toHtml();
                } else {
                    $html .= 'Equilibre atteint';
                }
                $html .= '</span>';
                $html .= '</div></div>';
            }
            $html .= '</div></div>';
        }
        return $html;
    }

    /**
     * Obtenir le formulaire de contact
     * @param BO $group
     * @return string
     */
    public static function getContactForm(BO $group) {
        $html = '<form action="' . Contact::getUrl() . '" class="form-horizontal" method="POST">
                    <input type="hidden" name="id" value="' . $group->id . '" />';
        $html .= '<div class="panel panel-success">
                    <div class="panel-heading"><h3 class="panel-title">Votre message</h3></div>
                    <div class="panel-body">';
        $html .= '<div class="form-group">
                    <label for="group_cont_titre" class="col-sm-2 control-label required">Sujet</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="group_cont_titre" name="group_cont_titre" placeholder="Donnez un titre à votre message" required="required" />
                    </div>
                  </div>';
        $html .= '<div class="form-group">
                    <label for="group_cont_message" class="col-sm-2 control-label required">Votre message</label>
                    <div class="col-sm-10">
                      <textarea id="group_cont_message" name="group_cont_message" class="form-control" rows="10" required="required"></textarea>
                    </div>
                  </div>';
        $html .= '<div class="form-group">
                    <div class="col-sm-offset-10 col-sm-2">
                      <button type="submit" class="btn btn-success pull-right" value="submit" name="submit" id="submit">Envoyer</button>
                    </div>
                  </div>';
        $html .= '</div></div></form>';
        return $html;
    }

}
