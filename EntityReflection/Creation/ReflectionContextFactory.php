<?php
/**
 * User: Tayfun Wiechert
 * Date: 13.08.13
 * Time: 19:18
 */

namespace Wiechert\DataTablesBundle\EntityReflection\Creation;


use Wiechert\DataTablesBundle\EntityReflection\ReflectionContext;
use Wiechert\DataTablesBundle\EntityReflection\Reflector\EntityClassReflector;
use Wiechert\DataTablesBundle\EntityReflection\Reflector\IEntityMemberReflector;

class ReflectionContextFactory implements IReflectionContextFactory{

    /**
     * @param $depth
     * @param EntityClassReflector $reflector
     * @param IEntityMemberReflector $rootReflector
     * @return \Wiechert\DataTablesBundle\EntityReflection\IReflectionContext
     */
    public function createReflectionContext($depth, EntityClassReflector $reflector, IEntityMemberReflector $rootReflector)
    {
        $reflectionContext = new ReflectionContext();
        $reflectionContext->setDepth($depth);
        $path = $rootReflector->getReflectionContext()->getPath().$rootReflector->getName().'.';
        $reflectionContext->setPath($path);
        $reflectionContext->setClassReflector($reflector);
        return $reflectionContext;
    }

    /**
     * @param $depth
     * @param EntityClassReflector $reflector
     * @return \Wiechert\DataTablesBundle\EntityReflection\IReflectionContext
     */
    public function createBaseReflectionContext($depth, EntityClassReflector $reflector)
    {
        $reflectionContext = new ReflectionContext();
        $reflectionContext->setDepth($depth);
        $reflectionContext->setClassReflector($reflector);
        return $reflectionContext;
    }


}