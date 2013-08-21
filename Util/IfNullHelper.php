<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tayfun
 * Date: 13.08.13
 * Time: 19:56
 * To change this template use File | Settings | File Templates.
 */

namespace Wiechert\DataTablesBundle\Util;


class IfNullHelper {

    public static function returnIfNotNull(array $closures) {

        foreach($closures as $closure)
        {
            $result = $closure();
            if($result != null)
            {
                return $result;
            }
        }

        return null;

    }
}