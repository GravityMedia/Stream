<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest;

use GravityMedia\Stream\InputStream;

/**
 * Input stream test
 *
 * @package GravityMedia\StreamTest
 */
class InputStreamTest extends StreamTestCase
{
    /**
     * @covers \GravityMedia\Stream\InputStream::__construct()
     * @covers \GravityMedia\Stream\InputStream::getResource()
     * @covers \GravityMedia\Stream\InputStream::__destruct()
     */
    public function testBasicAsserts()
    {
        $stream = new InputStream($this->createTempFile());

        $this->assertTrue(is_resource($stream->getResource()));
        $this->assertTrue($stream->close());

        unset($stream);
    }

    /**
     * @covers \GravityMedia\Stream\InputStream::read()
     * @covers \GravityMedia\Stream\InputStream::tell()
     * @covers \GravityMedia\Stream\InputStream::end()
     * @covers \GravityMedia\Stream\InputStream::rewind()
     * @covers \GravityMedia\Stream\InputStream::seek()
     * @covers \GravityMedia\Stream\InputStream::close()
     */
    public function testStream()
    {
        $length = 8192;
        $data = $this->createRandomData($length);
        $uri = $this->createTempFile();
        file_put_contents($uri, $data);
        $offset = mt_rand(0, $length - 1);

        $stream = new InputStream($uri);

        $this->assertEquals($data, $stream->read($length));
        $this->assertEquals($length, $stream->tell());

        $this->assertEmpty($stream->read());
        $this->assertTrue($stream->end());

        $this->assertEquals(0, $stream->rewind());
        $this->assertEquals($offset, $stream->seek($offset));
        $this->assertEquals($data{$offset}, $stream->read());

        $this->assertTrue($stream->close());

        unset($stream);
    }

    /**
     * @covers \GravityMedia\Stream\InputStream::stats()
     */
    public function testStreamStats()
    {
        $stream = new InputStream($this->createTempFile());

        $this->assertInstanceOf('GravityMedia\Stream\StreamStats', $stream->stats());

        unset($stream);
    }

    /**
     * @covers \GravityMedia\Stream\InputStream::metadata()
     */
    public function testStreamMetadata()
    {
        $stream = new InputStream($this->createTempFile());

        $this->assertInstanceOf('GravityMedia\Stream\StreamMetadata', $stream->metadata());

        unset($stream);
    }
}
