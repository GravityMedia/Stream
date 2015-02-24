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
        $resource = $this->createResource();
        $stream = new InputStream($resource);

        $this->assertTrue(is_resource($stream->getResource()));
        $this->assertEquals($resource, $stream->getResource());

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
        $offset = mt_rand(0, $length - 1);

        $resource = $this->createResource($data);
        $stream = new InputStream($resource);

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
        $resource = $this->createResource();
        $stream = new InputStream($resource);

        $this->assertInstanceOf('GravityMedia\Stream\StreamStats', $stream->stats());
    }

    /**
     * @covers \GravityMedia\Stream\InputStream::metadata()
     */
    public function testStreamMetadata()
    {
        $resource = $this->createResource();
        $stream = new InputStream($resource);

        $this->assertInstanceOf('GravityMedia\Stream\StreamMetadata', $stream->metadata());
    }
}