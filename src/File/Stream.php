<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream\File;

use GravityMedia\Stream\Exception;
use GravityMedia\Stream\InputStream;
use GravityMedia\Stream\OutputStream;
use GravityMedia\Uri\Uri;

/**
 * File stream
 *
 * @package GravityMedia\Stream\File
 */
class Stream
{
    /**
     * @var \GravityMedia\Uri\Uri
     */
    protected $uri;

    /**
     * @var \GravityMedia\Stream\InputStream
     */
    protected $inputStream;

    /**
     * @var \GravityMedia\Stream\OutputStream
     */
    protected $outputStream;

    /**
     * Create file stream
     *
     * @param \GravityMedia\Uri\Uri $uri
     */
    public function __construct(Uri $uri)
    {
        if ('file' !== $uri->getScheme()) {
            throw new Exception\InvalidArgumentException('Invalid URI argument');
        }
        $this->uri = $uri;
    }

    /**
     * Get input stream
     *
     * @return \GravityMedia\Stream\InputStream
     */
    public function getInputStream()
    {
        if (null === $this->inputStream) {
            $this->inputStream = new InputStream($this->uri);
        }
        return $this->inputStream;
    }

    /**
     * Get output stream
     *
     * @return \GravityMedia\Stream\OutputStream
     */
    public function getOutputStream()
    {
        if (null === $this->outputStream) {
            $this->outputStream = new OutputStream($this->uri);
        }
        return $this->outputStream;
    }
}
