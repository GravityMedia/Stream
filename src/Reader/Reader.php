<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream\Reader;

use GravityMedia\Stream\Exception;
use GravityMedia\Stream\StreamInterface;

/**
 * Stream reader
 *
 * @package GravityMedia\Stream\Reader
 */
class Reader
{
    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * Create reader object
     *
     * @throws Exception\InvalidArgumentException An exception will be thrown for non-readable streams
     *
     * @param StreamInterface $stream
     */
    public function __construct(StreamInterface $stream)
    {
        if (!$stream->isReadable()) {
            throw new Exception\InvalidArgumentException('Stream not readable');
        }

        $this->stream = $stream;
    }

    /**
     * Get stream
     *
     * @return StreamInterface
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * Read up to $length number of bytes of data from the stream
     *
     * @param int $length The maximum number of bytes to read
     *
     * @return string
     */
    public function read($length)
    {
        return $this->getStream()->read($length);
    }
}
