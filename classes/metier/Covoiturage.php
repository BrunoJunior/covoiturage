<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\metier;

use Exception;

// DAO
use covoiturage\classes\dao\Covoiturage as DAO;

/**
 * Description of Covoiturage
 *
 * @author bruno
 */
class Covoiturage extends DAO {
    const TYPE_ALLER = 0;
    const TYPE_RETOUR = 1;

    /**
     * Ne pas créer si un trajet existe déjà pour un groupe un type et une date donnés
     * @throws Exception
     */
    protected function avantAjout() {
        if ($this->isDejaPresent()) {
            throw new Exception('Covoiturage déjà présent à cette date !');
        }
        if ($this->transformerValeurPourBdd('date') > date('Y-m-d')) {
            throw new Exception('Vous ne pouvez renseigner de trajet à venir !');
        }
    }

    /**
     * Ne pas modifier si un trajet existe déjà pour un groupe un type et une date donnés
     * @throws Exception
     */
    protected function avantModification() {
        if ($this->isDejaPresent()) {
            throw new Exception('Covoiturage déjà présent à cette date !');
        }
        if ($this->transformerValeurPourBdd('date') > date('Y-m-d')) {
            throw new Exception('Vous ne pouvez renseigner de trajet à venir !');
        }
    }

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
     * Ajoute un passager au trajet
     * @param User $user
     * @return void
     */
    public function ajouterPassager(User $user) {
        if ($this->isPassagerDejaPresent($user)) {
            return;
        }
        $passager = new Passager();
        $passager->covoiturage_id = $this->id;
        $passager->user_id = $user->id;
        $passager->merger();
    }
}
