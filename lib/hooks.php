<?php 

return array(
    'file.create:after' => function($file) {
        if($file->type() == 'image') {
        	$size          = option('sylvainjule.colorextractor.average') ? 1 : 300;
        	$fallbackColor = option('sylvainjule.colorextractor.fallBackColor');

        	extractColor($file, $size, $fallbackColor);
        }
    },
    'file.replace:after' => function($file) {
        if($file->type() == 'image') {
        	$size          = option('sylvainjule.colorextractor.average') ? 1 : 300;
        	$fallbackColor = option('sylvainjule.colorextractor.fallBackColor');

        	extractColor($file, $size, $fallbackColor);
        }
    },
);