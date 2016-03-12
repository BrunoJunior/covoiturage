<?php
require __DIR__ . '/autoload.php';
$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
covoiturage\utils\Cache::add('', 'root', $root);
$page = covoiturage\utils\HRequete::getGET('page', 'liste');
$pagegroup = covoiturage\utils\HRequete::getGET('pagegroup', 'group');
$serviceName = "\\covoiturage\\pages\\" . $pagegroup . "\\" . ucfirst($page);
if (!class_exists($serviceName)) {
    $serviceName = "\\covoiturage\\pages\\Err404";
}
$service = new $serviceName();
covoiturage\utils\HRequete::setGetToPost();
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
              <img src="<?php echo $root; ?>resources/img/visu.jpg" class="img-responsive img-thumbnail pull-left" alt="Logo" />
              <h1 class="text-center"><span class="label label-default">Appli de gestion de co-voiturage</span></h1>
              <hr />
              <h3 class="text-center"><span class="label label-info"><?php echo $service->getTitre();?></span></h3>
          </div>
        </header>
        <div id="main-page" class='container'>
            <?php
                $service->executer();
            ?>
        </div>
    <script src="<?php echo $root; ?>lib/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
