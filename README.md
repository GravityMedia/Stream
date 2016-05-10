# Stream

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gravitymedia/stream.svg)](https://packagist.org/packages/gravitymedia/stream)
[![Software License](https://img.shields.io/packagist/l/gravitymedia/stream.svg)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/GravityMedia/Stream.svg)](https://travis-ci.org/GravityMedia/Stream)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/GravityMedia/Stream.svg)](https://scrutinizer-ci.com/g/GravityMedia/Stream/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/GravityMedia/Stream.svg)](https://scrutinizer-ci.com/g/GravityMedia/Stream)
[![Total Downloads](https://img.shields.io/packagist/dt/gravitymedia/stream.svg)](https://packagist.org/packages/gravitymedia/stream)
[![Dependency Status](https://img.shields.io/versioneye/d/php/gravitymedia:stream.svg)](https://www.versioneye.com/user/projects/54f76e264f31083e1b0017e2)

Stream is an object oriented library for reading and writing binary streams in PHP.

## Requirements

This library has the following requirements:

- PHP 5.6+

## Installation

Install Composer in your project:

```bash
$ curl -s https://getcomposer.org/installer | php
```

Add the package to your `composer.json` and install it via Composer:

```bash
$ php composer.phar require gravitymedia/stream
```

## Usage

This is a simple usage example for character streams but is applicable for binary data streams.

```php
require 'vendor/autoload.php';

use GravityMedia\Stream\Stream;

// create resource
$resource = fopen('php://temp', 'r+');

// create new stream object
$stream = Stream::fromResource($resource);

// write some data
$stream->write("\x63\x6f\x6e\x74\x65\x6e\x74\x73");

// seek a position
$stream->seek(4);

// print 32 bit unsigned integer
print $stream->readUInt32() . PHP_EOL;

// rewind stream
$stream->rewind();

// print the data previously written
while (!$stream->eof()) {
    print $stream->read(1);
}
print PHP_EOL;

// print position
print $stream->tell() . PHP_EOL;

// rewind stream
$stream->rewind();

// truncate random data
$stream->truncate(7);

// print the truncated data
while (!$stream->eof()) {
    print $stream->read(1);
}
print PHP_EOL;
```

## Testing

Clone this repository, install Composer and all dependencies:

```bash
$ php composer.phar install
```

Run the test suite:

```bash
$ php vendor/bin/phing test
```

## Generating documentation

Clone this repository, install Composer and all dependencies:

```bash
$ php composer.phar install
```

Generate the documentation to the `build/docs` directory:

```bash
$ php vendor/bin/phing doc
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Daniel Schröder](https://github.com/pCoLaSD)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
