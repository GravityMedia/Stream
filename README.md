# Stream

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gravitymedia/stream.svg)](https://packagist.org/packages/gravitymedia/stream)
[![Software License](https://img.shields.io/packagist/l/gravitymedia/stream.svg)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/GravityMedia/Stream.svg)](https://travis-ci.org/GravityMedia/Stream)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/GravityMedia/Stream.svg)](https://scrutinizer-ci.com/g/GravityMedia/Stream/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/GravityMedia/Stream.svg)](https://scrutinizer-ci.com/g/GravityMedia/Stream)
[![Total Downloads](https://img.shields.io/packagist/dt/gravitymedia/stream.svg)](https://packagist.org/packages/gravitymedia/stream)
[![Dependency Status](https://img.shields.io/versioneye/d/php/gravitymedia:stream.svg)](https://www.versioneye.com/user/projects/54f76e264f31083e1b0017e2)

Stream is an object oriented stream library for PHP.

## Requirements

This library has the following requirements:

- PHP 5.4+ or HHVM

## Installation

Install composer in your project:

``` bash
$ curl -s https://getcomposer.org/installer | php
```

Require the package via Composer:

``` bash
$ php composer.phar require gravitymedia/stream
```

## Usage

This is a simple usage example for character streams but is applicable for binary data streams.

``` php
require 'vendor/autoload.php';

use GravityMedia\Stream\Stream;

// create resource
$resource = fopen('php://temp', 'r+');

// create new stream object
$stream = Stream::fromResource($resource);

// write some random data
$stream->write('some random data...');

// truncate random data
$stream->truncate(16);

// rewind stream
$stream->rewind();

// print "some random data"
while (!$stream->eof()) {
    print $stream->read();
}
print PHP_EOL;

// seek a position
$stream->seek(5);

// print position
print $stream->tell() . PHP_EOL;

// print "random data"
while (!$stream->eof()) {
    print $stream->read();
}
print PHP_EOL;
```

## Testing

``` bash
$ php composer.phar test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Daniel Schröder](https://github.com/pCoLaSD)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
