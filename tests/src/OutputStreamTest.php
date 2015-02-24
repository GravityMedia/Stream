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
        $resource = $this->createResource();
        $stream = new OutputStream($resource);

        $this->assertTrue(is_resource($stream->getResource()));
        $this->assertEquals($resource, $stream->getResource());
        $this->assertTrue($stream->close());
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
        $resource = $this->createResource();
        $stream = new OutputStream($resource);

        $length = 8192;
        $data = $this->createRandomData($length);
        $offset = mt_rand(0, $length - 1);

        $this->assertEquals($length, $stream->write($data));
        $this->assertEquals($length, $stream->tell());

        $this->assertEquals(0, $stream->rewind());
        $this->assertEquals($offset, $stream->seek($offset));

        $this->assertTrue($stream->close());
    }

    /**
     * @covers \GravityMedia\Stream\OutputStream::stats()
     */
    public function testStreamStats()
    {
        $resource = $this->createResource();
        $stream = new OutputStream($resource);

        $this->assertInstanceOf('GravityMedia\Stream\StreamStats', $stream->stats());
    }

    /**
     * @covers \GravityMedia\Stream\OutputStream::metadata()
     */
    public function testStreamMetadata()
    {
        $resource = $this->createResource();
        $stream = new OutputStream($resource);

        $this->assertInstanceOf('GravityMedia\Stream\StreamMetadata', $stream->metadata());
    }
}
