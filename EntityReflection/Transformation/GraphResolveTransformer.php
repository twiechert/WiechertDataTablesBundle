<?php
/**
 * The transformer  resolves the graph of the ReflectionContext into a flat array.
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */

namespace Wiechert\DataTablesBundle\EntityReflection\Transformation;


use Wiechert\DataTablesBundle\EntityReflection\IReflectionContext;

class GraphResolveTransformer extends BaseTransformer
{

    /**
     * @var \Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\IEntityMemberReflector[]
     */
    private $resolvedGraph = array();

    /**
     * @param IReflectionContext $reflectionContext
     */
    public function __construct(IReflectionContext $reflectionContext)
    {
        parent::__construct($reflectionContext);
    }

    /**
     * @return \Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\IEntityMemberReflector[]
     */
    public function transform()
    {
        if ($this->resolvedGraph == null) {
            $this->normalize($this->getContext()->getMemberReflectors());
        }

        return $this->resolvedGraph;

    }

    /**
     * @param \Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\IEntityMemberReflector[] $reflectionMember
     */
    private function normalize(array $reflectionMember)
    {

        foreach ($reflectionMember as $reflectionMember) {
            if ($reflectionMember->hasReferencedReflectionContext()) {
                $this->resolvedGraph[] = $reflectionMember;
                $this->normalize($reflectionMember->getReferencedReflectionContext()->getMemberReflectors());

            }
        }

    }


}