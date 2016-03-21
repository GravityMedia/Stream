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
 * String reader
 *
 * @package GravityMedia\Stream\Reader
 */
class StringReader
{
    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * @var int
     */
    protected $length;

    /**
     * Create string reader object
     *
     * @throws Exception\InvalidArgumentException An exception will be thrown for non-readable streams
     *
     * @param StreamInterface $stream
     * @param int $length
     */
    public function __construct(StreamInterface $stream, $length)
    {
        if (!$stream->isReadable()) {
            throw new Exception\InvalidArgumentException('Stream not readable');
        }

        $this->stream = $stream;
        $this->length = $length;
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
     * Get length
     *
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Read string data from the stream
     *
     * @throws Exception\IOException    An exception will be thrown for invalid stream resources or when the data could
     *                                  not be read
     *
     * @return string
     */
    public function read()
    {
        return $this->stream->read($this->length);
    }
}
