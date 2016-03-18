<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\abstraites;

/**
 * Helpers
 */
use covoiturage\utils\HDatabase;
use covoiturage\utils\HLog;
use covoiturage\utils\Cache;
use Exception;
/**
 * Services
 */
use covoiturage\services\user\Logout;
use covoiturage\pages\user\Edit;

/**
 * Description of ServiceVue
 *
 * @author bruno
 */
abstract class ServiceVue extends Service {

    /**
     * Liste des fichiers js liés
     * @var array
     */
    private $jsFiles = [];
    /**
     * Liste des fichiers css liés
     * @var type
     */
    private $cssFiles = [];
    /**
     * Titre du service de vue
     * @var string
     */
    private $titre;
    /**
     * Ce service doit-il être chargé complètement
     * (page complète)
     * @var boolean
     */
    private $complete = TRUE;

    /**
     * @return string Titre de la page
     */
    public abstract function getTitre();

    /**
     * Setter titre de la page
     * @param type $titre
     */
    public function setTitre($titre) {
        $this->titre = $titre;
    }

    /**
     * La vue doit-elle être chargée complètement
     * @return boolean
     */
    public function isComplete() {
        return TRUE;
    }

    /**
     * Gestion de l'exécution d'un service de vue
     * @param string $titre
     */
    public function executer($titre = NULL) {
        if ($titre === NULL) {
            $titre = $this->getTitre();
        }
        $this->complete = $this->isComplete();
        $this->setTitre($titre);
        $this->addJs($this->getName());
        $this->addCss($this->getName());
        ob_start();
        $err = FALSE;
        if ($this->complete) {
            HDatabase::openTransaction();
        }
        try {
            if ($this->complete) {
                $this->afficher();
            } else {
                $this->executerService();
            }
            echo ob_get_clean();
        } catch (Exception $exc) {
            $err = TRUE;
            HLog::logError($exc->getMessage());
            HLog::logError($exc->getTraceAsString());
            echo '<div class="alert alert-danger" role="alert">';
            echo $exc->getMessage();
            echo '</div>';
        }
        if ($this->complete) {
            HDatabase::closeTransaction($err);
        }
        $this->insertJs();
        $this->insertCss();
    }

    /**
     * Afficher la vue complète
     */
    private function afficher() {
        $root = Cache::get('', 'root');
        $user = $this->getUser();
        echo '
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <title>Co-voiturage</title>
            <link rel="stylesheet" href="' . $root . 'lib/bootstrap/css/bootstrap.min.css">
            <link rel="stylesheet" href="' . $root . 'resources/css/global.css">
            <link rel="stylesheet" href="' . $root . 'lib/jquery-ui/jquery-ui.min.css">
        </head>
        <body>
            <script type="text/javascript" src="'.$root.'lib/jquery/jquery-2.1.4.min.js"></script>
            <script type="text/javascript" src="'.$root.'lib/jquery-ui/jquery-ui.min.js"></script>
            <header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
                <div class="container">
                    <a href="/"><img src="'.$root.'resources/img/visu.jpg" class="img-responsive img-thumbnail pull-left" alt="Logo" /></a>';
        if (!empty($user) && $user->existe()) {
            echo '<button id="cov-deco" class="btn btn-danger deconnexion" url="' . Logout::getUrl() . '" role="button" data-toggle="tooltip" title="Déconnexion"><span class="glyphicon glyphicon-log-out"></span></button>';
            echo '<a class="btn btn-primary account" href="' . Edit::getUrl($user->id) . '" role="button" data-toggle="tooltip" title="Mes infos"><span class="glyphicon glyphicon-user"></span></a>';
        }
        echo  '<h1 class="text-center"><span class="label label-default">Gestion de co-voiturage</span></h1>
                    <hr />
                    <h3 class="text-center"><span class="label label-info">' . $this->titre . '</span></h3>
                </div>
            </header>
            <div id="main-page" class="container">
            <div id="cov-alert-error" class="alert alert-danger hidden" role="alert">
                <span class="message"></span>
            </div>
            <div id="cov-alert-success" class="alert alert-success hidden" role="alert">
                <span class="message"></span>
            </div>';
        $this->executerService();
            echo '</div>
                <script src="' . $root . 'lib/bootstrap/js/bootstrap.min.js"></script>
                <script src="' . $root . 'resources/js/global.js"></script>
        </body>
    </html>';
    }

    /**
     * Ajouter un fichier javascript à la vue
     * @param string $filename
     */
    protected function addJs($filename) {
        $filePath = $this->getJsDirname() . DIRECTORY_SEPARATOR . $filename . '.js';
        if (file_exists($filePath)) {
            $this->jsFiles[] = $filePath;
        }
    }

    /**
     * Ajouter un ficheir css à la vue
     * @param string $filename
     */
    protected function addCss($filename) {
        $filePath = $this->getCssDirname() . DIRECTORY_SEPARATOR . $filename . '.css';
        if (file_exists($filePath)) {
            $this->cssFiles[] = $filePath;
        }
    }

    /**
     * Obtenir le répertoire où sont stockés les fichiers javascript pour la vue
     * @return string
     */
    protected function getJsDirname() {
        return $this->getDirname() . DIRECTORY_SEPARATOR . 'js';
    }

    /**
     * Obtenir le répertoire où sont stockés les fichiers css pour la vue
     * @return string
     */
    protected function getCssDirname() {
        return $this->getDirname() . DIRECTORY_SEPARATOR . 'css';
    }

    /**
     * Ajout des fichiers javascript à la vue
     */
    protected function insertJs() {
        foreach ($this->jsFiles as $file) {
            echo "\n<script type=\"text/javascript\" >\n";
            echo "$(document).ready(function(){\n";
            include $file;
            echo "\n});";
            echo "\n</script>\n";
        }
    }

    /**
     * Ajout des fichiers CSS à la vue
     */
    protected function insertCss() {
        foreach ($this->cssFiles as $file) {
            echo "\n<style type='text/css' media='all'>\n";
            include $file;
            echo "\n</style>\n";
        }
    }

    /**
     * L'extension d'un service de vue sera html
     * @return string
     */
    protected static function getExtension() {
        return 'html';
    }
}
