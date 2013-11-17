<?php

namespace Giosh94mhz\GeonamesBundle\Model;

/**
 * Hierarchy
 */
interface HierarchyLink
{
    /**
     * Get parent
     *
     * @return Toponym
     */
    public function getParent();

    /**
     * Get child
     *
     * @return Toponym
     */
    public function getChild();

    /**
     * Get type
     *
     * @return string
     */
    public function getType();
}
