<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\presentation;

use covoiturage\classes\metier\User as UserBO;
use covoiturage\pages\user\Login;
use covoiturage\pages\user\Edit;
use covoiturage\utils\HSession;

/**
 * Description of User
 *
 * @author bruno
 */
class User extends UserBO {
    public static function getConnexionForm() {
        return '<form action="'.Login::getUrl().'" class="form-horizontal" method="POST">
                    <div class="form-group">
                      <label for="user_email" class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Email">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="user_password" class="col-sm-2 control-label">Mot de passe</label>
                      <div class="col-sm-10">
                        <input type="password" class="form-control" id="user_password" name="user_password" placeholder="Mot de passe">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                          <label>
                            <input type="checkbox"> Se souvenir de moi
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default" value="submit" name="submit" id="submit">Se connecter</button>
                      </div>
                    </div>
                  </form>';
    }

    public function getForm() {
        $html = '<form action="'.Edit::getUrl().'" class="form-horizontal" method="POST">
                    <input type="hidden" name="id" value="' . $this->id . '" />';

        if (HSession::getUser()->admin) {
            $html .= '<div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="admin" id="admin" ' . $this->admin ? 'checked' : '' . '> Administrateur
                            </label>
                          </div>
                        </div>
                    </div>';
        }
                    
        $html .=   '<div class="form-group">
                      <label for="prenom" class="col-sm-2 control-label">Prénom</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom" value="' . $this->prenom . '">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="nom" class="col-sm-2 control-label">Nom</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" value="' . $this->nom . '">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="email" class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="' . $this->email . '">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="tel" class="col-sm-2 control-label">N° de téléphone</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="tel" name="tel" placeholder="0601020304" value="' . $this->tel . '">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="old_password" class="col-sm-2 control-label">Ancien mot de passe</label>
                      <div class="col-sm-10">
                        <input type="password" class="form-control" id="old_password" name="old_password">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="password" class="col-sm-2 control-label">Nouveau mot de passe</label>
                      <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" name="password">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="password_check" class="col-sm-2 control-label">Retapez</label>
                      <div class="col-sm-10">
                        <input type="password" class="form-control" id="password_check" name="password_check">
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
