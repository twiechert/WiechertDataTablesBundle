<?php

/**
 * This implementation of IExclusionStrategy can exlude properties and classes via the definition of groups
 * and a max. depth.
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */

namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Strategies;


use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\IReflectionContext;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\IEntityMemberReflector;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Strategies\BaseDisplayStrategy;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Strategies\IEntityReflector;

abstract class TreeGroupExclusionStrategy implements IExclusionStrategy
{

    const DEFAULT_GROUP = 'Default';
    protected static $idGruppe = "Id";
    protected static $superGruppe = "Super";
    protected static $simpleGruppe = "Simple";
    protected static $fullGruppe = "Full";
    protected static $noneGruppe = "None";
    protected static $delegationGruppe = "Delegation";
    protected static $slaveGruppe = "Slave";
    protected static $simpleReferenceGruppe = "SimpleReference";
    protected static $complexeReferenceGruppe = "ComplexeReference";
    protected static $reflexiveReferenceGruppe = "ReflexiveReference";

    /**
     * @param IReflectionContext $context
     * @return bool
     */
    public function shouldSkipClass(IReflectionContext $context)
    {
        return $context->getDepth() > $this->getMaxDepth();
    }

    /**
     * @param IEntityMemberReflector $entityReflector
     * @param IReflectionContext $context
     * @return bool
     */
    public function shouldSkipProperty(IEntityMemberReflector $entityReflector, IReflectionContext $context)
    {
        $groups = $this->normalizeGroups($this->getGroups());
        $inclusionGroups = $groups[$context->getDepth()];

        if (!$entityReflector->getGroups()) {
            return !isset($groups[self::DEFAULT_GROUP]);
        }

        foreach ($entityReflector->getGroups() as $group) {
            if (isset($inclusionGroups[$group])) {
                return false;
            }
        }

        return true;
    }


    /**
     * It is a multidimensional array, whereas the first level
     * iditentificates the reflection depth and the second level
     * those groups that are considered for reflection.
     *
     * @return string[][]
     */
    public abstract function getGroups();

    /**
     * If there are no definition for a certain reflection depth,
     * the default groups can be used.
     *
     * @return string[]
     */
    public function getDefaultGroups()
    {
        return array();
    }

    /**
     * Returns the max. reflection depth.
     *
     * @return int
     */
    public abstract function getMaxDepth();

    /**
     * @param array $groups
     * @return array
     */
    private function normalizeGroups(array $groups)
    {
        $newGroups = array();
        foreach ($groups as $hierachy => $grouphierachy) {
            foreach ($grouphierachy as $group) {
                $newGroups[$hierachy][$group] = true;
            }
        }

        return $newGroups;
    }


}