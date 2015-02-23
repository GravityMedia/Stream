<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

/**
 * Abstract stream
 *
 * @package GravityMedia\Stream
 */
abstract class AbstractStream
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * Destroys stream object
     */
    public function __destruct()
    {
        if (is_resource($this->resource)) {
            $this->close();
        }
    }

    /**
     * Returns the current position of the stream
     *
     * @return int
     */
    public function tell()
    {
        return ftell($this->resource);
    }

    /**
     * Seeks on the stream
     *
     * @param int $offset
     * @param int $whence
     *
     * @return int
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        return fseek($this->resource, $offset, $whence);
    }

    /**
     * Reset the stream
     *
     * @return int
     */
    public function reset()
    {
        return $this->seek(0);
    }

    /**
     * Close the stream
     *
     * @return bool
     */
    public function close()
    {
        return fclose($this->resource);
    }
}
