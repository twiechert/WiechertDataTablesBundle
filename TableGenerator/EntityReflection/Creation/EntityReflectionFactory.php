<?php
/**
 * User: Tayfun Wiechert
 * Date: 14.08.13
 * Time: 13:08
 * To change this template use File | Settings | File Templates.
 */

namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Creation;


use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\IReflectionContext;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reader\IAnnotationReader;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\EntityClassReflector;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\EntityMethodReflector;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\EntityPropertyReflector;

class EntityReflectionFactory implements IEntityReflectorFactory
{

    /**
     * @var null|\Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reader\IAnnotationReader
     */
    private $annotationReader = null;

    /**
     * @param IAnnotationReader $reader
     */
    public function __construct(IAnnotationReader $reader)
    {
        $this->annotationReader = $reader;
    }

    /**
     * @param \Reflector $reflector
     * @param IReflectionContext $context
     * @return \Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\BaseEntityReflector|EntityClassReflector|EntityMethodReflector|EntityPropertyReflector
     */
    public function createEntityReflector(\Reflector $reflector, IReflectionContext $context = null)
    {
        $entityReflector = null;
        if ($reflector instanceof \ReflectionClass) {
            $entityReflector = new EntityClassReflector($reflector, $this->annotationReader);

        } else if ($reflector instanceof \ReflectionMethod) {
            $entityReflector = new EntityMethodReflector($reflector, $this->annotationReader);

        } else {
            $entityReflector = new EntityPropertyReflector($reflector, $this->annotationReader);

        }

        if ($context != null && !$entityReflector instanceof EntityClassReflector) {
            $entityReflector->setReflectionContext($context);
        }

        return $entityReflector;

    }
}