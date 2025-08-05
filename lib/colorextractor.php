<?php

namespace SylvainJule;

use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor as Extractor;
use League\ColorExtractor\Palette;

use Kirby\Cms\Files;
use Kirby\Cms\File;

class ColorExtractor {

	public static function extractColor($image, $mode, $fallbackColor) {
        $colors = [];
        $data   = [];

		if($image->isResizable()) {
            if(option('sylvainjule.colorextractor.default.hook')) {
                if(in_array($mode, ['dominant', 'both'])) {
                    $colors[] = static::processImage($image, 300, $fallbackColor);
                }
                if(in_array($mode, ['average', 'both'])) {
                    $colors[] = static::processImage($image, 1, $fallbackColor);
                }

                $data['color'] = implode(',', $colors);
            }

            if(option('sylvainjule.colorextractor.palette.hook')) {
                $limit         = option('sylvainjule.colorextractor.palette.limit', 10);
                $fallbackColor = option('sylvainjule.colorextractor.fallbackColor', '#ffffff');
                $templates     = option('sylvainjule.colorextractor.palette.template', null);

                if($templates) {
                    $templates = is_string($templates) ? [$templates] : $templates;

                    if(in_array($image->template(), $templates)) {
                        $palette = static::extractPalette($image, $limit, 400, $fallbackColor);
                        $data['palette'] = $palette;
                    }
                }
                else {
                    $palette = static::extractPalette($image, $limit, 400, $fallbackColor);
                    $data['palette'] = $palette;
                }
            }

            if(count($data)) {
			    $image->save($data);
            }
		}
	}

    public static function processImage($image, $size = 300, $fallbackColor = '#ffffff') {
        $thumb     = $image->width() > $image->height() ? $image->resize(null, $size) : $image->resize($size);
        $thumb     = $thumb->save();
        $root      = $thumb->root();
        $palette   = Palette::fromFilename($root, Color::fromHexToInt($fallbackColor));
        $extractor = new Extractor($palette);
        $colors    = $extractor->extract(1);
        $hex       = Color::fromIntToHex($colors[0]);

        return $hex;
    }

    public static function extractPalette($image, $limit = 10, $size = 400, $fallbackColor = '#ffffff') {
        $thumb     = $image->width() > $image->height() ? $image->resize(null, $size) : $image->resize($size);
        $thumb     = $thumb->save();
        $root      = $thumb->root();
        $palette   = Palette::fromFilename($root, Color::fromHexToInt($fallbackColor));
        $extractor = new Extractor($palette);
        $colors    = $extractor->extract($limit);
        $colors    = array_map(function($value) { return Color::fromIntToHex($value); }, $colors);

        return $colors;
    }

    public static function getFilesIndex() {
        $published = site()->index()->images();
        $drafts    = site()->drafts()->images();
        $images    = new Files(array($published, $drafts));
        $images    = $images->filterBy('extension', 'in', ['png', 'jpg', 'jpeg', 'gif', 'webp']);

        return $images;
    }

}
