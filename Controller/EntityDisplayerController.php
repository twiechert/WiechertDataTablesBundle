<?php
/**
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */
namespace Wiechert\DataTablesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;
use Wiechert\DataTablesBundle\Serializer\TreeGroupExclusionStrategy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Wiechert\DataTablesBundle\Util\ArrayAccessor;


class EntityDisplayerController extends Controller
{
    /**
     *
     * @Route("/datatable/display/{bundle}/{entity}/{strategy}/{id}", name="wiechert_core_generic_entity_display", options={"expose"=true}, defaults={"strategy" = "Extended"})
     *
     * @param $strategy
     * @param $bundle
     * @param $entity
     * @param $id
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function displayEntityAction($strategy, $bundle, $entity, $id)
    {
        $config = Yaml::parse(__DIR__."/../Resources/config/datatables.yml");
        $bundleConfiguration  = ArrayAccessor::accessArray($config, array('Datatables', 'Bundles', $bundle));

        if(!$bundleConfiguration)
        {
            throw $this->createNotFoundException("Please configure the bundle '".$bundle."' in the datatables.yml first.'");
        }

        $namedDatatables= ArrayAccessor::accessArray($bundleConfiguration, array('NamedTables', $entity));

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder()
            ->select("e")
            ->from($bundleConfiguration['namespace'].$entity."", "e")
            ->where("e.id = :id")
            ->getQuery()
            ->setParameter('id', $id);


        try {
            $object = $query->getSingleResult();
            $entityDisplayer = $this->get('wiechert.datatables.tablegenerator.entitydisplayer');
            $entityDisplayer->setBundleName($bundle);
            $entityDisplayer->setEntityName($entity);

            $exclusionStrategy = "\\Wiechert\\DataTablesBundle\\TableGenerator\\EntityReflection\\Strategies\\".$strategy."Strategy";
            $exclusionStrategy = new $exclusionStrategy();

            $entityDisplayer->setEntity($object);
            $entityDisplayer->getReflector()
                ->setExclusionStrategy($exclusionStrategy);
            $entityDisplayer->initialize();


            // if  entities share the same datatables (for example when using inheritance)...
            if($namedDatatables && array_key_exists('extends',$namedDatatables)) {

                $namedDatatables = array_merge($namedDatatables, $bundleConfiguration["named_datatables"][$namedDatatables["extends"]]);
                unset($namedDatatables["extends"]);
            }


            return $this->render('WiechertDataTablesBundle:Helper:generic-entity-renderer.html.twig',
                array('entityDisplayer' => $entityDisplayer,
                      'entity' => $object,
                      'entityName' => $entity,
                      'namedDatatables' => $namedDatatables,
                      'strategy' =>  $strategy,
                      'bundle' =>  $bundle,
                      'id' => $id ));


        } catch(\Doctrine\ORM\NoResultException $e) {
            throw $this->createNotFoundException("There is no '".$entity."' with the given id.");
        }
    }

}
