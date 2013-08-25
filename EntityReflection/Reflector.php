<?php
/**
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */

namespace Wiechert\DataTablesBundle\EntityReflection;


use Wiechert\DataTablesBundle\EntityReflection\Creation\IEntityReflectorFactory;
use Wiechert\DataTablesBundle\EntityReflection\Creation\IReflectionContextFactory;
use Wiechert\DataTablesBundle\EntityReflection\Strategies\IExclusionStrategy;

class Reflector implements IReflector
{

    /**
     * @var null|string
     */
    private $class = null;

    /**
     * @var null|IExclusionStrategy
     */
    private $strategy = null;

    /**
     * @var null|IReflectionContext
     */
    private $baseContext = null;

    /**
     * @var null|IEntityReflectorFactory
     */
    private $entityReflectorFactory = null;

    /**
     * @var null|IReflectionContextFactory
     */
    private $reflectionContextFactory = null;

    /**
     * Creates a reflection context to call reflectByContext().
     */
    public function reflect()
    {
        $this->baseContext = $this->reflectionContextFactory->createBaseReflectionContext(0,
            $this->entityReflectorFactory->createEntityReflector(new \ReflectionClass($this->class)));

        $this->reflectByContext($this->baseContext);
    }

    /**
     * @param IReflectionContext $context
     */
    public function reflectByContext(IReflectionContext $context)
    {

        foreach ($context->getClassReflector()->getReflectionMembers($this->entityReflectorFactory) as $entityReflector) {

            $entityReflector->setReflectionContext($context);

            if ($this->strategy == null || !$this->strategy->shouldSkipProperty($entityReflector, $context)) {

                // does the class member reference another class...
                if ($entityReflector->isSimpleReference()) {

                    $newContext = $this->reflectionContextFactory->createReflectionContext($context->getDepth() + 1,
                        $this->entityReflectorFactory->createEntityReflector(new \ReflectionClass($entityReflector->getTargetEntityClass()), $context), $entityReflector);

                    $entityReflector->setReferencedReflectionContext($newContext);

                    if ($this->strategy == null || !$this->strategy->shouldSkipClass($newContext)) {
                        $this->reflectByContext($newContext);
                    }
                }
                $context->addMemberReflector($entityReflector);
            }
        }
    }

    /**
     * @param IExclusionStrategy $exlusionStrategy
     */
    public function setExclusionStrategy(IExclusionStrategy $exlusionStrategy)
    {
        $this->strategy = $exlusionStrategy;
    }

    /**
     * @return null|IExclusionStrategy
     */
    public function getExclusionStrategy()
    {
        return $this->strategy;
    }

    /**
     * @return null|string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @param IReflectionContextFactory $contextFactory
     */
    public function setReflectionContextFactory(IReflectionContextFactory $contextFactory)
    {
        $this->reflectionContextFactory = $contextFactory;
    }

    /**
     * @param IEntityReflectorFactory $factory
     */
    public function setEntityReflectionFactory(IEntityReflectorFactory $factory)
    {
        $this->entityReflectorFactory = $factory;
    }

    /**
     * @return null|IReflectionContext
     */
    public function getBaseContext()
    {
        return $this->baseContext;
    }
}