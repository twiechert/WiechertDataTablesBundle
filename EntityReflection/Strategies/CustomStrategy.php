<?php

/**
 * An exemplary TreeGroupExclusionStrategy.
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */
namespace Wiechert\DataTablesBundle\EntityReflection\Strategies;

class CustomStrategy extends TreeGroupExclusionStrategy
{
    /**
     * @var array
     */
    private $groups = null;
    /**
     * @var int
     */
    private $depth = null;

    /**
     * @param array $groups
     * @param int $depth
     */
    public function __construct(array $groups, $depth)
    {
        $this->groups = $groups;
    }

    /**
     * @return string[]
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return int
     */
    public function getMaxDepth()
    {
        return $this->depth;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "Custom";
    }

}
