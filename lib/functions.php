<?php

use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;

function extractColor($image, $size, $fallbackColor) {
	$thumb     = $image->resize($size);
	$url       = $thumb->url();
	$url       = substr($url, 0, 4) === 'http' ? $url : absoluteThumbUrl($url);
	$palette   = Palette::fromFilename($url, Color::fromHexToInt($fallbackColor));
	$extractor = new ColorExtractor($palette);
	$colors    = $extractor->extract(1);
	$hex       = Color::fromIntToHex($colors[0]);
			
	// Update image metadata
	$image->update(array(
		'color' => $hex
	));
}

function absoluteThumbUrl() {
    if (isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else {
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'] . $url;
}