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
     * @expectedExceptionMessage Failed to open stream
     */
    public function testConstructorThrowsExceptionOnInvalidUriArgument()
    {
        new Stream(false);
    }

    /**
     * Test that the constructor binds the resource
     */
    public function testConstructorBindsResource()
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
        $constructor->invoke($streamMock, 'php://input');
    }

    /**
     * Test that the an exception is thrown when trying to bind an invalid resources
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
     * Test that the resource is closed on destruct
     */
    public function testStreamClosesResourceOnDestruct()
    {
        $resource = fopen('php://input', 'r');
        $stream = new Stream();
        $stream->bind($resource);
        unset($stream);

        $this->assertFalse(is_resource($resource));
    }

    /**
     * Test that the stream returns the resource which was bound before
     */
    public function testStreamReturnsResource()
    {
        $resource = fopen('php://input', 'r');
        $stream = new Stream();
        $stream->bind($resource);

        $this->assertEquals($resource, $stream->getResource());
    }

    /**
     * Test that the resource setter initializes the meta data
     */
    public function testResourceSetterInitializesMetaData()
    {
        $uri = 'php://temp';
        $stream = new Stream($uri);

        $this->assertTrue($stream->isReadable());
        $this->assertFalse($stream->isWritable());
        $this->assertTrue($stream->isSeekable());
        $this->assertEquals($uri, $stream->getUri());
        $this->assertEquals(0, $stream->getSize());
        $this->assertTrue($stream->isLocal());
    }

    /**
     * Test that the reader getter throws an exception on non-readable streams
     *
     * @uses                     GravityMedia\Stream\StreamReader::__construct
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Operation not supported
     */
    public function testReaderGetterThrowsExceptionOnNonReadableStreams()
    {
        $stream = new Stream('php://output', 'w');

        $stream->getReader();
    }

    /**
     * Test that a stream reader is returned
     *
     * @uses GravityMedia\Stream\StreamReader::__construct
     */
    public function testStreamReturnsReader()
    {
        $stream = new Stream('php://input', 'r');

        $this->assertInstanceOf('GravityMedia\Stream\StreamReaderInterface', $stream->getReader());
    }

    /**
     * Test that the writer getter throws an exception on non-writable streams
     *
     * @uses                     GravityMedia\Stream\StreamWriter::__construct
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Operation not supported
     */
    public function testWriterGetterThrowsExceptionOnNonReadableStreams()
    {
        $stream = new Stream('php://input', 'r');

        $stream->getWriter();
    }

    /**
     * Test that a stream writer is returned
     *
     * @uses GravityMedia\Stream\StreamWriter::__construct
     */
    public function testStreamReturnsWriter()
    {
        $stream = new Stream('php://output', 'w');

        $this->assertInstanceOf('GravityMedia\Stream\StreamWriterInterface', $stream->getWriter());
    }

    /**
     * Test that a stream returns the correct size
     */
    public function testStreamReturnsCorrectSize()
    {
        $contents = 'contents';
        $resource = fopen('php://temp', 'w');
        fwrite($resource, $contents);

        $stream = new Stream();
        $stream->bind($resource);

        $this->assertEquals(8, $stream->getSize());
    }

    /**
     * Test that the end of stream was reached
     */
    public function testReachEndOfStream()
    {
        $resource = fopen('php://temp', 'r');
        fread($resource, 1);

        $stream = new Stream();
        $stream->bind($resource);

        $this->assertTrue($stream->eof());
    }

    /**
     * Test that the position of the stream is returned
     */
    public function testReturnPositionOfStream()
    {
        $position = 4;
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'contents');
        fseek($resource, $position);

        $stream = new Stream();
        $stream->bind($resource);

        $this->assertEquals($position, $stream->tell());
    }

    /**
     * Test that seeking the stream returns correct position
     */
    public function testSeekStream()
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'contents');

        $stream = new Stream();
        $stream->bind($resource);

        $this->assertEquals(0, $stream->seek(-8, SEEK_END));
    }

    /**
     * Test that rewinding the stream returns correct position
     */
    public function testRewindStream()
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'contents');

        $stream = new Stream();
        $stream->bind($resource);

        $this->assertEquals(0, $stream->rewind());
    }
}
