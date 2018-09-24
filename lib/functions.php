<?php

use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;

function extractColor($image, $size, $fallbackColor) {
	$thumb     = $image->resize($size);
	$palette   = Palette::fromFilename($thumb->url(), Color::fromHexToInt($fallbackColor));
	$extractor = new ColorExtractor($palette);
	$colors    = $extractor->extract(1);
	$hex       = Color::fromIntToHex($colors[0]);
			
	// Update image metadata
	$image->update(array(
		'color' => $hex
	));
}