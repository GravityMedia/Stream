<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest;

use GravityMedia\Stream\StreamReader;

/**
 * Stream reader test
 *
 * @package GravityMedia\StreamTest
 * @covers  GravityMedia\Stream\StreamReader
 */
class StreamReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the constructor throws an exception on non-readable stream argument
     *
     * @uses GravityMedia\Stream\Stream
     *
     * @expectedException        \GravityMedia\Stream\Exception\InvalidArgumentException
     * @expectedExceptionMessage Stream is not readable
     */
    public function testConstructorThrowsExceptionOnNonReadableStreamArgument()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isReadable'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(false));

        new StreamReader($streamMock);
    }

    /**
     * Test that reading data from a closed stream throws an exception
     *
     * @uses GravityMedia\Stream\Stream
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Invalid stream resource
     */
    public function testReadingDataThrowsExceptionOnClosedStream()
    {
        $resource = fopen('php://temp', 'r');
        fclose($resource);

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isReadable', 'isAccessible', 'getResource'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('isAccessible')
            ->will($this->returnValue(false));

        $reader = new StreamReader($streamMock);

        $reader->read();
    }

    /**
     * Test that reading data from an empty stream throws an exception
     *
     * @uses GravityMedia\Stream\Stream
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Unexpected result of operation
     */
    public function testReadingDataThrowsExceptionOnInvalidLength()
    {
        $resource = fopen('php://input', 'r');

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isReadable', 'isAccessible', 'getResource'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('isAccessible')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

        $reader = new StreamReader($streamMock);

        $reader->read(0);
    }

    /**
     * Test that the data can be read
     *
     * @uses GravityMedia\Stream\Stream
     */
    public function testReadingData()
    {
        $data = 'contents';
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, $data);
        fseek($resource, 0);

        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isReadable', 'isAccessible', 'getResource'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('isAccessible')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));

        $reader = new StreamReader($streamMock);

        $this->assertEquals($data, $reader->read(8));
    }
}
