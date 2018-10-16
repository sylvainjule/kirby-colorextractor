<?php

return array(
    array(
        'pattern' => 'api/colorextractor/process-image',
        'method'  => 'POST',
        'action'  => function() {
            $id            = get('id');
            $force         = get('index') == 0 ? true : false;
            $filesIndex    = SylvainJule\ColorExtractor::getFilesIndex($force);
            $file          = $filesIndex->find($id);
            $size          = option('sylvainjule.colorextractor.average') ? 1 : 300;
        	$fallbackColor = option('sylvainjule.colorextractor.fallBackColor');

        	try {
        		SylvainJule\ColorExtractor::extractColor($file, $size, $fallbackColor);
        		$response = array(
		            'status' => 'success',
		            'force' => $force,
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
