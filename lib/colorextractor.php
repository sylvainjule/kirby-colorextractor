<?php

namespace SylvainJule;

use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor as Extractor;
use League\ColorExtractor\Palette;

use Kirby\Cms\Files;
use Kirby\Cms\File;

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
        	$index     = new Files(array($published, $drafts));
            $files     = array();

            foreach($index as $f) {
                $files[] = array(
                    'filename' => $f->filename(),
                    'parent'   => $f->parent()->uri()
                );
            }
            static::cache()->set('files.index', $files, 15);
        }
        else {
            $index = array_map(function($a) { return kirby()->page($a['parent'])->file($a['filename']); }, $index);
            $index = new Files($index, kirby()->site());
        }
        return $index;
    }

}
