#Stream

Object oriented stream library for PHP

[![Packagist](https://img.shields.io/packagist/v/gravitymedia/stream.svg)](https://packagist.org/packages/gravitymedia/stream)
[![Downloads](https://img.shields.io/packagist/dt/gravitymedia/stream.svg)](https://packagist.org/packages/gravitymedia/stream)
[![License](https://img.shields.io/packagist/l/gravitymedia/stream.svg)](https://packagist.org/packages/gravitymedia/stream)
[![Build](https://img.shields.io/travis/GravityMedia/Stream.svg)](https://travis-ci.org/GravityMedia/Stream)
[![Code Quality](https://img.shields.io/scrutinizer/g/GravityMedia/Stream.svg)](https://scrutinizer-ci.com/g/GravityMedia/Stream/?branch=master)
[![Coverage](https://img.shields.io/scrutinizer/coverage/g/GravityMedia/Stream.svg)](https://scrutinizer-ci.com/g/GravityMedia/Stream/?branch=master)
[![PHP Dependencies](https://www.versioneye.com/user/projects/54a6c39d27b014005400004b/badge.svg)](https://www.versioneye.com/user/projects/54a6c39d27b014005400004b)

##Requirements##

This library has the following requirements:

 - PHP 5.4+

##Installation##

Install composer in your project:

```bash
$ curl -s https://getcomposer.org/installer | php
```

Create a `composer.json` file in your project root:

```json
{
    "require": {
        "gravitymedia/stream": "dev-master"
    }
}
```

Install via composer:

```bash
$ php composer.phar install
```

##Usage##

```php
require 'vendor/autoload.php';

use GravityMedia\Stream\InputStream;

// create new input stream object
$stream = new InputStream('/path/to/input/file.mp3');

// read all the data
$data = '';
while ($stream->isAvailable()) {
    $data .= $stream->read(1024);
}

// dump the data
var_dump($data);
```
