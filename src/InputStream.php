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
 * Input stream
 *
 * @package GravityMedia\Stream
 */
class InputStream extends AbstractStream
{
    /**
     * The resource
     *
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
        $resource = fopen($url, 'rb');
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
     * Tests if the end of the stream was reached
     *
     * @return bool
     */
    public function end()
    {
        return feof($this->getResource());
    }

    /**
     * Read up to $length number of bytes of data from the stream
     *
     * @param int $length Up to length number of bytes (defaults to 1)
     *
     * @throws Exception\StreamException
     *
     * @return string
     */
    public function read($length = 1)
    {
        $data = fread($this->getResource(), $length);
        if (false === $data) {
            throw new Exception\StreamException('Unexpected result of stream operation');
        }
        return $data;
    }
}
