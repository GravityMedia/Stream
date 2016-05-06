<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest\Reader;

use GravityMedia\Stream\Reader\Reader;

/**
 * Stream reader test
 *
 * @package GravityMedia\StreamTest\Reader
 *
 * @covers  GravityMedia\Stream\Reader\Reader
 */
class ReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that creating a reader with a non-readable stream throws an exception
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
        new Reader($streamMock);
    }

    /**
     * Test that the reader returns the stream which was provided as constructor argument
     */
    public function testReaderReturnsStreamProvidedByConstructor()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['isReadable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isReadable')
            ->will($this->returnValue(true));

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        $reader = new Reader($streamMock);

        $this->assertSame($streamMock, $reader->getStream());
    }

    /**
     * Test that the reader delegates the read method to the stream
     */
    public function testReaderDelegatesReadingToStream()
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
        $reader = new Reader($streamMock);

        $this->assertSame('contents', $reader->read(8));
    }
}
