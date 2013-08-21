<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tayfun
 * Date: 16.07.13
 * Time: 15:01
 * To change this template use File | Settings | File Templates.
 */

namespace Wiechert\DataTablesBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wiechert\DataTablesBundle\Configuration\DataTablesConfiguration;

class GenericActionsController extends Controller {

    /**
     * @Route("/test1")
     * @Template()
     */
    public function test1Action()
    {

    }

}