<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest;

use League\Url\Url;

/**
 * Stream test case
 *
 * @package GravityMedia\StreamTest
 */
class StreamTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Create test file
     *
     * @param string|null $data
     *
     * @return Url
     */
    protected function createFile($data = null)
    {
        $filename = tempnam(sys_get_temp_dir(), strtoupper(uniqid()));
        if (null !== $data) {
            file_put_contents($filename, $data);
        }
        return Url::createFromUrl('file://' . $filename);
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
