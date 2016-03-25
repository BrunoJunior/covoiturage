<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\metier;

use Exception;

// DAO
use covoiturage\classes\dao\TrajetPrevisionnel as DAO;

/**
 * Description of TrajetPrevisionnel
 *
 * @author bruno
 */
class TrajetPrevisionnel extends DAO {
    const TYPE_ALLER = 0;
    const TYPE_RETOUR = 1;
    const TYPE_ALLER_RETOUR = 2;


    /**
     * Supprimer les passager avant la suppression du trajet
     */
    protected function avantSuppression() {
        $passagers = $this->getListePassagers();
        foreach ($passagers as $passager) {
            $passager->supprimer();
        }
    }

    /**
     * Eviter les doublons
     * @throws Exception
     */
    protected function avantAjout() {
        if ($this->isDejaPresent()) {
            throw new Exception("Un trajet prévisionnel est déjà prévu à cette date !");
        }
    }

    /**
     * Suppression des trajets prévisionnels obsolètes
     */
    public static function supprimerObsoletes() {
        $obsoletes = static::getListeObsoletes();
        foreach ($obsoletes as $obsolete) {
            $obsolete->supprimer();
        }
    }

    /**
     * Valider un trajet prévisionnel
     */
    public function valider() {
        $passagers = $this->getListePassagers();
        foreach ($passagers as $passager) {
            $passager->valider();
        }
        $this->supprimer();
    }

    /**
     * Obtenir les types de trajet suivant le type de trajet prévisionnel
     * @return array
     */
    public function getCovoiturageTypes() {
        $types = [];
        switch ($this->type) {
            case TrajetPrevisionnel::TYPE_ALLER_RETOUR :
                $types = [Covoiturage::TYPE_ALLER, Covoiturage::TYPE_RETOUR];
                break;
            case TrajetPrevisionnel::TYPE_ALLER :
                $types = [Covoiturage::TYPE_ALLER];
                break;
            case TrajetPrevisionnel::TYPE_RETOUR :
                $types = [Covoiturage::TYPE_RETOUR];
                break;
        }
        return $types;
    }
}
