<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

use GravityMedia\Stream\Exception;

/**
 * Stream interface
 *
 * @package GravityMedia\Stream
 */
interface StreamInterface
{
    /**
     * Bind resource to stream and gather meta data from it
     *
     * @param resource $resource The resource to bind to the stream
     *
     * @throws Exception\InvalidArgumentException An exception will be thrown when the resource is invalid
     *
     * @return $this
     */
    public function bind($resource);

    /**
     * Get resource
     *
     * @return resource
     */
    public function getResource();

    /**
     * Return whether read access will be granted
     *
     * @return bool
     */
    public function isReadable();

    /**
     * Return whether write access will be granted
     *
     * @return bool
     */
    public function isWritable();

    /**
     * Return whether the current stream can be seeked
     *
     * @return bool
     */
    public function isSeekable();

    /**
     * Get the URI or filename associated with the stream
     *
     * @return string
     */
    public function getUri();

    /**
     * Return true for local streams
     *
     * @return bool
     */
    public function isLocal();

    /**
     * Get reader
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams
     *
     * @return StreamReaderInterface
     */
    public function getReader();

    /**
     * Get writer
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-writable streams
     *
     * @return StreamWriterInterface
     */
    public function getWriter();

    /**
     * Get size in bytes
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-local streams
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          size could not be determined
     *
     * @return int
     */
    public function getSize();

    /**
     * Return if the end of the stream was reached
     *
     * @throws Exception\IOException An exception will be thrown for invalid stream resources
     *
     * @return bool
     * @link   http://www.php.net/manual/en/function.feof.php
     */
    public function eof();

    /**
     * Return the current position of the stream
     *
     * @throws Exception\IOException An exception will be thrown for invalid stream resources
     *
     * @return int
     * @link   http://www.php.net/manual/en/function.ftell.php
     */
    public function tell();

    /**
     * Seeks and returns the position of the stream
     *
     * @param int $offset The offset
     * @param int $whence Either SEEK_SET (which is default), SEEK_CUR or SEEK_END
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-seekable streams
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          position could not be set
     *
     * @return int
     * @link   http://www.php.net/manual/en/function.fseek.php
     */
    public function seek($offset, $whence = SEEK_SET);

    /**
     * Rewind the position of the stream
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-seekable streams
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          position could not be set
     *
     * @return int
     */
    public function rewind();

    /**
     * Close the stream
     *
     * @return bool
     * @link   http://www.php.net/manual/en/function.fclose.php
     */
    public function close();
}
