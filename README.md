#Stream

Object oriented stream library for PHP

[![Packagist](https://img.shields.io/packagist/v/gravitymedia/stream.svg)](https://packagist.org/packages/gravitymedia/stream)
[![Downloads](https://img.shields.io/packagist/dt/gravitymedia/stream.svg)](https://packagist.org/packages/gravitymedia/stream)
[![License](https://img.shields.io/packagist/l/gravitymedia/stream.svg)](https://packagist.org/packages/gravitymedia/stream)
[![Build](https://img.shields.io/travis/GravityMedia/Stream.svg)](https://travis-ci.org/GravityMedia/Stream)
[![Code Quality](https://img.shields.io/scrutinizer/g/GravityMedia/Stream.svg)](https://scrutinizer-ci.com/g/GravityMedia/Stream/?branch=master)
[![Coverage](https://img.shields.io/scrutinizer/coverage/g/GravityMedia/Stream.svg)](https://scrutinizer-ci.com/g/GravityMedia/Stream/?branch=master)

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

Currently input/output streams for resources and files are implemented.

###Resources###

```php
require 'vendor/autoload.php';

use GravityMedia\Stream\InputStream;
use GravityMedia\Stream\OutputStream;

// create new file input stream object
$inputStream = new InputStream(fopen('/path/to/input/file.bin', 'rb'));

// create new file output stream object
$outputStream = new OutputStream(fopen('/path/to/output/file.bin', 'wb'));

// Pipe input stream to output stream
while (!$inputStream->end()) {
    $outputStream->write($inputStream->read());
}
```

###Files###

```php
require 'vendor/autoload.php';

use GravityMedia\Stream\FileInputStream;
use GravityMedia\Stream\FileOutputStream;

// create new file input stream object
$inputStream = new FileInputStream('/path/to/input/file.bin');

// create new file output stream object
$outputStream = new FileOutputStream('/path/to/output/file.bin');

// Pipe input stream to output stream
while (!$inputStream->end()) {
    $outputStream->write($inputStream->read());
}
```
