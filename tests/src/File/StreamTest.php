<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest\File;

use GravityMedia\Stream\File\Stream;
use GravityMedia\StreamTest\StreamTestCase;

/**
 * File stream test
 *
 * @package GravityMedia\Stream\File
 */
class StreamTest extends StreamTestCase
{
    /**
     * @covers \GravityMedia\Stream\File\Stream::getInputStream()
     * @covers \GravityMedia\Stream\File\Stream::getOutputStream()
     */
    public function testConstruction()
    {
        var_dump($this->createTempFile());

        $stream = new Stream($this->createTempFile());

        $this->assertInstanceOf('GravityMedia\Stream\InputStream', $stream->getInputStream());
        $this->assertInstanceOf('GravityMedia\Stream\OutputStream', $stream->getOutputStream());
    }
}
