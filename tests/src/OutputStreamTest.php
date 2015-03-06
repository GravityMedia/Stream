<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest;

use GravityMedia\Stream\OutputStream;

/**
 * Output stream test
 *
 * @package GravityMedia\StreamTest
 */
class OutputStreamTest extends StreamTestCase
{
    /**
     * @covers \GravityMedia\Stream\OutputStream::__construct()
     * @covers \GravityMedia\Stream\OutputStream::getResource()
     */
    public function testBasicAsserts()
    {
        $url = $this->createFile();
        $stream = new OutputStream($url);

        $this->assertTrue(is_resource($stream->getResource()));
        $this->assertEquals($url, $stream->getResource());
        $this->assertTrue($stream->close());

        unset($stream, $url);
    }

    /**
     * @covers \GravityMedia\Stream\OutputStream::write()
     * @covers \GravityMedia\Stream\OutputStream::tell()
     * @covers \GravityMedia\Stream\OutputStream::rewind()
     * @covers \GravityMedia\Stream\OutputStream::seek()
     * @covers \GravityMedia\Stream\OutputStream::close()
     */
    public function testStream()
    {
        $url = $this->createFile();
        $stream = new OutputStream($url);

        $length = 8192;
        $data = $this->createRandomData($length);
        $offset = mt_rand(0, $length - 1);

        $this->assertEquals($length, $stream->write($data));
        $this->assertEquals($length, $stream->tell());

        $this->assertEquals(0, $stream->rewind());
        $this->assertEquals($offset, $stream->seek($offset));

        $this->assertTrue($stream->close());

        unset($stream, $url);
    }

    /**
     * @covers \GravityMedia\Stream\OutputStream::stats()
     */
    public function testStreamStats()
    {
        $url = $this->createFile();
        $stream = new OutputStream($url);

        $this->assertInstanceOf('GravityMedia\Stream\StreamStats', $stream->stats());

        unset($stream, $url);
    }

    /**
     * @covers \GravityMedia\Stream\OutputStream::metadata()
     */
    public function testStreamMetadata()
    {
        $url = $this->createFile();
        $stream = new OutputStream($url);

        $this->assertInstanceOf('GravityMedia\Stream\StreamMetadata', $stream->metadata());

        unset($stream, $url);
    }
}
