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
use Exception;

/**
 * Description of Service
 *
 * @author bruno
 */
abstract class Service {

    private $reponse;

    public function __construct() {
        $this->reponse = new \stdClass();
    }

    protected function addResponseItem($key, $value) {
        if (is_object($this->reponse)) {
            $this->reponse->$key = $value;
        }
    }

    protected function setResponse($reponse) {
        $this->reponse = $reponse;
    }

    public abstract function executerService();

    public function executer() {
        ob_start();
        $retour = new \stdClass();
        $retour->isErr = FALSE;
        $retour->message = 'OK';
        HDatabase::openTransaction();
        try {
            $this->executerService();
            $retour->reponse = $this->reponse;
        } catch (Exception $exc) {
            $retour->message = $exc->getMessage();
            $retour->trace = $exc->getTraceAsString();
        }
        echo json_encode($retour);
        HDatabase::closeTransaction($retour->isErr);
    }

    public function getName() {
        return HString::getClassnameWithoutNamespace($this);
    }
    
    protected function getDirname() {
        $classname = get_called_class();
        $path = HString::getNamespacedClassPath($classname, 'covoiturage\\');
        return dirname($path);
    }
    
    protected static function getExtension() {
        return 'serv';
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
        $url .= strtolower($className[3]) . '.' . static::getExtension();
        if (!empty($params)) {
            $url .= '?';
            foreach ($params as $key => $value) {
                $url .= $key . '=' . $value . '&';
            }
        }
        return $url;
    }

}
