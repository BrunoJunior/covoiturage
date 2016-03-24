<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\services\trajetprevisionnel;

use covoiturage\classes\abstraites\Service;
use covoiturage\utils\HRequete;
use covoiturage\classes\metier\Group as GroupBO;
use Exception;
use covoiturage\classes\metier\TrajetPrevisionnel as BO;
use covoiturage\pages\trajetprevisionnel\Repondre;

/**
 * Description of Proposer
 *
 * @author bruno
 */
class Proposer extends Service {

    /**
     * Proposition d'un trajet prévisionnel
     */
    public function executerService() {
        $group = new GroupBO(HRequete::getPOSTObligatoire('group_id'));
        if (!$group->existe() || !$this->getUser()->existe() || $group->isUserPresent($this->getUser())) {
            throw new Exception("Vous n'êtes pas autorisé à accéder à ce service !");
        }
        $date = HRequete::getPOSTObligatoire('prev_date');

        // Création des trois types possibles
        $trajetAller = new BO();
        $trajetAller->conducteur_id = $this->getUser()->id;
        $trajetAller->group_id = $group->id;
        $trajetAller->date = $date;
        $trajetAller->type = BO::TYPE_ALLER;
        $trajetAller->merger();

        $trajetRetour = new BO();
        $trajetRetour->conducteur_id = $this->getUser()->id;
        $trajetRetour->group_id = $group->id;
        $trajetRetour->date = $date;
        $trajetRetour->type = BO::TYPE_RETOUR;
        $trajetRetour->merger();

        $trajetAR = new BO();
        $trajetAR->conducteur_id = $this->getUser()->id;
        $trajetAR->group_id = $group->id;
        $trajetAR->date = $date;
        $trajetAR->type = BO::TYPE_ALLER_RETOUR;
        $trajetAR->merger();

        // Envoi de l'email aux membres du groupe
        $userGroups = $group->getListeUserGroup();
        foreach ($userGroups as $userGroup) {
            $user = $userGroup->getUser();
            $params = ['user_id' => $user->id];
            $message = "<p>$group->nom</p>
                    <p>Je me propose de conduire le $date. <br /> Utilise un des trois liens ci-dessous pour donner une réponse :</p>
                    <a href='".Repondre::getUrl($trajetAller->id, $params)."'>Je suis intéressé par l'aller</a><br />
                    <a href='".Repondre::getUrl($trajetRetour->id, $params)."'>Je suis intéressé par le retour</a><br />
                    <a href='".Repondre::getUrl($trajetAR->id, $params)."'>Je suis intéressé par l'aller et le retour</a>";
            $user->contacter('Proposition de covoiturage le ' . $date, $message);
        }
        $this->setMessage('Proposition envoyée aux membres du groupe !');
    }
}
