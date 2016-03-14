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
    $serviceName = "\\covoiturage\\pages\\Err404";
}

$serviceInstance = new $serviceName();

// Accès sécurisé ?
if ($serviceInstance->isSecurised()) {
    session_start();
    if (!\covoiturage\utils\HSession::getUser()->existe()) {
        $serviceInstance = new covoiturage\pages\user\Login();
    }
}


covoiturage\utils\HRequete::setGetToPost();

if (!($serviceInstance instanceof covoiturage\classes\abstraites\ServiceVue)) {
    header('Content-Type: application/json');
    $serviceInstance->executer();
} else {
    ?>

    <!DOCTYPE html>
    <!--
    To change this license header, choose License Headers in Project Properties.
    To change this template file, choose Tools | Templates
    and open the template in the editor.
    -->
    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <title>Co-voiturage</title>
            <link rel="stylesheet" href="<?php echo $root; ?>lib/bootstrap/css/bootstrap.min.css">
            <link rel="stylesheet" href="<?php echo $root; ?>resources/css/global.css">
        </head>
        <body>
            <script type="text/javascript" src="<?php echo $root; ?>lib/jquery/jquery-2.1.4.min.js"></script>
            <header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
                <div class="container">
                    <a href="/"><img src="<?php echo $root; ?>resources/img/visu.jpg" class="img-responsive img-thumbnail pull-left" alt="Logo" /></a>
                    <?php
                        $user = \covoiturage\utils\HSession::getUser();
                        if ($user->existe()) {
                            echo '<a class="btn btn-danger deconnexion" href="'.\covoiturage\services\user\Logout::getUrl().'" role="button"><span class="glyphicon glyphicon-log-out"></span></a>';
                            echo '<a class="btn btn-primary account" href="' . \covoiturage\pages\user\Edit::getUrl($user->id) . '" role="button"><span class="glyphicon glyphicon-user"></span></a>';
                        }
                    ?>
                    <h1 class="text-center"><span class="label label-default">Gestion de co-voiturage</span></h1>
                    <hr />
                    <h3 class="text-center"><span class="label label-info"><?php echo $serviceInstance->getTitre(); ?></span></h3>
                </div>
            </header>
            <div id="main-page" class='container'>
                <?php
                $serviceInstance->executer();
                ?>
            </div>
            <script src="<?php echo $root; ?>lib/bootstrap/js/bootstrap.min.js"></script>
        </body>
    </html>
    <?php
}