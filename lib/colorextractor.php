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

		if($image->isResizable()) {
            if(in_array($mode, ['dominant', 'both'])) {
                $colors[] = static::processImage($image, 300, $fallbackColor);
            }
            if(in_array($mode, ['average', 'both'])) {
                $colors[] = static::processImage($image, 1, $fallbackColor);
            }

			$image->update(['color' => implode(',', $colors)]);
		}
	}

    public static function processImage($image, $size, $fallbackColor) {
        $thumb     = $image->width() > $image->height() ? $image->resize(null, $size) : $image->resize($size);
        $thumb     = $thumb->save();
        $root      = $thumb->root();
        $palette   = Palette::fromFilename($root, Color::fromHexToInt($fallbackColor));
        $extractor = new Extractor($palette);
        $colors    = $extractor->extract(1);
        $hex       = Color::fromIntToHex($colors[0]);

        return $hex;
    }

    public static function getFilesIndex() {
        $published = site()->index()->images();
        $drafts    = site()->drafts()->images();
        $images    = new Files(array($published, $drafts));
        $images    = $images->filterBy('extension', 'in', ['png', 'jpg', 'jpeg', 'gif', 'webp']);

        return $images;
    }

}
