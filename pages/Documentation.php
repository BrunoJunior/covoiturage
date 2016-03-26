<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\pages;

// Vue
use covoiturage\classes\abstraites\ServiceVue;
use covoiturage\utils\Cache;

/**
 * Description of Documentation
 *
 * @author bruno
 */
class Documentation extends ServiceVue {

    /**
     * Documentation
     */
    public function executerService() {
        echo '<div id="doc">';
        echo '<h4>Connexion / Oubli de mot de passe</h4>';
        echo static::getImage('connexion', 'connexion', "En cas d'oubli du mot de passe, renseignez votre adresse email et cliquez sur le bouton \"J'ai oublié\".");
        echo static::getImage('oubli', 'oubli de mot de passe', "Un email est envoyé si un utilisateur existe avec l'adresse email renseignée.");
        echo '<h4>Inscription</h4>';
        echo static::getImage('inscription', 'inscription', "Informations à remplir pour une inscription.");
        echo '<h4>Actions générales</h4>';
        echo static::getImage('action_nav', 'actions générales', "Actions présentes dans la barre de navigation.");
        echo '<h4>Informations utilisateur</h4>';
        echo static::getImage('maj_user', 'informations utilisateur', "Vous trouverez ici vos informations que vous pouvez modifier.");
        echo '<h4>Gestion des groupes</h4>';
        echo static::getImage('ajouter_groupe', 'ajouter groupe', "Cette tuile permet de créer un nouveau groupe, dont vous serez l'administrateur.");
        echo static::getImage('maj_group', 'modifier ou créer un groupe', "Vous devez ensuite renseigner un nom pour le groupe.<br />"
                . "La seconde partie de l'écran ne sera visible qu'après la création du groupe.<br />"
                . "Vous pouvez ensuite ajouter ou enlever des membres à votre groupe.");
        echo static::getImage('tuile', 'tuile d\'un groupe', "Cette tuile représente un groupe et liste les informations les plus utiles.<br />"
                . "Une série d'actions sont disponibles sur un groupe.");
        echo '<h4>Récapitulatif des encours</h4>';
        echo static::getImage('recap', 'Encours', "Cet écran présente, pour chaque membre du groupe, le nombre de trajet dus.<br />"
                . "Une valeur négative représente un membre débiteur.<br />"
                . "Une valeur positive représente un membre créditeur.");
        echo '<h4>Gestion des trajets</h4>';
        echo static::getImage('proposer_trajet', 'proposition d\'un trajet', "Le membre souhaite spécifier aux membres de son groupe qu'il se propose comme conducteur pour un futur trajet.<br />"
                . "Les autres membres vont recevoir un email pour pouvoir donner leur réponse.");
        echo static::getImage('trajets_prev', 'Liste des trajets proposés', "Les trajets proposés apparaissent dans la gestion de trajets.<br />"
                . "Chaque ligne représente un aller, un retour ou un aller/retour.<br />"
                . "Sur chaque ligne, les membres ayant répondu favorablement sont ajoutés en tant que passager.<br />"
                . "Si le covoiturage a lieu avec les personnes prévues, le conducteur peut valider ses trajets.<br />"
                . "Si des membres prévus n'étaient pas présent, il est possible de les enlever du trajet prévisionnel avant de le valider.");
        echo static::getImage('nouv_trajet', 'Ajouter un trajet', "Vous avez été conducteur pour le groupe sélectionné.<br />"
                . "Vous pouvez donc créer un trajet en spécifiant la date et les passagers.<br />"
                . "Par défaut, la date est initialisée à la date du jour.<br />"
                . "Seuls les administrateurs ont la possibilité de choisir un conducteur.");
        echo static::getImage('trajets_cond', 'Liste des trajets conducteur', "Cette tableau liste tous les trajets que vous avez réalisés en tant que conducteur.<br />"
                . "Ce tableau est paginé, il ne contient que 10 trajets par page.<br />"
                . "Vous pouvez naviguer grâce au bloc de pagination au dessus du tableau.<br />"
                . "Les trajets sont triés dans l'ordre chronologique inversé.");
        echo static::getImage('trajets_pass', 'Liste des trajets passager', "Cette tableau liste tous les trajets que vous avez réalisés en tant que passager.<br />"
                . "Ce tableau est paginé, il ne contient que 10 trajets par page.<br />"
                . "Vous pouvez naviguer grâce au bloc de pagination au dessus du tableau.<br />"
                . "Les trajets sont triés dans l'ordre chronologique inversé.");
        echo '<h4>Contacter les autres membres</h4>';
        echo static::getImage('contact', 'Formulaire de contact', "Vous pouvez joindre un membre ou tous les membres d'un groupe dont vous faites partie.<br />"
                . "Vous avez simplement à renseigner un titre et un message et un email sera envoyé à votre nom.");
        echo '</div>';
    }

    /**
     * Titre de la page
     * @return string
     */
    public function getTitre() {
        return 'Documentation';
    }

    /**
     * La page n'est pas sécurisé
     * @return boolean
     */
    public function isSecurised() {
        return FALSE;
    }

    /**
     * Image de doc
     * @param string $nom
     * @param string $alt
     * @return string
     */
    private static function getImage($nom, $alt, $legend = '') {
        $root = Cache::get('', 'root');
        $html = '<p class="text-center"><img src="' . $root . 'resources/img/doc/' . $nom . '.jpg" class="img-responsive img-thumbnail center-block" alt="' . $alt . '" />';
        if (!empty($legend)) {
            $html .= '<em class="legend">'.$legend.'</em>';
        }
        $html .= '</p>';
        return $html;
    }

}
