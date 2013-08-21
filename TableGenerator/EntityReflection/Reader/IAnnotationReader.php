<?php
/**
 * Implementations of this interface build a wrapper for the Doctrine annotation reader.
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */

namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reader;


interface IAnnotationReader {

    /**
     * @param \Reflector $reflectionMember
     * @param $annotationClass
     * @return mixed
     */
    public function readMemberAnnotation(\Reflector $reflectionMember, $annotationClass);


}