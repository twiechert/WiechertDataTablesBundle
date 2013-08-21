<?php
/**
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */

namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reader;

use Doctrine\Common\Annotations\Reader;


class AnnotationReader implements IAnnotationReader{

    private $reader = null;

    public function __construct(Reader $reader) {
        $this->reader = $reader;
    }


    /**
     * The methos reads a particular reflection member and provides an instance of the given
     * annotation class.
     *
     * @param \Reflector $reflectionMember
     * @param $annotationClass
     * @return mixed
     */
    public function readMemberAnnotation(\Reflector $reflectionMember, $annotationClass)
    {
        if ($reflectionMember instanceof \ReflectionMethod) {
            return $this->reader->getMethodAnnotation($reflectionMember, $annotationClass);

        } else if ($reflectionMember instanceof \ReflectionProperty) {
            return $this->reader->getPropertyAnnotation($reflectionMember, $annotationClass);

        } else {
            return $this->reader->getClassAnnotation($reflectionMember, $annotationClass);
        }

    }


}