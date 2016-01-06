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
     * Get stream
     *
     * @throws Exception\BadMethodCallException An exception will be thrown when the stream was not set
     *
     * @return StreamInterface
     */
    public function getStream()
    {
        if (null === $this->stream) {
            throw new Exception\BadMethodCallException('Stream not set');
        }

        return $this->stream;
    }

    /**
     * Set stream
     *
     * @param StreamInterface $stream
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams
     *
     * @return $this
     */
    public function setStream(StreamInterface $stream)
    {
        if (!$stream->isReadable()) {
            throw new Exception\BadMethodCallException('Stream not readable');
        }

        $this->stream = $stream;

        return $this;
    }

    /**
     * Get length
     *
     * @throws Exception\BadMethodCallException An exception will be thrown when the length was not set
     *
     * @return int
     */
    public function getLength()
    {
        if (null === $this->length) {
            throw new Exception\BadMethodCallException('Length not set');
        }

        return $this->length;
    }

    /**
     * Set length
     *
     * @param int $length
     *
     * @return $this
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Read string data from the stream
     *
     * @throws Exception\BadMethodCallException An exception will be thrown when the stream or length was not set
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be read
     *
     * @return string
     */
    public function read()
    {
        $length = $this->getLength();
        $stream = $this->getStream();

        return $stream->read($length);
    }
}
