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
     * @var StreamReaderInterface
     */
    protected $reader;

    /**
     * @var StreamWriterInterface
     */
    protected $writer;

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
            throw new Exception\IOException('Failed to open stream');
        }

        $this->bind($resource);
    }

    /**
     * Destroy a stream object
     */
    public function __destruct()
    {
        if (!is_resource($this->resource)) {
            return;
        }

        $this->close();
    }

    /**
     * @inheritdoc
     */
    public function bind($resource)
    {
        if (!is_resource($resource)) {
            throw new Exception\InvalidArgumentException('Invalid stream resource');
        }

        $meta = stream_get_meta_data($resource);
        $this->readable = in_array($meta['mode'], self::$readModes);
        $this->writable = in_array($meta['mode'], self::$writeModes);
        $this->seekable = $meta['seekable'];
        $this->uri = $meta['uri'];
        $this->local = stream_is_local($resource);
        $this->resource = $resource;
        $this->reader = null;
        $this->writer = null;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getResource()
    {
        return $this->resource;
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
    public function getReader()
    {
        if (null === $this->reader) {
            try {
                $this->reader = new StreamReader($this);
            } catch (Exception\InvalidArgumentException $exception) {
                throw new Exception\BadMethodCallException('Operation not supported', 0, $exception);
            }
        }

        return $this->reader;
    }

    /**
     * @inheritdoc
     */
    public function getWriter()
    {
        if (null === $this->writer) {
            try {
                $this->writer = new StreamWriter($this);
            } catch (Exception\InvalidArgumentException $exception) {
                throw new Exception\BadMethodCallException('Operation not supported', 0, $exception);
            }
        }

        return $this->writer;
    }

    /**
     * @inheritdoc
     */
    public function getSize()
    {
        if (!$this->isLocal()) {
            throw new Exception\BadMethodCallException('Operation not supported');
        }

        $resource = $this->getResource();
        if (!is_resource($resource)) {
            throw new Exception\IOException('Invalid stream resource');
        }

        $uri = $this->getUri();
        if (is_string($uri)) {
            clearstatcache(true, $uri);
        }

        $stats = @fstat($resource);
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
        $resource = $this->getResource();
        if (!is_resource($resource)) {
            throw new Exception\IOException('Invalid stream resource');
        }

        return feof($resource);
    }

    /**
     * @inheritdoc
     */
    public function tell()
    {
        $resource = $this->getResource();
        if (!is_resource($resource)) {
            throw new Exception\IOException('Invalid stream resource');
        }

        return ftell($resource);
    }

    /**
     * @inheritdoc
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->isSeekable()) {
            throw new Exception\BadMethodCallException('Operation not supported');
        }

        $resource = $this->getResource();
        if (!is_resource($resource)) {
            throw new Exception\IOException('Invalid stream resource');
        }

        if (fseek($resource, $offset, $whence) < 0) {
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
    public function close()
    {
        return @fclose($this->getResource());
    }
}
