<?php

/**
 * This custom TreeGroupExclusionStrategy is customizable.
 * Groups and the max. depth can be passed into the constructor.
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */
namespace Wiechert\DataTablesBundle\EntityReflection\Strategies;


class SimpleStrategy extends TreeGroupExclusionStrategy
{

    /**
     * @return string[]
     */
    public function getGroups()
    {
        return array(array(parent::$idGruppe, parent::$simpleGruppe, parent::$delegationGruppe),
            array(parent::$idGruppe, parent::$simpleGruppe));
    }

    /**
     * @return int
     */
    public function getMaxDepth()
    {
        return 1;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return "Simple";
    }


}
