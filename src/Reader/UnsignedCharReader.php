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
 * Unsigned char (8-bit integer) reader
 *
 * @package GravityMedia\Stream\Reader
 */
class UnsignedCharReader
{
    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * Create unsigned char (8-bit integer) reader object
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
     * Read string data from the stream
     *
     * @throws Exception\IOException    An exception will be thrown for invalid stream resources or when the data could
     *                                  not be read
     *
     * @return int
     */
    public function read()
    {
        $data = unpack('C', $this->stream->read(1));

        return $data[1];
    }
}
