<?php

$config = array(
	'basePath'   => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name'       => 'My Console Application',

	// preloading 'log' component
	'preload'    => array('log'),

	// application components
	'components' => array(
		'log' => array(
			'class'  => 'CLogRouter',
			'routes' => array(
				array(
					'class'  => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
			),
		),
	),
);

//get host
foreach ($_SERVER['argv'] as $arg) {
	if (strpos($arg, 'host=') === 0) {
		$host = substr($arg, 5);
		break;
	}
}

if (isset($host)) {
	$cs = __DIR__ . DIRECTORY_SEPARATOR . $host . '.php';
	if (!file_exists($cs))
		die('Domain config <b>' . $cs . '</b> not found');
	$configServer = require_once($cs);
	$config       = array_merge($config, $configServer);
}

return $config;