<?php
/**
 * User: Tayfun Wiechert
 * Date: 14.08.13
 * Time: 18:37
 */

namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector;


use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reader\IAnnotationReader;

class EntityPropertyReflector extends EntityMemberReflector
{

    /**
     * @param \ReflectionProperty $reflector
     * @param IAnnotationReader $reader
     */
    public function __construct(\ReflectionProperty $reflector, IAnnotationReader $reader)
    {
        parent::__construct($reflector, $reader);
    }

    /**
     * @return string
     */
    public function getName()
    {
        if ($this->name == null) {
            $this->name = $this->reflector->getName();
        }

        return $this->name;
    }

    /**
     * To access the property's value, an accessor (getter) has to be invoked.
     * according to convention it uses the pattern: "get".Name
     *
     * @return EntityMethodReflector
     */
    private function getAccessor()
    {
        $reflectionMethod =  $this->getReflectionContext()->getClassReflector()
                                                          ->getReflector()
                                                          ->getMethod('get'.ucwords($this->getName()));

        return new EntityMethodReflector($reflectionMethod, $this->getAnnotationReader());

    }

    public function getValue($object)
    {
        return $this->getAccessor()->getValue($object);
    }


}