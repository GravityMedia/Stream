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
     * Test that getting a stream from the reader with no stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Stream not set
     */
    public function testGettingStreamThrowsExceptionOnMissingStream()
    {
        $reader = new StringReader();

        $reader->getStream();
    }

    /**
     * Test that setting a non-readable stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Stream not readable
     */
    public function testSettingStreamThrowsExceptionOnNonReadableStream()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['isReadable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(false));

        $reader = new StringReader();

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        $reader->setStream($streamMock);
    }

    /**
     * Test that the stream equals the one which was previously set
     */
    public function testSettingStream()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['isReadable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(true));

        $reader = new StringReader();

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        $this->assertEquals($reader, $reader->setStream($streamMock));
        $this->assertEquals($streamMock, $reader->getStream());
    }

    /**
     * Test that getting the length from the reader with no length throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\BadMethodCallException
     * @expectedExceptionMessage Length not set
     */
    public function testGettingLengthThrowsExceptionOnMissingLength()
    {
        $reader = new StringReader();

        $reader->getLength();
    }

    /**
     * Test that the length equals the one which was previously set
     */
    public function testSettingLength()
    {
        $reader = new StringReader();

        $this->assertEquals($reader, $reader->setLength(8));
        $this->assertEquals(8, $reader->getLength());
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

        $reader = new StringReader();

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        $reader->setStream($streamMock);
        $reader->setLength(8);

        $this->assertEquals('contents', $reader->read());
    }
}
