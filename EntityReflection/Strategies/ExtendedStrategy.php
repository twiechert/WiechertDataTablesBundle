<?php

/**
 * An exemplary TreeGroupExclusionStrategy.
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */

namespace Wiechert\DataTablesBundle\EntityReflection\Strategies;


class ExtendedStrategy extends TreeGroupExclusionStrategy
{
    /**
     * @return string[]
     */
    public  function getGroups()
    {
        return array ( array(parent::$idGruppe, parent::$simpleGruppe, parent::$simpleReferenceGruppe, parent::$reflexiveReferenceGruppe ),
                      array(parent::$idGruppe, parent::$simpleGruppe,  parent::$simpleReferenceGruppe), array(parent::$idGruppe, parent::$simpleGruppe));
    }


    /**
     *
     * @return int
     */
    public function getMaxDepth()
    {
        return 3;
    }

    /**
     * Returns the name of the display strategy.
     *
     * @return string
     */
    public function getName()
    {
        return "Extended";
    }


}
