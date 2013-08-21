<?php
/**
 * User: Tayfun Wiechert
 * Date: 14.08.13
 * Time: 18:37
 */

namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector;


use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reader\IAnnotationReader;

class EntityMethodReflector extends EntityMemberReflector
{

    /**
     * @param \ReflectionMethod $reflector
     * @param IAnnotationReader $reader
     */
    public function __construct(\ReflectionMethod $reflector, IAnnotationReader $reader)
    {
        parent::__construct($reflector, $reader);
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        if ($this->name == null) {
            $name = $this->reflector->getName();
            $name = strtolower($name{3}) . substr($name, 4);
            $this->name = $name;

        }
        return $this->name;
    }

    /**
     * @param $object
     * @return mixed
     */
    public function getValue($object)
    {
        return $this->getReflector()->invoke($object);
    }


}