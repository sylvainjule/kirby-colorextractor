# Kirby 3 – Color extractor

![colorextractor](https://user-images.githubusercontent.com/14079751/45950127-c73c0d00-bffe-11e8-8e10-eef90185f624.jpg)

This plugins extracts a dominant / average color of any image and stores it in the file's metadata as a HEX value.

<br/>

## Overview

> This plugin is completely free and published under the MIT license. However, if you are using it in a commercial project and want to help me keep up with maintenance, please consider [making a donation of your choice](https://www.paypal.me/sylvainjl) or purchasing your license(s) through [my affiliate link](https://a.paddle.com/v2/click/1129/36369?link=1170).

- [1. Installation](#1-installation)
- [2. Usage](#2-usage)
  - [2.1. Hooks](#21-hooks) 
  - [2.2. Panel button](#22-panel-button)
- [3. Configuration](#3-configuration)
  - [3.1. Extraction mode](#31-extraction-mode)
  - [3.2. Transparency handling](#32-transparency-handling) 
- [4. Displaying and using the color](#4-displaying-and-using-the-color)
  - [4.1. If a single color is extracted](#41-if-a-single-color-is-extracted)
  - [4.2. If both colors are extracted](#42-if-both-colors-are-extracted)
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

#### 2.2. Panel button

If you happen to upload files manually, from frontend or any other way while not trigerring the hooks, custom [janitor](https://github.com/bnomei/kirby3-janitor) jobs are also available to catch up with all the images of a website without an associated color.

You'll first need to install the janitor plugin.

Then register the jobs provided by this plugin in your `config.php`:

```php
'bnomei.janitor.jobs-extends' => [
    'sylvainjule.colorextractor.jobs',
],
```

You can now use it in your blueprints:

```yaml
colorextractor:
    type: janitor
    label: Extract missing colors
    progress: 'Processing…'
    job: extractColors
```

The `extractColors` job will only extract the **missing colors**. If you want to force a re-extraction of existing colors, use the `forceExtractColors` job instead.

<br/>

## 3. Options

#### 3.1. Extraction mode

By default, the plugin tends to extract the most dominant / vibrant color of the image. Sometimes though, it can be handy to extract an average one based on an approximation of the whole color palette. When set to `average`, this options shrinks the image to a 1x1 pixel thumb, then grab the color the image processor chose as the average one. You'll find some examples [here](https://github.com/sylvainjule/kirby3-colorextractor/blob/master/docs/examples.md).

You can also set it to `both`, if you want both colors to be extracted and pick from them later from your templates (see the [`toColors`](#42-if-both-colors-are-extracted) method)



Available options are `dominant | average | both`. Default is `dominant`.

```php
// config/config.php
return array(
  'sylvainjule.colorextractor.mode' => 'dominant',
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

#### 4.1. If a single color is extracted

In case you have chosen either `dominant` (default) or `average` extraction mode, you can access it directly from your template under the `color` fieldname:

```php
$image->color();
```

The plugin works well combined with [@hananils's color picker](https://github.com/hananils/kirby-colors), which might come handy to preview and adjust the detected color.

```yaml
# Place this inside your file blueprint
fields:
  color:
    type: colors
```

#### 4.2. If both colors are extracted

If you have chosen to extract and store both colors, the color field will store both HEX values delimited by a comma. The plugin provides a file method to get a specific color from there:

```php
$image->color()->toColor('dominant');
$image->color()->toColor('average');
```

<br/>

## 5. License

MIT

<br/>

## 6. Credits

- K2 Field by [@iandoe](https://github.com/iandoe/kirby-dominant-color/blob/master/README.md)
- Color extracting process by [@thephpleague](https://github.com/thephpleague/color-extractor)
