<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream\Writer;

use GravityMedia\Stream\ByteOrder\ByteOrder;
use GravityMedia\Stream\ByteOrder\ByteOrderAwareTrait;

/**
 * Integer writer
 *
 * @package GravityMedia\Stream\Writer
 */
class IntegerWriter extends Writer
{
    /**
     * Use byte order aware trait
     */
    use ByteOrderAwareTrait;

    /**
     * Write unsigned 8-bit integer (char) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeUnsignedInteger8($value)
    {
        return $this->write(pack('C', $value));
    }

    /**
     * Write signed 8-bit integer (char) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeSignedInteger8($value)
    {
        return $this->write(pack('c', $value));
    }

    /**
     * Write unsigned 16-bit integer (short) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeUnsignedInteger16($value)
    {
        switch ($this->getByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $format = 'n';
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $format = 'v';
                break;
            default:
                $format = 'S';
        }

        return $this->write(pack($format, $value));
    }

    /**
     * Write signed 16-bit integer (short) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeSignedInteger16($value)
    {
        $data = pack('s', $value);

        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        return $this->write($data);
    }

    /**
     * Write unsigned 24-bit integer (short) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeUnsignedInteger24($value)
    {
        $data = pack('C3', $value, $value >> 8, $value >> 16);

        $byteOrder = $this->getByteOrder();
        if ($byteOrder === ByteOrder::MACHINE_ENDIAN) {
            $byteOrder = $this->getMachineByteOrder();
        }

        if ($byteOrder !== $this->getMachineByteOrder()) {
            $data = strrev($data);
        }

        return $this->write($data);
    }

    /**
     * Write signed 24-bit integer (short) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeSignedInteger24($value)
    {
        if ($value & 0x7fffff) {
            $value += 2 ** 24;
        }

        return $this->writeUnsignedInteger24($value);
    }

    /**
     * Write unsigned 32-bit integer (long) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeUnsignedInteger32($value)
    {
        switch ($this->getByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $format = 'N';
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $format = 'V';
                break;
            default:
                $format = 'L';
        }

        return $this->write(pack($format, $value));
    }

    /**
     * Write signed 32-bit integer (long) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeSignedInteger32($value)
    {
        $data = pack('l', $value);

        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        return $this->write($data);
    }

    /**
     * Write unsigned 64-bit integer (long long) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeUnsignedInteger64($value)
    {
        switch ($this->getByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $format = 'J';
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $format = 'P';
                break;
            default:
                $format = 'Q';
        }

        return $this->write(pack($format, $value));
    }

    /**
     * Write signed 64-bit integer (long long) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeSignedInteger64($value)
    {
        $data = pack('q', $value);

        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        return $this->write($data);
    }
}
