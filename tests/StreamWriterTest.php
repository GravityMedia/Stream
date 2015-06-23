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
 * @package GravityMedia\StreamTest
 * @covers  GravityMedia\Stream\StreamWriter
 */
class StreamWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the constructor throws an exception on non-writable stream argument
     *
     * @uses GravityMedia\Stream\Stream::__destruct
     *
     * @expectedException        \GravityMedia\Stream\Exception\InvalidArgumentException
     * @expectedExceptionMessage Stream is not writable
     */
    public function testConstructorThrowsExceptionOnNonWritableStreamArgument()
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
     * Test that writing contents to a closed stream throws an exception
     *
     * @uses GravityMedia\Stream\Stream::__destruct
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Unexpected result of operation
     */
    public function testWritingContentsThrowsExceptionOnClosedStream()
    {
        $resource = fopen('php://temp', 'r+b');
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

        $writer = new StreamWriter($streamMock);

        $writer->write('contents');
    }

    /**
     * Test that the contents can be written and the length is returned
     *
     * @uses GravityMedia\Stream\Stream::__destruct
     */
    public function testWritingContentsReturnsContentLength()
    {
        $contents = 'contents';
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

        $writer = new StreamWriter($streamMock);

        $this->assertEquals(8, $writer->write($contents));

        fclose($resource);
    }

    /**
     * Test that truncating a closed stream throws an exception
     *
     * @uses GravityMedia\Stream\Stream::__destruct
     *
     * @expectedException        \GravityMedia\Stream\Exception\IOException
     * @expectedExceptionMessage Unexpected result of operation
     */
    public function testTruncatingThrowsExceptionOnClosedStream()
    {
        $resource = fopen('php://temp', 'r+b');
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

        $writer = new StreamWriter($streamMock);

        $writer->truncate(8);
    }

    /**
     * Test that the stream can be truncated
     *
     * @uses GravityMedia\Stream\Stream::__destruct
     */
    public function testTruncateStream()
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

        $writer = new StreamWriter($streamMock);
        $writer->truncate(8);

        $this->assertEquals(str_repeat("\x00", 8), stream_get_contents($resource));

        fclose($resource);
    }
}
