<?php
require __DIR__ . '/erreurs/ErrorHandler.php';
require __DIR__ . '/erreurs/EndHandler.php';
require __DIR__ . '/utils/Conf.php';
require __DIR__ . '/utils/HString.php';


spl_autoload_register(function ($class) {
    // base directory for the namespace prefix
    \covoiturage\utils\Conf::setNonExistingConfByCode(\covoiturage\utils\Conf::BASE_DIR, __DIR__ . DIRECTORY_SEPARATOR);
    // Récupération du chemin avec les namespaces
    $chemin = \covoiturage\utils\HString::getNamespacedClassPath($class, 'covoiturage\\');
    // does the class use the namespace prefix?
    if ($chemin === NULL) {
        // no, move to the next registered autoloader
        return;
    }
    // Le fichier est inconnu --> move to the next registered autoloader
    if (!file_exists($chemin)) {
        return;
    }
    require $chemin;
});