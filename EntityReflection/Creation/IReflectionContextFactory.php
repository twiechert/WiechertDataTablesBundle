<?php
/**
 * The factory creates a ReflectionContext.
 *
 * User: Tayfun Wiechert
 * Date: 13.08.13
 * Time: 19:16
 */

namespace Wiechert\DataTablesBundle\EntityReflection\Creation;


use Wiechert\DataTablesBundle\EntityReflection\Reflector\EntityClassReflector;
use Wiechert\DataTablesBundle\EntityReflection\Reflector\IEntityMemberReflector;

interface IReflectionContextFactory
{

    /**
     * @param $depth
     * @param EntityClassReflector $classReflector
     * @param IEntityMemberReflector $rootReflector
     * @return \Wiechert\DataTablesBundle\EntityReflection\IReflectionContext
     */
    public function createReflectionContext($depth, EntityClassReflector $classReflector, IEntityMemberReflector $rootReflector);

    /**
     * @param $depth
     * @param EntityClassReflector $classReflector
     * @return \Wiechert\DataTablesBundle\EntityReflection\IReflectionContext
     */
    public function createBaseReflectionContext($depth, EntityClassReflector $classReflector);

}