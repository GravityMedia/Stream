<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream\Reader;

use GravityMedia\Stream\Exception;

/**
 * Abstract integer reader
 *
 * @package GravityMedia\Stream\Reader
 */
abstract class AbstractIntegerReader extends AbstractReader
{
    /**
     * @var boolean
     */
    protected $signed;

    /**
     * Is signed
     *
     * @return boolean
     */
    public function isSigned()
    {
        if (null === $this->signed) {
            return false;
        }

        return $this->signed;
    }

    /**
     * Set signed
     *
     * @param boolean $signed
     *
     * @return $this
     */
    public function setSigned($signed)
    {
        $this->signed = $signed;
        return $this;
    }

    /**
     * Read integer data from the stream
     *
     * @return int
     */
    public function read()
    {
        if ($this->isSigned()) {
            return $this->readSigned();
        }

        return $this->readUnsigned();
    }

    /**
     * Read unsigned integer data from the stream
     *
     * @return int
     */
    abstract protected function readUnsigned();

    /**
     * Read signed integer data from the stream
     *
     * @return int
     */
    abstract protected function readSigned();
}
