<?php
/**
 * User: Tayfun Wiechert
 * Date: 13.08.13
 * Time: 19:18
 */

namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Creation;


use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\ReflectionContext;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\EntityClassReflector;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\IEntityMemberReflector;

class ReflectionContextFactory implements IReflectionContextFactory{

    /**
     * @param $depth
     * @param EntityClassReflector $reflector
     * @param IEntityMemberReflector $rootReflector
     * @return \Wiechert\DataTablesBundle\TableGenerator\EntityReflection\IReflectionContext
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
     * @return \Wiechert\DataTablesBundle\TableGenerator\EntityReflection\IReflectionContext
     */
    public function createBaseReflectionContext($depth, EntityClassReflector $reflector)
    {
        $reflectionContext = new ReflectionContext();
        $reflectionContext->setDepth($depth);
        $reflectionContext->setClassReflector($reflector);
        return $reflectionContext;
    }


}