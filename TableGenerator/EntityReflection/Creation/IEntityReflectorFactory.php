<?php
/**
 * The factory creates a EntiyReflector.
 *
 * User: Tayfun Wiechert
 * Date: 14.08.13
 * Time: 13:06
 */

namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Creation;


use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\IReflectionContext;

interface IEntityReflectorFactory
{

    /**
     * @param \Reflector $reflector a PHP-Reflector (ClassReflector, MethodReflector or PopertyReflector)
     * @param IReflectionContext $context
     * @return \Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\BaseEntityReflector
     */
    public function createEntityReflector(\Reflector $reflector, IReflectionContext $context = null);


}