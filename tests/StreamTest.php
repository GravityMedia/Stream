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
     * Test that the stream creation throws an exception on invalid resource argument
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid resource
     */
    public function testCreatingStreamThrowsExceptionOnInvalidResourceArgument()
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
        $stream = new Stream();
        $stream->bind(null);
    }

    /**
     * Test that the resource is closed on destruct
     */
    public function testStreamClosesResourceOnDestruct()
    {
        $resource = fopen('php://input', 'r');
        $stream = Stream::fromResource($resource);
        unset($stream);

        $this->assertFalse(is_resource($resource));
    }

    /**
     * Test that the stream returns the resource which was bound before
     */
    public function testGettingResourceFromStream()
    {
        $resource = fopen('php://input', 'r');
        $stream = Stream::fromResource($resource);

        $this->assertEquals($resource, $stream->getResource());
    }

    /**
     * Test that the resource setter initializes the meta data
     */
    public function testConstructorInitializesMetaData()
    {
        $resource = fopen('php://temp', 'r');
        $stream = Stream::fromResource($resource);

        $this->assertTrue($stream->isReadable());
        $this->assertFalse($stream->isWritable());
        $this->assertTrue($stream->isSeekable());
        $this->assertEquals('php://temp', $stream->getUri());
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
    public function testReadingThrowsExceptionOnNonReadableStreams()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isReadable'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(false));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $streamMock->read();
    }

    /**
     * Test that getting the writer throws an exception on non-writable streams
     *
     * @uses                     GravityMedia\Stream\StreamWriter::__construct
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Operation not supported
     */
    public function testWritingThrowsExceptionOnNonWritableStreams()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isWritable'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(false));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $streamMock->write(null);
    }

    /**
     * Test that getting the size from a non-local stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Operation not supported
     */
    public function testGettingSizeThrowsExceptionOnNonLocalStream()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isLocal'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isLocal')
            ->will($this->returnValue(false));

        /** @var \GravityMedia\Stream\Stream $streamMock */
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
        $resource = fopen('php://input', 'r');
        $stream = Stream::fromResource($resource);
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
        $resource = fopen('php://temp', 'r');
        $stream = Stream::fromResource($resource);

        $stream->getSize();
    }

    /**
     * Test that a stream returns the correct size
     */
    public function testGettingSize()
    {
        $resource = fopen('php://temp', 'w');
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
        $resource = fopen('php://temp', 'r');
        $stream = Stream::fromResource($resource);
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
        $resource = fopen('php://temp', 'r');
        $stream = Stream::fromResource($resource);
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

        $stream = Stream::fromResource($resource);

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
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isSeekable'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isSeekable')
            ->will($this->returnValue(false));

        /** @var \GravityMedia\Stream\Stream $streamMock */
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
        $resource = fopen('php://temp', 'r');
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
        $resource = fopen('php://temp', 'r');
        $stream = Stream::fromResource($resource);

        $stream->seek(-1);
    }

    /**
     * Test that seeking the stream returns correct position
     */
    public function testSeekingPosition()
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'contents');

        $stream = Stream::fromResource($resource);

        $this->assertEquals(0, $stream->seek(-8, SEEK_END));
    }

    /**
     * Test that rewinding the stream returns correct position
     */
    public function testRewindPosition()
    {
        $resource = fopen('php://temp', 'r+');
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
        $resource = fopen('php://temp', 'r');
        fclose($resource);

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isReadable', 'getResource'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $streamMock->read();
    }

    /**
     * Test that reading data from an empty stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Unexpected result of operation
     */
    public function testReadingDataThrowsExceptionOnInvalidLength()
    {
        $resource = fopen('php://input', 'r');

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isReadable', 'getResource'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $streamMock->read(0);
    }

    /**
     * Test that the data can be read
     */
    public function testReadingData()
    {
        $data = 'contents';
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, $data);
        fseek($resource, 0);

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isReadable', 'getResource'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $this->assertEquals($data, $streamMock->read(8));
    }

    /**
     * Test that writing data to a closed stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid resource
     */
    public function testWritingDataThrowsExceptionOnClosedStream()
    {
        $resource = fopen('php://temp', 'w');
        fclose($resource);

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isWritable', 'getResource'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

        /** @var \GravityMedia\Stream\Stream $streamMock */
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
        $resource = fopen('php://temp', 'w');

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isWritable', 'getResource'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $streamMock->write(new \stdClass());
    }

    /**
     * Test that the data can be written and the length is returned
     */
    public function testWritingData()
    {
        $resource = fopen('php://temp', 'w');

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isWritable', 'getResource'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $this->assertEquals(8, $streamMock->write('contents'));

        fclose($resource);
    }

    /**
     * Test that truncating a closed stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid resource
     */
    public function testTruncatingThrowsExceptionOnClosedStream()
    {
        $resource = fopen('php://temp', 'w');
        fclose($resource);

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isWritable', 'getResource'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $streamMock->truncate(8);
    }

    /**
     * Test that truncating a closed stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Unexpected result of operation
     */
    public function testTruncatingThrowsExceptionOnInvalidSize()
    {
        $resource = fopen('php://temp', 'w');

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isWritable', 'getResource'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $streamMock->truncate(new \stdClass());
    }

    /**
     * Test that the stream can be truncated
     */
    public function testTruncating()
    {
        $resource = fopen('php://temp', 'w+');

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isWritable', 'getResource'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $streamMock->truncate(8);

        $this->assertEquals(str_repeat("\x00", 8), stream_get_contents($resource));

        fclose($resource);
    }

    /**
     * Test that closing the stream closes the trem resource
     */
    public function testCloseStreamResource()
    {
        $resource = fopen('php://input', 'r');
        $stream = Stream::fromResource($resource);
        $stream->close();

        $this->assertFalse(is_resource($resource));
    }
}
