<?php
/**
 * An inmplementation of this interface can coordinate the reflection process.
 * It will need an instance of IReflectionContext that represents a base- or node-context of the reflection.
 *
 * Alternatively it should be possible to pass the class name and let the reflector generate the context.
 *
 * As well, it is possible to set an ExclusionStrategy that decides whether a property or class should be reflected or not.
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */

namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection;


use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Creation\IEntityReflectorFactory;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Creation\IReflectionContextFactory;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Strategies\IExclusionStrategy;

interface IReflector
{

    /**
     * @param IExclusionStrategy $exlusionStrategy
     */
    public function setExclusionStrategy(IExclusionStrategy $exlusionStrategy);

    /**
     * @return IExclusionStrategy
     */
    public function getExclusionStrategy();

    /**
     * @return string
     */
    public function getClass();

    /**
     * @param string $class
     */
    public function setClass($class);

    /**
     * @return IReflectionContext
     */
    public function getBaseContext();

    /**
     * Starts the process of reflection.
     */
    public function reflect();

    /**
     * Starts the process of reflection using $context as the root context.
     * @param IReflectionContext $context
     */
    public function reflectByContext(IReflectionContext $context);

    /**
     * To generate EntityReflectors, implementations need the factory.
     * @param IEntityReflectorFactory $factory
     */
    public function setEntityReflectionFactory(IEntityReflectorFactory $factory);

    /**
     * To generate ReflectionContexts, implementations need the factory.
     * @param IReflectionContextFactory $factory
     */
    public function setReflectionContextFactory(IReflectionContextFactory $factory);


}