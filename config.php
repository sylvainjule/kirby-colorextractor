<?php

require_once __DIR__ . '/lib/colorextractor.php';

Kirby::plugin('sylvainjule/colorextractor', [
	'options' => array(
		'average' => false,
		'fallbackColor' => '#ffffff',
	),
    'fields' => require_once __DIR__ . DS . 'lib' . DS . 'fields.php',
    'hooks'  => require_once __DIR__ . DS . 'lib' . DS . 'hooks.php',
    'routes' => require_once __DIR__ . DS . 'lib' . DS . 'routes.php',
]);