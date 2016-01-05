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
class Stream implements StreamInterface
{
    /**
     * @var string[]
     */
    protected static $readModes = array('r', 'w+', 'r+', 'x+', 'c+', 'rb', 'w+b', 'r+b', 'x+b', 'c+b', 'rt', 'w+t',
        'r+t', 'x+t', 'c+t', 'a+');

    /**
     * @var string[]
     */
    protected static $writeModes = array('w', 'w+', 'rw', 'r+', 'x+', 'c+', 'wb', 'w+b', 'r+b', 'x+b', 'c+b', 'w+t',
        'r+t', 'x+t', 'c+t', 'a', 'a+');

    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var bool
     */
    protected $local;

    /**
     * @var bool
     */
    protected $readable;

    /**
     * @var bool
     */
    protected $writable;

    /**
     * @var bool
     */
    protected $seekable;

    /**
     * @var string
     */
    protected $uri;

    /**
     * Create stream object from resource
     *
     * @param resource $resource
     *
     * @return StreamInterface
     */
    public static function fromResource($resource)
    {
        $instance = new static();

        return $instance->bind($resource);
    }

    /**
     * Destroy a stream object
     */
    public function __destruct()
    {
        if (!is_resource($this->resource)) {
            return;
        }

        @fclose($this->resource);
    }

    /**
     * @inheritdoc
     */
    public function bind($resource)
    {
        $this->resource = $resource;

        $this->local = stream_is_local($this->getResource());

        $meta = stream_get_meta_data($this->getResource());
        $this->readable = in_array($meta['mode'], self::$readModes);
        $this->writable = in_array($meta['mode'], self::$writeModes);
        $this->seekable = $meta['seekable'];
        $this->uri = $meta['uri'];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getResource()
    {
        if (!is_resource($this->resource)) {
            throw new Exception\IOException('Invalid resource');
        }

        return $this->resource;
    }

    /**
     * @inheritdoc
     */
    public function isLocal()
    {
        return $this->local;
    }

    /**
     * @inheritdoc
     */
    public function isReadable()
    {
        return $this->readable;
    }

    /**
     * @inheritdoc
     */
    public function isWritable()
    {
        return $this->writable;
    }

    /**
     * @inheritdoc
     */
    public function isSeekable()
    {
        return $this->seekable;
    }

    /**
     * @inheritdoc
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @inheritdoc
     */
    public function getSize()
    {
        if (!$this->isLocal()) {
            throw new Exception\BadMethodCallException('Operation not supported');
        }

        $uri = $this->getUri();
        if (is_string($uri)) {
            clearstatcache(true, $uri);
        }

        $stats = @fstat($this->getResource());
        if (!is_array($stats) || !isset($stats['size'])) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $stats['size'];
    }

    /**
     * @inheritdoc
     */
    public function eof()
    {
        return feof($this->getResource());
    }

    /**
     * @inheritdoc
     */
    public function tell()
    {
        return ftell($this->getResource());
    }

    /**
     * @inheritdoc
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->isSeekable()) {
            throw new Exception\BadMethodCallException('Operation not supported');
        }

        if (fseek($this->getResource(), $offset, $whence) < 0) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $this->tell();
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        return $this->seek(0);
    }

    /**
     * @inheritdoc
     */
    public function read($length = 1)
    {
        if (!$this->isReadable()) {
            throw new Exception\BadMethodCallException('Operation not supported');
        }

        $data = @fread($this->getResource(), $length);
        if (false === $data) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function write($data)
    {
        if (!$this->isWritable()) {
            throw new Exception\BadMethodCallException('Operation not supported');
        }

        $length = @fwrite($this->getResource(), $data);
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
        if (!$this->isWritable()) {
            throw new Exception\BadMethodCallException('Operation not supported');
        }

        if (!@ftruncate($this->getResource(), $size)) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function close()
    {
        return @fclose($this->resource);
    }
}
