<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream\Reader;

use GravityMedia\Stream\Enum\Endian;
use GravityMedia\Stream\Exception;
use GravityMedia\Stream\StreamInterface;

/**
 * Unsigned short (16-bit integer) reader
 *
 * @package GravityMedia\Stream\Reader
 */
class UnsignedShortReader
{
    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * @var int
     */
    protected $endian;

    /**
     * Create unsigned short (16-bit integer) reader object
     *
     * @throws Exception\InvalidArgumentException   An exception will be thrown for non-readable streams or an invalid
     *                                              endian value
     *
     * @param StreamInterface $stream
     * @param int $endian
     */
    public function __construct(StreamInterface $stream, $endian)
    {
        if (!$stream->isReadable()) {
            throw new Exception\InvalidArgumentException('Stream not readable');
        }

        if (!in_array($endian, [Endian::ENDIAN_BIG, Endian::ENDIAN_LITTLE])) {
            throw new Exception\InvalidArgumentException('Invalid endian');
        }

        $this->stream = $stream;
        $this->endian = $endian;
    }

    /**
     * Get stream
     *
     * @return StreamInterface
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * Get endian
     *
     * @return int
     */
    public function getEndian()
    {
        return $this->endian;
    }

    /**
     * Read string data from the stream
     *
     * @throws Exception\IOException    An exception will be thrown for invalid stream resources or when the data could
     *                                  not be read
     *
     * @return int
     */
    public function read()
    {
        $format = 'n';
        if (Endian::ENDIAN_LITTLE === $this->endian) {
            $format = 'v';
        }

        $data = unpack($format, $this->stream->read(2));

        return $data[1];
    }
}
