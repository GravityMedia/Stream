<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest\Helper;

use GravityMedia\Stream\ByteOrder;

/**
 * Byte order helper class
 *
 * @package GravityMedia\StreamTest\Helper
 */
class ByteOrderHelper
{
    /**
     * @var int
     */
    protected static $machineByteOrder;

    /**
     * Get machine byte order
     *
     * @return int
     */
    public static function getMachineByteOrder()
    {
        if (null === static::$machineByteOrder) {
            static::$machineByteOrder = ByteOrder::BIG_ENDIAN;

            list(, $value) = unpack('s', "\x01\x00");
            if (1 === $value) {
                static::$machineByteOrder = ByteOrder::LITTLE_ENDIAN;
            }
        }

        return static::$machineByteOrder;
    }
}
