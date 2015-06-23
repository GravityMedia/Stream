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
 * @package GravityMedia\Stream
 * @covers  GravityMedia\Stream\StreamReader
 */
class StreamReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the constructor throws an exception for non-readable stream argument
     *
     * @uses GravityMedia\Stream\Stream::__destruct
     *
     * @expectedException        \GravityMedia\Stream\Exception\InvalidArgumentException
     * @expectedExceptionMessage Stream is not readable
     */
    public function testConstructorThrowsExceptionForNonReadableStreamArgument()
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
     * Test that the contents returned are equal to those which were previously written
     *
     * @uses GravityMedia\Stream\Stream::__destruct
     */
    public function testReadContents()
    {
        $contents = 'contents';
        $resource = fopen('php://temp', 'r+b');
        fwrite($resource, $contents);
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

        $reader = new StreamReader($streamMock);

        $this->assertEquals($contents, $reader->read(8));
    }
}
