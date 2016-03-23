<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream\Reader;

use GravityMedia\Stream\Exception;

/**
 * 8-bit integer (char) reader
 *
 * @package GravityMedia\Stream\Reader
 */
class Integer8Reader extends AbstractIntegerReader
{
    /**
     * Read unsigned 8-bit integer (char) data from the stream
     *
     * @return int
     */
    protected function readUnsigned()
    {
        return ord($this->getStream()->read(1));
    }

    /**
     * Read signed 8-bit integer (char) data from the stream
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
