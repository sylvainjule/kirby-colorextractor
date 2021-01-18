<?php

return array(
    'file.create:after'  => function ($file) {
		$file->extractColor();
	},
    'file.replace:after' => function ($newFile, $oldFile) {
		$newFile->extractColor();
	},
);
