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

This is a simple usage example for character streams but is applicable for binary data streams.

```php
require 'vendor/autoload.php';

use GravityMedia\Stream\Stream;

// create new stream object
$stream = new Stream('php://temp', 'r+');

// get stream writer object
$writer = $stream->getWriter();

// write some random data
$writer->write('some random data...');

// truncate random data
$writer->truncate(16);

// rewind stream
$stream->rewind();

// get stream reader object
$reader = $stream->getReader();

// print "some random data"
while (!$stream->eof()) {
    print $reader->read();
}
print PHP_EOL;

// seek a position
$stream->seek(5);

// print position
print $stream->tell() . PHP_EOL;

// print "random data"
while (!$stream->eof()) {
    print $reader->read();
}
print PHP_EOL;
```
