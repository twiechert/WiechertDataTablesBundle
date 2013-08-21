<?php
/**
 * The transformer  transforms the ReflectionContext into an array that stores labels (of every reference member) and count of simple node members.
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */

namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Transformation;

use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\ReflectionContext;

class CountPerEntityTransformer extends BaseTransformer
{
    /**
     * @var GraphResolveTransformer
     */
    private $graphResolveTransformer = null;

    /**
     * @param ReflectionContext $reflectionContext
     * @param GraphResolveTransformer $graphResolveTransformer
     */
    public function __construct(ReflectionContext $reflectionContext, GraphResolveTransformer $graphResolveTransformer)
    {
        parent::__construct($reflectionContext);
        $this->graphResolveTransformer = $graphResolveTransformer;
    }

    /**
     * @return array|mixed
     */
    public function transform()
    {
        $resolvedGraph = $this->graphResolveTransformer->transform();
        $label = $this->getContext()->getClassReflector()->getLabel();

        $countsPerEntity = array();
        $i = 0;
        $countsPerEntity[$i]['count'] = $this->getContext()->getCountOfSimpleMemberReflectors();
        $countsPerEntity[$i++]['label'] = $label;


        foreach ($resolvedGraph as $member) {
            $countsPerEntity[$i]['count'] = $member->getReferencedReflectionContext()
                ->getCountOfSimpleMemberReflectors();

            $countsPerEntity[$i++]['label'] = $member->getLabel();
        }

        return $countsPerEntity;

    }


}