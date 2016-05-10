<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest;

use GravityMedia\Stream\ByteOrder;
use GravityMedia\Stream\Stream;
use GravityMedia\StreamTest\Helper\ByteOrderHelper;
use GravityMedia\StreamTest\Helper\ResourceHelper;

/**
 * Stream test
 *
 * @package GravityMedia\StreamTest
 *
 * @covers  GravityMedia\Stream\Stream
 * @uses    GravityMedia\Stream\ByteOrder
 */
class StreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Provide signed 8-bit characters
     *
     * @return array
     */
    public function provideInt8Values()
    {
        return [
            ["\x80", -128],
            ["\x00", 0],
            ["\x7f", 127]
        ];
    }

    /**
     * Provide unsigned 8-bit characters
     *
     * @return array
     */
    public function provideUInt8Values()
    {
        return [
            ["\x00", 0],
            ["\x7f", 127],
            ["\x80", 128],
            ["\xff", 255]
        ];
    }

    /**
     * Provide signed 16-bit integers
     *
     * @return array
     */
    public function provideInt16Values()
    {
        $values = [
            [ByteOrder::BIG_ENDIAN, "\x80\x00", -32768],
            [ByteOrder::BIG_ENDIAN, "\x00\x00", 0],
            [ByteOrder::BIG_ENDIAN, "\x7f\xff", 32767],
            [ByteOrder::LITTLE_ENDIAN, "\x00\x80", -32768],
            [ByteOrder::LITTLE_ENDIAN, "\x00\x00", 0],
            [ByteOrder::LITTLE_ENDIAN, "\xff\x7f", 32767]
        ];

        if (ByteOrder::LITTLE_ENDIAN === ByteOrderHelper::getMachineByteOrder()) {
            return array_merge($values, [
                [ByteOrder::MACHINE_ENDIAN, "\x00\x80", -32768],
                [ByteOrder::MACHINE_ENDIAN, "\x00\x00", 0],
                [ByteOrder::MACHINE_ENDIAN, "\xff\x7f", 32767]
            ]);
        }

        return array_merge($values, [
            [ByteOrder::MACHINE_ENDIAN, "\x80\x00", -32768],
            [ByteOrder::MACHINE_ENDIAN, "\x00\x00", 0],
            [ByteOrder::MACHINE_ENDIAN, "\x7f\xff", 32767]
        ]);
    }

    /**
     * Provide unsigned 16-bit integers
     *
     * @return array
     */
    public function provideUInt16Values()
    {
        $values = [
            [ByteOrder::BIG_ENDIAN, "\x00\x00", 0],
            [ByteOrder::BIG_ENDIAN, "\x00\x01", 1],
            [ByteOrder::BIG_ENDIAN, "\x00\xff", 255],
            [ByteOrder::BIG_ENDIAN, "\xff\xfe", 65534],
            [ByteOrder::BIG_ENDIAN, "\xff\xff", 65535],
            [ByteOrder::LITTLE_ENDIAN, "\x00\x00", 0],
            [ByteOrder::LITTLE_ENDIAN, "\x01\x00", 1],
            [ByteOrder::LITTLE_ENDIAN, "\xff\x00", 255],
            [ByteOrder::LITTLE_ENDIAN, "\xfe\xff", 65534],
            [ByteOrder::LITTLE_ENDIAN, "\xff\xff", 65535]
        ];

        if (ByteOrder::LITTLE_ENDIAN === ByteOrderHelper::getMachineByteOrder()) {
            return array_merge($values, [
                [ByteOrder::MACHINE_ENDIAN, "\x00\x00", 0],
                [ByteOrder::MACHINE_ENDIAN, "\x01\x00", 1],
                [ByteOrder::MACHINE_ENDIAN, "\xff\x00", 255],
                [ByteOrder::MACHINE_ENDIAN, "\xfe\xff", 65534],
                [ByteOrder::MACHINE_ENDIAN, "\xff\xff", 65535]
            ]);
        }

        return array_merge($values, [
            [ByteOrder::MACHINE_ENDIAN, "\x00\x00", 0],
            [ByteOrder::MACHINE_ENDIAN, "\x00\x01", 1],
            [ByteOrder::MACHINE_ENDIAN, "\x00\xff", 255],
            [ByteOrder::MACHINE_ENDIAN, "\xff\xfe", 65534],
            [ByteOrder::MACHINE_ENDIAN, "\xff\xff", 65535]
        ]);
    }

    /**
     * Provide signed 24-bit integers
     *
     * @return array
     */
    public function provideInt24Values()
    {
        $values = [
            [ByteOrder::BIG_ENDIAN, "\x80\x00\x00", -8388608],
            [ByteOrder::BIG_ENDIAN, "\x00\x00\x00", 0],
            [ByteOrder::BIG_ENDIAN, "\x7f\xff\xff", 8388607],
            [ByteOrder::LITTLE_ENDIAN, "\x00\x00\x80", -8388608],
            [ByteOrder::LITTLE_ENDIAN, "\x00\x00\x00", 0],
            [ByteOrder::LITTLE_ENDIAN, "\xff\xff\x7f", 8388607]
        ];

        if (ByteOrder::LITTLE_ENDIAN === ByteOrderHelper::getMachineByteOrder()) {
            return array_merge($values, [
                [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x80", -8388608],
                [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00", 0],
                [ByteOrder::MACHINE_ENDIAN, "\xff\xff\x7f", 8388607]
            ]);
        }

        return array_merge($values, [
            [ByteOrder::MACHINE_ENDIAN, "\x80\x00\x00", -8388608],
            [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00", 0],
            [ByteOrder::MACHINE_ENDIAN, "\x7f\xff\xff", 8388607]
        ]);
    }

    /**
     * Provide unsigned 24-bit integers
     *
     * @return array
     */
    public function provideUInt24Values()
    {
        $values = [
            [ByteOrder::BIG_ENDIAN, "\x00\x00\x00", 0],
            [ByteOrder::BIG_ENDIAN, "\x00\x00\x01", 1],
            [ByteOrder::BIG_ENDIAN, "\x00\x00\xff", 255],
            [ByteOrder::BIG_ENDIAN, "\xff\xff\xff", 16777215],
            [ByteOrder::LITTLE_ENDIAN, "\x00\x00\x00", 0],
            [ByteOrder::LITTLE_ENDIAN, "\x01\x00\x00", 1],
            [ByteOrder::LITTLE_ENDIAN, "\xff\x00\x00", 255],
            [ByteOrder::LITTLE_ENDIAN, "\xff\xff\xff", 16777215]
        ];

        if (ByteOrder::LITTLE_ENDIAN === ByteOrderHelper::getMachineByteOrder()) {
            return array_merge($values, [
                [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00", 0],
                [ByteOrder::MACHINE_ENDIAN, "\x01\x00\x00", 1],
                [ByteOrder::MACHINE_ENDIAN, "\xff\x00\x00", 255],
                [ByteOrder::MACHINE_ENDIAN, "\xff\xff\xff", 16777215]
            ]);
        }

        return array_merge($values, [
            [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00", 0],
            [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x01", 1],
            [ByteOrder::MACHINE_ENDIAN, "\x00\x00\xff", 255],
            [ByteOrder::MACHINE_ENDIAN, "\xff\xff\xff", 16777215]
        ]);
    }

    /**
     * Provide signed 32-bit integers
     *
     * @return array
     */
    public function provideInt32Values()
    {
        $values = [
            [ByteOrder::BIG_ENDIAN, "\x80\x00\x00\x00", -2147483648],
            [ByteOrder::BIG_ENDIAN, "\x00\x00\x00\x00", 0],
            [ByteOrder::BIG_ENDIAN, "\x7f\xff\xff\xff", 2147483647],
            [ByteOrder::LITTLE_ENDIAN, "\x00\x00\x00\x80", -2147483648],
            [ByteOrder::LITTLE_ENDIAN, "\x00\x00\x00\x00", 0],
            [ByteOrder::LITTLE_ENDIAN, "\xff\xff\xff\x7f", 2147483647]
        ];

        if (ByteOrder::LITTLE_ENDIAN === ByteOrderHelper::getMachineByteOrder()) {
            return array_merge($values, [
                [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00\x80", -2147483648],
                [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00\x00", 0],
                [ByteOrder::MACHINE_ENDIAN, "\xff\xff\xff\x7f", 2147483647]
            ]);
        }

        return array_merge($values, [
            [ByteOrder::MACHINE_ENDIAN, "\x80\x00\x00\x00", -2147483648],
            [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00\x00", 0],
            [ByteOrder::MACHINE_ENDIAN, "\x7f\xff\xff\xff", 2147483647]
        ]);
    }

    /**
     * Provide unsigned 32-bit integers
     *
     * @return array
     */
    public function provideUInt32Values()
    {
        $values = [
            [ByteOrder::BIG_ENDIAN, "\x00\x00\x00\x00", 0],
            [ByteOrder::BIG_ENDIAN, "\x00\x00\x00\x01", 1],
            [ByteOrder::BIG_ENDIAN, "\x00\x00\x00\xff", 255],
            [ByteOrder::BIG_ENDIAN, "\xff\xff\xff\xff", 4294967295],
            [ByteOrder::LITTLE_ENDIAN, "\x00\x00\x00\x00", 0],
            [ByteOrder::LITTLE_ENDIAN, "\x01\x00\x00\x00", 1],
            [ByteOrder::LITTLE_ENDIAN, "\xff\x00\x00\x00", 255],
            [ByteOrder::LITTLE_ENDIAN, "\xff\xff\xff\xff", 4294967295]
        ];

        if (ByteOrder::LITTLE_ENDIAN === ByteOrderHelper::getMachineByteOrder()) {
            return array_merge($values, [
                [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00\x00", 0],
                [ByteOrder::MACHINE_ENDIAN, "\x01\x00\x00\x00", 1],
                [ByteOrder::MACHINE_ENDIAN, "\xff\x00\x00\x00", 255],
                [ByteOrder::MACHINE_ENDIAN, "\xff\xff\xff\xff", 4294967295]
            ]);
        }

        return array_merge($values, [
            [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00\x00", 0],
            [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00\x01", 1],
            [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00\xff", 255],
            [ByteOrder::MACHINE_ENDIAN, "\xff\xff\xff\xff", 4294967295]
        ]);
    }

    /**
     * Provide signed 64-bit integers
     *
     * @return array
     */
    public function provideInt64Values()
    {
        $values = [
            [ByteOrder::BIG_ENDIAN, "\x80\x00\x00\x00\x00\x00\x00\x01", -9223372036854775807],
            [ByteOrder::BIG_ENDIAN, "\x00\x00\x00\x00\x00\x00\x00\x00", 0],
            [ByteOrder::BIG_ENDIAN, "\x7f\xff\xff\xff\xff\xff\xff\xff", 9223372036854775807],
            [ByteOrder::LITTLE_ENDIAN, "\x01\x00\x00\x00\x00\x00\x00\x80", -9223372036854775807],
            [ByteOrder::LITTLE_ENDIAN, "\x00\x00\x00\x00\x00\x00\x00\x00", 0],
            [ByteOrder::LITTLE_ENDIAN, "\xff\xff\xff\xff\xff\xff\xff\x7f", 9223372036854775807]
        ];

        if (ByteOrder::LITTLE_ENDIAN === ByteOrderHelper::getMachineByteOrder()) {
            return array_merge($values, [
                [ByteOrder::MACHINE_ENDIAN, "\x01\x00\x00\x00\x00\x00\x00\x80", -9223372036854775807],
                [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00\x00\x00\x00\x00\x00", 0],
                [ByteOrder::MACHINE_ENDIAN, "\xff\xff\xff\xff\xff\xff\xff\x7f", 9223372036854775807]
            ]);
        }

        return array_merge($values, [
            [ByteOrder::MACHINE_ENDIAN, "\x80\x00\x00\x00\x00\x00\x00\x01", -9223372036854775807],
            [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00\x00\x00\x00\x00\x00", 0],
            [ByteOrder::MACHINE_ENDIAN, "\x7f\xff\xff\xff\xff\xff\xff\xff", 9223372036854775807]
        ]);
    }

    /**
     * Provide unsigned 64-bit integers
     *
     * @return array
     */
    public function provideUInt64Values()
    {
        $values = [
            [ByteOrder::BIG_ENDIAN, "\x00\x00\x00\x00\x00\x00\x00\x00", 0],
            [ByteOrder::BIG_ENDIAN, "\x00\x00\x00\x00\x00\x00\x00\x01", 1],
            [ByteOrder::BIG_ENDIAN, "\x00\x00\x00\x00\x00\x00\x00\xff", 255],
            [ByteOrder::BIG_ENDIAN, "\x7f\xff\xff\xff\xff\xff\xff\xff", 9223372036854775807],
            [ByteOrder::LITTLE_ENDIAN, "\x00\x00\x00\x00\x00\x00\x00\x00", 0],
            [ByteOrder::LITTLE_ENDIAN, "\x01\x00\x00\x00\x00\x00\x00\x00", 1],
            [ByteOrder::LITTLE_ENDIAN, "\xff\x00\x00\x00\x00\x00\x00\x00", 255],
            [ByteOrder::LITTLE_ENDIAN, "\xff\xff\xff\xff\xff\xff\xff\x7f", 9223372036854775807]
        ];

        if (ByteOrder::LITTLE_ENDIAN === ByteOrderHelper::getMachineByteOrder()) {
            return array_merge($values, [
                [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00\x00\x00\x00\x00\x00", 0],
                [ByteOrder::MACHINE_ENDIAN, "\x01\x00\x00\x00\x00\x00\x00\x00", 1],
                [ByteOrder::MACHINE_ENDIAN, "\xff\x00\x00\x00\x00\x00\x00\x00", 255],
                [ByteOrder::MACHINE_ENDIAN, "\xff\xff\xff\xff\xff\xff\xff\x7f", 9223372036854775807]
            ]);
        }

        return array_merge($values, [
            [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00\x00\x00\x00\x00\x00", 0],
            [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00\x00\x00\x00\x00\x01", 1],
            [ByteOrder::MACHINE_ENDIAN, "\x00\x00\x00\x00\x00\x00\x00\xff", 255],
            [ByteOrder::MACHINE_ENDIAN, "\x7f\xff\xff\xff\xff\xff\xff\xff", 9223372036854775807]
        ]);
    }

    /**
     * Test that the stream creation throws an exception on invalid resource argument
     *
     * @expectedException        \GravityMedia\Stream\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid resource
     */
    public function testStreamCreationThrowsExceptionOnInvalidResourceArgument()
    {
        Stream::fromResource(null);
    }

    /**
     * Test that the an exception is thrown when trying to bind an invalid resources
     *
     * @expectedException        \GravityMedia\Stream\Exception\InvalidArgumentException
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
     * Test that the stream returns the default byte order
     */
    public function testStreamReturnsDefaultByteOrder()
    {
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);

        $this->assertSame(ByteOrder::MACHINE_ENDIAN, $stream->getByteOrder());
    }

    /**
     * Test that setting an invalid byte order throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid byte order
     */
    public function testSettingInvalidByteOrderThrowsExceptions()
    {
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);
        $stream->setByteOrder(null);
    }

    /**
     * Thest that the byte order which was set is being returned
     */
    public function testStreamReturnsByteOrderPreviouslySet()
    {
        $resourceHelper = new ResourceHelper();
        $resource = $resourceHelper->getResource();

        $stream = Stream::fromResource($resource);
        $stream->setByteOrder(ByteOrder::LITTLE_ENDIAN);

        $this->assertSame(ByteOrder::LITTLE_ENDIAN, $stream->getByteOrder());
    }

    /**
     * Test that getting the size from a non-local stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Stream not local
     */
    public function testGettingSizeThrowsExceptionOnNonLocalStream()
    {
        $streamMock = $this->getMockBuilder(Stream::class)
            ->setMethods(['isLocal'])
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
     * @expectedExceptionMessage Invalid stream resource
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
     * @expectedExceptionMessage Invalid stream resource
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
     * @expectedExceptionMessage Invalid stream resource
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
        $streamMock = $this->getMockBuilder(Stream::class)
            ->setMethods(['isSeekable'])
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
     * @expectedExceptionMessage Invalid stream resource
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
     * @expectedExceptionMessage Invalid stream resource
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
        $streamMock = $this->getMockBuilder(Stream::class)
            ->setMethods(['isReadable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(false));

        /** @var \GravityMedia\Stream\Stream $streamMock */
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

        $this->assertSame('contents', $stream->read(8));
    }

    /**
     * Test reading signed 8-bit character
     *
     * @dataProvider provideInt8Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadingInt8($data, $value)
    {
        $streamMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('read')
            ->with(1)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $this->assertSame($value, $streamMock->readInt8());
    }

    /**
     * Test reading unsigned 8-bit character
     *
     * @dataProvider provideUInt8Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadingUInt8($data, $value)
    {
        $streamMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('read')
            ->with(1)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $this->assertSame($value, $streamMock->readUInt8());
    }

    /**
     * Test reading signed 16-bit integer
     *
     * @dataProvider provideInt16Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testReadingInt16($byteOrder, $data, $value)
    {
        $readerMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(2)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Stream $readerMock */
        $this->assertSame($value, $readerMock->readInt16());
    }

    /**
     * Test reading unsigned 16-bit integer
     *
     * @dataProvider provideUInt16Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testReadingUInt16($byteOrder, $data, $value)
    {
        $readerMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(2)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Stream $readerMock */
        $this->assertSame($value, $readerMock->readUInt16());
    }

    /**
     * Test reading signed 24-bit integer
     *
     * @dataProvider provideInt24Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testReadingInt24($byteOrder, $data, $value)
    {
        $readerMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(3)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Stream $readerMock */
        $this->assertSame($value, $readerMock->readInt24());
    }

    /**
     * Test reading unsigned 24-bit integer
     *
     * @dataProvider provideUInt24Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testReadingUInt24($byteOrder, $data, $value)
    {
        $readerMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(3)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Stream $readerMock */
        $this->assertSame($value, $readerMock->readUInt24());
    }

    /**
     * Test reading signed 32-bit integer
     *
     * @dataProvider provideInt32Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testReadingInt32($byteOrder, $data, $value)
    {
        $readerMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(4)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Stream $readerMock */
        $this->assertSame($value, $readerMock->readInt32());
    }

    /**
     * Test reading unsigned 32-bit integer
     *
     * @dataProvider provideUInt32Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testReadingUInt32($byteOrder, $data, $value)
    {
        $readerMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(4)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Stream $readerMock */
        $this->assertSame($value, $readerMock->readUInt32());
    }

    /**
     * Test reading signed 64-bit integer
     *
     * @dataProvider provideInt64Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testReadingInt64($byteOrder, $data, $value)
    {
        $readerMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(8)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Stream $readerMock */
        $this->assertSame($value, $readerMock->readInt64());
    }

    /**
     * Test reading unsigned 64-bit integer
     *
     * @dataProvider provideUInt64Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testReadingUInt64($byteOrder, $data, $value)
    {
        $readerMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(8)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Stream $readerMock */
        $this->assertSame($value, $readerMock->readUInt64());
    }

    /**
     * Test that writing data to a closed stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid stream resource
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
        $streamMock = $this->getMockBuilder(Stream::class)
            ->setMethods(['isWritable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(false));

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
     * Test writing signed 8-bit character
     *
     * @dataProvider provideInt8Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWritingInt8($data, $value)
    {
        $streamMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['write'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(1));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $this->assertSame(1, $streamMock->writeInt8($value));
    }

    /**
     * Test writing unsigned 8-bit character
     *
     * @dataProvider provideUInt8Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWritingUInt8($data, $value)
    {
        $streamMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['write'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(1));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $this->assertSame(1, $streamMock->writeUInt8($value));
    }

    /**
     * Test writing signed 16-bit integer
     *
     * @dataProvider provideInt16Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testWritingInt16($byteOrder, $data, $value)
    {
        $streamMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $streamMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $streamMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(2));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $this->assertSame(2, $streamMock->writeInt16($value));
    }

    /**
     * Test writing unsigned 16-bit integer
     *
     * @dataProvider provideUInt16Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testWritingUInt16($byteOrder, $data, $value)
    {
        $streamMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $streamMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $streamMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(2));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $this->assertSame(2, $streamMock->writeUInt16($value));
    }

    /**
     * Test writing signed 24-bit integer
     *
     * @dataProvider provideInt24Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testWritingInt24($byteOrder, $data, $value)
    {
        $streamMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $streamMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $streamMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(3));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $this->assertSame(3, $streamMock->writeInt24($value));
    }

    /**
     * Test writing unsigned 24-bit integer
     *
     * @dataProvider provideUInt24Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testWritingUInt24($byteOrder, $data, $value)
    {
        $streamMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $streamMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $streamMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(3));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $this->assertSame(3, $streamMock->writeUInt24($value));
    }

    /**
     * Test writing signed 32-bit integer
     *
     * @dataProvider provideInt32Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testWritingInt32($byteOrder, $data, $value)
    {
        $streamMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $streamMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $streamMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(4));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $this->assertSame(4, $streamMock->writeInt32($value));
    }

    /**
     * Test writing unsigned 32-bit integer
     *
     * @dataProvider provideUInt32Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testWritingUInt32($byteOrder, $data, $value)
    {
        $streamMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $streamMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $streamMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(4));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $this->assertSame(4, $streamMock->writeUInt32($value));
    }

    /**
     * Test writing signed 64-bit integer
     *
     * @dataProvider provideInt64Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testWritingInt64($byteOrder, $data, $value)
    {
        $streamMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $streamMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $streamMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(8));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $this->assertSame(8, $streamMock->writeInt64($value));
    }

    /**
     * Test writing unsigned 64-bit integer
     *
     * @dataProvider provideUInt64Values()
     *
     * @param int    $byteOrder
     * @param string $data
     * @param int    $value
     */
    public function testWritingUInt64($byteOrder, $data, $value)
    {
        $streamMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $streamMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue($byteOrder));

        $streamMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(8));

        /** @var \GravityMedia\Stream\Stream $streamMock */
        $this->assertSame(8, $streamMock->writeUInt64($value));
    }

    /**
     * Test that truncating a closed stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid stream resource
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
        $streamMock = $this->getMockBuilder(Stream::class)
            ->setMethods(['isWritable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(false));

        /** @var \GravityMedia\Stream\Stream $streamMock */
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
     * Test that closing the stream closes the stream resource
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
