<?php

namespace SylvainJule;

use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor as Extractor;
use League\ColorExtractor\Palette;

class ColorExtractor {

	public static function extractColor($image, $size, $fallbackColor) {
		if($image->isResizable()) {
			$thumb     = $image->resize($size)->save();
			$root      = $thumb->root();
			$palette   = Palette::fromFilename($root, Color::fromHexToInt($fallbackColor));
			$extractor = new Extractor($palette);
			$colors    = $extractor->extract(1);
			$hex       = Color::fromIntToHex($colors[0]);
					
			// Update image metadata
			$image->update(array(
				'color' => $hex
			));
		}
	}

    private static $cache = null;

    private static function cache(): \Kirby\Cache\Cache {
        if(!static::$cache) {
            static::$cache = kirby()->cache('sylvainjule.colorextractor');
        }
        return static::$cache;
    }

    public static function getFilesIndex($force = false) {
        $index = $force ? null : static::cache()->get('files.index');
        if(!$index) {
        	$published = site()->index()->images();
        	$drafts    = site()->drafts()->images();
        	$index     = new \Kirby\Cms\Files(array($published, $drafts));
            static::cache()->set('files.index', $index, 15);
        }
        return $index;
    }

}