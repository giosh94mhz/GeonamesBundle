<?php

namespace Giosh94mhz\GeonamesBundle\Entity;

use Giosh94mhz\GeonamesBundle\Model\HierarchyLink as HierarchyLinkInterface;

/**
 * Hierarchy
 */
class HierarchyLink implements HierarchyLinkInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var Toponym
     */
    private $parent;

    /**
     * @var Toponym
     */
    private $child;

    public function __construct(Toponym $parent, Toponym $child)
    {
        $this->parent = $parent;
        $this->child = $child;
    }

    /**
     * Get parent
     *
     * @return Toponym
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get child
     *
     * @return Toponym
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Set type
     *
     * @param  string    $type
     * @return Hierarchy
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
