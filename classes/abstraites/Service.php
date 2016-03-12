<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\abstraites;

use covoiturage\utils\HString;
use covoiturage\utils\HDatabase;
use covoiturage\utils\Cache;

/**
 * Description of Service
 *
 * @author bruno
 */
abstract class Service {

    private $jsFiles = [];
    private $cssFiles = [];

    /**
     * @return string Titre de la page
     */
    public abstract function getTitre();

    public abstract function executerService();

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

    public function getName() {
        return HString::getClassnameWithoutNamespace($this);
    }

    private function getDirname() {
        $classname = get_called_class();
        $path = HString::getNamespacedClassPath($classname, 'covoiturage\\');
        return dirname($path);
    }

    protected function getJsDirname() {
        $this->getDirname() . DIRECTORY_SEPARATOR . 'js';
    }

    protected function getCssDirname() {
        $this->getDirname() . DIRECTORY_SEPARATOR . 'js';
    }

    private function insertJs() {
        foreach ($this->jsFiles as $file) {
            echo "\n<script type=\"text/javascript\" >\n";
            echo "$(document).ready(function(){\n";
            include $file;
            echo "\n});";
            echo "\n</script>\n";
        }
    }

    private function insertCss() {
        foreach ($this->cssFiles as $file) {
            echo "\n<style type='text/css' media='all'>\n";
            include $file;
            echo "\n</style>\n";
        }
    }

    public static function getUrl($id = NULL, $params = []) {
        if (array_key_exists('id', $params)) {
            $id = $params['id'];
            unset($params['id']);
        }
        $className = explode('\\', get_called_class());
        $url = Cache::get('', 'root');
        $url .= $className[2] . '/';
        if (!empty($id)) {
            $url .= $id . '/';
        }
        $url .= strtolower($className[3]) .'.html';
        if (!empty($params)) {
            $url .= '?';
            foreach ($params as $key => $value) {
                $url .= $key . '=' . $value . '&';
            }
        }
        return $url;
    }

}
