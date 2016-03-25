<?php

require __DIR__ . '/autoload.php';
require __DIR__ . '/lib/password.php';
$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
covoiturage\utils\Cache::add('', 'root', $root);
$service = covoiturage\utils\HRequete::getGET('service');
$serviceName = "\\covoiturage\\";
$page = '';
if (empty($service)) {
    $page = covoiturage\utils\HRequete::getGET('page', 'liste');
    $service = $page;
    $group = covoiturage\utils\HRequete::getGET('pagegroup', 'group');
    $serviceName .= "pages\\";
} else {
    $group = covoiturage\utils\HRequete::getGET('pagegroup');
    $serviceName .= "services\\";
}
if (!empty($group)) {
    $serviceName .= $group . "\\";
}
$serviceName .= ucfirst($service);

if (!class_exists($serviceName)) {
    if ($serviceInstance instanceof covoiturage\classes\abstraites\ServiceVue) {
        $serviceName = "\\covoiturage\\pages\\Err404";
    } else {
        $serviceName = "\\covoiturage\\services\\Err404";
    }
}

$serviceInstance = new $serviceName();

// Accès sécurisé ?
if ($serviceInstance->isSecurised()) {
    session_start();
    if ($serviceInstance instanceof \covoiturage\classes\abstraites\ServiceVue &&
            !\covoiturage\utils\HSession::getUser()->existe()) {
        $serviceInstance = new covoiturage\pages\user\Login();
    }
}
covoiturage\utils\HRequete::setGetToPost();
\covoiturage\utils\Cache::add('', 'service', $serviceInstance);

if (!($serviceInstance instanceof covoiturage\classes\abstraites\ServiceVue)) {
    header('Content-Type: application/json');
}
$serviceInstance->executer();
