<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

use GravityMedia\Stream\Exception;

/**
 * Stream reader
 *
 * @package GravityMedia\Stream
 */
class StreamReader implements StreamReaderInterface
{
    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * Create stream reader object
     *
     * @param StreamInterface $stream The stream to read from
     *
     * @throws Exception\InvalidArgumentException An exception will be thrown when the provided stream is not readable
     */
    public function __construct(StreamInterface $stream)
    {
        if (!$stream->isReadable()) {
            throw new Exception\InvalidArgumentException('Stream is not readable');
        }

        $this->stream = $stream;
    }

    /**
     * @inheritdoc
     */
    public function read($length = 1)
    {
        if (!$this->stream->isAccessible()) {
            throw new Exception\IOException('Invalid stream resource');
        }

        $data = @fread($this->stream->getResource(), $length);
        if (false === $data) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $data;
    }
}
