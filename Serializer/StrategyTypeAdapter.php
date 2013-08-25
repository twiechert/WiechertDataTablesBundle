<?php
/**
 * This is an adapter between the JMS ExclusionStrategyInterface and the Wiechert IExclusionStrategy.
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */

namespace Wiechert\DataTablesBundle\Serializer;

use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\NavigatorContext;
use Wiechert\DataTablesBundle\EntityReflection\Creation\IEntityReflectorFactory;
use Wiechert\DataTablesBundle\EntityReflection\EntityReflector;
use Wiechert\DataTablesBundle\EntityReflection\ReflectionContext;
use Wiechert\DataTablesBundle\EntityReflection\Strategies\IExclusionStrategy;

class StrategyTypeAdapter implements ExclusionStrategyInterface
{


    /**
     * @var IExclusionStrategy
     */
    private $strategyType;

    /**
     * @var IEntityReflectorFactory
     */
    private $entityReflectorFactory;

    /**
     * Default constructor
     *
     * @param IExclusionStrategy $strategyType
     * @param IEntityReflectorFactory $entityReflectorFactory
     */
    public function __construct(IExclusionStrategy $strategyType, IEntityReflectorFactory $entityReflectorFactory)
    {
        $this->strategyType = $strategyType;
        $this->entityReflectorFactory = $entityReflectorFactory;
    }

    /**
     * Whether the class should be skipped.
     *
     * @param ClassMetadata $metadata
     * @param NavigatorContext $navigatorContext
     * @return bool
     */
    public function shouldSkipClass(ClassMetadata $metadata, NavigatorContext $navigatorContext)
    {
        $reflectionContext = new ReflectionContext();
        $reflectionContext->setDepth($navigatorContext->getDepth() - 1);
        $entityReflector = $this->entityReflectorFactory->createEntityReflector($metadata->reflection, $reflectionContext);
        $reflectionContext->setClassReflector($entityReflector);
        $entityReflector->setName($metadata->name);

        return $this->strategyType->shouldSkipClass($reflectionContext);
    }

    /**
     * Whether the property should be skipped.
     *
     * @param PropertyMetadata $property
     * @param NavigatorContext $navigatorContext
     * @return bool
     */
    public function shouldSkipProperty(PropertyMetadata $property, NavigatorContext $navigatorContext)
    {
        $reflectionContext = new ReflectionContext();
        $reflectionContext->setDepth($navigatorContext->getDepth() - 1);

        $entityReflector = $this->entityReflectorFactory->createEntityReflector($property->reflection, $reflectionContext);
        $entityReflector->setGroups($property->groups);

        return $this->strategyType->shouldSkipProperty($entityReflector, $reflectionContext);
    }
}