<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream\Reader;

use GravityMedia\Stream\ByteOrder\ByteOrder;
use GravityMedia\Stream\ByteOrder\ByteOrderAwareTrait;

/**
 * Integer reader
 *
 * @package GravityMedia\Stream\Reader
 */
class IntegerReader extends Reader
{
    /**
     * Use byte order aware trait
     */
    use ByteOrderAwareTrait;

    /**
     * Read unsigned 8-bit integer (char) data from the stream
     *
     * @return int
     */
    public function readUnsignedInteger8()
    {
        list(, $value) = unpack('C', $this->read(1));
        return $value;
    }

    /**
     * Read signed 8-bit integer (char) data from the stream
     *
     * @return int
     */
    public function readSignedInteger8()
    {
        list(, $value) = unpack('c', $this->read(1));
        return $value;
    }

    /**
     * Read unsigned 16-bit integer (short) data from the stream
     *
     * @return int
     */
    public function readUnsignedInteger16()
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

        list(, $value) = unpack($format, $this->read(2));
        return $value;
    }

    /**
     * Read signed 16-bit integer (short) data from the stream
     *
     * @return int
     */
    public function readSignedInteger16()
    {
        $data = $this->read(2);

        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        list(, $value) = unpack('s', $data);
        return $value;
    }

    /**
     * Read unsigned 24-bit integer (short) data from the stream
     *
     * @return int
     */
    public function readUnsignedInteger24()
    {
        $data = $this->read(3);

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

        switch ($this->getMachineByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $data = $data . "\x00";
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $data = "\x00" . $data;
                break;
        }

        list(, $value) = unpack($format, $data);
        return $value;
    }

    /**
     * Read signed 24-bit integer (short) data from the stream
     *
     * @return int
     */
    public function readSignedInteger24()
    {
        $data = $this->read(3);

        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        switch ($this->getMachineByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $data = $data . "\x00";
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $data = "\x00" . $data;
                break;
        }

        list(, $value) = unpack('l', $data);
        return $value;
    }

    /**
     * Read unsigned 32-bit integer (long) data from the stream
     *
     * @return int
     */
    public function readUnsignedInteger32()
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

        list(, $value) = unpack($format, $this->read(4));
        return $value;
    }

    /**
     * Read signed 32-bit integer (long) data from the stream
     *
     * @return int
     */
    public function readSignedInteger32()
    {
        $data = $this->read(4);

        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        list(, $value) = unpack('l', $data);
        return $value;
    }

    /**
     * Read unsigned 64-bit integer (long long) data from the stream
     *
     * @return int
     */
    public function readUnsignedInteger64()
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

        list(, $value) = unpack($format, $this->read(8));
        return $value;
    }

    /**
     * Read signed 64-bit integer (long long) data from the stream
     *
     * @return int
     */
    public function readSignedInteger64()
    {
        $data = $this->read(8);

        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        list(, $value) = unpack('q', $data);
        return $value;
    }
}
