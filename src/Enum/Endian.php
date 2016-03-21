<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream\Enum;

/**
 * Endian enum
 *
 * @package GravityMedia\Stream\Enum
 */
class Endian
{
    /**
     * Big endian
     */
    const ENDIAN_BIG = 1;

    /**
     * Little endian
     */
    const ENDIAN_LITTLE = 2;

    /**
     * Valid values
     *
     * @var int[]
     */
    protected static $values = [
        self::ENDIAN_BIG,
        self::ENDIAN_LITTLE
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
