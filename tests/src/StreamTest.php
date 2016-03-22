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
 *
 * @covers  GravityMedia\Stream\Stream
 */
class StreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The URI of the resource
     */
    const RESOURCE_URI = 'php://temp';

    /**
     * The read/write-mode of the resource
     */
    const RESOURCE_MODE = 'r+';

    /**
     * @var resource
     */
    protected $resource;

    /**
     * Get resource
     *
     * @param string|null $contents
     * @param int         $offset
     *
     * @return resource
     */
    protected function getResource($contents = null, $offset = 0)
    {
        if (null === $this->resource) {
            $this->resource = fopen(static::RESOURCE_URI, static::RESOURCE_MODE);
        }

        ftruncate($this->resource, 0);

        if (null !== $contents) {
            fwrite($this->resource, $contents);
        }

        fseek($this->resource, $offset);

        return $this->resource;
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        @fclose($this->resource);
    }

    /**
     * Test that the stream creation throws an exception on invalid resource argument
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid resource
     */
    public function testStreamCreationThrowsExceptionOnInvalidResourceArgument()
    {
        Stream::fromResource(null);
    }

    /**
     * Test that the an exception is thrown when trying to bind an invalid resources
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid resource
     */
    public function testBindingInvalidResourceThrowsException()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);

        $stream->bindResource(null);
    }

    /**
     * Test that the stream returns the resource which was bound before
     */
    public function testGettingResourceFromStream()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);

        $this->assertEquals($resource, $stream->getResource());
    }

    /**
     * Test that the resource setter initializes the meta data
     */
    public function testStreamCreationInitializesMetaData()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);

        $this->assertTrue($stream->isLocal());
        $this->assertTrue($stream->isReadable());
        $this->assertTrue($stream->isWritable());
        $this->assertTrue($stream->isSeekable());
        $this->assertEquals(static::RESOURCE_URI, $stream->getUri());
    }

    /**
     * Test that getting the size from a non-local stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Stream not local
     */
    public function testGettingSizeThrowsExceptionOnNonLocalStream()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['isLocal'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isLocal')
            ->will($this->returnValue(false));

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        $streamMock->getSize();
    }

    /**
     * Test that getting the size from a closed stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid resource
     */
    public function testGettingSizeThrowsExceptionOnClosedStream()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);
        fclose($resource);

        $stream->getSize();
    }

    /**
     * Test that a stream returns the correct size
     */
    public function testGettingSize()
    {
        $resource = $this->getResource('contents');
        $stream = Stream::fromResource($resource);

        $this->assertEquals(8, $stream->getSize());
    }

    /**
     * Test that checking for the end of stream throws an exception on closed stream
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid resource
     */
    public function testEndOfStreamThrowsExceptionOnClosedStream()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);
        fclose($resource);

        $stream->eof();
    }

    /**
     * Test that the end of stream was reached
     */
    public function testEndOfStream()
    {
        $resource = $this->getResource();
        fread($resource, 1);
        $stream = Stream::fromResource($resource);

        $this->assertTrue($stream->eof());
    }

    /**
     * Test that locating the position on a closed stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid resource
     */
    public function testLocatingPositionThrowsExceptionOnClosedStream()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);
        fclose($resource);

        $stream->tell();
    }

    /**
     * Test that the position of the stream can be located
     */
    public function testLocatingPosition()
    {
        $resource = $this->getResource('contents', 4);
        $stream = Stream::fromResource($resource);

        $this->assertEquals(4, $stream->tell());
    }

    /**
     * Test that seeking on a non-seekable stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Stream not seekable
     */
    public function testSeekingPositionThrowsExceptionOnNonSeekableStream()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['getResource', 'isSeekable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($this->getResource()));

        $streamMock->expects($this->once())
            ->method('isSeekable')
            ->will($this->returnValue(false));

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        $streamMock->seek(0);
    }

    /**
     * Test that seeking on a closed stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid resource
     */
    public function testSeekingPositionThrowsExceptionOnClosedStream()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);
        fclose($resource);

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
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);

        $stream->seek(-1);
    }

    /**
     * Test that seeking the stream returns correct position
     */
    public function testSeekingPosition()
    {
        $resource = $this->getResource('contents');
        $stream = Stream::fromResource($resource);

        $this->assertEquals(0, $stream->seek(-8, SEEK_END));
    }

    /**
     * Test that rewinding the stream returns correct position
     */
    public function testRewindPosition()
    {
        $resource = $this->getResource('contents');
        $stream = Stream::fromResource($resource);

        $this->assertEquals(0, $stream->rewind());
    }

    /**
     * Test that reading data from a closed stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid resource
     */
    public function testReadingDataThrowsExceptionOnClosedStream()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);
        fclose($resource);

        $stream->read(1);
    }

    /**
     * Test that reading data from a non-readable stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Stream not readable
     */
    public function testReadingDataThrowsExceptionOnNonReadableStream()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['getResource', 'isReadable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($this->getResource()));

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(false));

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        $streamMock->read(1);
    }

    /**
     * Test that reading data from an empty stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Unexpected result of operation
     */
    public function testReadingDataThrowsExceptionOnInvalidLength()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);

        $stream->read(0);
    }

    /**
     * Test that the data can be read
     */
    public function testReadingData()
    {
        $resource = $this->getResource('contents');
        $stream = Stream::fromResource($resource);

        $this->assertEquals('contents', $stream->read(8));
    }

    /**
     * Test that writing data to a closed stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid resource
     */
    public function testWritingDataThrowsExceptionOnClosedStream()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);
        fclose($resource);

        $stream->write('contents');
    }

    /**
     * Test that writing data to a non-writable stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Stream not writable
     */
    public function testWritingDataThrowsExceptionOnNonWritableStream()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['getResource', 'isWritable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($this->getResource()));

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(false));

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        $streamMock->write('contents');
    }

    /**
     * Test that writing invalid data to a stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Unexpected result of operation
     */
    public function testWritingDataThrowsExceptionOnInvalidData()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);

        $stream->write(new \stdClass());
    }

    /**
     * Test that the data can be written and the length is returned
     */
    public function testWritingData()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);

        $this->assertEquals(8, $stream->write('contents'));
    }

    /**
     * Test that truncating a closed stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid resource
     */
    public function testTruncatingThrowsExceptionOnClosedStream()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);
        fclose($resource);

        $stream->truncate(0);
    }

    /**
     * Test that truncating a non-writable stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Stream not writable
     */
    public function testTruncatingThrowsExceptionOnNonWritableStream()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['getResource', 'isWritable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($this->getResource()));

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(false));

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        $streamMock->truncate(0);
    }

    /**
     * Test that the stream can be truncated
     */
    public function testTruncating()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);

        $this->assertTrue($stream->truncate(8));
        $this->assertEquals(str_repeat("\x00", 8), stream_get_contents($resource));
    }

    /**
     * Test that closing the stream closes the trem resource
     */
    public function testCloseStreamResource()
    {
        $resource = $this->getResource();
        $stream = Stream::fromResource($resource);

        $this->assertTrue($stream->close());
        $this->assertFalse(is_resource($resource));
    }
}
