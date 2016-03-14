<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services;

/**
 * Description of Infos
 *
 * @author bruno
 */
class Infos extends \covoiturage\classes\abstraites\Service {
    public function executerService() {
        phpinfo();
    }
}
