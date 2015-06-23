<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

use GravityMedia\Stream\Exception;

/**
 * Stream writer
 *
 * @package GravityMedia\Stream
 */
class StreamWriter implements StreamWriterInterface
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * Create stream writer object
     *
     * @param StreamInterface $stream The stream to write to
     *
     * @throws Exception\InvalidArgumentException An exception will be thrown when the provided stream is not writable
     */
    public function __construct(StreamInterface $stream)
    {
        if (!$stream->isWritable()) {
            throw new Exception\InvalidArgumentException('Stream is not writable');
        }

        $this->resource = $stream->getResource();
    }

    /**
     * @inheritdoc
     */
    public function write($data)
    {
        $length = @fwrite($this->resource, $data);
        if (false === $length) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $length;
    }

    /**
     * @inheritdoc
     */
    public function truncate($size)
    {
        if (!@ftruncate($this->resource, $size)) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $this;
    }
}
