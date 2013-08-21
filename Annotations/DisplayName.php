<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Tayfun Wiechert
 * Date: 10.01.13
 * Time: 11:14
 *
 */

namespace Wiechert\DataTablesBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
final class DisplayName extends Annotation
{
   public $name = "Name";

   public function getName()
   {
       return $this->name;
   }


}