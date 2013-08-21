<?php
/**
 *
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */

namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection;


use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\EntityClassReflector;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\IEntityMemberReflector;

class ReflectionContext implements IReflectionContext
{

    /**
     * @var string
     */
    private $path = "";
    /**
     * @var null|string[]
     */
    private $arrayPath = null;
    /**
     * @var int
     */
    private $depth = 0;
    /**
     * @var null|EntityClassReflector
     */
    private $classReflector = null;
    /**
     * @var IEntityMemberReflector[]
     */
    private $memberReflectors = array();
    /**
     * @var int
     */
    private $simpleMembers = 0;
    /**
     * @var int
     */
    private $referenceMembers = 0;

    /**
     * @return EntityClassReflector
     */
    public function getClassReflector()
    {
        return $this->classReflector;
    }

    /**
     * @param EntityClassReflector $reflector
     */
    public function setClassReflector(EntityClassReflector $reflector)
    {
        $this->classReflector = $reflector;
    }

    /**
     * @param IEntityMemberReflector $reflector
     * @return void|IEntityMemberReflector
     */
    public function addMemberReflector(IEntityMemberReflector $reflector)
    {
        $this->memberReflectors[$reflector->getName()] = $reflector;

        if ($reflector->hasReferencedReflectionContext()) {
            $this->referenceMembers++;
        } else {
            $this->simpleMembers++;

        }
    }

    /**
     * @return int
     */
    public function getCountOfSimpleMemberReflectors()
    {
        return $this->simpleMembers;
    }

    /**
     * @return int
     */
    public function getCountOfReferenceMembersReflectors()
    {
        return $this->referenceMembers;
    }

    /**
     * @return IEntityMemberReflector[]
     */
    public function getSimpleMemberReflectors()
    {
        return array_filter($this->getMemberReflectors(), function ($reflector) {
            return !$reflector->hasReferencedReflectionContext();
        });
    }

    /**
     * @return IEntityMemberReflector[]
     */
    public function getMemberReflectors()
    {
        return $this->memberReflectors;
    }

    /**
     * @return IEntityMemberReflector[]
     */
    public function getReferenceMemberReflectors()
    {
        return array_filter($this->getMemberReflectors(), function ($reflector) {
            return $reflector->hasReferencedReflectionContext();
        });

    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
        $this->arrayPath = explode(".", $path);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = array();
        $array['class'] = $this->classReflector;

        foreach ($this->getMemberReflectors() as $memberReflector) {
            if (!$memberReflector->hasReferencedReflectionContext()) {
                $array[$memberReflector->getName()] = $memberReflector;

            } else {
                $array[$memberReflector->getName()] = $memberReflector->getReferencedReflectionContext()->toArray();

            }
        }

        return $array;

    }

    /**
     * The method traverses the object graph with the help of the reflectors path.
     * The method works recursively.
     *
     * @param IEntityMemberReflector $reflector
     * @param $object
     * @return mixed|null
     */
    public function getValue(IEntityMemberReflector $reflector, $object)
    {
        $reflectorArrayPath = $reflector->getReflectionContext()->getArrayPath();

        if ($reflectorArrayPath != $this->getArrayPath()) {
            $member = $this->memberReflectors[$reflectorArrayPath[$this->getDepth()]];
            $nodeReflectionContext = $member->getReferencedReflectionContext();
            $nodeObject = $member->getValue($object);
            if ($nodeObject != null) {
                return $nodeReflectionContext->getValue($reflector, $nodeObject);
            } else {
                return null;
            }

        } else {
            return $reflector->getValue($object);
        }

    }

    /**
     * @return string[]
     */
    public function getArrayPath()
    {
        return $this->arrayPath;
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param $depth
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;
    }


}