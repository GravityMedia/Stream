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
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        static::$machineByteOrder = ByteOrder::BIG_ENDIAN;

        list(, $value) = unpack('l', "\x01\x00\x00\x00");
        if (1 === $value) {
            static::$machineByteOrder = ByteOrder::LITTLE_ENDIAN;
        }
    }

    /**
     * Test reading unsigned 8-bit character
     *
     * @dataProvider provideUnsignedInteger8Values()
     *
     * @param int $value
     */
    public function testReadUnsignedInteger8($value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();

        $readerMock->expects($this->once())
            ->method('read')
            ->will($this->returnValue(pack('C', $value)));

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
            [0],
            [2 ** 8 - 1]
        ];
    }

    /**
     * Test reading signed 8-bit character
     *
     * @dataProvider provideSignedInteger8Values()
     *
     * @param int $value
     */
    public function testReadSignedInteger8($value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();

        $readerMock->expects($this->once())
            ->method('read')
            ->will($this->returnValue(pack('c', $value)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger8());
    }

    /**
     * Provide signed 8-bit character
     *
     * @return array
     */
    public function provideSignedInteger8Values()
    {
        return [
            [-2 ** 8 / 2],
            [0],
            [2 ** 8 / 2 - 1]
        ];
    }

    /**
     * Test reading unsigned 16-bit integer with big endian byte order
     *
     * @dataProvider provideUnsignedInteger16Values()
     *
     * @param int $value
     */
    public function testReadUnsignedInteger16BigEndian($value)
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
            ->will($this->returnValue(pack('n', $value)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger16());
    }

    /**
     * Test reading unsigned 16-bit integer with little endian byte order
     *
     * @dataProvider provideUnsignedInteger16Values()
     *
     * @param int $value
     */
    public function testReadUnsignedInteger16LittleEndian($value)
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
            ->will($this->returnValue(pack('v', $value)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger16());
    }

    /**
     * Test reading unsigned 16-bit integer
     *
     * @dataProvider provideUnsignedInteger16Values()
     *
     * @param int $value
     */
    public function testReadUnsignedInteger16($value)
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
            ->will($this->returnValue(pack('S', $value)));

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
        return [
            [0],
            [2 ** 16 - 1]
        ];
    }

    /**
     * Test reading signed 16-bit integer with big endian byte order
     *
     * @dataProvider provideSignedInteger16Values()
     *
     * @param int $value
     */
    public function testReadSignedInteger16BigEndian($value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $data = pack('s', $value);
        if (ByteOrder::BIG_ENDIAN !== self::$machineByteOrder) {
            $data = strrev($data);
        }

        $readerMock->expects($this->once())
            ->method('read')
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger16());
    }

    /**
     * Test reading signed 16-bit integer with little endian byte order
     *
     * @dataProvider provideSignedInteger16Values()
     *
     * @param int $value
     */
    public function testReadSignedInteger16LittleEndian($value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $data = pack('s', $value);
        if (ByteOrder::LITTLE_ENDIAN !== self::$machineByteOrder) {
            $data = strrev($data);
        }

        $readerMock->expects($this->once())
            ->method('read')
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger16());
    }

    /**
     * Test reading signed 16-bit integer
     *
     * @dataProvider provideSignedInteger16Values()
     *
     * @param int $value
     */
    public function testReadSignedInteger16($value)
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
            ->will($this->returnValue(pack('s', $value)));

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
        return [
            [-2 ** 16 / 2],
            [0],
            [2 ** 16 / 2 - 1]
        ];
    }

    /**
     * Test reading unsigned 24-bit integer with big endian byte order
     *
     * @dataProvider provideUnsignedInteger24Values()
     *
     * @param int $value
     */
    public function testReadUnsignedInteger24BigEndian($value)
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
            ->will($this->returnValue(pack('C3', $value >> 16, $value >> 8, $value)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger24());
    }

    /**
     * Test reading unsigned 24-bit integer with little endian byte order
     *
     * @dataProvider provideUnsignedInteger24Values()
     *
     * @param int $value
     */
    public function testReadUnsignedInteger24LittleEndian($value)
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
            ->will($this->returnValue(pack('C3', $value, $value >> 8, $value >> 16)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger24());
    }

    /**
     * Test reading unsigned 24-bit integer
     *
     * @dataProvider provideUnsignedInteger24Values()
     *
     * @param int $value
     */
    public function testReadUnsignedInteger24($value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        if (ByteOrder::LITTLE_ENDIAN === self::$machineByteOrder) {
            $data = pack('C3', $value, $value >> 8, $value >> 16);
        } else {
            $data = pack('C3', $value >> 16, $value >> 8, $value);
        }

        $readerMock->expects($this->once())
            ->method('read')
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger24());
    }

    /**
     * Provide unsigned 24-bit integer
     *
     * @return array
     */
    public function provideUnsignedInteger24Values()
    {
        return [
            [0],
            [2 ** 24 - 1]
        ];
    }

    /**
     * Test reading signed 24-bit integer with big endian byte order
     *
     * @dataProvider provideSignedInteger24Values()
     *
     * @param int $value
     */
    public function testReadSignedInteger24BigEndian($value)
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
            ->will($this->returnValue(pack('c3', $value >> 16, $value >> 8, $value)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger24());
    }

    /**
     * Test reading signed 24-bit integer with little endian byte order
     *
     * @dataProvider provideSignedInteger24Values()
     *
     * @param int $value
     */
    public function testReadSignedInteger24LittleEndian($value)
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
            ->will($this->returnValue(pack('c3', $value, $value >> 8, $value >> 16)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger24());
    }

    /**
     * Test reading signed 24-bit integer
     *
     * @dataProvider provideSignedInteger24Values()
     *
     * @param int $value
     */
    public function testReadSignedInteger24($value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::MACHINE_ENDIAN));

        if (ByteOrder::LITTLE_ENDIAN === self::$machineByteOrder) {
            $data = pack('c3', $value, $value >> 8, $value >> 16);
        } else {
            $data = pack('c3', $value >> 16, $value >> 8, $value);
        }

        $readerMock->expects($this->once())
            ->method('read')
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger24());
    }

    /**
     * Provide signed 24-bit integer
     *
     * @return array
     */
    public function provideSignedInteger24Values()
    {
        return [
            [-2 ** 24 / 2],
            [0],
            [2 ** 24 / 2 - 1]
        ];
    }

    /**
     * Test reading unsigned 32-bit integer with big endian byte order
     *
     * @dataProvider provideUnsignedInteger32Values()
     *
     * @param int $value
     */
    public function testReadUnsignedInteger32BigEndian($value)
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
            ->will($this->returnValue(pack('N', $value)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger32());
    }

    /**
     * Test reading unsigned 32-bit integer with little endian byte order
     *
     * @dataProvider provideUnsignedInteger32Values()
     *
     * @param int $value
     */
    public function testReadUnsignedInteger32LittleEndian($value)
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
            ->will($this->returnValue(pack('V', $value)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger32());
    }

    /**
     * Test reading unsigned 32-bit integer
     *
     * @dataProvider provideUnsignedInteger32Values()
     *
     * @param int $value
     */
    public function testReadUnsignedInteger32($value)
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
            ->will($this->returnValue(pack('L', $value)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger32());
    }

    /**
     * Provide unsigned 32-bit integer
     *
     * @return array
     */
    public function provideUnsignedInteger32Values()
    {
        return [
            [0],
            [2 ** 32 - 1]
        ];
    }

    /**
     * Test reading signed 32-bit integer with big endian byte order
     *
     * @dataProvider provideSignedInteger32Values()
     *
     * @param int $value
     */
    public function testReadSignedInteger32BigEndian($value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $data = pack('l', $value);
        if (ByteOrder::BIG_ENDIAN !== self::$machineByteOrder) {
            $data = strrev($data);
        }

        $readerMock->expects($this->once())
            ->method('read')
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger32());
    }

    /**
     * Test reading signed 32-bit integer with little endian byte order
     *
     * @dataProvider provideSignedInteger32Values()
     *
     * @param int $value
     */
    public function testReadSignedInteger32LittleEndian($value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $data = pack('l', $value);
        if (ByteOrder::LITTLE_ENDIAN !== self::$machineByteOrder) {
            $data = strrev($data);
        }

        $readerMock->expects($this->once())
            ->method('read')
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger32());
    }

    /**
     * Test reading signed 32-bit integer
     *
     * @dataProvider provideSignedInteger32Values()
     *
     * @param int $value
     */
    public function testReadSignedInteger32($value)
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
            ->will($this->returnValue(pack('l', $value)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger32());
    }

    /**
     * Provide signed 32-bit integer
     *
     * @return array
     */
    public function provideSignedInteger32Values()
    {
        return [
            [-2 ** 32 / 2],
            [0],
            [2 ** 32 / 2 - 1]
        ];
    }

    /**
     * Test reading unsigned 64-bit integer with big endian byte order
     *
     * @dataProvider provideUnsignedInteger64Values()
     *
     * @param int $value
     */
    public function testReadUnsignedInteger64BigEndian($value)
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
            ->will($this->returnValue(pack('J', $value)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger64());
    }

    /**
     * Test reading unsigned 64-bit integer with little endian byte order
     *
     * @dataProvider provideUnsignedInteger64Values()
     *
     * @param int $value
     */
    public function testReadUnsignedInteger64LittleEndian($value)
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
            ->will($this->returnValue(pack('P', $value)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger64());
    }

    /**
     * Test reading unsigned 64-bit integer
     *
     * @dataProvider provideUnsignedInteger64Values()
     *
     * @param int $value
     */
    public function testReadUnsignedInteger64($value)
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
            ->will($this->returnValue(pack('Q', $value)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readUnsignedInteger64());
    }

    /**
     * Provide unsigned 64-bit integer
     *
     * @return array
     */
    public function provideUnsignedInteger64Values()
    {
        return [
            [0],
            [2 ** 64 - 1]
        ];
    }

    /**
     * Test reading signed 64-bit integer with big endian byte order
     *
     * @dataProvider provideSignedInteger64Values()
     *
     * @param int $value
     */
    public function testReadSignedInteger64BigEndian($value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::BIG_ENDIAN));

        $data = pack('q', $value);
        if (ByteOrder::BIG_ENDIAN !== self::$machineByteOrder) {
            $data = strrev($data);
        }

        $readerMock->expects($this->once())
            ->method('read')
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger64());
    }

    /**
     * Test reading signed 64-bit integer with little endian byte order
     *
     * @dataProvider provideSignedInteger64Values()
     *
     * @param int $value
     */
    public function testReadSignedInteger64LittleEndian($value)
    {
        $readerMock = $this->getMockBuilder('GravityMedia\Stream\Reader\IntegerReader')
            ->disableOriginalConstructor()
            ->setMethods(['getByteOrder', 'read'])
            ->getMock();

        $readerMock->expects($this->atLeast(1))
            ->method('getByteOrder')
            ->will($this->returnValue(ByteOrder::LITTLE_ENDIAN));

        $data = pack('q', $value);
        if (ByteOrder::LITTLE_ENDIAN !== self::$machineByteOrder) {
            $data = strrev($data);
        }

        $readerMock->expects($this->once())
            ->method('read')
            ->will($this->returnValue($data));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger64());
    }

    /**
     * Test reading signed 64-bit integer
     *
     * @dataProvider provideSignedInteger64Values()
     *
     * @param int $value
     */
    public function testReadSignedInteger64($value)
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
            ->will($this->returnValue(pack('q', $value)));

        /** @var \GravityMedia\Stream\Reader\IntegerReader $readerMock */
        $this->assertSame($value, $readerMock->readSignedInteger64());
    }

    /**
     * Provide signed 64-bit integer
     *
     * @return array
     */
    public function provideSignedInteger64Values()
    {
        return [
            [-2 ** 64 / 2],
            [0],
            [2 ** 64 / 2 - 1]
        ];
    }
}
