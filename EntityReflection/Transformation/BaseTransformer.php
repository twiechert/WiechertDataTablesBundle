<?php
/**
 * An inmplementation of this abstract class can transform a ReflectionContext into any other strucutre (array, object..)
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */
namespace Wiechert\DataTablesBundle\EntityReflection\Transformation;


use Wiechert\DataTablesBundle\EntityReflection\IReflectionContext;

abstract class BaseTransformer
{


    /**
     * @var IReflectionContext
     */
    private $context = null;

    /**
     * @param IReflectionContext $context
     */
    public function __construct(IReflectionContext $context)
    {
        $this->context = $context;
    }

    /**
     * @return IReflectionContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param IReflectionContext $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * Transforms the ReflectionContext into any other structure
     * @return mixed
     */
    public abstract function transform();
}