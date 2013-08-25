<?php
/**
 * This EntityReflector can reflect classes
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */

namespace Wiechert\DataTablesBundle\EntityReflection\Reflector;


use Wiechert\DataTablesBundle\EntityReflection\Creation\IEntityReflectorFactory;
use Wiechert\DataTablesBundle\EntityReflection\Reader\IAnnotationReader;

class EntityClassReflector extends BaseEntityReflector
{

    /**
     * @param \ReflectionClass $reflector
     * @param IAnnotationReader $reader
     */
    public function __construct(\ReflectionClass $reflector, IAnnotationReader $reader)
    {
        parent::__construct($reflector, $reader);
    }

    /**
     * @param IEntityReflectorFactory $factory
     * @return IEntityReflectorFactory[]
     */
    public function getReflectionMembers(IEntityReflectorFactory $factory)
    {
        $members = array_merge($this->getReflector()->getProperties(),
            $this->getReflector()->getMethods());

        $entityReflectors = array();

        foreach ($members as $member) {
            $entityReflectors[] = $factory->createEntityReflector($member);
        }

        return $entityReflectors;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        if ($this->name == null) {
            $this->name = $this->getReflector()->getShortName();
        }

        return $this->name;
    }


}