<?php

return array(
    array(
        'pattern' => 'plugins/colorextractor/process-image',
        'method'  => 'GET|POST',
        'action'  => function() {
            $id            = $_POST['id'];
            $file          = site()->index()->files()->find($id);
            $size          = option('sylvainjule.colorextractor.average') ? 1 : 300;
        	$fallbackColor = option('sylvainjule.colorextractor.fallBackColor');

        	try {
        		extractColor($file, $size, $fallbackColor);
        		$data = array(
		            'status' => 'success',
		        );
		        return response::json($data);
        	}
        	catch (Exception $e) {
        		$data = array(
		            'status' => 'error',
		            'error'  => $e->getMessage()
		        );
		        return response::json($data);
        	}
        }
    )
);
