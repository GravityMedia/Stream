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
     * @throws Exception\IOException An exception will be thrown for invalid stream resources
     *
     * @return $this
     */
    public function bindResource($resource);

    /**
     * Get resource from stream
     *
     * @return resource
     */
    public function getResource();

    /**
     * Return whether the stream is local
     *
     * @return bool
     */
    public function isLocal();

    /**
     * Return whether read access on the stream will be granted
     *
     * @return bool
     */
    public function isReadable();

    /**
     * Return whether write access on the stream will be granted
     *
     * @return bool
     */
    public function isWritable();

    /**
     * Return whether the stream can be sought
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
     * Get size of the stream in bytes
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-local streams
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          size could not be determined
     *
     * @return int
     */
    public function getSize();

    /**
     * Return whether the end of the stream was reached
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
     * Seek and return the position of the stream
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
     * Read up to $length number of bytes of data from the stream
     *
     * @param int $length The maximum number of bytes to read (default is 1)
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be read
     *
     * @return string
     * @link   http://www.php.net/manual/en/function.fread.php
     */
    public function read($length = 1);


    /**
     * Write data to the stream and return the number of bytes written
     *
     * @param string $data The data
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-writable streams
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be written
     *
     * @return int
     * @link   http://www.php.net/manual/en/function.fwrite.php
     */
    public function write($data);

    /**
     * Truncate the stream to a given length
     *
     * @param int $size The size to truncate to
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-writable streams
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          stream could not be truncated
     *
     * @return bool
     * @link   http://www.php.net/manual/en/function.ftruncate.php
     */
    public function truncate($size);

    /**
     * Close the stream
     *
     * @return bool
     * @link   http://www.php.net/manual/en/function.fclose.php
     */
    public function close();
}
