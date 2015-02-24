<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

use GravityMedia\Stream\Exception;

/**
 * Stream information
 *
 * @package GravityMedia\Stream
 */
class StreamMetadata
{
    /**
     * @var bool
     */
    protected $timedOut;

    /**
     * @var bool
     */
    protected $blocked;

    /**
     * @var string
     */
    protected $streamType;

    /**
     * @var string
     */
    protected $wrapperType;

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
     * @var bool
     */
    protected $local;

    /**
     * Is timed out
     *
     * @return boolean
     */
    public function isTimedOut()
    {
        return $this->timedOut;
    }

    /**
     * Set timed out
     *
     * @param boolean $timedOut
     *
     * @return StreamMetadata
     */
    public function setTimedOut($timedOut)
    {
        $this->timedOut = $timedOut;
        return $this;
    }

    /**
     * Is blocked
     *
     * @return boolean
     */
    public function isBlocked()
    {
        return $this->blocked;
    }

    /**
     * Set blocked
     *
     * @param boolean $blocked
     *
     * @return StreamMetadata
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;
        return $this;
    }

    /**
     * Get stream type
     *
     * @return string
     */
    public function getStreamType()
    {
        return $this->streamType;
    }

    /**
     * Set stream type
     *
     * @param string $streamType
     *
     * @return StreamMetadata
     */
    public function setStreamType($streamType)
    {
        $this->streamType = $streamType;
        return $this;
    }

    /**
     * Get wrapper type
     *
     * @return string
     */
    public function getWrapperType()
    {
        return $this->wrapperType;
    }

    /**
     * Set wrapper type
     *
     * @param string $wrapperType
     *
     * @return StreamMetadata
     */
    public function setWrapperType($wrapperType)
    {
        $this->wrapperType = $wrapperType;
        return $this;
    }

    /**
     * Is readable
     *
     * @return boolean
     */
    public function isReadable()
    {
        return $this->readable;
    }

    /**
     * Set readable
     *
     * @param boolean $readable
     *
     * @return StreamMetadata
     */
    public function setReadable($readable)
    {
        $this->readable = $readable;
        return $this;
    }

    /**
     * Is writable
     *
     * @return boolean
     */
    public function isWritable()
    {
        return $this->writable;
    }

    /**
     * Set writable
     *
     * @param boolean $writable
     *
     * @return StreamMetadata
     */
    public function setWritable($writable)
    {
        $this->writable = $writable;
        return $this;
    }

    /**
     * Is seekable
     *
     * @return boolean
     */
    public function isSeekable()
    {
        return $this->seekable;
    }

    /**
     * Set seekable
     *
     * @param boolean $seekable
     *
     * @return StreamMetadata
     */
    public function setSeekable($seekable)
    {
        $this->seekable = $seekable;
        return $this;
    }

    /**
     * Is local
     *
     * @return boolean
     */
    public function isLocal()
    {
        return $this->local;
    }

    /**
     * Set local
     *
     * @param boolean $local
     *
     * @return StreamMetadata
     */
    public function setLocal($local)
    {
        $this->local = $local;
        return $this;
    }

    /**
     * Create stream metadata from array
     *
     * @param array $data
     *
     * @return StreamMetadata
     */
    public static function fromArray(array $data)
    {
        /** @var StreamMetadata $metadata */
        $metadata = new static();
        return $metadata
            ->setTimedOut($data['timed_out'])
            ->setBlocked($data['blocked'])
            ->setStreamType($data['stream_type'])
            ->setWrapperType($data['wrapper_type'])
            ->setReadable($data['readable'])
            ->setWritable($data['writable'])
            ->setSeekable($data['seekable'])
            ->setLocal($data['local']);
    }
}
