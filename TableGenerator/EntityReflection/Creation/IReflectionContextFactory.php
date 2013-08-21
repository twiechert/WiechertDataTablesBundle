<?php
/**
 * The factory creates a ReflectionContext.
 *
 * User: Tayfun Wiechert
 * Date: 13.08.13
 * Time: 19:16
 */

namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Creation;


use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\EntityClassReflector;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\IEntityMemberReflector;

interface IReflectionContextFactory
{

    /**
     * @param $depth
     * @param EntityClassReflector $classReflector
     * @param IEntityMemberReflector $rootReflector
     * @return \Wiechert\DataTablesBundle\TableGenerator\EntityReflection\IReflectionContext
     */
    public function createReflectionContext($depth, EntityClassReflector $classReflector, IEntityMemberReflector $rootReflector);

    /**
     * @param $depth
     * @param EntityClassReflector $classReflector
     * @return \Wiechert\DataTablesBundle\TableGenerator\EntityReflection\IReflectionContext
     */
    public function createBaseReflectionContext($depth, EntityClassReflector $classReflector);

}