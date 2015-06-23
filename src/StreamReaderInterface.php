<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

use GravityMedia\Stream\Exception;

/**
 * Stream reader interface
 *
 * @package GravityMedia\Stream
 */
interface StreamReaderInterface
{
    /**
     * Read up to $length number of bytes of data from the stream
     *
     * @param int $length The maximum number of bytes to read (default is 1)
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams
     * @throws Exception\IOException            An exception will be thrown when the data could not be read
     *
     * @return string
     * @link   http://www.php.net/manual/en/function.fread.php
     */
    public function read($length = 1);
}
