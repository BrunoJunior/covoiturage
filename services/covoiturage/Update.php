<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\covoiturage;

// Service traitement
use covoiturage\classes\abstraites\Service;
// BO
use covoiturage\classes\metier\User as UserBO;
use covoiturage\classes\metier\Covoiturage as BO;
use covoiturage\classes\metier\Passager as PassagerBO;
// Helpers
use covoiturage\utils\HRequete;
use Exception;

/**
 * Description of Add
 *
 * @author bruno
 */
class Update extends Service {

    /**
     * Ajout d'un trajet
     * @throws Exception
     */
    public function executerService() {
        $user = $this->getUser();
        if (!$user->admin) {
            throw new Exception('Vous n\'êtes pas autorisé à exécuter cette action !');
        }
        $covoiturage = new BO(HRequete::getPOSTObligatoire('covoiturage_id'));
        if (!$covoiturage->existe()) {
            throw new Exception("Trajet inconnu !");
        }
        $group = $covoiturage->getGroup();
        $conducteur = new UserBO(HRequete::getPOSTObligatoire('cov_conducteur'));
        if (!$conducteur->isDansGroupe($group)) {
            throw new Exception("Le conducteur n'appartient pas au groupe !");
        }
        $clesCbPassager = HRequete::getListeClePostCommencant('cov_pass_cb_');
        if (empty($clesCbPassager)) {
            throw new Exception("Sélectionnez au moins un passager !");
        }
        $type = HRequete::getPOSTObligatoire('cov_type');
        if ($type != 0 && $type != 1) {
            throw new Exception("Type de trajet inconnu !");
        }
        
        $covoiturage->conducteur_id = $conducteur->id;
        $covoiturage->group_id = $group->id;
        $covoiturage->date = HRequete::getPOSTObligatoire('cov_date');
        $covoiturage->type = $type;
        $covoiturage->merger();

        $idPassagers = [];
        foreach ($clesCbPassager as $cle) {
            $idPassager = HRequete::getPOST($cle);
            $idPassagers[] = $idPassager;
            $userPassager = new UserBO($idPassager);
            if (!$userPassager->isDansGroupe($group)) {
                throw new Exception("Un des passager ne fait pas partie du groupe !");
            }
            $passager = PassagerBO::chargerParCovoiturageEtUser($covoiturage, $userPassager);
            $passager->merger();
        }

        $passagers = $covoiturage->getListePassagers();
        foreach ($passagers as $passagerASuppr) {
            if (!in_array($passagerASuppr->user_id, $idPassagers)) {
                $passagerASuppr->supprimer();
            }
        }

        $this->setMessage('Trajet modifié');
    }

}
