<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\metier;

// DAO
use covoiturage\classes\dao\PassagerPrevisionnel as DAO;
use Exception;

/**
 * Description of Passager
 *
 * @author bruno
 */
class PassagerPrevisionnel extends DAO {

    /**
     * Valider un passager prévisionnel
     * @throws Exception
     */
    public function valider() {
        $trajet = $this->getTrajetPrevisionnel();
        $groupe = $trajet->getGroup();
        $types = $trajet->getCovoiturageTypes();
        foreach ($types as $type) {
            $covoiturage = Covoiturage::chercherDejaPresent($groupe->id, $trajet->date, $type);
            if ($covoiturage->existe() && $covoiturage->conducteur_id != $trajet->conducteur_id) {
                throw new Exception("Un trajet existe déjà à cette date, et vous n'en n'ête pas le conducteur !");
            }
            $covoiturage->conducteur_id = $trajet->conducteur_id;
            $covoiturage->merger();
            $covoiturage->ajouterPassager($this->getUser());
        }

        $this->supprimer();
    }

    /**
     * Un utilisateur ne peut pas répondre pour l'aller, le retour et l'aller-retour
     * @throws Exception
     */
    protected function avantAjout() {
        $trajetPrevisionnel = $this->getTrajetPrevisionnel();
        $trajetsPrevisionnels = TrajetPrevisionnel::getListePourGroupDateEtPassager($trajetPrevisionnel->group_id, $trajetPrevisionnel->date, $this->user_id);
        $dejaRep = [TrajetPrevisionnel::TYPE_ALLER => FALSE, TrajetPrevisionnel::TYPE_RETOUR => FALSE, TrajetPrevisionnel::TYPE_ALLER_RETOUR => FALSE];
        foreach ($trajetsPrevisionnels as $trajetRepondus) {
            // Un type déjà présent s'auto bloque
            $dejaRep[$trajetRepondus->type] = TRUE;
            if ($trajetRepondus->type == TrajetPrevisionnel::TYPE_ALLER_RETOUR) {
                // L'aller retour bloque la possibiltié d'ajouter un aller ou un retour
                $dejaRep[TrajetPrevisionnel::TYPE_ALLER] = TRUE;
                $dejaRep[TrajetPrevisionnel::TYPE_RETOUR] = TRUE;
            } else {
                // L'aller ou le retour bloque la possibiltié d'ajouter l'alelr-retour
                $dejaRep[TrajetPrevisionnel::TYPE_ALLER_RETOUR] = TRUE;
            }
        }
        if ($dejaRep[$trajetPrevisionnel->type]) {
            throw new Exception("Vous avez déjà répondu à ce trajet prévisionnel !");
        }
    }

}
