<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\abstraites;

use covoiturage\utils\HDatabase;
use Exception;

/**
 * Description of ServiceVue
 *
 * @author bruno
 */
abstract class ServiceVue extends Service {

    private $jsFiles = [];
    private $cssFiles = [];

    /**
     * @return string Titre de la page
     */
    public abstract function getTitre();

    public function executer() {
        $this->addJs($this->getName());
        $this->addCss($this->getName());
        ob_start();
        $err = FALSE;
        HDatabase::openTransaction();
        try {
            $this->executerService();
            echo ob_get_clean();
        } catch (Exception $exc) {
            $err = TRUE;
            echo '<div class="alert alert-danger" role="alert">';
            echo 'Doh! ' . $exc->getTraceAsString();
            echo '</div>';
        }
        HDatabase::closeTransaction($err);
        $this->insertJs();
        $this->insertCss();
    }

    protected function addJs($filename) {
        $filePath = $this->getDirname() . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $filename . '.js';
        if (file_exists($filePath)) {
            $this->jsFiles[] = $filePath;
        }
    }

    protected function addCss($filename) {
        $filePath = $this->getDirname() . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $filename . '.css';
        if (file_exists($filePath)) {
            $this->cssFiles[] = $filePath;
        }
    }

    protected function getJsDirname() {
        $this->getDirname() . DIRECTORY_SEPARATOR . 'js';
    }

    protected function getCssDirname() {
        $this->getDirname() . DIRECTORY_SEPARATOR . 'js';
    }

    protected function insertJs() {
        foreach ($this->jsFiles as $file) {
            echo "\n<script type=\"text/javascript\" >\n";
            echo "$(document).ready(function(){\n";
            include $file;
            echo "\n});";
            echo "\n</script>\n";
        }
    }

    protected function insertCss() {
        foreach ($this->cssFiles as $file) {
            echo "\n<style type='text/css' media='all'>\n";
            include $file;
            echo "\n</style>\n";
        }
    }

    protected static function getExtension() {
        return 'html';
    }
}
