<?php
require_once __DIR__.'/config/config.php';
require_once __DIR__.'/classes/database.php';
require_once __DIR__.'/interfaces/imodel.php';
require_once __DIR__.'/classes/model.php';
require_once __DIR__.'/classes/user.php';
require_once __DIR__.'/classes/customer.php';
require_once __DIR__.'/classes/reasonvisit.php';
require_once __DIR__.'/classes/area.php';
require_once __DIR__.'/classes/visit.php';
require_once __DIR__.'/classes/report.php';
require_once __DIR__.'/classes/observation.php';
require_once __DIR__.'/classes/visitarea.php';
require_once __DIR__.'/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

$pagina = [];
// Crear servicio de registro
$logger = new Logger('my_logger');

// agrega algunos procesadores
$logger->pushHandler(new StreamHandler(__DIR__.'/my_app.log', Logger::DEBUG));
$logger->pushHandler(new FirePHPHandler());



