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
            $thumb     = $image->width() > $image->height() ? $image->resize(null, $size) : $image->resize($size);
            $thumb     = $thumb->save();
			$root      = $thumb->root();
			$palette   = Palette::fromFilename($root, Color::fromHexToInt($fallbackColor));
			$extractor = new Extractor($palette);
			$colors    = $extractor->extract(1);
			$hex       = Color::fromIntToHex($colors[0]);

			$image->update(['color' => $hex]);
		}
	}

    public static function getFilesIndex() {
        $published = site()->index()->images();
        $drafts    = site()->drafts()->images();
        $images    = new Files(array($published, $drafts));
        $images    = $images->filterBy('extension', 'in', ['png', 'jpg', 'jpeg', 'gif', 'webp']);

        return $images;
    }

}
