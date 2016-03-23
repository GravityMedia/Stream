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
 * 24-bit integer (short) reader
 *
 * @package GravityMedia\Stream\Reader
 */
class Integer24Reader extends AbstractIntegerReader
{
    /**
     * Use byte order aware trait
     */
    use ByteOrderAwareTrait;

    /**
     * Read unsigned 24-bit integer (short) data from the stream
     *
     * @return int
     */
    protected function readUnsigned()
    {
        $data = $this->getStream()->read(3);

        switch ($this->getByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $format = 'N*';
                $data = $data . "\x00";
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $format = 'V*';
                $data = "\x00" . $data;
                break;
            default:
                $format = 'L*';
                $data = $data . "\x00";
        }

        list(, $value) = unpack($format, $data);
        return $value;
    }

    /**
     * Read signed 24-bit integer (short) data from the stream
     *
     * @return int
     */
    protected function readSigned()
    {
        $data = $this->getStream()->read(3);

        if (ByteOrder::MACHINE_ENDIAN !== $this->getByteOrder()
            && $this->getMachineByteOrder() !== $this->getByteOrder()
        ) {
            $data = strrev($data);
        }

        switch ($this->getByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $data = $data . "\x00";
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $data = "\x00" . $data;
                break;
            default:
                $data = $data . "\x00";
        }


        list(, $value) = unpack('l*', $data);
        return $value;
    }
}
