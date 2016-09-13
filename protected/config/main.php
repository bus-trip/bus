<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name'     => 'Sprint tour',
	'theme'    => 'bus',
	'language' => 'ru',
	// preloading 'log' component
	'preload'  => array('log'),
	// autoloading model and component classes
	'import'   => array(
		'application.models.*',
		'application.components.*',
		'application.extensions.yii-mailer.YiiMailer',
	),

	'modules'    => array(
		// uncomment the following to enable the Gii tool
		'gii' => array(
			'class'     => 'system.gii.GiiModule',
			'password'  => '442',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters' => array('127.0.0.1'),
		),
		'UserInterface'
	),

	// application components
	'components' => array(
		'user'       => array(
			// enable cookie-based authentication
			'allowAutoLogin' => true,
			'loginUrl'       => null,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager' => array(
			'urlFormat'      => 'path',
			'showScriptName' => false,
			'rules'          => array(
				'<controller:\w+>/<id:\d+>'                                    => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'                       => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>'                                => '<controller>/<action>',
				'account'                                                      => 'account/profile',
				'account/passengers/add'                                       => 'account/passengersadd',
				'account/passengers/delete/<id:\d+>'                           => 'account/passengersdelete',
				'account/passengers/edit/<id:\d+>'                             => 'account/passengersedit',
				'trips/sheet/<tripId:\d+>/<placeId:\d+>/<directionId:\d+>'     => 'trips/profiles',
				'trips/sheetpart/<tripId:\d+>/<placeId:\d+>/<directionId:\d+>' => 'trips/sheetpart',
				'trips/sheet/<tripId:\d+>/<placeId:\d+>/<profileId:\d+>'       => 'trips/createticket',
				'about'                                                        => 'page/about',
				'bus-schedule'                                                 => 'page/busSchedule',
				'contacts'                                                     => 'page/contacts',
				'actions'                                                      => 'page/actions',
				'/'                                                            => 'UserInterface/default/index/step/find'
			),
		),
		//		'robokassa'  => [
		//			'class'          => 'application.components.Robokassa',
		//			'sMerchantLogin' => 'bus.webjails.ru',
		//			'sMerchantPass1' => 'F20wqziXYNpzp9TR28vn',
		//			'sMerchantPass2' => 'Pe7dSx8SLE46bbnvmUR0',
		//			'sCulture'       => 'ru',
		//			'sIncCurrLabel'  => '',
		//			'orderModel'     => 'Invoice', // ваша модель для выставления счетов
		//			'priceField'     => 'amount', // атрибут модели, где хранится сумма
		//			'isTest'         => true, // тестовый либо боевой режим работы
		//		],
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/

		'db'           => array(
//			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
//			'emulatePrepare' => true,
//			'username' => 'root',
//			'password' => '',
//			'charset' => 'utf8',

		),
		'errorHandler' => array(
			// use 'site/error' action to display errors
			'errorAction' => 'site/error',
		),
		'log'          => array(
			'class'  => 'CLogRouter',
			'routes' => array(
				array(
					'class'  => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		'dadata'       => array(
			'class' => 'ext.dadata.Dadata',
			'token' => 'd3adf370dbb3515f283445a47d9f3d17a2e99162',
		)
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'     => array(
		// this is used in contact page
		'adminEmail' => 'webmaster@example.com',
		'siteEmail'  => 'no-reply@bus.webjails.ru',
		'frontPage'  => [
			'admin' => '/admin/index',
			'user'  => '/UserInterface/default/index/step/find'
		]
	),
);