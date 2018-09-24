# Kirby 3 – Color extractor

![colorextractor-preview](https://user-images.githubusercontent.com/14079751/45942863-78817980-bfe4-11e8-97c6-d2982d993af2.jpg)

This plugins extracts the most dominant color of any image and stores it in the file's metadata as a HEX value.



## Usage

It can be used in two ways :

#### Hook

Onceinstalled within the ```plugins``` folder, it will automatically start extracting colors for any image uploaded or replaced in the panel.

#### Field

If you happen to upload files manually, from frontend or any other way while not trigerring the hooks, a field is also available to catch up with all the images of a website without an associated color.

```yaml
colorextractor:
  label: Color extractor
  type: colorextractor
```

It will show a button, which will guide you through the image processing.

![colorextractor-field](https://user-images.githubusercontent.com/14079751/45931472-ef7c2b00-bf6e-11e8-8260-ec1d2ed64ed5.png)



## Options

#### Vibrant / Average

By default, the plugin tends to extract the most dominant / vibrant color of the image. Sometimes though, it can be handy to extract an average one based on an approximation of the whole color palette. When set to ```true```, this options shrinks the image to a 1x1 pixel thumb, then grab the color the image processor chose as the average one.

Default is ```false``` 

```php
// config/config.php
return array(
  'sylvainjule.colorextractor.average' => true,
);
```

#### Transparency handling

The plugin needs to know how to handle colors with alpha value greater than zero, and what color to fallback to when transparency is detected.

Default is ```#ffffff```

```php
// config/config.php
return array(
  'sylvainjule.colorextractor.fallbackColor' => '#000000',
);
```

## Displaying and using the color

#### Displaying / changing the color

The plugin works (or at least it should pretty soon) well together with [@TimOetting's color picker](https://github.com/TimOetting/kirby-color), which might come handy to preview and adjust the detected color.

```yaml
# Page blueprint within pages/ folder
sections:
      files:
        headline: Images
        type: files
        template: color
```

```yaml
# Color template within files/color.yml
title: Color
accept:
  mime: image/jpeg, image/png
fields:
  color:
    label: Color
    type: color
```


Accessing the color from a template is straightforward :

```php
<?php 
// make sure $image is a File object
$image = $page->image('image.jpg');  
echo $image->color(); ?>
```

## Todo

- [ ] Merge hooks into one shared callback

## License

MIT

## Credits

- K2 Field by [@iandoe](https://github.com/iandoe/kirby-dominant-color/blob/master/README.md)
- Color extracting process by [@thephpleague](https://github.com/thephpleague/color-extractor)
