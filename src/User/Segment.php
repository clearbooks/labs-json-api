<?php
namespace Clearbooks\LabsApi\User;

class Segment implements \Clearbooks\Labs\Client\Toggle\Entity\Segment
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $priority;

    /**
     * @var bool
     */
    private $locked;

    /**
     * @param string $id
     * @param int $priority
     * @param bool $locked
     */
    public function __construct( $id, $priority, $locked )
    {
        $this->id = $id;
        $this->priority = $priority;
        $this->locked = $locked;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return boolean
     */
    public function isLocked()
    {
        return $this->locked;
    }
}
