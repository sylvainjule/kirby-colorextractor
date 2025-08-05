<?php

if (!class_exists('SylvainJule\ColorExtractor')) {
	require_once __DIR__ . '/lib/colorextractor.php';
}

Kirby::plugin('sylvainjule/colorextractor', [
	'options' => [
		'average'        => false,
		'fallbackColor'  => '#ffffff',
        'mode'           => 'dominant',
        'default.hook'  => true,
        'palette'       => [
            'hook'     => false,
            'template' => null,
            'limit'    => 10
        ],
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
        'extractColor' => function() : Kirby\Content\Field {
            if($this->type() === 'image') {
                $mode          = option('sylvainjule.colorextractor.mode');
                // compatibility with previous versions
                $mode          = option('sylvainjule.colorextractor.average') == true ? 'average' : $mode;
                $fallbackColor = option('sylvainjule.colorextractor.fallbackColor', '#ffffff');

                SylvainJule\ColorExtractor::extractColor($this, $mode, $fallbackColor);
            }

            return $this->color();
        },
        'getPalette' => function() {
            if($this->palette()->isNotEmpty()) {
                return $this->palette()->yaml();
            }
            else {
                $limit         = option('sylvainjule.colorextractor.palette.limit', 10);
                $fallbackColor = option('sylvainjule.colorextractor.fallbackColor', '#ffffff');
                $palette = SylvainJule\ColorExtractor::extractPalette($this, $limit, 400, $fallbackColor);

                $this->save(['palette' => $palette]);
                return $palette;
            }
        }
    ],
    'fieldMethods' => [
        'dominantColor' => function($field, $mode = 'dominant') {
            $colors = $field->split(',');
            $count  = count($colors);

            if($count == 0) $field->value = null;
            else $field->value = $colors[0];

            return $field;
        },
        'averageColor' => function($field, $mode = 'dominant') {
            $colors = $field->split(',');
            $count  = count($colors);

            if($count == 0) $field->value = null;
            else $field->value = $count == 1 ? $colors[0] : $colors[1];

            return $field;
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
