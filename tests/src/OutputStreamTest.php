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
     * @covers \GravityMedia\Stream\OutputStream::__destruct()
     */
    public function testBasicAsserts()
    {
        $stream = new OutputStream($this->createTempFile());

        $this->assertTrue(is_resource($stream->getResource()));
        $this->assertTrue($stream->close());

        unset($stream);
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
        $stream = new OutputStream($this->createTempFile());

        $length = 8192;
        $data = $this->createRandomData($length);
        $offset = mt_rand(0, $length - 1);

        $this->assertEquals($length, $stream->write($data));
        $this->assertEquals($length, $stream->tell());

        $this->assertEquals(0, $stream->rewind());
        $this->assertEquals($offset, $stream->seek($offset));

        $this->assertTrue($stream->close());

        unset($stream);
    }

    /**
     * @covers \GravityMedia\Stream\OutputStream::stats()
     */
    public function testStreamStats()
    {
        $stream = new OutputStream($this->createTempFile());

        $this->assertInstanceOf('GravityMedia\Stream\StreamStats', $stream->stats());

        unset($stream);
    }

    /**
     * @covers \GravityMedia\Stream\OutputStream::metadata()
     */
    public function testStreamMetadata()
    {
        $stream = new OutputStream($this->createTempFile());

        $this->assertInstanceOf('GravityMedia\Stream\StreamMetadata', $stream->metadata());

        unset($stream);
    }
}
