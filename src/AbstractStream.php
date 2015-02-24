<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

use GravityMedia\Stream\Exception;

/**
 * Abstract stream
 *
 * @package GravityMedia\Stream
 */
abstract class AbstractStream
{
    /**
     * Read modes
     *
     * @var string[]
     */
    protected static $readModes = array('r', 'w+', 'r+', 'x+', 'c+', 'rb', 'w+b', 'r+b', 'x+b', 'c+b', 'rt', 'w+t',
        'r+t', 'x+t', 'c+t', 'a+');

    /**
     * Write modes
     *
     * @var string[]
     */
    protected static $writeModes = array('w', 'w+', 'rw', 'r+', 'x+', 'c+', 'wb', 'w+b', 'r+b', 'x+b', 'c+b', 'w+t',
        'r+t', 'x+t', 'c+t', 'a', 'a+');

    /**
     * Cache
     *
     * @var array|null
     */
    protected $cache;

    /**
     * Destroys the stream object
     */
    public function __destruct()
    {
        if (is_resource($this->getResource())) {
            $this->close();
        }
    }

    /**
     * Get resource
     *
     * @return resource
     */
    abstract public function getResource();

    /**
     * Clear stat cache
     *
     * @return void
     */
    public function clearStatCache()
    {
        clearstatcache();
    }

    /**
     * Retrieve information about the stream
     *
     * @param bool $clearCache True to clear cache before reading stats
     *
     * @throws Exception\StreamException
     * @throws Exception\InvalidArgumentException
     *
     * @return StreamStats
     */
    public function stats($clearCache = false)
    {
        if ($clearCache) {
            $this->clearStatCache();
        }
        $stats = fstat($this->getResource());
        if (false === $stats) {
            throw new Exception\StreamException('Unexpected result of stream operation');
        }
        return StreamStats::fromArray($stats);
    }

    /**
     * Clear metadata cache
     *
     * @return void
     */
    public function clearMetadataCache()
    {
        $this->cache = null;
    }

    /**
     * Retrieve metadata from the stream
     *
     * @param bool $clearCache True to clear cache
     *
     * @throws Exception\StreamException
     * @throws Exception\InvalidArgumentException
     *
     * @return mixed
     */
    public function metadata($clearCache = false)
    {
        if ($clearCache) {
            $this->clearMetadataCache();
        }
        if (null === $this->cache) {
            $this->cache = stream_get_meta_data($this->getResource());
            $this->cache['readable'] = in_array($this->cache['mode'], self::$readModes);
            $this->cache['writable'] = in_array($this->cache['mode'], self::$writeModes);
            $this->cache['local'] = stream_is_local($this->getResource());
        }
        return StreamMetadata::fromArray($this->cache);
    }

    /**
     * Returns the current position of the stream
     *
     * @throws Exception\StreamException
     *
     * @return int
     */
    public function tell()
    {
        $position = ftell($this->getResource());
        if (false === $position) {
            throw new Exception\StreamException('Unexpected result of stream operation');
        }
        return $position;
    }

    /**
     * Seeks and returns the position on the stream
     *
     * @param int $offset The offset
     * @param int $whence Either SEEK_SET (default), SEEK_CUR or SEEK_END
     *
     * @throws Exception\StreamException
     *
     * @return int
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (fseek($this->getResource(), $offset, $whence) < 0) {
            throw new Exception\StreamException('Unexpected result of stream operation');
        }
        return $this->tell();
    }

    /**
     * Rewind the position of the stream
     *
     * @throws Exception\StreamException
     *
     * @return int
     */
    public function rewind()
    {
        return $this->seek(0);
    }

    /**
     * Close the stream
     *
     * @return bool
     */
    public function close()
    {
        return fclose($this->getResource());
    }
}
