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
use covoiturage\utils\HString;
use covoiturage\utils\HDatabase;
use covoiturage\utils\Cache;
use covoiturage\utils\HSession;
use Exception;

/**
 * Description of Service
 *
 * @author bruno
 */
abstract class Service {

    /**
     * Réponse du service
     * @var \stdClass
     */
    private $reponse;

    /**
     * Message simple de retour
     * @var string
     */
    private $message = 'OK';

    /**
     * Utilisateur connecté
     * @var \covoiturage\classes\metier\User
     */
    private $user;

    /**
     * Constructeur
     */
    public function __construct() {
        $this->reponse = new \stdClass();
    }

    /**
     * Ajouter un élément à la réponse
     * @param string $key
     * @param mixed $value
     */
    protected function addResponseItem($key, $value) {
        if (is_object($this->reponse)) {
            $this->reponse->$key = $value;
        }
    }

    /**
     * Setter $reponse
     * @param \stdClass $reponse
     */
    protected function setResponse($reponse) {
        $this->reponse = $reponse;
    }

    /**
     * Setter message simple de retour
     * @param string $message
     */
    protected function setMessage($message) {
        $this->message = $message;
    }

    /**
     * Méthode d'exécution du service à surcharger
     */
    public abstract function executerService();

    /**
     * Gestion de l'exécution du service
     * Overture/Fermeture ... transaction
     */
    public function executer() {
        ob_start();
        $retour = new \stdClass();
        $retour->isErr = FALSE;
        $retour->message = 'OK';
        HDatabase::openTransaction();
        try {
            $this->executerService();
            $retour->reponse = $this->reponse;
            $retour->message = $this->message;
        } catch (Exception $exc) {
            $retour->isErr = TRUE;
            $retour->message = $exc->getMessage();
            $retour->trace = $exc->getTraceAsString();
        }
        echo json_encode($retour);
        HDatabase::closeTransaction($retour->isErr);
    }

    /**
     * Récupérer le nom du service
     * @return string
     */
    public function getName() {
        return HString::getClassnameWithoutNamespace($this);
    }

    /**
     * Récupérer le répertoire du service
     * @return string
     */
    protected function getDirname() {
        $classname = get_called_class();
        $path = HString::getNamespacedClassPath($classname, 'covoiturage\\');
        return dirname($path);
    }

    /**
     * Extension par défaut d'un service
     * Les services héritant de cette classe seront des services de traitement
     * @return string
     */
    protected static function getExtension() {
        return 'serv';
    }

    /**
     * Obtenir l'url du service
     * @param int $id
     * @param array $params
     * @return string
     */
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

    /**
     * L'accès au service est-il sécurisé
     * @return boolean
     */
    public function isSecurised() {
        return TRUE;
    }

    /**
     * Utilisateur connecté
     * @return \covoiturage\classes\metier\User
     */
    protected function getUser() {
        if (!$this->isSecurised()) {
            return NULL;
        }
        if ($this->user == NULL) {
            $this->user = HSession::getUser();
        }
        return $this->user;
    }

    /**
     *
     */
    protected function avantExecuterService() {

    }

    /**
     *
     * @param boolean $isErr
     */
    protected function apresExecuterService($isErr) {

    }

}
