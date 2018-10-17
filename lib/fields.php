<?php

return array(
    'colorextractor' => array(
    	'props' => array(
            'message' => function($message = 'Extract missing colors') {
                return $message;
            },
            'messageEmpty' => function($messageEmpty = 'No missing colors') {
                return $messageEmpty;
            },
        ),
        'computed' => array(
        	'files' => function() {
        		$array = array();
        		$index = SylvainJule\ColorExtractor::getFilesIndex(true);
        		$files = $index->filter(function($file) {
        			return $file->color()->isEmpty();
        		});

        		foreach($files as $file) {
        			$data = array(
        				'filename' => $file->filename(),
        				'name'     => $file->name(),
        				'id'       => $file->id(),
        			);
        			array_push($array, $data);
        		}

        		return $array;
        	}
        )
    ),
);