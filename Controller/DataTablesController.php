<?php
/**
 * This controller provides actions for creating datatables.
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */
namespace Wiechert\DataTablesBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;
use Wiechert\DataTablesBundle\Serializer\StrategyTypeAdapter;
use Wiechert\DataTablesBundle\Serializer\TreeGroupExclusionStrategyTypeAdapter;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Transformation\DoctrineQueryTransformer;
use Wiechert\DataTablesBundle\Util\ArrayAccessor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DataTablesController extends Controller
{
    /**
     *
     * The controller generates a datatable for the specified Entity.
     *
     * @Route("/datatable/generate/{bundle}/{entity}/{strategy}", name="wiechert_core_get_generic_table", options={"expose"=true})
     *
     * @param $bundle the entities bundle
     * @param $entity the entity
     * @param $strategy Der Name der Strategie (ohne Strategy)
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function generateTableAction($bundle, $entity, $strategy)
    {
        $config = $this->container->getParameter('datatables.bundles');
        $entityConfiguration = ArrayAccessor::accessArray($config, array($bundle, 'Tables', $entity));

        if ($entityConfiguration) {
            $tableGenerator = $this->get('wiechert.datatables.tablegenerator.tablegenerator');
            $tableGenerator->setConfig($config);
            $tableGenerator->setBundleName($bundle);
            $tableGenerator->setEntityName($entity);
            $options = ArrayAccessor::accessArray($entityConfiguration, array('options'));

            if ($options) {
                $tableGenerator->setOptions($options);

            }

            // if options are specified in the request
            if ($this->getRequest()->query->has('options')) {
                $tableGenerator->setOptions($this->getRequest()->query->get('options'));
            }

           $strategies = $this->container->getParameter('datatables.strategies');
            if(array_key_exists($strategy, $strategies))
            {
                $strategy = $strategies[$strategy];
            } else {
                $strategy = $strategies['Default'];
            }

            $exclusionStrategy = new $strategy();

            $tableGenerator->getReflector()
                ->setExclusionStrategy($exclusionStrategy);

            $tableGenerator->initialize();

            return $this->render('WiechertDataTablesBundle:helper:generic-table-renderer-ajax.html.twig', array('tableGenerator' => $tableGenerator,
                'title' => $entityConfiguration['display_name'],
                'entityName' => $entity));


        } else {
            throw new \Exception("Please configure the datatable.yml first!");
        }

    }

    /**
     *
     * The controller generates a datatable for the specified entity
     *
     * @Route("/datatable/generate/{bundle}/{entity}/{strategy}/{name}/{id}", name="wiechert_core_get_generic_named_table", options={"expose"=true}, defaults={"ajax" = false})
     *
     * @param $bundle
     * @param $entity
     * @param $strategy
     * @param $name
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function generateNamedTableAction($bundle, $entity, $strategy, $name, $id)
    {
        $config = $this->container->getParameter('datatables.bundles');
        $namedDatatableConfiguration =  ArrayAccessor::accessArray($config, array($bundle, 'Tables', $entity,  'NamedTables', $name));

        if ($namedDatatableConfiguration) {
            $tableGenerator = $this->get('wiechert.datatables.tablegenerator.tablegenerator');
            $tableGenerator->setConfig($config);


            if (array_key_exists("select_table", $namedDatatableConfiguration)) {
                $joinEntity = $namedDatatableConfiguration['select_table'];
                $joinBundle = $namedDatatableConfiguration['select_table_bundle'];

                $tableGenerator->setBundleName($joinBundle);
                $tableGenerator->setEntityName($joinEntity);

                $tableGenerator->setRelatedDatatableEntityName($entity);
                $tableGenerator->setRelatedDatatableBundleName($bundle);
            } else {
                $tableGenerator->setBundleName($bundle);
                $tableGenerator->setEntityName($entity);
            }


            $tableGenerator->setWhereParam($id);
            $tableGenerator->setDatatableName($name);

            $strategies = $this->container->getParameter('datatables.strategies');
            if(array_key_exists($strategy, $strategies))
            {
                $strategy = $strategies[$strategy];
            } else {
                $strategy = $strategies['Default'];
            }

            $tableGenerator->getReflector()
                ->setExclusionStrategy(new $strategy());

            $tableGenerator->initialize();


            return $this->render('WiechertDataTablesBundle:Helper:generic-table-renderer-ajax.html.twig', array('tableGenerator' => $tableGenerator,
                'title' => "test",
                'entityName' => $entity));
        } else {
            throw new \Exception();
        }

    }

    /**
     * Returns the JSON-formated result for a Datatable query.
     *
     * @Route("/datatable/get/{strategy}/{bundle}/{entity}/{name}/{id}", name="wiechert_core_get_data_for_datatable", options={"expose"=true}, defaults={"name" = false, "id" = false})
     *
     * @param $strategy
     * @param $bundle
     * @param $entity
     * @param null $name
     * @param null $id
     * @return Response
     */
    public function getAction($strategy, $bundle, $entity, $name = false, $id = false)
    {
        $request = $this->getRequest();
        $attributes = $request->query->all();
        $isNamedTable = false;
        $em = $this->getDoctrine()->getManager();
        $config = $this->container->getParameter('datatables.bundles');
        $bundleConfiguration = ArrayAccessor::accessArray($config, array($bundle));

        $strategies = $this->container->getParameter('datatables.strategies');
        if(array_key_exists($strategy, $strategies))
        {
            $exclusionStrategy = $strategies[$strategy];
        } else {
            $exclusionStrategy = $strategies['Default'];
        }

        $exclusionStrategy = new $exclusionStrategy();
        $reflector = $this->get('wiechert.datatables.tablegenerator.entityreflection.reflector');
        $reflector->setExclusionStrategy($exclusionStrategy);

        if ($name != null && $id != null) {
            $namedDatatableConfiguration = ArrayAccessor::accessArray($bundleConfiguration, array($entity, 'NamedTables', $name));
            if ($namedDatatableConfiguration) {


                $isNamedTable = true;
                $where_clause = $namedDatatableConfiguration['where_caluse'];
                $joins = $namedDatatableConfiguration['joins'];
                $select_entity = $namedDatatableConfiguration['select_table'];
                $select_bundle = $namedDatatableConfiguration['select_table_bundle'];

                $queryBuilder = $em->getRepository($select_bundle . ':' . $select_entity)->createQueryBuilder('e0');
                $queryBuilder->andWhere($where_clause)->setParameter('id', $id);


                foreach ($joins as $join) {
                    $queryBuilder->join($join[0], $join[1]);
                }
            }

            $bundleConfigurationForNamedTable = ArrayAccessor::accessArray($config, array($select_bundle));

            $reflector->setClass($bundleConfigurationForNamedTable['namespace'] . $select_entity);
            $reflector->reflect();
            $transformer = new DoctrineQueryTransformer($reflector->getBaseContext(), $em->getRepository($bundle . ':' . $entity), $attributes, $queryBuilder);

        } else {
            $reflector->setClass($bundleConfiguration['namespace'] . $entity);
            $reflector->reflect();
            $transformer = new DoctrineQueryTransformer($reflector->getBaseContext(), $em->getRepository($bundle . ':' . $entity), $attributes);
        }

        $result = $transformer->transform();

        $objectQueryBuilder = $result['querybuilder'];
        $filteredCountQueryBuilder = $result['filtered-count-querybuilder'];
        $simpleCountQueryBuilder = $result['count-querybuilder'];

        $objects = $objectQueryBuilder->getQuery()->getResult();
        $filtered_count = $filteredCountQueryBuilder->getQuery()->getSingleScalarResult();
        $count = $simpleCountQueryBuilder->getQuery()->getSingleScalarResult();

        $arr = array('sEcho' => $request->query->get('sEcho') . '',
            'iTotalRecords' => $count . '',
            'iTotalDisplayRecords' => $filtered_count . '',
            'aaData' => $objects);

        $entityreflectorfactory = $this->container->get('wiechert.datatables.tablegenerator.entityreflection.creation.entityreflectionfactory');

        $this->container
            ->get('serializer')
            ->setExclusionStrategy(new StrategyTypeAdapter($exclusionStrategy, $entityreflectorfactory));

        return new Response($this->container
            ->get('serializer')
            ->serialize($arr, 'json'));
    }

}
