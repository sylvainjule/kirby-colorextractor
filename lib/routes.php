<?php

return array(
    array(
        'pattern' => 'colorextractor/process-image',
        'method'  => 'POST',
        'action'  => function() {
            $id            = get('id');
            $filesIndex    = SylvainJule\ColorExtractor::getFilesIndex();
            $file          = $filesIndex->find($id);

        	try {
        		$file->extractColor();
        		$response = array(
		            'status' => 'success',
		            'plugin' => 'colorextractor',
		        );
		        return $response;
        	}
        	catch (Exception $e) {
        		$response = array(
		            'status' => 'error',
		            'plugin' => 'colorextractor',
		            'message'  => $e->getMessage()
		        );
		        return $response;
        	}
        }
    )
);
