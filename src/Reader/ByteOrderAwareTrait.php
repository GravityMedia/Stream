<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream\Reader;

use GravityMedia\Stream\Enum\ByteOrder;
use GravityMedia\Stream\Exception;

/**
 * Short (16-bit integer) reader
 *
 * @package GravityMedia\Stream\Reader
 */
trait ByteOrderAwareTrait
{
    /**
     * @var int
     */
    protected $byteOrder;

    /**
     * @var int
     */
    protected static $machineByteOrder;

    /**
     * Get byte order
     *
     * @return int
     */
    public function getByteOrder()
    {
        if (null === $this->byteOrder) {
            return ByteOrder::MACHINE_ENDIAN;
        }

        return $this->byteOrder;
    }

    /**
     * Set byteOrder
     *
     * @param int $byteOrder
     *
     * @throws Exception\InvalidArgumentException An exception will be thrown for invalid byte order arguments
     *
     * @return $this
     */
    public function setByteOrder($byteOrder)
    {
        if (!in_array($byteOrder, ByteOrder::values())) {
            throw new Exception\InvalidArgumentException('Invalid byte order');
        }

        $this->byteOrder = $byteOrder;
        return $this;
    }

    /**
     * Get machine byte order
     *
     * @return int
     */
    public function getMachineByteOrder()
    {
        if (null === static::$machineByteOrder) {
            static::$machineByteOrder = ByteOrder::BIG_ENDIAN;

            list(, $value) = unpack('l*', "\x01\x00\x00\x00");
            if (1 === $value) {
                static::$machineByteOrder = ByteOrder::LITTLE_ENDIAN;
            }
        }

        return static::$machineByteOrder;
    }
}
