<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages\trajetprevisionnel;

use covoiturage\classes\abstraites\ServiceVue;
use covoiturage\utils\HRequete;
use covoiturage\classes\metier\TrajetPrevisionnel as BO;
use covoiturage\classes\metier\User as UserBO;
use covoiturage\classes\metier\PassagerPrevisionnel as PassagerPrevisionnelBO;

/**
 * Description of Repondre
 *
 * @author bruno
 */
class Repondre extends ServiceVue {

    /**
     * La chaine suivant le type
     * @param BO $trajetPrevisionnel
     * @return string
     */
    private static function getStrType(BO $trajetPrevisionnel) {
        switch ($trajetPrevisionnel->type) {
            case BO::TYPE_ALLER:
                $type = "l'aller";
                break;
            case BO::TYPE_RETOUR:
                $type = "le retour";
                break;
            case BO::TYPE_ALLER_RETOUR:
                $type = "l'aller et le retour";
                break;
        }
        return $type;
    }
    /**
     * Réception d'une réponse pour un trajet prévisionnel
     */
    public function executerService() {
        $trajetPrevisionnel = new BO(HRequete::getPOSTObligatoire('id'));
        $user = new UserBO(HRequete::getPOSTObligatoire('user_id'));
        if (!$trajetPrevisionnel->existe() || !$user->existe()) {
            throw new Exception("Vous n'êtes pas autorisé à accéder à cette page !");
        }
        $passager = new PassagerPrevisionnelBO();
        $passager->trajet_previsionnel_id = $trajetPrevisionnel->id;
        $passager->user_id = $user->id;
        $passager->merger();
        $type = static::getStrType($trajetPrevisionnel);
        $conducteur = $trajetPrevisionnel->getConducteur();
        $conducteur->contacter(
                "Proposition de trajet du " . $trajetPrevisionnel->date, 
                $user->toHtml() . " a accpeté de covoiturer le " . $trajetPrevisionnel->date . " pour " . $type . ".");

        echo "<p>Merci, l'information a été transmise à " . $conducteur->toHtml() . "</p>";
    }

    public function isSecurised() {
        return FALSE;
    }

    public function getTitre() {
        return 'Réponse';
    }

}
