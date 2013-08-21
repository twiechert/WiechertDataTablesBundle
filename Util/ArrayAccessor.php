<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tayfun
 * Date: 11.08.13
 * Time: 19:31
 * To change this template use File | Settings | File Templates.
 */

namespace Wiechert\DataTablesBundle\Util;


class ArrayAccessor {

    /**
     *
     * @param array $array
     * @param array $path
     * @param boolean $returnEmpty
     * @return mixed
     */
    public static function accessArray(array $array, array $path, $returnFalseWhenEmpty = true ) {

         return self::accessArrayWithCallback($array, $path, $returnFalseWhenEmpty,  function($key) {return $key;});

    }


    public static function accessArrayWithCallback(array $array, array $path, $returnFalseWhenEmpty = true, $callback ) {

        $pathElement = $callback($path[0]);
        if(array_key_exists($pathElement, $array) &&
            (!$returnFalseWhenEmpty || ($returnFalseWhenEmpty &&
                    count($array[$pathElement]) > 0 )) )
        {
            if(count($path) > 1) {
                return ArrayAccessor::accessArray($array[$pathElement], array_slice($path,1));
            } else {
                $result = $array[$pathElement];
                return $result;
            }

        } else {
            return false;
        }


    }


}