<?php
/**
 * This file is part of the stream package
 *
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace GravityMedia\Stream;

use GravityMedia\Stream\Exception;

/**
 * Stream stats
 *
 * @package GravityMedia\Stream
 */
class StreamStats
{
    /**
     * Device number
     *
     * @var int
     */
    protected $deviceNumber;

    /**
     * Inode number
     *
     * @var int
     */
    protected $inodeNumber;

    /**
     * Inode protection mode
     *
     * @var int
     */
    protected $inodeProtectionMode;

    /**
     * Number of links
     *
     * @var int
     */
    protected $numberOfLinks;

    /**
     * User ID
     *
     * @var int
     */
    protected $userId;

    /**
     * Group ID
     *
     * @var int
     */
    protected $groupId;

    /**
     * Device type
     *
     * @var int
     */
    protected $deviceType;

    /**
     * Size in bytes
     *
     * @var int
     */
    protected $size;

    /**
     * Access time as timestamp
     *
     * @var int
     */
    protected $accessTime;

    /**
     * Modification time as timestamp
     *
     * @var int
     */
    protected $modificationTime;

    /**
     * Inode change time as time stamp
     *
     * @var int
     */
    protected $inodeChangeTime;

    /**
     * Block size
     *
     * @var int
     */
    protected $blockSize;

    /**
     * Number of allocated blocks
     *
     * @var int
     */
    protected $numberOfAllocatedBlocks;

    /**
     * Get device number
     *
     * @return int
     */
    public function getDeviceNumber()
    {
        return $this->deviceNumber;
    }

    /**
     * Set device number
     *
     * @param int $deviceNumber
     *
     * @return StreamStats
     */
    public function setDeviceNumber($deviceNumber)
    {
        $this->deviceNumber = $deviceNumber;
        return $this;
    }

    /**
     * Get inode number
     *
     * @return int|null
     */
    public function getInodeNumber()
    {
        return $this->inodeNumber;
    }

    /**
     * Set inode number
     *
     * @param int $inodeNumber
     *
     * @return StreamStats
     */
    public function setInodeNumber($inodeNumber)
    {
        $this->inodeNumber = $inodeNumber;
        return $this;
    }

    /**
     * Get inode protection mode
     *
     * @return int
     */
    public function getInodeProtectionMode()
    {
        return $this->inodeProtectionMode;
    }

    /**
     * Set inode protection mode
     *
     * @param int $inodeProtectionMode
     *
     * @return StreamStats
     */
    public function setInodeProtectionMode($inodeProtectionMode)
    {
        $this->inodeProtectionMode = $inodeProtectionMode;
        return $this;
    }

    /**
     * Get number of links
     *
     * @return int
     */
    public function getNumberOfLinks()
    {
        return $this->numberOfLinks;
    }

    /**
     * Set number of links
     *
     * @param int $numberOfLinks
     *
     * @return StreamStats
     */
    public function setNumberOfLinks($numberOfLinks)
    {
        $this->numberOfLinks = $numberOfLinks;
        return $this;
    }

    /**
     * Get user ID
     *
     * @return int|null
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set user ID
     *
     * @param int $userId
     *
     * @return StreamStats
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Get group ID
     *
     * @return int|null
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set group ID
     *
     * @param int $groupId
     *
     * @return StreamStats
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * Get device type
     *
     * @return int
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * Set device type
     *
     * @param int $deviceType
     *
     * @return StreamStats
     */
    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;
        return $this;
    }

    /**
     * Get size in bytes
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set size in bytes
     *
     * @param int $size
     *
     * @return StreamStats
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Get access time as Unix timestamp
     *
     * @return int
     */
    public function getAccessTime()
    {
        return $this->accessTime;
    }

    /**
     * Set access time as Unix timestamp
     *
     * @param int $accessTime
     *
     * @return StreamStats
     */
    public function setAccessTime($accessTime)
    {
        $this->accessTime = $accessTime;
        return $this;
    }

    /**
     * Get modification time as Unix timestamp
     *
     * @return int
     */
    public function getModificationTime()
    {
        return $this->modificationTime;
    }

    /**
     * Set modification time as Unix timestamp
     *
     * @param int $modificationTime
     *
     * @return StreamStats
     */
    public function setModificationTime($modificationTime)
    {
        $this->modificationTime = $modificationTime;
        return $this;
    }

    /**
     * Get inode change time as Unix timestamp
     *
     * @return int
     */
    public function getInodeChangeTime()
    {
        return $this->inodeChangeTime;
    }

    /**
     * Set inode change time as Unix timestamp
     *
     * @param int $inodeChangeTime
     *
     * @return StreamStats
     */
    public function setInodeChangeTime($inodeChangeTime)
    {
        $this->inodeChangeTime = $inodeChangeTime;
        return $this;
    }

    /**
     * Get block size
     *
     * @return int|null
     */
    public function getBlockSize()
    {
        return $this->blockSize;
    }

    /**
     * Set block size
     *
     * @param int $blockSize
     *
     * @return StreamStats
     */
    public function setBlockSize($blockSize)
    {
        $this->blockSize = $blockSize;
        return $this;
    }

    /**
     * Get number of allocated blocks
     *
     * @return int|null
     */
    public function getNumberOfAllocatedBlocks()
    {
        return $this->numberOfAllocatedBlocks;
    }

    /**
     * Set number of allocated blocks
     *
     * @param int $numberOfAllocatedBlocks
     *
     * @return StreamStats
     */
    public function setNumberOfAllocatedBlocks($numberOfAllocatedBlocks)
    {
        $this->numberOfAllocatedBlocks = $numberOfAllocatedBlocks;
        return $this;
    }

    /**
     * Create stream stats from array
     *
     * @param array $data
     *
     * @return StreamStats
     */
    public static function fromArray(array $data)
    {
        /** @var StreamStats $stats */
        $stats = new static();
        $stats
            ->setDeviceNumber($data['dev'])
            ->setInodeProtectionMode($data['mode'])
            ->setNumberOfLinks($data['nlink'])
            ->setDeviceType($data['rdev'])
            ->setSize($data['size'])
            ->setAccessTime($data['atime'])
            ->setModificationTime($data['mtime'])
            ->setInodeChangeTime($data['ctime']);
        if (0 !== $data['ino']) {
            $stats->setInodeNumber($data['ino']);
        }
        if (0 !== $data['uid']) {
            $stats->setUserId($data['uid']);
        }
        if (0 !== $data['gid']) {
            $stats->setGroupId($data['gid']);
        }
        if ($data['blksize'] > -1) {
            $stats->setBlockSize($data['blksize']);
        }
        if ($data['blocks'] > -1) {
            $stats->setNumberOfAllocatedBlocks($data['blocks']);
        }
        return $stats;
    }
}
