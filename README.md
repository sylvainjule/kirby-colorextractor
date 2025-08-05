# Kirby – Color extractor

![colorextractor](https://user-images.githubusercontent.com/14079751/45950127-c73c0d00-bffe-11e8-8e10-eef90185f624.jpg)

This plugins extracts a dominant / average color of any image and stores it in the file's metadata as a HEX value.
Optionally, it can also generate a color palette to be used in combination with the `color` field.

<br/>

## Overview

> This plugin is completely free and published under the MIT license. However, if you are using it in a commercial project and want to help me keep up with maintenance, you can consider [making a donation of your choice](https://www.paypal.me/sylvainjl).

- [1. Installation](#1-installation)
- [2. Default usage](#2-default-usage)
  - [2.1. Hook](#21-hook) 
  - [2.2. Panel button](#22-panel-button)
- [3. Default options](#3-default-options)
  - [3.1. Extraction mode](#31-extraction-mode)
  - [3.2. Transparency handling](#32-transparency-handling) 
- [4. Displaying and using the color](#4-displaying-and-using-the-color)
  - [4.1. If a single color is extracted](#41-if-a-single-color-is-extracted)
  - [4.2. If both colors are extracted](#42-if-both-colors-are-extracted)
- [5. Palette usage]
  - [5.1. Hook](#51-hook) 
  - [5.2. Field method](#52-field-method)
- [6. License](#6-license)
- [7. Credits](#7-credits)

<br/>


## 1. Installation

> Compatible with Kirby 5 (latest release) as well as Kirby 3 and 4 (maintained / tested up to v2.0.0 of this plugin).

Download and copy this repository to ```/site/plugins/colorextractor```

Alternatively, you can install it with composer: ```composer require sylvainjule/colorextractor```

<br/>

## 2. Default usage

It can be used in two ways :

#### 2.1. Hook

Once installed within the ```plugins``` folder, it will automatically start extracting colors for any image uploaded or replaced in the panel.

#### 2.2. Panel button

If you happen to upload files manually, from frontend or any other way while not trigerring the hooks, custom [janitor](https://github.com/bnomei/kirby3-janitor) jobs are also available to catch up with all the images of a website without an associated color.

You'll first need to install the janitor plugin and the Kirby CLI:

```bash
composer require getkirby/cli bnomei/kirby3-janitor
```

Then use the jobs in your blueprints:

```yaml
colorextractor:
    type: janitor
    label: Extract missing colors
    progress: 'Processing…'
    command: janitor:job --key sylvainjule.colorextractor.jobs.extractColors
```

The `extractColors` job will only extract the **missing colors**. If you want to force a re-extraction of existing colors, use the `forceExtractColors` job instead.

<br/>

## 3. Default options

#### 3.1. Extraction mode

By default, the plugin tends to extract the most dominant / vibrant color of the image. Sometimes though, it can be handy to extract an average one based on an approximation of the whole color palette. When set to `average`, this options shrinks the image to a 1x1 pixel thumb, then grab the color the image processor chose as the average one. You'll find some examples [here](https://github.com/sylvainjule/kirby3-colorextractor/blob/master/docs/examples.md).

You can also set it to `both`, if you want both colors to be extracted and pick from them later from your templates (see the [`plugin's methods`](#42-if-both-colors-are-extracted))



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
$image->color()->dominantColor();
$image->color()->averageColor();
```

<br/>

## 5. Palette usage

You have two options to generate the palette:

#### 5.1. Hook

You can generate a palette for any image uploaded or replaced in the panel. In order to to so, you need to set the 'palette.hook' option to `true` (default if `false`).

Additionally, you can:
- The number of colors to be extracted with the `palette.limit` option (default is `10`)
- If the palette generation is to be restricted to specific file templates with the `palette.template` option (default is `null`: a palette will be generated for any image with any template if the palette hook is active. `String | Array`).

```php
# default values
'sylvainjule.colorextractor.palette' => [
    'hook'     => false,
    'limit'    => 10,
    'template' => null
],

# example values
'sylvainjule.colorextractor.palette' => [
    'hook'     => true,
    'limit'    => 12,
    'template' => ['template-1', 'template-2'],
    // 'template' => 'template-1', also works
],
```

The palette will be stored in the file .txt with the fieldname: `palette`. The plugin provides a dedicated field methods to use is, see below.

If you only need this plugin to extract palettes, you can also disable the default average / dominant color extraction hook with the `default.hook` option (default is `true`):

```php
'sylvainjule.colorextractor.default.hook' => false,
```

#### 5.2. Field method

If you don't want any hook running, you can also choose not to activate the hook but use the `->getPalette()` method.
- If the `palette` exists (= is not empty), the method will return it as an array
- If the `palette` doesn't exist, it will process the image, generate and save it, then return it as an array.

You can use is to populate the [`color` field options](https://getkirby.com/docs/reference/panel/fields/color#options), for example:

```yaml
colorPicker:
  type: color
  mode: options
  options:
    type: query
    query: page.filePicker.toFile.getPalette
    # query: page.files.first.getPalette
    # query: page.files.first.palette.yaml → also works if the palette has already been generated
    # ...
```


<br/>

## 6. License

MIT

<br/>

## 7. Credits

- K2 Field by [@iandoe](https://github.com/iandoe/kirby-dominant-color/blob/master/README.md)
- Color extracting process by [@thephpleague](https://github.com/thephpleague/color-extractor)
