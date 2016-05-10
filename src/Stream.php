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
     * @var int
     */
    protected $byteOrder;

    /**
     * @var int
     */
    protected static $machineByteOrder;

    /**
     * Create stream object from resource.
     *
     * @param resource $resource
     *
     * @throws Exception\InvalidArgumentException An exception will be thrown for invalid resource arguments.
     *
     * @return static
     */
    public static function fromResource($resource)
    {
        if (!is_resource($resource)) {
            throw new Exception\InvalidArgumentException('Invalid resource');
        }

        $stream = new static();

        return $stream->bindResource($resource);
    }

    /**
     * Bind resource to stream and gather meta data.
     *
     * @param resource $resource The resource to bind to the stream.
     *
     * @throws Exception\InvalidArgumentException An exception will be thrown for invalid resource arguments.
     *
     * @return $this
     */
    public function bindResource($resource)
    {
        if (!is_resource($resource)) {
            throw new Exception\InvalidArgumentException('Invalid resource');
        }

        $metaData = stream_get_meta_data($resource);

        $this->resource = $resource;
        $this->local = stream_is_local($resource);
        $this->readable = in_array($metaData['mode'], self::$readModes);
        $this->writable = in_array($metaData['mode'], self::$writeModes);
        $this->seekable = $metaData['seekable'];
        $this->uri = $metaData['uri'];

        return $this;
    }

    /**
     * Return whether the stream is local.
     *
     * @return bool
     */
    public function isLocal()
    {
        return $this->local;
    }

    /**
     * Return whether read access on the stream will be granted.
     *
     * @return bool
     */
    public function isReadable()
    {
        return $this->readable;
    }

    /**
     * Return whether write access on the stream will be granted.
     *
     * @return bool
     */
    public function isWritable()
    {
        return $this->writable;
    }

    /**
     * Return whether the stream can be sought.
     *
     * @return bool
     */
    public function isSeekable()
    {
        return $this->seekable;
    }

    /**
     * Get the URI or filename associated with the stream.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get the byte order for integer handling.
     *
     * @return int
     */
    public function getByteOrder()
    {
        if (null === $this->byteOrder) {
            return ByteOrder::MACHINE_ENDIAN;
        }

        return $this->byteOrder;
    }

    /**
     * Set the byte order for integer handling.
     *
     * @param int $byteOrder The byte order to set. Must be one of the constants defined by the byte order enum.
     *
     * @throws Exception\InvalidArgumentException An exception will be thrown for invalid byte order arguments.
     *
     * @return $this
     */
    public function setByteOrder($byteOrder)
    {
        if (!in_array($byteOrder, ByteOrder::values(), true)) {
            throw new Exception\InvalidArgumentException('Invalid byte order');
        }

        $this->byteOrder = $byteOrder;

        return $this;
    }

    /**
     * Get the machine byte order.
     *
     * @return int
     */
    public function getMachineByteOrder()
    {
        if (null === static::$machineByteOrder) {
            static::$machineByteOrder = ByteOrder::BIG_ENDIAN;

            list(, $value) = unpack('s', "\x01\x00");
            if (1 === $value) {
                static::$machineByteOrder = ByteOrder::LITTLE_ENDIAN;
            }
        }

        return static::$machineByteOrder;
    }

    /**
     * Get information about the stream.
     *
     * @param string $info The information to retrieve.
     *
     * @throws Exception\IOException An exception will be thrown for invalid stream resources.
     *
     * @return int
     */
    protected function getStat($info)
    {
        if (!is_resource($this->resource)) {
            throw new Exception\IOException('Invalid stream resource');
        }

        $uri = $this->getUri();
        if (is_string($uri)) {
            clearstatcache(true, $uri);
        }

        $stat = fstat($this->resource);

        return $stat[$info];
    }

    /**
     * Get size of the stream in bytes
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-local streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources.
     *
     * @return int
     */
    public function getSize()
    {
        if (!$this->isLocal()) {
            throw new Exception\BadMethodCallException('Stream not local');
        }

        return $this->getStat('size');
    }

    /**
     * Return whether the end of the stream was reached.
     *
     * @throws Exception\IOException An exception will be thrown for invalid stream resources.
     *
     * @return bool
     * @link   http://www.php.net/manual/en/function.feof.php
     */
    public function eof()
    {
        if (!is_resource($this->resource)) {
            throw new Exception\IOException('Invalid stream resource');
        }

        return feof($this->resource);
    }

    /**
     * Return the current position of the stream.
     *
     * @throws Exception\IOException An exception will be thrown for invalid stream resources.
     *
     * @return int
     * @link   http://www.php.net/manual/en/function.ftell.php
     */
    public function tell()
    {
        if (!is_resource($this->resource)) {
            throw new Exception\IOException('Invalid stream resource');
        }

        return ftell($this->resource);
    }

    /**
     * Seek and return the position of the stream.
     *
     * @param int $offset The offset.
     * @param int $whence Either SEEK_SET (which is default), SEEK_CUR or SEEK_END.
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-seekable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          position could not be set.
     *
     * @return int
     * @link   http://www.php.net/manual/en/function.fseek.php
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->isSeekable()) {
            throw new Exception\BadMethodCallException('Stream not seekable');
        }

        if (!is_resource($this->resource)) {
            throw new Exception\IOException('Invalid stream resource');
        }

        if (fseek($this->resource, $offset, $whence) < 0) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $this->tell();
    }

    /**
     * Rewind the position of the stream.
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-seekable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          position could not be set.
     *
     * @return int
     */
    public function rewind()
    {
        return $this->seek(0);
    }

    /**
     * Read up to $length number of bytes of data from the stream.
     *
     * @param int $length The maximum number of bytes to read.
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be read.
     *
     * @return string
     * @link   http://www.php.net/manual/en/function.fread.php
     */
    public function read($length)
    {
        if (!$this->isReadable()) {
            throw new Exception\BadMethodCallException('Stream not readable');
        }

        if (!is_resource($this->resource)) {
            throw new Exception\IOException('Invalid stream resource');
        }

        $data = @fread($this->resource, $length);
        if (false === $data) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $data;
    }

    /**
     * Read signed 8-bit integer (char) data from the stream.
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be read.
     *
     * @return int
     */
    public function readInt8()
    {
        list(, $value) = unpack('c', $this->read(1));
        return $value;
    }

    /**
     * Read unsigned 8-bit integer (char) data from the stream.
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be read.
     *
     * @return int
     */
    public function readUInt8()
    {
        list(, $value) = unpack('C', $this->read(1));
        return $value;
    }

    /**
     * Read signed 16-bit integer (short) data from the stream.
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be read.
     *
     * @return int
     */
    public function readInt16()
    {
        $data = $this->read(2);

        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        list(, $value) = unpack('s', $data);
        return $value;
    }

    /**
     * Read unsigned 16-bit integer (short) data from the stream.
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be read.
     *
     * @return int
     */
    public function readUInt16()
    {
        switch ($this->getByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $format = 'n';
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $format = 'v';
                break;
            default:
                $format = 'S';
        }

        list(, $value) = unpack($format, $this->read(2));
        return $value;
    }

    /**
     * Read signed 24-bit integer (short) data from the stream.
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be read.
     *
     * @return int
     */
    public function readInt24()
    {
        $value = $this->readUInt24();

        if ($value & 0x800000) {
            return $value - 2 ** 24;
        }

        return $value;
    }

    /**
     * Read unsigned 24-bit integer (short) data from the stream.
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be read.
     *
     * @return int
     */
    public function readUInt24()
    {
        $data = $this->read(3);

        $byteOrder = $this->getByteOrder();
        if ($byteOrder === ByteOrder::MACHINE_ENDIAN) {
            $byteOrder = $this->getMachineByteOrder();
        }

        if ($byteOrder !== $this->getMachineByteOrder()) {
            $data = strrev($data);
        }

        $values = unpack('C3', $data);
        return $values[1] | $values[2] << 8 | $values[3] << 16;
    }

    /**
     * Read signed 32-bit integer (long) data from the stream.
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be read.
     *
     * @return int
     */
    public function readInt32()
    {
        $data = $this->read(4);

        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        list(, $value) = unpack('l', $data);
        return $value;
    }

    /**
     * Read unsigned 32-bit integer (long) data from the stream.
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be read.
     *
     * @return int
     */
    public function readUInt32()
    {
        switch ($this->getByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $format = 'N';
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $format = 'V';
                break;
            default:
                $format = 'L';
        }

        list(, $value) = unpack($format, $this->read(4));
        return $value;
    }

    /**
     * Read signed 64-bit integer (long long) data from the stream.
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be read.
     *
     * @return int
     */
    public function readInt64()
    {
        $data = $this->read(8);

        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        list(, $value) = unpack('q', $data);
        return $value;
    }

    /**
     * Read unsigned 64-bit integer (long long) data from the stream.
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-readable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be read.
     *
     * @return int
     */
    public function readUInt64()
    {
        switch ($this->getByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $format = 'J';
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $format = 'P';
                break;
            default:
                $format = 'Q';
        }

        list(, $value) = unpack($format, $this->read(8));
        return $value;
    }

    /**
     * Write data to the stream and return the number of bytes written.
     *
     * @param string $data The data
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-writable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          data could not be written.
     *
     * @return int
     * @link   http://www.php.net/manual/en/function.fwrite.php
     */
    public function write($data)
    {
        if (!$this->isWritable()) {
            throw new Exception\BadMethodCallException('Stream not writable');
        }

        if (!is_resource($this->resource)) {
            throw new Exception\IOException('Invalid stream resource');
        }

        $length = @fwrite($this->resource, $data);
        if (false === $length) {
            throw new Exception\IOException('Unexpected result of operation');
        }

        return $length;
    }

    /**
     * Write signed 8-bit integer (char) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeInt8($value)
    {
        return $this->write(pack('c', $value));
    }

    /**
     * Write unsigned 8-bit integer (char) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeUInt8($value)
    {
        return $this->write(pack('C', $value));
    }

    /**
     * Write signed 16-bit integer (short) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeInt16($value)
    {
        $data = pack('s', $value);

        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        return $this->write($data);
    }

    /**
     * Write unsigned 16-bit integer (short) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeUInt16($value)
    {
        switch ($this->getByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $format = 'n';
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $format = 'v';
                break;
            default:
                $format = 'S';
        }

        return $this->write(pack($format, $value));
    }

    /**
     * Write signed 24-bit integer (short) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeInt24($value)
    {
        if ($value & 0x7fffff) {
            $value += 2 ** 24;
        }

        return $this->writeUInt24($value);
    }

    /**
     * Write unsigned 24-bit integer (short) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeUInt24($value)
    {
        $data = pack('C3', $value, $value >> 8, $value >> 16);

        $byteOrder = $this->getByteOrder();
        if ($byteOrder === ByteOrder::MACHINE_ENDIAN) {
            $byteOrder = $this->getMachineByteOrder();
        }

        if ($byteOrder !== $this->getMachineByteOrder()) {
            $data = strrev($data);
        }

        return $this->write($data);
    }

    /**
     * Write signed 32-bit integer (long) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeInt32($value)
    {
        $data = pack('l', $value);

        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        return $this->write($data);
    }

    /**
     * Write unsigned 32-bit integer (long) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeUInt32($value)
    {
        switch ($this->getByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $format = 'N';
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $format = 'V';
                break;
            default:
                $format = 'L';
        }

        return $this->write(pack($format, $value));
    }

    /**
     * Write signed 64-bit integer (long long) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeInt64($value)
    {
        $data = pack('q', $value);

        if ($this->getByteOrder() !== ByteOrder::MACHINE_ENDIAN
            && $this->getByteOrder() !== $this->getMachineByteOrder()
        ) {
            $data = strrev($data);
        }

        return $this->write($data);
    }

    /**
     * Write unsigned 64-bit integer (long long) data to the stream
     *
     * @param int $value The value
     *
     * @return int
     */
    public function writeUInt64($value)
    {
        switch ($this->getByteOrder()) {
            case ByteOrder::BIG_ENDIAN:
                $format = 'J';
                break;
            case ByteOrder::LITTLE_ENDIAN:
                $format = 'P';
                break;
            default:
                $format = 'Q';
        }

        return $this->write(pack($format, $value));
    }

    /**
     * Truncate the stream to a given length.
     *
     * @param int $size The size to truncate to.
     *
     * @throws Exception\BadMethodCallException An exception will be thrown for non-writable streams.
     * @throws Exception\IOException            An exception will be thrown for invalid stream resources or when the
     *                                          stream could not be truncated.
     *
     * @return bool
     * @link   http://www.php.net/manual/en/function.ftruncate.php
     */
    public function truncate($size)
    {
        if (!$this->isWritable()) {
            throw new Exception\BadMethodCallException('Stream not writable');
        }

        if (!is_resource($this->resource)) {
            throw new Exception\IOException('Invalid stream resource');
        }

        return @ftruncate($this->resource, $size);
    }

    /**
     * Close the stream.
     *
     * @return bool
     * @link   http://www.php.net/manual/en/function.fclose.php
     */
    public function close()
    {
        return @fclose($this->resource);
    }
}
