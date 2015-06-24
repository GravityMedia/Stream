<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest\Util;

/**
 * Test stream wrapper
 *
 * @package GravityMedia\StreamTest
 */
class TestStreamWrapper
{
    function stream_open()
    {
        return true;
    }

    function stream_eof()
    {
        return false;
    }
}
