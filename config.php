<?php

if (!class_exists('SylvainJule\ColorExtractor')) {
	require_once __DIR__ . '/lib/colorextractor.php';
}

Kirby::plugin('sylvainjule/colorextractor', [
	'options' => array(
		'cache' => true,
		'average' => false,
		'fallbackColor' => '#ffffff',
	),
    'fields' => require_once __DIR__ . '/lib/fields.php',
    'hooks'  => require_once __DIR__ . '/lib/hooks.php',
    'routes' => require_once __DIR__ . '/lib/routes.php',
]);