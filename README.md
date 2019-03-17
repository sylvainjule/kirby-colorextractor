# Kirby 3 – Color extractor

![colorextractor](https://user-images.githubusercontent.com/14079751/45950127-c73c0d00-bffe-11e8-8e10-eef90185f624.jpg)

This plugins extracts a dominant / average color of any image and stores it in the file's metadata as a HEX value.

<br/>

## Overview

> This plugin is completely free and published under the MIT license. However, if you are using it in a commercial project and want to help me keep up with maintenance, please consider [making a donation of your choice](https://www.paypal.me/sylvainjule) or purchasing your license(s) through [my affiliate link](https://a.paddle.com/v2/click/1129/36369?link=1170).

- [1. Installation](#1-installation)
- [2. Usage](#2-usage)
  - [2.1. Hooks](#21-hooks) 
  - [2.2. Hooks](#22-field) 
- [3. Configuration](#3-configuration)
  - [3.1. Vibrant / Average](#31-vibrant--average) 
  - [3.2. Transparency handling](#32-transparency-handling) 
- [4. Displaying and using the color](#4-displaying-and-using-the-color)
- [5. License](#5-license)
- [6. Credits](#6-credits)

<br/>

## 1. Installation

Download and copy this repository to ```/site/plugins/colorextractor```

Alternatively, you can install it with composer: ```composer require sylvainjule/colorextractor```

<br/>

## 2. Usage

It can be used in two ways :

#### 2.1. Hooks

Once installed within the ```plugins``` folder, it will automatically start extracting colors for any image uploaded or replaced in the panel.

#### 2.2. Field

If you happen to upload files manually, from frontend or any other way while not trigerring the hooks, a field is also available to catch up with all the images of a website without an associated color.

```yaml
colorextractor:
  label: Color extractor
  type: colorextractor
```

It will show a button, which will guide you through the image processing.

![colorextractor-field](https://user-images.githubusercontent.com/14079751/45931472-ef7c2b00-bf6e-11e8-8260-ec1d2ed64ed5.png)

<br/>

## 3. Options

#### 3.1. Vibrant / Average

By default, the plugin tends to extract the most dominant / vibrant color of the image. Sometimes though, it can be handy to extract an average one based on an approximation of the whole color palette. When set to ```true```, this options shrinks the image to a 1x1 pixel thumb, then grab the color the image processor chose as the average one. You'll find some examples [here](https://github.com/sylvainjule/kirby3-colorextractor/blob/master/docs/examples.md).

Default is ```false``` 

```php
// config/config.php
return array(
  'sylvainjule.colorextractor.average' => true,
);
```

#### 3.2. Transparency handling

The plugin needs to know how to handle colors with alpha value greater than zero, and what color to fallback to when transparency is detected.

Default is ```#ffffff```

```php
// config/config.php
return array(
  'sylvainjule.colorextractor.fallbackColor' => '#000000',
);
```

<br/>

## 4. Displaying and using the color

The plugin works (or at least it should pretty soon) well together with [@TimOetting's color picker](https://github.com/TimOetting/kirby-color), which might come handy to preview and adjust the detected color.

```yaml
# Page blueprint within site/blueprints/pages/ folder
sections:
      files:
        headline: Images
        type: files
        template: color
```

```yaml
# Color template within site/blueprints/files/color.yml
title: Color
accept:
  mime: image/jpeg, image/png
fields:
  color:
    label: Color
    type: color
```

Here's how to access it from your template :

```php
<?php 
// make sure $image is a File object
$image = $page->image('image.jpg');  
echo $image->color(); ?>
```

<br/>

## 5. License

MIT

<br/>

## 6. Credits

- K2 Field by [@iandoe](https://github.com/iandoe/kirby-dominant-color/blob/master/README.md)
- Color extracting process by [@thephpleague](https://github.com/thephpleague/color-extractor)
