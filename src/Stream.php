<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

use GravityMedia\Stream\Exception;
use GravityMedia\Stream\Reader\StringReader;

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
    private static $readModes = ['r', 'w+', 'r+', 'x+', 'c+', 'rb', 'w+b', 'r+b', 'x+b', 'c+b', 'rt', 'w+t',
        'r+t', 'x+t', 'c+t', 'a+'];

    /**
     * @var string[]
     */
    private static $writeModes = ['w', 'w+', 'rw', 'r+', 'x+', 'c+', 'wb', 'w+b', 'r+b', 'x+b', 'c+b', 'w+t',
        'r+t', 'x+t', 'c+t', 'a', 'a+'];

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
     * @var StringReader
     */
    protected $stringReader;

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

        return $instance->bindResource($resource);
    }

    /**
     * Get meta data from the stream
     *
     * @throws Exception\IOException An exception will be thrown for invalid stream resources
     *
     * @return array
     */
    protected function getMetaData()
    {
        return stream_get_meta_data($this->getResource());
    }

    /**
     * {@inheritdoc}
     */
    public function bindResource($resource)
    {
        $this->resource = $resource;

        $this->local = stream_is_local($this->getResource());

        $metaData = $this->getMetaData();
        $this->readable = in_array($metaData['mode'], self::$readModes);
        $this->writable = in_array($metaData['mode'], self::$writeModes);
        $this->seekable = $metaData['seekable'];
        $this->uri = $metaData['uri'];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getResource()
    {
        if (!is_resource($this->resource)) {
            throw new Exception\IOException('Invalid resource');
        }

        return $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function isLocal()
    {
        return $this->local;
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable()
    {
        return $this->readable;
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable()
    {
        return $this->writable;
    }

    /**
     * {@inheritdoc}
     */
    public function isSeekable()
    {
        return $this->seekable;
    }

    /**
     * {@inheritdoc}
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get string reader
     *
     * @return StringReader
     */
    public function getStringReader()
    {
        if (null === $this->stringReader) {
            $this->stringReader = new StringReader();
        }

        return $this->stringReader;
    }

    /**
     * Set string reader
     *
     * @param StringReader $stringReader
     *
     * @return $this
     */
    public function setStringReader(StringReader $stringReader)
    {
        $this->stringReader = $stringReader;

        return $this;
    }

    /**
     * Get information about the stream
     *
     * @param string $info The information to retrieve
     *
     * @throws Exception\IOException An exception will be thrown for invalid stream resources
     *
     * @return int
     */
    protected function getStat($info)
    {
        $resource = $this->getResource();

        $uri = $this->getUri();
        if (is_string($uri)) {
            clearstatcache(true, $uri);
        }

        $stat = fstat($resource);

        return $stat[$info];
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        if (!$this->isLocal()) {
            throw new Exception\BadMethodCallException('Stream not local');
        }

        return $this->getStat('size');
    }

    /**
     * {@inheritdoc}
     */
    public function eof()
    {
        return feof($this->getResource());
    }

    /**
     * {@inheritdoc}
     */
    public function tell()
    {
        return ftell($this->getResource());
    }

    /**
     * {@inheritdoc}
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        $resource = $this->getResource();

        if (!$this->isSeekable()) {
            throw new Exception\BadMethodCallException('Stream not seekable');
        }

        if (fseek($resource, $offset, $whence) < 0) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $this->tell();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return $this->seek(0);
    }

    /**
     * {@inheritdoc}
     */
    public function read($length)
    {
        $resource = $this->getResource();

        if (!$this->isReadable()) {
            throw new Exception\BadMethodCallException('Stream not readable');
        }

        $data = @fread($resource, $length);
        if (false === $data) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function readString($length = 1)
    {
        return $this->getStringReader()
            ->setStream($this)
            ->setLength($length)
            ->read();
    }

    /**
     * {@inheritdoc}
     */
    public function write($data)
    {
        $resource = $this->getResource();

        if (!$this->isWritable()) {
            throw new Exception\BadMethodCallException('Stream not writable');
        }

        $length = @fwrite($resource, $data);
        if (false === $length) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $length;
    }

    /**
     * {@inheritdoc}
     */
    public function truncate($size)
    {
        $resource = $this->getResource();

        if (!$this->isWritable()) {
            throw new Exception\BadMethodCallException('Stream not writable');
        }

        return @ftruncate($resource, $size);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return @fclose($this->resource);
    }
}
