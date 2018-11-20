<?php

namespace SylvainJule;

use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor as Extractor;
use League\ColorExtractor\Palette;

class ColorExtractor {

	public static function extractColor($image, $size, $fallbackColor) {
		$thumb     = $image->resize($size);
		$url       = $thumb->url();
		$url       = substr($url, 0, 4) === 'http' ? $url : self::absoluteThumbUrl($url);
		$palette   = Palette::fromFilename($url, Color::fromHexToInt($fallbackColor));
		$extractor = new Extractor($palette);
		$colors    = $extractor->extract(1);
		$hex       = Color::fromIntToHex($colors[0]);
				
		// Update image metadata
		$image->update(array(
			'color' => $hex
		));
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

	public static function absoluteThumbUrl($url) {
	    if (isset($_SERVER['HTTPS'])){
	        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
	    }
	    else {
	        $protocol = 'http';
	    }
	    return $protocol . "://" . $_SERVER['HTTP_HOST'] . $url;
	}

}