<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest\Helper;

/**
 * Resource helper class
 *
 * @package GravityMedia\StreamTest\Helper
 */
class ResourceHelper
{
    /**
     * The URI of the resource
     */
    const RESOURCE_URI = 'php://temp';

    /**
     * The read/write-mode of the resource
     */
    const RESOURCE_MODE = 'r+';

    /**
     * @var resource
     */
    protected $resource;

    /**
     * Create resource helper object
     *
     * @param string $uri
     * @param string $mode
     */
    public function __construct($uri = self::RESOURCE_URI, $mode = self::RESOURCE_MODE)
    {
        $this->resource = fopen($uri, $mode);
    }

    /**
     * Destroy resource helper object
     */
    public function __destruct()
    {
        @fclose($this->resource);
    }

    /**
     * Get resource
     *
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }
}
