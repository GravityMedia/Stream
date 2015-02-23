<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

/**
 * Output stream
 *
 * @package GravityMedia\Stream
 */
class OutputStream extends AbstractStream
{
    /**
     * Creates stream object
     *
     * @param resource $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Write data to the stream
     *
     * @param string $data
     * @param int|null $length
     *
     * @return int
     */
    public function write($data, $length = null)
    {
        return fwrite($this->resource, $data, $length);
    }
}
