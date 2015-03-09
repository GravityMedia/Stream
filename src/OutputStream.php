<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

use GravityMedia\Stream\Exception;
use GravityMedia\Uri\Uri;

/**
 * Output stream
 *
 * @package GravityMedia\Stream
 */
class OutputStream extends AbstractStream
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * Creates a stream object
     *
     * @param Uri $uri
     *
     * @throws Exception\InvalidArgumentException
     */
    public function __construct(Uri $uri)
    {
        $resource = fopen($uri, 'wb');
        if (!is_resource($resource)) {
            throw new Exception\InvalidArgumentException('Invalid URI argument');
        }
        $this->resource = $resource;
    }

    /**
     * Get resource
     *
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Write data to the stream and returns the number of bytes written
     *
     * @param string $data The data
     *
     * @throws Exception\StreamException
     *
     * @return int
     */
    public function write($data)
    {
        $length = fwrite($this->getResource(), $data);
        if (false === $length) {
            throw new Exception\StreamException('Unexpected result of stream operation');
        }
        return $length;
    }

    /**
     * Truncates the stream to a given length
     *
     * @param int $size The size to truncate to
     *
     * @throws Exception\StreamException
     *
     * @return $this
     */
    public function truncate($size)
    {
        if (!ftruncate($this->getResource(), $size)) {
            throw new Exception\StreamException('Unexpected result of stream operation');
        }
        return $this;
    }
}
