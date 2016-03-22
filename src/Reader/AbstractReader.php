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
 * Abstract reader
 *
 * @package GravityMedia\Stream\Reader
 */
abstract class AbstractReader
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
     * Read data from the stream
     *
     * @return mixed
     */
    abstract public function read();
}
