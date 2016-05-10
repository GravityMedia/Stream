<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

/**
 * Byte order enum
 *
 * @package GravityMedia\Stream
 */
class ByteOrder
{
    /**
     * Machine endian
     */
    const MACHINE_ENDIAN = 0;

    /**
     * Big endian
     */
    const BIG_ENDIAN = 1;

    /**
     * Little endian
     */
    const LITTLE_ENDIAN = 2;

    /**
     * Valid values
     *
     * @var int[]
     */
    protected static $values = [
        self::MACHINE_ENDIAN,
        self::BIG_ENDIAN,
        self::LITTLE_ENDIAN
    ];

    /**
     * Return valid values
     *
     * @return int[]
     */
    public static function values()
    {
        return static::$values;
    }
}
