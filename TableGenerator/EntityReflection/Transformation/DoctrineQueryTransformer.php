<?php
/**
 * The transformer  transforms the ReflectionContext into a Doctrine Select-Query.
 * This class applies where-clauses and sorting - the required information is passes into the constructor.
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */

namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Transformation;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\IReflectionContext;

class DoctrineQueryTransformer extends BaseTransformer
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;

    /**
     * Number of aliases assigned yet
     * @var int
     */
    private $aliases = 0;

    /**
     *
     * Maps the pathes of member reflectors onto the Query-Selectors:
     *
     *   $this->aliasMapping[$memberReflector->getPath()] = $alias . "." . $memberReflector->getName();
     * @var array
     */
    private $aliasMapping = array();

    /**
     *
     * Maps the pathes of member reflectors (only the simple ones) onto the Query-Selectors:
     *
     * @var array
     */
    private $aliasMappingForSimpleMembers = array();


    /**
     * @var array
     */
    private $countMapping = array();

    /**
     * GET-Parameter passed into the constructor
     * @var array
     */
    private $attributes;

    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    private $queryBuilder = null;

    /**
     * @param IReflectionContext $reflectionContext
     * @param EntityRepository $repository to generate the QueryBuilder
     * @param array $attributes
     * @param QueryBuilder $queryBuilder or alternatively pass your own..
     */
    public function __construct(IReflectionContext $reflectionContext, EntityRepository $repository, array $attributes, QueryBuilder $queryBuilder = null)
    {
        parent::__construct($reflectionContext);
        $this->repository =  $repository;
        $this->attributes = $attributes;
        $this->queryBuilder = $queryBuilder;
    }



    public function transform()
    {
        $alias = $this->getNewAlias();
        $queryBuilder = ($this->queryBuilder != null)? clone $this->queryBuilder :
                                                       $this->repository->createQueryBuilder($alias);

        $this->traverse($this->getContext(), $queryBuilder, $alias);

        $startCount = array_key_exists('iDisplayStart', $this->attributes) ? $this->attributes['iDisplayStart'] : null;
        $limitCount = array_key_exists('iDisplayLength', $this->attributes) ? $this->attributes['iDisplayLength'] : null;

        if ($startCount != null && $limitCount != null) {
            $queryBuilder->setFirstResult($startCount)
                ->setMaxResults($limitCount);
        }


        if (isset($this->attributes['iSortCol_0'])) {
            for ($i = 0; $i < intval($this->attributes['iSortingCols']); $i++) {

                $columnNumber = intval($this->attributes['iSortCol_' . $i]);
                if ($this->attributes['bSortable_' . $columnNumber] == "true") {
                    $sortMode = $this->attributes['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc';
                    $queryBuilder->orderBy($this->aliasMapping[$this->countMapping[$columnNumber-1]], $sortMode);

                }
            }
        }

        $searchWord = $this->attributes['sSearch'];

        if (isset($searchWord) && $searchWord != "") {

            foreach ($this->aliasMappingForSimpleMembers as $select) {
                $queryBuilder->orWhere($select . ' LIKE :searchterm');
                $queryBuilder->setParameter('searchterm', '%' . $searchWord . '%');
            }
        }

        $queryBuilderForCounting = clone $queryBuilder;
        $queryBuilderForCounting->select('count(e0.id)');

        $countQueryBuilder  = clone $queryBuilder;
        $countQueryBuilder->select('count(e0.id)');

        return array('querybuilder' => $queryBuilder,
                     'filtered-count-querybuilder' => $queryBuilderForCounting,
                     'count-querybuilder' => $countQueryBuilder);

    }

    private function getNewAlias()
    {
        return 'e' . $this->aliases++;
    }

    private function traverse(IReflectionContext $reflectionContext, QueryBuilder $queryBuilder, $alias)
    {
        foreach ($reflectionContext->getMemberReflectors() as $memberReflector) {
            $this->aliasMapping[$memberReflector->getPath()] = $alias . "." . $memberReflector->getName();
            $this->countMapping[] = $memberReflector->getPath();

            if ($memberReflector->hasReferencedReflectionContext()) {
                $newAlias = $this->getNewAlias();
                $queryBuilder->leftJoin($alias.'.'. $memberReflector->getName(), $newAlias);
                $this->traverse($memberReflector->getReferencedReflectionContext(), $queryBuilder, $newAlias);
            } else {
                $this->aliasMappingForSimpleMembers[$memberReflector->getPath()] = $alias . "." . $memberReflector->getName();

            }
        }
    }


}