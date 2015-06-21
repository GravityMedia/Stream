<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

use GravityMedia\Stream\Exception;

/**
 * Stream
 *
 * @package GravityMedia\Stream
 */
class Stream
{
    /**
     * Read modes
     *
     * @var string[]
     */
    protected static $readModes = array('r', 'w+', 'r+', 'x+', 'c+', 'rb', 'w+b', 'r+b', 'x+b', 'c+b', 'rt', 'w+t',
        'r+t', 'x+t', 'c+t', 'a+');

    /**
     * Write modes
     *
     * @var string[]
     */
    protected static $writeModes = array('w', 'w+', 'rw', 'r+', 'x+', 'c+', 'wb', 'w+b', 'r+b', 'x+b', 'c+b', 'w+t',
        'r+t', 'x+t', 'c+t', 'a', 'a+');

    /**
     * Resource
     *
     * @var resource
     */
    protected $resource;

    /**
     * Readable
     *
     * @var boolean
     */
    protected $readable;

    /**
     * Writable
     *
     * @var boolean
     */
    protected $writable;

    /**
     * Seekable
     *
     * @var boolean
     */
    protected $seekable;

    /**
     * URI
     *
     * @var string
     */
    protected $uri;

    /**
     * Local
     *
     * @var boolean
     */
    protected $local;

    /**
     * Creates a stream object
     *
     * @param string $uri
     * @param string $mode
     *
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($uri, $mode = 'rb+')
    {
        $resource = fopen($uri, $mode);
        if (!is_resource($resource)) {
            throw new Exception\InvalidArgumentException('Invalid URL argument');
        }

        $this->setResource($resource);
    }

    /**
     * Destroy a stream object
     */
    public function __destruct()
    {
        if (is_resource($this->resource)) {
            $this->close();
        }

        $this->resource = null;
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
     * Set resource
     *
     * @param resource $resource
     *
     * @throws Exception\InvalidArgumentException
     *
     * @return $this
     */
    public function setResource($resource)
    {
        if (!is_resource($resource)) {
            throw new Exception\InvalidArgumentException('Invalid resource argument');
        }

        $metaData = stream_get_meta_data($resource);
        $this->readable = in_array($metaData['mode'], self::$readModes);
        $this->writable = in_array($metaData['mode'], self::$writeModes);
        $this->seekable = $metaData['seekable'];
        $this->uri = $metaData['uri'];
        $this->local = stream_is_local($resource);
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get size
     *
     * @return int
     */
    public function getSize()
    {
        if ($this->uri) {
            clearstatcache(true, $this->uri);
        }

        $statData = fstat($this->resource);
        if (false === $statData) {
            throw new Exception\IOException('Unexpected result of stream operation');
        }

        return $statData['size'];
    }

    /**
     * Tests if the end of the stream was reached
     *
     * @return boolean
     */
    public function eof()
    {
        return feof($this->getResource());
    }

    /**
     * Returns the current position of the stream
     *
     * @throws Exception\IOException
     *
     * @return int
     */
    public function tell()
    {
        $position = ftell($this->getResource());
        if (false === $position) {
            throw new Exception\IOException('Unexpected result of stream operation');
        }

        return $position;
    }

    /**
     * Seeks and returns the position on the stream
     *
     * @param int $offset The offset
     * @param int $whence Either SEEK_SET (default), SEEK_CUR or SEEK_END
     *
     * @throws Exception\IOException
     *
     * @return int
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (fseek($this->getResource(), $offset, $whence) < 0) {
            throw new Exception\IOException('Unexpected result of stream operation');
        }

        return $this->tell();
    }

    /**
     * Rewind the position of the stream
     *
     * @throws Exception\IOException
     *
     * @return int
     */
    public function rewind()
    {
        return $this->seek(0);
    }

    /**
     * Read up to $length number of bytes of data from the stream
     *
     * @param int $length Up to length number of bytes (defaults to 1)
     *
     * @throws Exception\IOException
     *
     * @return string
     */
    public function read($length = 1)
    {
        $data = fread($this->getResource(), $length);
        if (false === $data) {
            throw new Exception\IOException('Unexpected result of stream operation');
        }

        return $data;
    }

    /**
     * Write data to the stream and returns the number of bytes written
     *
     * @param string $data The data
     *
     * @throws Exception\IOException
     *
     * @return int
     */
    public function write($data)
    {
        $length = fwrite($this->getResource(), $data);
        if (false === $length) {
            throw new Exception\IOException('Unexpected result of stream operation');
        }

        return $length;
    }

    /**
     * Truncates the stream to a given length
     *
     * @param int $size The size to truncate to
     *
     * @throws Exception\IOException
     *
     * @return $this
     */
    public function truncate($size)
    {
        if (!ftruncate($this->getResource(), $size)) {
            throw new Exception\IOException('Unexpected result of stream operation');
        }

        return $this;
    }

    /**
     * Close the stream
     *
     * @return boolean
     */
    public function close()
    {
        return fclose($this->getResource());
    }
}
