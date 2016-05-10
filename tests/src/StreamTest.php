<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest;

use GravityMedia\Stream\Stream;
use GravityMedia\StreamTest\Helper\ResourceHelper;

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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);

        $stream->bindResource(null);
    }

    /**
     * Test that the stream returns the resource which was bound before
     */
    public function testGettingResourceFromStream()
    {
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);

        $this->assertEquals($resource, $stream->getResource());
    }

    /**
     * Test that the resource setter initializes the meta data
     */
    public function testStreamCreationInitializesMetaData()
    {
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);

        $this->assertTrue($stream->isLocal());
        $this->assertTrue($stream->isReadable());
        $this->assertTrue($stream->isWritable());
        $this->assertTrue($stream->isSeekable());
        $this->assertEquals(ResourceHelper::RESOURCE_URI, $stream->getUri());
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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);
        $stream->close();
        $stream->getSize();
    }

    /**
     * Test that a stream returns the correct size
     */
    public function testGettingSize()
    {
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();
        fwrite($resource, 'contents');

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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);
        $stream->close();
        $stream->eof();
    }

    /**
     * Test that the end of stream was reached
     */
    public function testEndOfStream()
    {
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();
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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);
        $stream->close();
        $stream->tell();
    }

    /**
     * Test that the position of the stream can be located
     */
    public function testLocatingPosition()
    {
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();
        fwrite($resource, 'contents');

        $stream = Stream::fromResource($resource);
        $stream->seek(4);

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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['getResource', 'isSeekable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);
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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);
        $stream->seek(-1);
    }

    /**
     * Test that seeking the stream returns correct position
     */
    public function testSeekingPosition()
    {
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();
        fwrite($resource, 'contents');

        $stream = Stream::fromResource($resource);

        $this->assertEquals(0, $stream->seek(-8, SEEK_END));
    }

    /**
     * Test that rewinding the stream returns correct position
     */
    public function testRewindPosition()
    {
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();
        fwrite($resource, 'contents');

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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);
        $stream->close();
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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['getResource', 'isReadable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);
        $stream->read(0);
    }

    /**
     * Test that the data can be read
     */
    public function testReadingData()
    {
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();
        fwrite($resource, 'contents');
        fseek($resource, 0);

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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);
        $stream->close();
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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['getResource', 'isWritable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);
        $stream->write(new \stdClass());
    }

    /**
     * Test that the data can be written and the length is returned
     */
    public function testWritingData()
    {
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);
        $stream->close();
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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['getResource', 'isWritable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

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
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);

        $this->assertTrue($stream->truncate(8));
        $this->assertEquals("\x00\x00\x00\x00\x00\x00\x00\x00", stream_get_contents($resource));
    }

    /**
     * Test that closing the stream closes the trem resource
     */
    public function testCloseStreamResource()
    {
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);

        $this->assertTrue($stream->close());
        $this->assertFalse(is_resource($resource));
    }
}
