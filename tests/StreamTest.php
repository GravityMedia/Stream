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
 * @package GravityMedia\StreamTest
 * @covers  GravityMedia\Stream\Stream
 */
class StreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        stream_wrapper_register('test', '\GravityMedia\StreamTest\Util\TestStreamWrapper', STREAM_IS_URL);
    }

    /**
     * Test that the constructor throws an exception on invalid URI argument
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
     * @expectedExceptionMessage Invalid stream resource
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
     * Test that getting the reader throws an exception on non-readable streams
     *
     * @uses                     GravityMedia\Stream\StreamReader::__construct
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Operation not supported
     */
    public function testGettingReaderThrowsExceptionOnNonReadableStreams()
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
     * Test that getting the writer throws an exception on non-writable streams
     *
     * @uses                     GravityMedia\Stream\StreamWriter::__construct
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Operation not supported
     */
    public function testGettingWriterThrowsExceptionOnNonReadableStreams()
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
     * Test that getting the size from a non-local stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Operation not supported
     */
    public function testGettingSizeThrowsExceptionOnNonLocalStream()
    {
        $stream = new Stream('test://phpunit');

        $stream->getSize();
    }

    /**
     * Test that getting the size from a closed stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid stream resource
     */
    public function testGettingSizeThrowsExceptionOnClosedStream()
    {
        $stream = new Stream('php://temp');
        $stream->close();

        $stream->getSize();
    }

    /**
     * Test that getting the size from a stream throws an exception when there are no stats available
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Unexpected result of operation
     */
    public function testGettingSizeThrowsExceptionOnStreamWithNoStats()
    {
        $stream = new Stream('php://input');

        $stream->getSize();
    }

    /**
     * Test that a stream returns the correct size
     */
    public function testGettingSize()
    {
        $resource = fopen('php://temp', 'w');
        fwrite($resource, 'contents');

        $stream = new Stream();
        $stream->bind($resource);

        $this->assertEquals(8, $stream->getSize());
    }

    /**
     * Test that checking for the end of stream throws an exception on closed stream
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid stream resource
     */
    public function testEndOfStreamThrowsExceptionOnClosedStream()
    {
        $stream = new Stream('php://temp');
        $stream->close();

        $stream->eof();
    }

    /**
     * Test that the end of stream was reached
     */
    public function testEndOfStream()
    {
        $resource = fopen('php://temp', 'r');
        fread($resource, 1);

        $stream = new Stream();
        $stream->bind($resource);

        $this->assertTrue($stream->eof());
    }

    /**
     * Test that locating the position on a closed stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid stream resource
     */
    public function testLocatingPositionThrowsExceptionOnClosedStream()
    {
        $stream = new Stream('php://temp');
        $stream->close();

        $stream->tell();
    }

    /**
     * Test that the position of the stream can be located
     */
    public function testLocatingPosition()
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
     * Test that seeking on a non-seekable stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Operation not supported
     */
    public function testSeekingPositionThrowsExceptionOnNonSeekableStream()
    {
        $stream = new Stream('php://input');

        $stream->seek(0);
    }

    /**
     * Test that seeking on a closed stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid stream resource
     */
    public function testSeekingPositionThrowsExceptionOnClosedStream()
    {
        $stream = new Stream('php://temp');
        $stream->close();

        $stream->seek(0);
    }

    /**
     * Test that seeking on a stream throws an exception when trying to seek with an invalid offset
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Unexpected result of operation
     */
    public function testSeekingPositionThrowsExceptionOnInvalidOffset()
    {
        $stream = new Stream('php://temp');

        $stream->seek(-1);
    }

    /**
     * Test that seeking the stream returns correct position
     */
    public function testSeekingPosition()
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
    public function testRewindPosition()
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'contents');

        $stream = new Stream();
        $stream->bind($resource);

        $this->assertEquals(0, $stream->rewind());
    }

    /**
     * Test that closing the stream closes the trem resource
     */
    public function testCloseStreamResource()
    {
        $resource = fopen('php://input', 'r');
        $stream = new Stream();
        $stream->bind($resource);
        $stream->close();

        $this->assertFalse(is_resource($resource));
    }
}
