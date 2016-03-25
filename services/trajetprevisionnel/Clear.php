<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\trajetprevisionnel;

use covoiturage\classes\abstraites\Service;

use covoiturage\classes\metier\Group as GroupBO;
use covoiturage\classes\metier\TrajetPrevisionnel as BO;
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Clear
 *
 * @author bruno
 */
class Clear extends Service {
    /**
     * Suppression des trajets prévisionnels passés
     */
    public function executerService() {
        $groupe = new GroupBO(HRequete::getPOSTObligatoire('group_id'));
        if (!$groupe->isUserPresent($this->getUser())) {
            throw new Exception("Vous n'êtes pas autorisé à exécuter ce service !");
        }
        BO::supprimerObsoletes();
        $this->setMessage("Trajets prévisionnels passés supprimés !");
    }
}
