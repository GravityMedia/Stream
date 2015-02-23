<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

/**
 * Input stream
 *
 * @package GravityMedia\Stream
 */
class InputStream extends AbstractStream
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
     * Tests if data is available
     *
     * @return bool
     */
    public function isAvailable()
    {
        return !feof($this->resource);
    }

    /**
     * Read data from the stream
     *
     * @param int $length
     *
     * @return string
     */
    public function read($length)
    {
        return fread($this->resource, $length);
    }
}
