<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream\Reader;

use GravityMedia\Stream\Exception;

/**
 * Char (8-bit integer) reader
 *
 * @package GravityMedia\Stream\Reader
 */
class CharReader extends AbstractIntegerReader
{
    /**
     * Read unsigned char (8-bit integer) data from the stream
     *
     * @return int
     */
    protected function readUnsigned()
    {
        return ord($this->getStream()->read(1));
    }

    /**
     * Read signed char (8-bit integer) data from the stream
     *
     * @return int
     */
    protected function readSigned()
    {
        $value = ord($this->getStream()->read(1));
        if ($value > 127) {
            return -$value - 2 * (128 - $value);
        }

        return $value;
    }
}
