<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest\Writer;

use GravityMedia\Stream\ByteOrder\ByteOrder;

/**
 * Integer writer test
 *
 * @package GravityMedia\StreamTest\Writer
 *
 * @covers  GravityMedia\Stream\Writer\IntegerWriter
 * @uses    GravityMedia\Stream\ByteOrder\ByteOrderAwareTrait
 */
class IntegerWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var int
     */
    protected static $machineByteOrder;

    /**
     * Get machine byte order
     *
     * @return int
     */
    public function getMachineByteOrder()
    {
        if (null === static::$machineByteOrder) {
            static::$machineByteOrder = ByteOrder::BIG_ENDIAN;

            list(, $value) = unpack('s', "\x01\x00");
            if (1 === $value) {
                static::$machineByteOrder = ByteOrder::LITTLE_ENDIAN;
            }
        }

        return static::$machineByteOrder;
    }

    /**
     * Test writing unsigned 8-bit character
     *
     * @dataProvider provideUnsignedInteger8Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteUnsignedInteger8($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['write'])
            ->getMock();

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(1));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(1, $writerMock->writeUnsignedInteger8($value));
    }

    /**
     * Provide unsigned 8-bit characters
     *
     * @return array
     */
    public function provideUnsignedInteger8Values()
    {
        return [
            ["\x00", 0],
            ["\x7f", 127],
            ["\x80", 128],
            ["\xff", 255]
        ];
    }

    /**
     * Test writing signed 8-bit character
     *
     * @dataProvider provideSignedInteger8Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteSignedInteger8($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['write'])
            ->getMock();

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(1));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(1, $writerMock->writeSignedInteger8($value));
    }

    /**
     * Provide signed 8-bit characters
     *
     * @return array
     */
    public function provideSignedInteger8Values()
    {
        return [
            ["\x80", -128],
            ["\x00", 0],
            ["\x7f", 127]
        ];
    }


    /**
     * Test writing unsigned 16-bit integer with big endian byte order
     *
     * @dataProvider provideUnsignedInteger16BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteUnsignedInteger16BigEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(2));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(2, $writerMock->writeUnsignedInteger16($value));
    }

    /**
     * Provide unsigned 16-bit integers with big endian byte order
     *
     * @return array
     */
    public function provideUnsignedInteger16BigEndianValues()
    {
        return [
            ["\x00\x00", 0],
            ["\x00\x01", 1],
            ["\x00\xff", 255],
            ["\xff\xfe", 65534],
            ["\xff\xff", 65535]
        ];
    }

    /**
     * Test writing unsigned 16-bit integer with little endian byte order
     *
     * @dataProvider provideUnsignedInteger16LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteUnsignedInteger16LittleEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(2));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(2, $writerMock->writeUnsignedInteger16($value));
    }

    /**
     * Provide unsigned 16-bit integers with little endian byte order
     *
     * @return array
     */
    public function provideUnsignedInteger16LittleEndianValues()
    {
        return [
            ["\x00\x00", 0],
            ["\x01\x00", 1],
            ["\xff\x00", 255],
            ["\xfe\xff", 65534],
            ["\xff\xff", 65535]
        ];
    }

    /**
     * Test writing unsigned 16-bit integer
     *
     * @dataProvider provideUnsignedInteger16Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteUnsignedInteger16($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(2));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(2, $writerMock->writeUnsignedInteger16($value));
    }

    /**
     * Provide unsigned 16-bit integers
     *
     * @return array
     */
    public function provideUnsignedInteger16Values()
    {
        if (ByteOrder::LITTLE_ENDIAN === $this->getMachineByteOrder()) {
            return [
                ["\x00\x00", 0],
                ["\x01\x00", 1],
                ["\xff\x00", 255],
                ["\xfe\xff", 65534],
                ["\xff\xff", 65535]
            ];
        }

        return [
            ["\x00\x00", 0],
            ["\x00\x01", 1],
            ["\x00\xff", 255],
            ["\xff\xfe", 65534],
            ["\xff\xff", 65535]
        ];
    }

    /**
     * Test writing signed 16-bit integer with big endian byte order
     *
     * @dataProvider provideSignedInteger16BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteSignedInteger16BigEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(2));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(2, $writerMock->writeSignedInteger16($value));
    }

    /**
     * Provide signed 16-bit integers with big endian byte order
     *
     * @return array
     */
    public function provideSignedInteger16BigEndianValues()
    {
        return [
            ["\x80\x00", -32768],
            ["\x00\x00", 0],
            ["\x7f\xff", 32767]
        ];
    }

    /**
     * Test writing signed 16-bit integer with little endian byte order
     *
     * @dataProvider provideSignedInteger16LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteSignedInteger16LittleEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(2));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(2, $writerMock->writeSignedInteger16($value));
    }

    /**
     * Provide signed 16-bit integers with little endian byte order
     *
     * @return array
     */
    public function provideSignedInteger16LittleEndianValues()
    {
        return [
            ["\x00\x80", -32768],
            ["\x00\x00", 0],
            ["\xff\x7f", 32767]
        ];
    }

    /**
     * Test writing signed 16-bit integer
     *
     * @dataProvider provideSignedInteger16Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteSignedInteger16($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(2));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(2, $writerMock->writeSignedInteger16($value));
    }

    /**
     * Provide signed 16-bit integers
     *
     * @return array
     */
    public function provideSignedInteger16Values()
    {
        if (ByteOrder::LITTLE_ENDIAN === $this->getMachineByteOrder()) {
            return [
                ["\x00\x80", -32768],
                ["\x00\x00", 0],
                ["\xff\x7f", 32767]
            ];
        }

        return [
            ["\x80\x00", -32768],
            ["\x00\x00", 0],
            ["\x7f\xff", 32767]
        ];
    }

    /**
     * Test writing unsigned 24-bit integer with big endian byte order
     *
     * @dataProvider provideUnsignedInteger24BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteUnsignedInteger24BigEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(3));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(3, $writerMock->writeUnsignedInteger24($value));
    }

    /**
     * Provide unsigned 24-bit integers with big endian byte order
     *
     * @return array
     */
    public function provideUnsignedInteger24BigEndianValues()
    {
        return [
            ["\x00\x00\x00", 0],
            ["\x00\x00\x01", 1],
            ["\x00\x00\xff", 255],
            ["\xff\xff\xff", 16777215]
        ];
    }

    /**
     * Test writing unsigned 24-bit integer with little endian byte order
     *
     * @dataProvider provideUnsignedInteger24LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteUnsignedInteger24LittleEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(3));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(3, $writerMock->writeUnsignedInteger24($value));
    }

    /**
     * Provide unsigned 24-bit integers with little endian byte order
     *
     * @return array
     */
    public function provideUnsignedInteger24LittleEndianValues()
    {
        return [
            ["\x00\x00\x00", 0],
            ["\x01\x00\x00", 1],
            ["\xff\x00\x00", 255],
            ["\xff\xff\xff", 16777215]
        ];
    }

    /**
     * Test writing unsigned 24-bit integer
     *
     * @dataProvider provideUnsignedInteger24Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteUnsignedInteger24($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(3));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(3, $writerMock->writeUnsignedInteger24($value));
    }

    /**
     * Provide unsigned 24-bit integers
     *
     * @return array
     */
    public function provideUnsignedInteger24Values()
    {
        if (ByteOrder::LITTLE_ENDIAN === $this->getMachineByteOrder()) {
            return [
                ["\x00\x00\x00", 0],
                ["\x01\x00\x00", 1],
                ["\xff\x00\x00", 255],
                ["\xff\xff\xff", 16777215]
            ];
        }

        return [
            ["\x00\x00\x00", 0],
            ["\x00\x00\x01", 1],
            ["\x00\x00\xff", 255],
            ["\xff\xff\xff", 16777215]
        ];
    }

    /**
     * Test writing signed 24-bit integer with big endian byte order
     *
     * @dataProvider provideSignedInteger24BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteSignedInteger24BigEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(3));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(3, $writerMock->writeSignedInteger24($value));
    }

    /**
     * Provide signed 24-bit integers with big endian byte order
     *
     * @return array
     */
    public function provideSignedInteger24BigEndianValues()
    {
        return [
            ["\x80\x00\x00", -8388608],
            ["\x00\x00\x00", 0],
            ["\x7f\xff\xff", 8388607]
        ];
    }

    /**
     * Test writing signed 24-bit integer with little endian byte order
     *
     * @dataProvider provideSignedInteger24LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteSignedInteger24LittleEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(3));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(3, $writerMock->writeSignedInteger24($value));
    }

    /**
     * Provide signed 24-bit integers with little endian byte order
     *
     * @return array
     */
    public function provideSignedInteger24LittleEndianValues()
    {
        return [
            ["\x00\x00\x80", -8388608],
            ["\x00\x00\x00", 0],
            ["\xff\xff\x7f", 8388607]
        ];
    }

    /**
     * Test writing signed 24-bit integer
     *
     * @dataProvider provideSignedInteger24Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteSignedInteger24($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(3));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(3, $writerMock->writeSignedInteger24($value));
    }

    /**
     * Provide signed 24-bit integers
     *
     * @return array
     */
    public function provideSignedInteger24Values()
    {
        if (ByteOrder::LITTLE_ENDIAN === $this->getMachineByteOrder()) {
            return [
                ["\x00\x00\x80", -8388608],
                ["\x00\x00\x00", 0],
                ["\xff\xff\x7f", 8388607]
            ];
        }

        return [
            ["\x80\x00\x00", -8388608],
            ["\x00\x00\x00", 0],
            ["\x7f\xff\xff", 8388607]
        ];
    }

    /**
     * Test writing unsigned 32-bit integer with big endian byte order
     *
     * @dataProvider provideUnsignedInteger32BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteUnsignedInteger32BigEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(4));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(4, $writerMock->writeUnsignedInteger32($value));
    }

    /**
     * Provide unsigned 32-bit integers with big endian byte order
     *
     * @return array
     */
    public function provideUnsignedInteger32BigEndianValues()
    {
        return [
            ["\x00\x00\x00\x00", 0],
            ["\x00\x00\x00\x01", 1],
            ["\x00\x00\x00\xff", 255],
            ["\xff\xff\xff\xff", 4294967295]
        ];
    }

    /**
     * Test writing unsigned 32-bit integer with little endian byte order
     *
     * @dataProvider provideUnsignedInteger32LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteUnsignedInteger32LittleEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(4));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(4, $writerMock->writeUnsignedInteger32($value));
    }

    /**
     * Provide unsigned 32-bit integers with little endian byte order
     *
     * @return array
     */
    public function provideUnsignedInteger32LittleEndianValues()
    {
        return [
            ["\x00\x00\x00\x00", 0],
            ["\x01\x00\x00\x00", 1],
            ["\xff\x00\x00\x00", 255],
            ["\xff\xff\xff\xff", 4294967295]
        ];
    }

    /**
     * Test writing unsigned 32-bit integer
     *
     * @dataProvider provideUnsignedInteger32Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteUnsignedInteger32($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(4));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(4, $writerMock->writeUnsignedInteger32($value));
    }

    /**
     * Provide unsigned 32-bit integers
     *
     * @return array
     */
    public function provideUnsignedInteger32Values()
    {
        if (ByteOrder::LITTLE_ENDIAN === $this->getMachineByteOrder()) {
            return [
                ["\x00\x00\x00\x00", 0],
                ["\x01\x00\x00\x00", 1],
                ["\xff\x00\x00\x00", 255],
                ["\xff\xff\xff\xff", 4294967295]
            ];
        }

        return [
            ["\x00\x00\x00\x00", 0],
            ["\x00\x00\x00\x01", 1],
            ["\x00\x00\x00\xff", 255],
            ["\xff\xff\xff\xff", 4294967295]
        ];
    }

    /**
     * Test writing signed 32-bit integer with big endian byte order
     *
     * @dataProvider provideSignedInteger32BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteSignedInteger32BigEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(4));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(4, $writerMock->writeSignedInteger32($value));
    }

    /**
     * Provide signed 32-bit integers with big endian byte order
     *
     * @return array
     */
    public function provideSignedInteger32BigEndianValues()
    {
        return [
            ["\x80\x00\x00\x00", -2147483648],
            ["\x00\x00\x00\x00", 0],
            ["\x7f\xff\xff\xff", 2147483647]
        ];
    }

    /**
     * Test writing signed 32-bit integer with little endian byte order
     *
     * @dataProvider provideSignedInteger32LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteSignedInteger32LittleEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(4));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(4, $writerMock->writeSignedInteger32($value));
    }

    /**
     * Provide signed 32-bit integers with little endian byte order
     *
     * @return array
     */
    public function provideSignedInteger32LittleEndianValues()
    {
        return [
            ["\x00\x00\x00\x80", -2147483648],
            ["\x00\x00\x00\x00", 0],
            ["\xff\xff\xff\x7f", 2147483647]
        ];
    }

    /**
     * Test writing signed 32-bit integer
     *
     * @dataProvider provideSignedInteger32Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteSignedInteger32($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(4));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(4, $writerMock->writeSignedInteger32($value));
    }

    /**
     * Provide signed 32-bit integers
     *
     * @return array
     */
    public function provideSignedInteger32Values()
    {
        if (ByteOrder::LITTLE_ENDIAN === $this->getMachineByteOrder()) {
            return [
                ["\x00\x00\x00\x80", -2147483648],
                ["\x00\x00\x00\x00", 0],
                ["\xff\xff\xff\x7f", 2147483647]
            ];
        }

        return [
            ["\x80\x00\x00\x00", -2147483648],
            ["\x00\x00\x00\x00", 0],
            ["\x7f\xff\xff\xff", 2147483647]
        ];
    }

    /**
     * Test writing unsigned 64-bit integer with big endian byte order
     *
     * @dataProvider provideUnsignedInteger64BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteUnsignedInteger64BigEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(8));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(8, $writerMock->writeUnsignedInteger64($value));
    }

    /**
     * Provide unsigned 64-bit integers with big endian byte order
     *
     * @return array
     */
    public function provideUnsignedInteger64BigEndianValues()
    {
        return [
            ["\x00\x00\x00\x00\x00\x00\x00\x00", 0],
            ["\x00\x00\x00\x00\x00\x00\x00\x01", 1],
            ["\x00\x00\x00\x00\x00\x00\x00\xff", 255],
            ["\x7f\xff\xff\xff\xff\xff\xff\xff", 9223372036854775807]
        ];
    }

    /**
     * Test writing unsigned 64-bit integer with little endian byte order
     *
     * @dataProvider provideUnsignedInteger64LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteUnsignedInteger64LittleEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(8));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(8, $writerMock->writeUnsignedInteger64($value));
    }

    /**
     * Provide unsigned 64-bit integers with little endian byte order
     *
     * @return array
     */
    public function provideUnsignedInteger64LittleEndianValues()
    {
        return [
            ["\x00\x00\x00\x00\x00\x00\x00\x00", 0],
            ["\x01\x00\x00\x00\x00\x00\x00\x00", 1],
            ["\xff\x00\x00\x00\x00\x00\x00\x00", 255],
            ["\xff\xff\xff\xff\xff\xff\xff\x7f", 9223372036854775807]
        ];
    }

    /**
     * Test writing unsigned 64-bit integer
     *
     * @dataProvider provideUnsignedInteger64Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteUnsignedInteger64($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(8));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(8, $writerMock->writeUnsignedInteger64($value));
    }

    /**
     * Provide unsigned 64-bit integers
     *
     * @return array
     */
    public function provideUnsignedInteger64Values()
    {
        if (ByteOrder::LITTLE_ENDIAN === $this->getMachineByteOrder()) {
            return [
                ["\x00\x00\x00\x00\x00\x00\x00\x00", 0],
                ["\x01\x00\x00\x00\x00\x00\x00\x00", 1],
                ["\xff\x00\x00\x00\x00\x00\x00\x00", 255],
                ["\xff\xff\xff\xff\xff\xff\xff\x7f", 9223372036854775807]
            ];
        }

        return [
            ["\x00\x00\x00\x00\x00\x00\x00\x00", 0],
            ["\x00\x00\x00\x00\x00\x00\x00\x01", 1],
            ["\x00\x00\x00\x00\x00\x00\x00\xff", 255],
            ["\x7f\xff\xff\xff\xff\xff\xff\xff", 9223372036854775807]
        ];
    }

    /**
     * Test writing signed 64-bit integer with big endian byte order
     *
     * @dataProvider provideSignedInteger64BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteSignedInteger64BigEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(8));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(8, $writerMock->writeSignedInteger64($value));
    }

    /**
     * Provide signed 64-bit integers with big endian byte order
     *
     * @return array
     */
    public function provideSignedInteger64BigEndianValues()
    {
        return [
            ["\x80\x00\x00\x00\x00\x00\x00\x01", -9223372036854775807],
            ["\x00\x00\x00\x00\x00\x00\x00\x00", 0],
            ["\x7f\xff\xff\xff\xff\xff\xff\xff", 9223372036854775807]
        ];
    }

    /**
     * Test writing signed 64-bit integer with little endian byte order
     *
     * @dataProvider provideSignedInteger64LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteSignedInteger64LittleEndian($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(8));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(8, $writerMock->writeSignedInteger64($value));
    }

    /**
     * Provide signed 64-bit integers with little endian byte order
     *
     * @return array
     */
    public function provideSignedInteger64LittleEndianValues()
    {
        return [
            ["\x01\x00\x00\x00\x00\x00\x00\x80", -9223372036854775807],
            ["\x00\x00\x00\x00\x00\x00\x00\x00", 0],
            ["\xff\xff\xff\xff\xff\xff\xff\x7f", 9223372036854775807]
        ];
    }

    /**
     * Test writing signed 64-bit integer
     *
     * @dataProvider provideSignedInteger64Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testWriteSignedInteger64($data, $value)
    {
        $writerMock = $this->getMockBuilder('GravityMedia\Stream\Writer\IntegerWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'write'])
            ->getMock();

        $writerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $writerMock->expects($this->once())
            ->method('write')
            ->with($data)
            ->will($this->returnValue(8));

        /** @var \GravityMedia\Stream\Writer\IntegerWriter $writerMock */
        $this->assertSame(8, $writerMock->writeSignedInteger64($value));
    }

    /**
     * Provide signed 64-bit integers
     *
     * @return array
     */
    public function provideSignedInteger64Values()
    {
        if (ByteOrder::LITTLE_ENDIAN === $this->getMachineByteOrder()) {
            return [
                ["\x01\x00\x00\x00\x00\x00\x00\x80", -9223372036854775807],
                ["\x00\x00\x00\x00\x00\x00\x00\x00", 0],
                ["\xff\xff\xff\xff\xff\xff\xff\x7f", 9223372036854775807]
            ];
        }

        return [
            ["\x80\x00\x00\x00\x00\x00\x00\x01", -9223372036854775807],
            ["\x00\x00\x00\x00\x00\x00\x00\x00", 0],
            ["\x7f\xff\xff\xff\xff\xff\xff\xff", 9223372036854775807]
        ];
    }
}
