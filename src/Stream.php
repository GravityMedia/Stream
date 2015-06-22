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
     * @var bool
     */
    protected $local;

    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var int
     */
    protected $size;

    /**
     * Create a stream object
     *
     * @param string $uri  The URI which describes the stream (default is null; resource must be bound manually)
     * @param string $mode The mode specifies the type of access you require to the stream (default is rb)
     *
     * @throws Exception\IOException An exception will be thrown when the stream could not be opened
     */
    public function __construct($uri = null, $mode = 'rb')
    {
        if (null === $uri) {
            return;
        }

        $resource = @fopen($uri, $mode);
        if (!is_resource($resource)) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        $this->bind($resource);
    }

    /**
     * Destroy a stream object
     */
    public function __destruct()
    {
        if (is_resource($this->resource)) {
            $this->close();
        }
    }

    /**
     * @inheritdoc
     */
    public function bind($resource)
    {
        if (!is_resource($resource)) {
            throw new Exception\InvalidArgumentException('Invalid resource argument');
        }

        $meta = stream_get_meta_data($resource);
        $this->readable = in_array($meta['mode'], self::$readModes);
        $this->writable = in_array($meta['mode'], self::$writeModes);
        $this->seekable = $meta['seekable'];
        $this->uri = $meta['uri'];
        $this->local = stream_is_local($resource);
        $this->resource = $resource;

        return $this;
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
    public function isLocal()
    {
        return $this->local;
    }

    /**
     * @inheritdoc
     */
    public function getSize()
    {
        if (!$this->local) {
            throw new Exception\BadMethodCallException('Operation not supported');
        }

        if (null !== $this->size) {
            return $this->size;
        }

        if ($this->uri) {
            clearstatcache(true, $this->uri);
        }

        $stats = fstat($this->resource);
        if (!isset($stats['size'])) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        $this->size = $stats['size'];

        return $this->size;
    }

    /**
     * @inheritdoc
     */
    public function getContents($length = -1, $offset = -1)
    {
        if (!$this->readable) {
            throw new Exception\BadMethodCallException('Operation not supported');
        }

        $data = stream_get_contents($this->resource, $length, $offset);
        if (false === $data) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function eof()
    {
        return feof($this->resource);
    }

    /**
     * @inheritdoc
     */
    public function tell()
    {
        $position = ftell($this->resource);
        if (false === $position) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $position;
    }

    /**
     * @inheritdoc
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (fseek($this->resource, $offset, $whence) < 0) {
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
        if (!$this->readable) {
            throw new Exception\BadMethodCallException('Operation not supported');
        }

        $data = fread($this->resource, $length);
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
        if (!$this->writable) {
            throw new Exception\BadMethodCallException('Operation not supported');
        }

        // reset size because we don't know the size after the data was written
        $this->size = null;

        $length = fwrite($this->resource, $data);
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
        if (!$this->writable) {
            throw new Exception\BadMethodCallException('Operation not supported');
        }

        if (!ftruncate($this->resource, $size)) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        $this->size = $size;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function close()
    {
        return fclose($this->resource);
    }
}
