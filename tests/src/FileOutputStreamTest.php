<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest;

use GravityMedia\Stream\FileOutputStream;

/**
 * File output stream test
 *
 * @package GravityMedia\StreamTest
 */
class FileOutputStreamTest extends StreamTestCase
{
    /**
     * @covers \GravityMedia\Stream\FileOutputStream::__construct()
     * @covers \GravityMedia\Stream\FileOutputStream::getResource()
     * @covers \GravityMedia\Stream\FileOutputStream::getFileInfo()
     * @covers \GravityMedia\Stream\FileOutputStream::__destruct()
     */
    public function testBasicAsserts()
    {
        $filename = $this->createFile();
        $stream = new FileOutputStream($filename);

        $this->assertTrue(is_resource($stream->getResource()));
        $this->assertInstanceOf('\SplFileInfo', $stream->getFileInfo());

        unset($stream);
        unlink($filename);
    }

    /**
     * @covers \GravityMedia\Stream\FileOutputStream::write()
     * @covers \GravityMedia\Stream\FileOutputStream::tell()
     * @covers \GravityMedia\Stream\FileOutputStream::rewind()
     * @covers \GravityMedia\Stream\FileOutputStream::seek()
     * @covers \GravityMedia\Stream\FileOutputStream::close()
     */
    public function testStream()
    {
        $filename = $this->createFile();
        $stream = new FileOutputStream($filename);

        $length = 8192;
        $data = $this->createRandomData($length);
        $offset = mt_rand(0, $length - 1);

        $this->assertEquals($length, $stream->write($data));
        $this->assertEquals($length, $stream->tell());

        $this->assertEquals(0, $stream->rewind());
        $this->assertEquals($offset, $stream->seek($offset));

        $this->assertTrue($stream->close());

        unset($stream);
        unlink($filename);
    }

    /**
     * @covers \GravityMedia\Stream\FileOutputStream::stats()
     */
    public function testStreamStats()
    {
        $filename = $this->createFile();
        $stream = new FileOutputStream($filename);

        $this->assertInstanceOf('GravityMedia\Stream\StreamStats', $stream->stats());
    }

    /**
     * @covers \GravityMedia\Stream\FileOutputStream::metadata()
     */
    public function testStreamMetadata()
    {
        $filename = $this->createFile();
        $stream = new FileOutputStream($filename);

        $this->assertInstanceOf('GravityMedia\Stream\StreamMetadata', $stream->metadata());
    }
}
