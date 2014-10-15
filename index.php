<?php

require_once(__DIR__ . '/protected/helpers/functions.php');

// change the following paths if necessary
$yii = dirname(__FILE__) . '/../framework/yii.php';
$config = dirname(__FILE__) . '/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', TRUE);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

// define value tickets statuses
define('TICKET_CANCELED', 0);
define('TICKET_RESERVED', 1);
define('TICKET_CONFIRMED', 2);

$configMain = require_once(__DIR__ . '/protected/config/main.php');

$cs = __DIR__ . '/protected/config/' . $_SERVER['HTTP_HOST'] . '.php';
if (!file_exists($cs))
	die('Domain config <b>' . $cs . '</b> not found');
$configServer = require_once($cs);

require_once($yii);
$config = CMap::mergeArray($configMain, $configServer);
Yii::createWebApplication($config)->run();