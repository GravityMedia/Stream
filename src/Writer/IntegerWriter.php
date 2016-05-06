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
     * @param string $data The data
     *
     * @return int
     */
    public function writeUnsignedInteger8($data)
    {
        return $this->write(pack('C', $data));
    }

    /**
     * Write signed 8-bit integer (char) data to the stream
     *
     * @param string $data The data
     *
     * @return int
     */
    public function writeSignedInteger8($data)
    {
        return $this->write(pack('c', $data));
    }

    /**
     * Write unsigned 16-bit integer (short) data to the stream
     *
     * @param string $data The data
     *
     * @return int
     */
    public function writeUnsignedInteger16($data)
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

        return $this->write(pack($format, $data));
    }

    /**
     * Write signed 16-bit integer (short) data to the stream
     *
     * @param string $data The data
     *
     * @return int
     */
    public function writeSignedInteger16($data)
    {
        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        return $this->write(pack('s', $data));
    }

    /**
     * Write unsigned 32-bit integer (long) data to the stream
     *
     * @param string $data The data
     *
     * @return int
     */
    public function writeUnsignedInteger32($data)
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

        return $this->write(pack($format, $data));
    }

    /**
     * Write signed 32-bit integer (long) data to the stream
     *
     * @param string $data The data
     *
     * @return int
     */
    public function writeSignedInteger32($data)
    {
        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        return $this->write(pack('l', $data));
    }

    /**
     * Write unsigned 64-bit integer (long long) data to the stream
     *
     * @param string $data The data
     *
     * @return int
     */
    public function writeUnsignedInteger64($data)
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

        return $this->write(pack($format, $data));
    }

    /**
     * Write signed 64-bit integer (long long) data to the stream
     *
     * @param string $data The data
     *
     * @return int
     */
    public function writeSignedInteger64($data)
    {
        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        return $this->write(pack('q', $data));
    }
}
