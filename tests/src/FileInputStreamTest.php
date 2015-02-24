<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest;

use GravityMedia\Stream\FileInputStream;

/**
 * File input stream test
 *
 * @package GravityMedia\StreamTest
 */
class FileInputStreamTest extends StreamTestCase
{
    /**
     * @covers \GravityMedia\Stream\FileInputStream::__construct()
     * @covers \GravityMedia\Stream\FileInputStream::getResource()
     * @covers \GravityMedia\Stream\FileInputStream::getFileInfo()
     * @covers \GravityMedia\Stream\FileInputStream::__destruct()
     */
    public function testBasicAsserts()
    {
        $filename = $this->createFile();
        $stream = new FileInputStream($filename);

        $this->assertTrue(is_resource($stream->getResource()));
        $this->assertInstanceOf('\SplFileInfo', $stream->getFileInfo());
        $this->assertTrue($stream->close());

        unset($stream);
        unlink($filename);
    }

    /**
     * @covers \GravityMedia\Stream\FileInputStream::read()
     * @covers \GravityMedia\Stream\FileInputStream::tell()
     * @covers \GravityMedia\Stream\FileInputStream::end()
     * @covers \GravityMedia\Stream\FileInputStream::rewind()
     * @covers \GravityMedia\Stream\FileInputStream::seek()
     * @covers \GravityMedia\Stream\FileInputStream::close()
     */
    public function testStream()
    {
        $length = 8192;
        $data = $this->createRandomData($length);
        $offset = mt_rand(0, $length - 1);

        $filename = $this->createFile($data);
        $stream = new FileInputStream($filename);

        $this->assertEquals($data, $stream->read($length));
        $this->assertEquals($length, $stream->tell());

        $this->assertEmpty($stream->read());
        $this->assertTrue($stream->end());

        $this->assertEquals(0, $stream->rewind());
        $this->assertEquals($offset, $stream->seek($offset));
        $this->assertEquals($data{$offset}, $stream->read());

        $this->assertTrue($stream->close());

        unset($stream);
        unlink($filename);
    }

    /**
     * @covers \GravityMedia\Stream\FileInputStream::stats()
     */
    public function testStreamStats()
    {
        $filename = $this->createFile();
        $stream = new FileInputStream($filename);

        $this->assertInstanceOf('GravityMedia\Stream\StreamStats', $stream->stats());
    }

    /**
     * @covers \GravityMedia\Stream\FileInputStream::metadata()
     */
    public function testStreamMetadata()
    {
        $filename = $this->createFile();
        $stream = new FileInputStream($filename);

        $this->assertInstanceOf('GravityMedia\Stream\StreamMetadata', $stream->metadata());
    }
}
