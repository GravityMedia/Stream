<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest;

use GravityMedia\Stream\StreamWriter;

/**
 * Stream writer test
 *
 * @package GravityMedia\Stream
 * @covers  GravityMedia\Stream\StreamWriter
 */
class StreamWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the constructor throws an exception for non-writable stream argument
     *
     * @uses GravityMedia\Stream\Stream::__destruct
     *
     * @expectedException        \GravityMedia\Stream\Exception\InvalidArgumentException
     * @expectedExceptionMessage Stream is not writable
     */
    public function testConstructorThrowsExceptionForNonWritableStreamArgument()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->disableOriginalConstructor()
            ->setMethods(array('isWritable'))
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(false));

        new StreamWriter($streamMock);
    }

    /**
     * Test that the contents can be written and the length is returned
     *
     * @uses GravityMedia\Stream\Stream::__destruct
     */
    public function testWritingContentsReturnContentLength()
    {
        $contents = 'contents';
        $resource = fopen('php://temp', 'r+b');

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

        $writer = new StreamWriter($streamMock);

        $this->assertEquals(8, $writer->write($contents));

        fclose($resource);
    }

    /**
     * Test that the stream can be truncated
     *
     * @uses GravityMedia\Stream\Stream::__destruct
     */
    public function testTruncateStream()
    {
        $resource = fopen('php://temp', 'r+b');

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

        $writer = new StreamWriter($streamMock);
        $writer->truncate(8);

        $this->assertEquals(str_repeat("\x00", 8), stream_get_contents($resource));

        fclose($resource);
    }
}
