<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tayfun
 * Date: 12.08.13
 * Time: 11:54
 * To change this template use File | Settings | File Templates.
 */

namespace Wiechert\DataTablesBundle\Util;


class Appender implements IAppender{

    private $jsLines = "";

    public function __construct()
    {

    }

    public function appendIf($condition, array $jsLines)
    {
        if($condition)
        {
            $this->append($jsLines);
        }
    }

    public function appendIfElse($condition, array $jsLinesTrue, array $jsLinesFalse)
    {
        if($condition)
        {
            $this->append($jsLinesTrue);

        }   else {
            $this->append($jsLinesFalse);
        }

    }

    public function append(array $jsLines)
    {
        foreach($jsLines as $jsLine)
        {
            $this->jsLines.= $jsLine." ";

        }
    }

    public function getCode()
    {
        return $this->jsLines;
    }
}