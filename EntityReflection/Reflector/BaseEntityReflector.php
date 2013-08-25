<?php
/**
 * User: Tayfun Wiechert
 * Date: 13.08.13
 * Time: 18:06
 */

namespace Wiechert\DataTablesBundle\EntityReflection\Reflector;


use Wiechert\DataTablesBundle\EntityReflection\IEntityReflector;
use Wiechert\DataTablesBundle\EntityReflection\Reader\IAnnotationReader;

abstract class BaseEntityReflector implements Reflectable
{

    /**
     * @var null|\Reflector
     */
    protected $reflector = null;

    /**
     * @var null|IAnnotationReader
     */
    protected $annotationReader = null;

    /**
     * @var null|string
     */
    protected $label = null;

    /**
     * @var null|string
     */
    protected $name = null;

    /**
     * Default constructor
     * @param \Reflector $reflector
     * @param IAnnotationReader $reader
     */
    public function __construct(\Reflector $reflector, IAnnotationReader $reader)
    {
        $this->reflector = $reflector;
        $this->annotationReader = $reader;
    }

    /**
     * @return null|\Reflector
     */
    public function getReflector()
    {
        return $this->reflector;
    }

    /**
     * @param \Reflector $reflector
     */
    public function setReflector(\Reflector $reflector)
    {
        $this->reflector = $reflector;
    }

    /**
     * @return null|IAnnotationReader
     */
    public function getAnnotationReader()
    {
        return $this->annotationReader;
    }

    /**
     * @param IAnnotationReader $annotationReader
     */
    public function setAnnotationReader($annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * Returns a label for the reflection member, either  set via the DisplayAnnotation
     * or if not declared the name of the member.
     *
     * @return string
     */
    public function getLabel()
    {
        if ($this->label == null) {

            $displayAnnotation = $this->annotationReader->readMemberAnnotation($this->reflector, self::DISPLAYNAMEANNOTATIONCLASS);
            return ($displayAnnotation !== null) ? $displayAnnotation->getName() :
                $this->getName();
        }

        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}