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
 * @covers  GravityMedia\Stream\Stream
 */
class StreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the constructor throws an exception for invalid URI argument
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Unexpected result of operation
     */
    public function testConstructorThrowsExceptionOnInvalidUriArgument()
    {
        new Stream(false);
    }

    /**
     * Test that the constructor internally binds the resource
     */
    public function testConstructorInternallyBindsResource()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('bind'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('bind')
            ->with($this->isType('resource'));

        $reflectedClass = new \ReflectionClass('GravityMedia\Stream\Stream');
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($streamMock, 'php://temp');
    }

    /**
     * Test that the an exception is thrown for invalid resources
     *
     * @expectedException        \GravityMedia\Stream\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid resource argument
     */
    public function testBindingInvalidResourceThrowsException()
    {
        $stream = new Stream();
        $stream->bind(null);
    }

    /**
     * Test that a readable stream is readable
     */
    public function testReadableStreamIsReadable()
    {
        $stream = new Stream('php://temp', 'rb');

        $this->assertTrue($stream->isReadable());
    }

    /**
     * Test that a writable stream is writable
     */
    public function testWritableStreamIsWritable()
    {
        $stream = new Stream('php://temp', 'wb');

        $this->assertTrue($stream->isWritable());
    }

    /**
     * Test that the resource setter initializes the meta data
     */
    public function testResourceSetterInitializesMetaData()
    {
        $uri = 'php://temp';
        $stream = new Stream($uri, 'r+b');

        $this->assertTrue($stream->isReadable());
        $this->assertTrue($stream->isWritable());
        $this->assertTrue($stream->isSeekable());
        $this->assertEquals($uri, $stream->getUri());
        $this->assertEquals(0, $stream->getSize());
        $this->assertTrue($stream->isLocal());
    }

    /**
     * Test that a stream returns the correct size
     */
    public function testStreamReturnsCorrectSize()
    {
        $contents = 'contents';
        $stream = new Stream('php://temp', 'r+b');
        $stream->write($contents);

        $this->assertEquals(8, $stream->getSize());
    }

    /**
     * Test that a stream returns its contents
     */
    public function testStreamReturnsContents()
    {
        $contents = 'contents';
        $stream = new Stream('php://temp', 'r+b');
        $stream->write($contents);
        $stream->rewind();

        $this->assertEquals($contents, $stream->getContents());
    }

    /**
     * Test that the end of stream was reached
     */
    public function testReachEndOfStream()
    {
        $stream = new Stream('php://temp');
        $stream->read();

        $this->assertTrue($stream->eof());
    }

    /**
     * Test that the position of the stream is returned
     */
    public function testReturnPositionOfStream()
    {
        $position = 4;
        $stream = new Stream('php://temp', 'r+b');
        $stream->write('contents');
        $stream->seek($position);

        $this->assertEquals($position, $stream->tell());
    }

    /**
     * Test that seeking the stream returns correct position
     */
    public function testSeekStream()
    {
        $stream = new Stream('php://temp', 'r+b');
        $stream->write('contents');

        $this->assertEquals(0, $stream->seek(-8, SEEK_END));
    }

    /**
     * Test that rewinding the stream returns correct position
     */
    public function testRewindStream()
    {
        $stream = new Stream('php://temp', 'r+b');
        $stream->write('contents');

        $this->assertEquals(0, $stream->rewind());
    }

    /**
     * Tests that a stream can be truncated
     */
    public function testTruncateStream()
    {
        $stream = new Stream('php://temp', 'w+b');
        $stream->truncate(8);

        $this->assertEquals(str_repeat("\x00", 8), $stream->getContents());
    }
}
