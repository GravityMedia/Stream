<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest;

use GravityMedia\Uri\Uri;

/**
 * Stream test case
 *
 * @package GravityMedia\StreamTest
 */
class StreamTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Create temp file and return its URI
     *
     * @return \GravityMedia\Uri\Uri
     */
    protected function createTempFile()
    {
        return Uri::fromString('file://' . tempnam(sys_get_temp_dir(), strtoupper(uniqid())));
    }

    /**
     * Create random data
     *
     * @param int $length
     *
     * @return string
     */
    public function createRandomData($length)
    {
        $data = '';
        $dictionary = range("\x00", "\x7f");
        $max = count($dictionary) - 1;
        while (0 <= --$length) {
            $data .= $dictionary[mt_rand(0, $max)];
        }
        return $data;
    }
}
