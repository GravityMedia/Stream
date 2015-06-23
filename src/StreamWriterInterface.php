<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

use GravityMedia\Stream\Exception;

/**
 * Stream interface
 *
 * @package GravityMedia\Stream
 */
interface StreamWriterInterface
{
    /**
     * Write data to the stream and returns the number of bytes written
     *
     * @param string $data The data
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-writable streams
     * @throws Exception\IOException            An exception will be thrown when the data could not be written
     *
     * @return int
     * @link   http://www.php.net/manual/en/function.fwrite.php
     */
    public function write($data);

    /**
     * Truncates the stream to a given length
     *
     * @param int $size The size to truncate to
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-writable streams
     * @throws Exception\IOException            An exception will be thrown when the stream could not be truncated
     *
     * @return $this
     * @link   http://www.php.net/manual/en/function.ftruncate.php
     */
    public function truncate($size);
}
