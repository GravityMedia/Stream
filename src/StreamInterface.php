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
     * @param resource $resource The resource to attach on the stream
     *
     * @throws Exception\InvalidArgumentException An exception will be thrown when the resource argument is actually no
     *                                            resource
     *
     * @return $this
     */
    public function bind($resource);

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
     * Get size in bytes
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-local streams
     * @throws Exception\IOException            An exception will be thrown when the size could not be determined
     *
     * @return int
     */
    public function getSize();

    /**
     * Get remainder of stream data
     *
     * @param int $length The maximum number of bytes to read (default is -1; read all the remaining data)
     * @param int $offset Seek to the specified offset before reading (default is -1; read from current position)
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams
     * @throws Exception\IOException            An exception will be thrown when the data could not be read
     *
     * @return string
     * @link   http://www.php.net/manual/en/function.stream-get-contents.php
     */
    public function getContents($length = -1, $offset = -1);

    /**
     * Return if the end of the stream was reached
     *
     * @return bool
     * @link   http://www.php.net/manual/en/function.feof.php
     */
    public function eof();

    /**
     * Return the current position of the stream
     *
     * @throws Exception\IOException An exception will be thrown when the position could not be determined
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
     * @throws Exception\IOException An exception will be thrown when the position could not be set
     *
     * @return int
     * @link   http://www.php.net/manual/en/function.fseek.php
     */
    public function seek($offset, $whence = SEEK_SET);

    /**
     * Rewind the position of the stream
     *
     * @throws Exception\IOException An exception will be thrown when the position could not be rewound
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
     * @throws Exception\IOException            An exception will be thrown when the data could not be read
     *
     * @return string
     * @link   http://www.php.net/manual/en/function.fread.php
     */
    public function read($length = 1);

    /**
     * Write data to the stream and returns the number of bytes written
     *
     * @param string $data The data
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-writable streams
     * @throws Exception\IOException            An exception will be thrown when the data could not be written
     *
     * @return int
     * @link   http://www.php.net/manual/en/function.fwrite.php
     */
    public function write($data);

    /**
     * Truncates the stream to a given length
     *
     * @param int $size The size to truncate to
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-writable streams
     * @throws Exception\IOException            An exception will be thrown when the stream could not be truncated
     *
     * @return $this
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
