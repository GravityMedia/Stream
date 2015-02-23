<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest;

use GravityMedia\Stream\InputStream;

/**
 * Input stream test
 *
 * @package GravityMedia\StreamTest
 */
class InputStreamTest extends \PHPUnit_Framework_TestCase
{
    public function testRead()
    {
        $data = <<<EOF
Test
EOF;

        $resource = tmpfile();
        fwrite($resource, $data);
        fseek($resource, 0);

        $stream = new InputStream($resource);

        $this->assertEquals($data, $stream->read(1024));
    }
}
