<?php

if (!class_exists('SylvainJule\ColorExtractor')) {
	require_once __DIR__ . '/lib/colorextractor.php';
}

Kirby::plugin('sylvainjule/colorextractor', [
	'options' => [
		'average' => false,
		'fallbackColor' => '#ffffff',
        'jobs' => [
            'extractColors' => function () {
                $files      = SylvainJule\ColorExtractor::getFilesIndex();
                $files      = $files->filter(function($file) { return $file->color()->isEmpty(); });
                $filesCount = $files->count();

                foreach($files as $file) {
                    $file->extractColor();
                }

                return [
                    'status' => 200,
                    'label' => tc('colorextractor.processed', $filesCount)
                ];
            },
            'forceExtractColors' => function () {
                $files      = SylvainJule\ColorExtractor::getFilesIndex();
                $filesCount = $files->count();

                foreach($files as $file) {
                    $file->extractColor();
                }

                return [
                    'status' => 200,
                    'label' => tc('colorextractor.processed', $filesCount)
                ];
            },
        ]
	],
    'hooks'  => [
        'file.create:after'  => function ($file) {
            $file->extractColor();
        },
        'file.replace:after' => function ($newFile, $oldFile) {
            $newFile->extractColor();
        }
    ],
    'fileMethods' => [
        'extractColor' => function() : Kirby\Cms\Field {
            if($this->type() === 'image') {
                $size          = option('sylvainjule.colorextractor.average') ? 1 : 300;
                $fallbackColor = option('sylvainjule.colorextractor.fallBackColor');

                SylvainJule\ColorExtractor::extractColor($this, $size, $fallbackColor);
            }
            return $this->color();
        }
    ],
    'translations' => array(
        'en' => [
            'colorextractor.processed'  => [
                'There are no images with missing colors.',
                '1 image processed!',
                '{{ count }} images processed!'
            ],
        ],
        'de' => [
            'colorextractor.processed'  => [
                'Keine fehlenden Farben.',
                'Bild verarbeitet!',
                '{{ count }} Bilder verarbeitet!'
            ],
        ],
        'fr' => [
            'colorextractor.processed'  => [
                'Aucune couleur manquante.',
                '1 couleur extraite !',
                '{{ count }} couleurs extraites !'
            ],
        ]
    ),
]);
