<?php

return array(
    array(
        'pattern' => 'colorextractor/process-image',
        'method'  => 'POST',
        'action'  => function() {
            $id            = get('id');
            $filesIndex    = SylvainJule\ColorExtractor::getFilesIndex();
            $file          = $filesIndex->find($id);
            $size          = option('sylvainjule.colorextractor.average') ? 1 : 300;
        	$fallbackColor = option('sylvainjule.colorextractor.fallBackColor');

        	try {
        		SylvainJule\ColorExtractor::extractColor($file, $size, $fallbackColor);
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
