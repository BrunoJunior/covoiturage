<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\covoiturage;

use covoiturage\classes\abstraites\Service;
use covoiturage\utils\HRequete;
use Exception;
use covoiturage\classes\metier\Covoiturage as CovoiturageBO;
use covoiturage\classes\metier\Group as GroupBO;
use covoiturage\classes\metier\Passager as PassagerBO;
use covoiturage\classes\metier\User as UserBO;
use DateTime;

/**
 * Description of Add
 *
 * @author bruno
 */
class Add extends Service {

    public function executerService() {
        $user = $this->getUser();
        $idConducteur = HRequete::getPOST('cov_conducteur', $user->id);
        if (!$user->admin && $idConducteur != $user->id) {
            throw new Exception("Vous n'êtes pas autorisé à créer un trajet avec un autre conducteur que vous-même !");
        }
        $date = DateTime::createFromFormat('d/m/Y', HRequete::getPOSTObligatoire('cov_date'));
//        $idPassagers = explode(',', HRequete::getPOSTObligatoire('cov_passagers'));
        $clesCbPassager = HRequete::getListeClePostCommencant('cov_pass_cb_');
        if (empty($clesCbPassager)) {
            throw new Exception("Sélectionnez au moins un passager !");
        }
        $types = explode(',', HRequete::getPOSTObligatoire('submit'));
        $group = new GroupBO(HRequete::getPOSTObligatoire('group_id'));
        if (!$user->isDansGroupe($group) && !$user->admin) {
            throw new Exception("Vous ne faites pas partie de ce groupe !");
        }
        foreach ($types as $type) {
            if ($type != 0 && $type !=1) {
                throw new Exception("Type de trajet inconnu !");
            }
            $covoiturage = new CovoiturageBO();
            $covoiturage->conducteur_id = $idConducteur;
            $covoiturage->group_id = $group->id;
            $covoiturage->date = $date->format('Y-m-d');
            $covoiturage->type = $type;
            $covoiturage->merger();

            foreach ($clesCbPassager as $cle) {
                $idPassager = HRequete::getPOST($cle);
                $userPassager = new UserBO($idPassager);
                if (!$userPassager->isDansGroupe($group)) {
                    throw new Exception("Un des passager ne fait pas partie du groupe !");
                }
                $passager = new PassagerBO();
                $passager->covoiturage_id = $covoiturage->id;
                $passager->user_id = $idPassager;
                $passager->merger();
            }
        }

        $this->setMessage('Trajet créé');
    }

}