<?php
/**
 * A Displayer is an object for generating  user interfaces based on reflected Doctrine enities.
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */

namespace Wiechert\DataTablesBundle\TableGenerator;


use Symfony\Component\Yaml\Yaml;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\IEntityReflector;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\IReflector;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Transformation\CountPerEntityTransformer;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Transformation\GraphResolveTransformer;
use Wiechert\DataTablesBundle\Util\ArrayAccessor;

abstract class Displayer
{

    /**
     * The name of the entity
     * @var string
     */
    protected $entityName = null;
    /**
     * @var string
     */
    protected $bundleName = null;
    /**
     * @var IReflectionContext
     */
    protected $baseContext = null;
    /**
     * @var GraphResolveTransformer|null
     */
    protected $graphResolveTransformer = null;
    /**
     * @var CountPerEntityTransformer|null
     */
    protected $countPerEntityTransformer = null;
    /**
     * @var IReflector
     */
    protected $reflector = null;
    /**
     * @var array
     */
    protected $tableConfig = null;
    /**
     * @var array
     */
    protected $bundleConfig = null;

    /**
     * @var array
     */
    private $config = null;


    public function initialize()
    {
        $this->bundleConfig = ArrayAccessor::accessArray($this->config, array($this->getBundleName()));
        $this->tableConfig = ArrayAccessor::accessArray($this->bundleConfig, array('Tables', $this->getEntityName()));
        $class = $this->bundleConfig['namespace'] . $this->getEntityName();
        $this->reflector->setClass($class);
        $this->reflector->reflect();
        $this->baseContext = $this->reflector->getBaseContext();
    }

    /**
     * @return GraphResolveTransformer
     */
    public function getGraphResolveTransformer()
    {
        if ($this->graphResolveTransformer == null) {
            $this->graphResolveTransformer = new GraphResolveTransformer($this->baseContext);

        }

        return $this->graphResolveTransformer;
    }

    /**
     * @return CountPerEntityTransformer
     */
    public function getCountPerEntityTransformer()
    {
        if ($this->countPerEntityTransformer == null) {
            $this->countPerEntityTransformer = new CountPerEntityTransformer($this->baseContext, $this->graphResolveTransformer);
        }

        return $this->countPerEntityTransformer;
    }

    /**
     * @return string|null
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param string $entityName
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * @return null|string
     */
    public function getBundleName()
    {
        return $this->bundleName;
    }

    /**
     * @param string $bundleName
     */
    public function setBundleName($bundleName)
    {
        $this->bundleName = $bundleName;
    }

    /**
     * @return null|IReflector
     */
    public function getReflector()
    {
        return $this->reflector;
    }

    /**
     * @param IReflector $reflector
     */
    public function setReflector(IReflector $reflector)
    {
        return $this->reflector = $reflector;
    }


    /**
     * @return \Wiechert\DataTablesBundle\TableGenerator\EntityReflection\IReflectionContext
     */
    public function getBaseContext()
    {
        return $this->baseContext;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }


}