<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest\Reader;

use GravityMedia\Stream\Reader\StringReader;

/**
 * String reader test
 *
 * @package GravityMedia\StreamTest\Reader
 *
 * @covers  GravityMedia\Stream\Reader\StringReader
 */
class StringReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that setting a non-readable stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\InvalidArgumentException
     * @expectedExceptionMessage Stream not readable
     */
    public function testCreatingReaderThrowsExceptionOnNonReadableStream()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['isReadable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(false));

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        new StringReader($streamMock, 1);
    }

    /**
     * Test that the stream equals the one which was previously set
     */
    public function testCreatingReader()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['isReadable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(true));

        $length = 1;

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        $reader = new StringReader($streamMock, $length);

        $this->assertEquals($streamMock, $reader->getStream());
        $this->assertEquals($length, $reader->getLength());
    }

    /**
     * Test reading string data
     */
    public function testReading()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['isReadable', 'read'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('read')
            ->with(8)
            ->will($this->returnValue('contents'));

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        $reader = new StringReader($streamMock, 8);

        $this->assertEquals('contents', $reader->read());
    }
}
