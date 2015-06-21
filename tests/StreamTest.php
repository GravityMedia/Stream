<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest;

use GravityMedia\Stream\Stream;

/**
 * Stream test
 *
 * @package GravityMedia\Stream
 */
class StreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create temp file and return its location
     *
     * @return string
     */
    protected function createTempFile()
    {
        return tempnam(sys_get_temp_dir(), strtoupper(uniqid()));
    }

    /**
     * Create random data
     *
     * @param int $length
     *
     * @return string
     */
    public function createRandomData($length)
    {
        $data = '';
        $dictionary = range("\x00", "\x7f");
        $max = count($dictionary) - 1;
        while (0 <= --$length) {
            $data .= $dictionary[mt_rand(0, $max)];
        }
        return $data;
    }

    /**
     * @covers \GravityMedia\Stream\Stream::__construct()
     * @covers \GravityMedia\Stream\Stream::getResource()
     * @covers \GravityMedia\Stream\Stream::__destruct()
     */
    public function testBasicAsserts()
    {
        $stream = new Stream($this->createTempFile());

        $this->assertTrue(is_resource($stream->getResource()));
        $this->assertTrue($stream->close());

        unset($stream);
    }

    /**
     * @covers \GravityMedia\Stream\Stream::read()
     * @covers \GravityMedia\Stream\Stream::tell()
     * @covers \GravityMedia\Stream\Stream::eof()
     * @covers \GravityMedia\Stream\Stream::rewind()
     * @covers \GravityMedia\Stream\Stream::seek()
     * @covers \GravityMedia\Stream\Stream::close()
     */
    public function testReadStream()
    {
        $length = 8192;
        $data = $this->createRandomData($length);
        $uri = $this->createTempFile();
        file_put_contents($uri, $data);
        $offset = mt_rand(0, $length - 1);

        $stream = new Stream($uri);

        $this->assertEquals($data, $stream->read($length));
        $this->assertEquals($length, $stream->tell());

        $this->assertEmpty($stream->read());
        $this->assertTrue($stream->eof());

        $this->assertEquals(0, $stream->rewind());
        $this->assertEquals($offset, $stream->seek($offset));
        $this->assertEquals($data{$offset}, $stream->read());

        $this->assertTrue($stream->close());

        unset($stream);
    }

    /**
     * @covers \GravityMedia\Stream\Stream::write()
     * @covers \GravityMedia\Stream\Stream::tell()
     * @covers \GravityMedia\Stream\Stream::rewind()
     * @covers \GravityMedia\Stream\Stream::seek()
     * @covers \GravityMedia\Stream\Stream::close()
     */
    public function testWriteStream()
    {
        $stream = new Stream($this->createTempFile());

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
}
