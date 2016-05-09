<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\StreamTest\Reader;

use GravityMedia\Stream\ByteOrder\ByteOrder;

/**
 * Integer reader test
 *
 * @package GravityMedia\StreamTest\Reader
 *
 * @covers  GravityMedia\Stream\Reader\IntegerReader
 * @uses    GravityMedia\Stream\ByteOrder\ByteOrderAwareTrait
 */
class IntegerReaderTest extends \PHPUnit_Framework_TestCase
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
     * Test reading unsigned 8-bit character
     *
     * @dataProvider provideUnsignedInteger8Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadUnsignedInteger8($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();

        $readerMock->expects($this->once())
            ->method('read')
            ->with(1)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger8());
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
     * Test reading signed 8-bit character
     *
     * @dataProvider provideSignedInteger8Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadSignedInteger8($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();

        $readerMock->expects($this->once())
            ->method('read')
            ->with(1)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger8());
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
     * Test reading unsigned 16-bit integer with big endian byte order
     *
     * @dataProvider provideUnsignedInteger16BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadUnsignedInteger16BigEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(2)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger16());
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
     * Test reading unsigned 16-bit integer with little endian byte order
     *
     * @dataProvider provideUnsignedInteger16LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadUnsignedInteger16LittleEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(2)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger16());
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
     * Test reading unsigned 16-bit integer
     *
     * @dataProvider provideUnsignedInteger16Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadUnsignedInteger16($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(2)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger16());
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
     * Test reading signed 16-bit integer with big endian byte order
     *
     * @dataProvider provideSignedInteger16BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadSignedInteger16BigEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(2)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger16());
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
     * Test reading signed 16-bit integer with little endian byte order
     *
     * @dataProvider provideSignedInteger16LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadSignedInteger16LittleEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(2)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger16());
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
     * Test reading signed 16-bit integer
     *
     * @dataProvider provideSignedInteger16Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadSignedInteger16($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(2)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger16());
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
     * Test reading unsigned 24-bit integer with big endian byte order
     *
     * @dataProvider provideUnsignedInteger24BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadUnsignedInteger24BigEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(3)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger24());
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
     * Test reading unsigned 24-bit integer with little endian byte order
     *
     * @dataProvider provideUnsignedInteger24LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadUnsignedInteger24LittleEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(3)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger24());
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
     * Test reading unsigned 24-bit integer
     *
     * @dataProvider provideUnsignedInteger24Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadUnsignedInteger24($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(3)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger24());
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
     * Test reading signed 24-bit integer with big endian byte order
     *
     * @dataProvider provideSignedInteger24BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadSignedInteger24BigEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(3)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger24());
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
     * Test reading signed 24-bit integer with little endian byte order
     *
     * @dataProvider provideSignedInteger24LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadSignedInteger24LittleEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(3)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger24());
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
     * Test reading signed 24-bit integer
     *
     * @dataProvider provideSignedInteger24Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadSignedInteger24($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(3)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger24());
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
     * Test reading unsigned 32-bit integer with big endian byte order
     *
     * @dataProvider provideUnsignedInteger32BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadUnsignedInteger32BigEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(4)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger32());
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
     * Test reading unsigned 32-bit integer with little endian byte order
     *
     * @dataProvider provideUnsignedInteger32LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadUnsignedInteger32LittleEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(4)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger32());
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
     * Test reading unsigned 32-bit integer
     *
     * @dataProvider provideUnsignedInteger32Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadUnsignedInteger32($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(4)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger32());
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
     * Test reading signed 32-bit integer with big endian byte order
     *
     * @dataProvider provideSignedInteger32BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadSignedInteger32BigEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(4)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger32());
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
     * Test reading signed 32-bit integer with little endian byte order
     *
     * @dataProvider provideSignedInteger32LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadSignedInteger32LittleEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(4)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger32());
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
     * Test reading signed 32-bit integer
     *
     * @dataProvider provideSignedInteger32Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadSignedInteger32($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(4)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger32());
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
     * Test reading unsigned 64-bit integer with big endian byte order
     *
     * @dataProvider provideUnsignedInteger64BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadUnsignedInteger64BigEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(8)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger64());
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
     * Test reading unsigned 64-bit integer with little endian byte order
     *
     * @dataProvider provideUnsignedInteger64LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadUnsignedInteger64LittleEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(8)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger64());
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
     * Test reading unsigned 64-bit integer
     *
     * @dataProvider provideUnsignedInteger64Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadUnsignedInteger64($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(8)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger64());
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
     * Test reading signed 64-bit integer with big endian byte order
     *
     * @dataProvider provideSignedInteger64BigEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadSignedInteger64BigEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(8)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger64());
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
     * Test reading signed 64-bit integer with little endian byte order
     *
     * @dataProvider provideSignedInteger64LittleEndianValues()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadSignedInteger64LittleEndian($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(8)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger64());
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
     * Test reading signed 64-bit integer
     *
     * @dataProvider provideSignedInteger64Values()
     *
     * @param string $data
     * @param int    $value
     */
    public function testReadSignedInteger64($data, $value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        $readerMock->expects($this->once())
            ->method('read')
            ->with(8)
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger64());
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
