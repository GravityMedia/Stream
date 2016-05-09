<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest\Writer;

use GravityMedia\Stream\Writer\Writer;

/**
 * Stream writer test
 *
 * @package GravityMedia\StreamTest\Writer
 *
 * @covers  GravityMedia\Stream\Writer\Writer
 */
class WriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that creating a writer with a non-writable stream throws an exception
     *
     * @expectedException        \GravityMedia\Stream\Exception\InvalidArgumentException
     * @expectedExceptionMessage Stream not writable
     */
    public function testCreatingWriterThrowsExceptionOnNonWritableStream()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['isWritable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(false));

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        new Writer($streamMock);
    }

    /**
     * Test that the writer returns the stream which was provided as constructor argument
     */
    public function testWriterReturnsStreamProvidedByConstructor()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['isWritable'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(true));

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        $writer = new Writer($streamMock);

        $this->assertSame($streamMock, $writer->getStream());
    }

    /**
     * Test that the writer delegates the write method to the stream
     */
    public function testWriterDelegatesWritingToStream()
    {
        $streamMock = $this->getMockBuilder('GravityMedia\Stream\Stream')
            ->setMethods(['isWritable', 'write'])
            ->getMock();

        $streamMock->expects($this->once())
            ->method('isWritable')
            ->will($this->returnValue(true));

        $streamMock->expects($this->once())
            ->method('write')
            ->with('contents')
            ->will($this->returnValue(8));

        /** @var \GravityMedia\Stream\StreamInterface $streamMock */
        $writer = new Writer($streamMock);

        $this->assertSame(8, $writer->write('contents'));
    }
}
