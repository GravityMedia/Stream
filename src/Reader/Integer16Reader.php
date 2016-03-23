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
 * 16-bit integer (short) reader
 *
 * @package GravityMedia\Stream\Reader
 */
class Integer16Reader extends AbstractIntegerReader
{
    /**
     * Use byte order aware trait
     */
    use ByteOrderAwareTrait;

    /**
     * Read unsigned 16-bit integer (short) data from the stream
     *
     * @return int
     */
    protected function readUnsigned()
    {
        switch ($this->getByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $format = 'n*';
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $format = 'v*';
                break;
            default:
                $format = 'S*';
        }

        list(, $value) = unpack($format, $this->getStream()->read(2));
        return $value;
    }

    /**
     * Read signed 16-bit integer (short) data from the stream
     *
     * @return int
     */
    protected function readSigned()
    {
        $data = $this->getStream()->read(2);

        if (ByteOrder::MACHINE_ENDIAN !== $this->getByteOrder()
            && $this->getMachineByteOrder() !== $this->getByteOrder()
        ) {
            $data = strrev($data);
        }

        list(, $value) = unpack('s*', $data);
        return $value;
    }
}
