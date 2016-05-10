<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest;

use GravityMedia\Stream\ByteOrder;

/**
 * Stream test
 *
 * @package GravityMedia\StreamTest
 *
 * @covers  GravityMedia\Stream\ByteOrder
 */
class ByteOrderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the byte order enum returns all possible values
     */
    public function testByteOrderValues()
    {
        $this->assertSame(
            [ByteOrder::MACHINE_ENDIAN, ByteOrder::BIG_ENDIAN, ByteOrder::LITTLE_ENDIAN],
            ByteOrder::values()
        );
    }
}
