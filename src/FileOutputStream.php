<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

use GravityMedia\Stream\Exception;
use SplFileInfo;

/**
 * File output stream
 *
 * @package GravityMedia\Stream
 */
class FileOutputStream extends OutputStream
{
    /**
     * @var SplFileInfo
     */
    protected $fileInfo;

    /**
     * Creates a stream object from file
     *
     * @param string|\SplFileInfo $file
     *
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($file)
    {
        if (!$file instanceof SplFileInfo) {
            $file = new SplFileInfo($file);
        }
        if (!$file->isFile() || !$file->isWritable()) {
            throw new Exception\InvalidArgumentException('Invalid file argument');
        }
        $this->fileInfo = $file;
        parent::__construct(fopen($file, 'wb'));
    }

    /**
     * Get file info
     *
     * @return SplFileInfo
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    /**
     * Clear stat cache
     *
     * @return void
     */
    public function clearStatCache()
    {
        clearstatcache(true, $this->getFileInfo());
    }
}
