<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest;

/**
 * Stream test case
 *
 * @package GravityMedia\StreamTest
 */
class StreamTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Create file
     *
     * @param string|null $data
     *
     * @return string
     */
    protected function createFile($data = null)
    {
        $filename = tempnam(sys_get_temp_dir(), strtoupper(uniqid()));
        if (null !== $data) {
            file_put_contents($filename, $data);
        }
        return $filename;
    }

    /**
     * Create resource
     *
     * @param string|null $data
     *
     * @return resource
     */
    protected function createResource($data = null)
    {
        $resource = tmpfile();
        if (null !== $data) {
            fwrite($resource, $data);
            fseek($resource, 0);
        }
        return $resource;
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
