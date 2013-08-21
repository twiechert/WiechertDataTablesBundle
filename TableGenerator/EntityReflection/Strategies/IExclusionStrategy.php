<?php
/**
 * Interface for ExlusionStrategies influenced by:
 * @see JMS\Serializer\Exclusion\ExclusionStrategyInterface;
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */
namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Strategies;



use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\IReflectionContext;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\IEntityMemberReflector;

interface IExclusionStrategy {

    /**
     * Decides whether the class of the reflection context  should be skipped or not.
     *
     * @param IReflectionContext $context
     * @return bool
     */
    public function shouldSkipClass(IReflectionContext $context);

    /**
     *  Decides whether the the given member reflector of the reflection context should be skipped or not.
     *
     * @param IEntityMemberReflector $entityReflector
     * @param IReflectionContext $context
     * @return bool
     */
    public function shouldSkipProperty(IEntityMemberReflector $entityReflector, IReflectionContext $context);

    /**
     * @return mixed
     */
    public function getName();

}