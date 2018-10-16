<?php 

$extract = function($file) {
	if($file->type() == 'image') {
    	$size          = option('sylvainjule.colorextractor.average') ? 1 : 300;
    	$fallbackColor = option('sylvainjule.colorextractor.fallBackColor');

    	ColExtractor::extractColor($file, $size, $fallbackColor);
    }
};

return array(
    'file.create:after'  => $extract,
    'file.replace:after' => $extract,
);