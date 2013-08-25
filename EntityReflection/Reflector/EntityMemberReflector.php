<?php
/**
 * User: Tayfun Wiechert
 * Date: 14.08.13
 * Time: 13:44
 */

namespace Wiechert\DataTablesBundle\EntityReflection\Reflector;


use Wiechert\DataTablesBundle\EntityReflection\IReflectionContext;
use Wiechert\DataTablesBundle\EntityReflection\Reader\IAnnotationReader;
use Wiechert\DataTablesBundle\Util\IfNullHelper;

abstract class EntityMemberReflector extends BaseEntityReflector implements IEntityMemberReflector
{

    /**
     * @var array
     */
    private $groups = null;

    /**
     * @var IReflectionContext
     */
    private $referencedReflectionContext = null;

    /**
     * @var IReflectionContext
     */
    private $reflectionContext = null;


    /**
     * @param \Reflector $reflector
     * @param IAnnotationReader $reader
     */
    public function __construct(\Reflector $reflector, IAnnotationReader $reader)
    {
        parent::__construct($reflector, $reader);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getTargetEntityClass()
    {

        $instance = $this;
        $referenceAnnotation = IfNullHelper::returnIfNotNull(array(function () use ($instance) {
            return $instance->getAnnotationReader()->readMemberAnnotation($instance->getReflector(), Reflectable::ONETOONEANNOTATIONCLASS);
        },

            function () use ($instance) {
                return $instance->getAnnotationReader()->readMemberAnnotation($instance->getReflector(), Reflectable::MANYTOONEANNOTATIONCLASS);
            },

            function () use ($instance) {
                return $instance->getAnnotationReader()->readMemberAnnotation($instance->getReflector(), Reflectable::MANYTOMANYANNOTATIONCLASS);
            },
            function () use ($instance) {
                return $instance->getAnnotationReader()->readMemberAnnotation($instance->getReflector(), Reflectable::ONETOMANYANNOTATIONCLASS);
            }));


        if ($referenceAnnotation != null) {
            return $referenceAnnotation->targetEntity;

        } else {
            throw new \Exception("Association Mapping is missing for referencing class member.");
        }


    }

    /**
     * @return bool
     */
    public function isSimpleReference()
    {
        return $this->getAnnotationReader()->readMemberAnnotation($this->getReflector(), Reflectable::MANYTOONEANNOTATIONCLASS) != null ||
        $this->getAnnotationReader()->readMemberAnnotation($this->getReflector(), Reflectable::ONETOONEANNOTATIONCLASS) != null;
    }

    /**
     * @return bool
     */
    public function isComplexeReference()
    {
        return $this->getAnnotationReader()->readMemberAnnotation($this->getReflector(), Reflectable::MANYTOMANYANNOTATIONCLASS) != null ||
        $this->getAnnotationReader()->readMemberAnnotation($this->getReflector(), Reflectable::ONETOMANYANNOTATIONCLASS) != null;
    }

    /**
     * @return mixed|null
     */
    public function getGroups()
    {
        if ($this->groups == null) {
            $groupAnnotation = $this->getAnnotationReader()->readMemberAnnotation($this->getReflector(), Reflectable::GROUPANNOTATIONCLASS);
            $this->groups = ($groupAnnotation != null)? $groupAnnotation->groups: $groupAnnotation;
        }

        return $this->groups;
    }

    /**
     * @param array $groups
     */
    public function setGroups(array $groups)
    {
        $this->groups = $groups;
    }

    /**
     * @param IReflectionContext $context
     */
    public function setReferencedReflectionContext(IReflectionContext $context)
    {
       $this->referencedReflectionContext = $context;
    }

    /**
     * @return null|IReflectionContext
     */
    public function getReferencedReflectionContext()
    {
       return  $this->referencedReflectionContext;
    }

    /**
     * @return bool
     */
    public function hasReferencedReflectionContext()
    {
        return  $this->referencedReflectionContext != null;
    }

    /**
     * @param IReflectionContext $reflectionContext
     */
    public function setReflectionContext(IReflectionContext $reflectionContext)
    {
        $this->reflectionContext = $reflectionContext;
    }

    /**
     * @return IReflectionContext
     */
    public function getReflectionContext()
    {
        return $this->reflectionContext;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->getReflectionContext()->getPath().$this->getName();
    }


}