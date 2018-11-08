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
    'translations' => array(
        'en' => require_once __DIR__ . '/lib/languages/en.php',
        'de' => require_once __DIR__ . '/lib/languages/de.php',
        'fr' => require_once __DIR__ . '/lib/languages/fr.php',
    ),
    'api' => array(
    	'routes' => require_once __DIR__ . '/lib/routes.php',
    ),
]);