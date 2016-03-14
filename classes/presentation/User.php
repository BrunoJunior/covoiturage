<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\presentation;

use covoiturage\classes\metier\User as UserBO;
use covoiturage\pages\user\Login;

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
}
