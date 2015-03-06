<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

use GravityMedia\Stream\Exception;
use League\Url\UrlInterface;

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
     * @param UrlInterface $url
     *
     * @throws Exception\InvalidArgumentException
     */
    public function __construct(UrlInterface $url)
    {
        $resource = fopen($url, 'wb');
        if (!is_resource($resource)) {
            throw new Exception\InvalidArgumentException('Invalid stream argument');
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
