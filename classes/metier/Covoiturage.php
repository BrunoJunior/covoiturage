<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\metier;

use covoiturage\classes\dao\Covoiturage as CovoiturageDAO;

/**
 * Description of Covoiturage
 *
 * @author bruno
 */
class Covoiturage extends CovoiturageDAO {
    const TYPE_ALLER = 0;
    const TYPE_RETOUR = 1;
}
