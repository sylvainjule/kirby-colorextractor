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

	public static function absoluteThumbUrl() {
	    if (isset($_SERVER['HTTPS'])){
	        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
	    }
	    else {
	        $protocol = 'http';
	    }
	    return $protocol . "://" . $_SERVER['HTTP_HOST'] . $url;
	}

}