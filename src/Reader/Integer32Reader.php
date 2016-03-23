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
 * 32-bit integer (long) reader
 *
 * @package GravityMedia\Stream\Reader
 */
class Integer32Reader extends AbstractIntegerReader
{
    /**
     * Use byte order aware trait
     */
    use ByteOrderAwareTrait;

    /**
     * Read unsigned 32-bit integer (long) data from the stream
     *
     * @return int
     */
    protected function readUnsigned()
    {
        switch ($this->getByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $format = 'N*';
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $format = 'V*';
                break;
            default:
                $format = 'L*';
        }

        list(, $value) = unpack($format, $this->getStream()->read(4));
        return $value;
    }

    /**
     * Read signed 32-bit integer (long) data from the stream
     *
     * @return int
     */
    protected function readSigned()
    {
        $data = $this->stream->read(4);

        if (ByteOrder::MACHINE_ENDIAN !== $this->getByteOrder()
            && $this->getMachineByteOrder() !== $this->getByteOrder()
        ) {
            $data = strrev($data);
        }

        list(, $value) = unpack('l*', $data);
        return $value;
    }
}
