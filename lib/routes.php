<?php

return array(
    array(
        'pattern' => 'api/colorextractor/process-image',
        'method'  => 'POST',
        'action'  => function() {
            $id            = get('id');
            $file          = site()->index()->files()->find($id);
            $size          = option('sylvainjule.colorextractor.average') ? 1 : 300;
        	$fallbackColor = option('sylvainjule.colorextractor.fallBackColor');

        	try {
        		ColExtractor::extractColor($file, $size, $fallbackColor);
        		$response = array(
		            'status' => 'success',
		        );
		        return $response;
        	}
        	catch (Exception $e) {
        		$response = array(
		            'status' => 'error',
		            'message'  => $e->getMessage()
		        );
		        return $response;
        	}
        }
    )
);
