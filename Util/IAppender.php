<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tayfun
 * Date: 12.08.13
 * Time: 11:58
 * To change this template use File | Settings | File Templates.
 */

namespace Wiechert\DataTablesBundle\Util;


interface IAppender {


    public function appendIf($condition, array $jsLines);

    public function appendIfElse($condition, array $jsLinesTrue, array $jsLinesFalse);

    public function append(array $jsLines);

    public function getCode();


}