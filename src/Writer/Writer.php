<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream\Writer;

use GravityMedia\Stream\Exception;
use GravityMedia\Stream\StreamInterface;

/**
 * Stream writer
 *
 * @package GravityMedia\Stream\Writer
 */
class Writer
{
    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * Create writer object
     *
     * @throws Exception\InvalidArgumentException An exception will be thrown for non-writable streams
     *
     * @param StreamInterface $stream
     */
    public function __construct(StreamInterface $stream)
    {
        if (!$stream->isWritable()) {
            throw new Exception\InvalidArgumentException('Stream not writable');
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
     * Write data to the stream and return the number of bytes written
     *
     * @param string $data The data
     *
     * @return int
     */
    public function write($data)
    {
        return $this->getStream()->write($data);
    }
}
