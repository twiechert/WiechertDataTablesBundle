<?php

/**
 * Diese Klasse kann den nötigen HTML-Code, sowie das nötige JavaScript generieren,
 * welches für die Generierung einer Datatable (zu einem Doctrine-Repositry gehörend) notwendig ist.
 *
 * Die Klasse ist als Service implementiert und erwartet den Doctrine AnnotationReader sowie den EntityManager.
 * User: Tayfun Wiechert
 * Date: 10.01.13
 * Time: 11:14
 *
 */

namespace Wiechert\DataTablesBundle\TableGenerator;

use Wiechert\DataTablesBundle\Util\Appender;
use Wiechert\DataTablesBundle\Util\ArrayAccessor;
use Wiechert\DataTablesBundle\Util\IAppender;

class TableGenerator extends Displayer
{

    /**
     * @var array
     */
    private $options = array(
        'draggable' => false,
        'droppable' => false,
        'actions' => true,
        'offlineMode' => false,
        'customId' => false,
        'globalActions' => true,
        'initialCall' => true,
        'customRoute' => false
    );

    /**
     * @var null|string
     */
    private $whereParam = null;

    /**
     * @var null|string
     */
    private $datatableName = null;

    /**
     * @var null|string
     */
    private $relatedDatatableEntityName = null;

    /**
     * @var null|string
     */
    private $relatedDatatableBundleName = null;

    /**
     * @var string
     */
    protected $tableID = null;


    public function initialize()
    {
        parent::initialize();
        $this->tableID = uniqid();
    }

    /**
     *
     * @return bool
     */
    public function hasPhpActions()
    {
        return ($this->getPhpActions()) ? true : false;
    }

    /**
     *
     * @return array
     */
    public function getPhpActions()
    {
        return ArrayAccessor::accessArray($this->tableConfig, array('Actions', 'PHPActions'));
    }

    /**
     *
     * @return bool
     */
    public function hasGlobalPhpActions()
    {
        return ($this->getGlobalPhpActions()) ? true : false;
    }

    /**
     *
     * @return array
     */
    public function getGlobalPhpActions()
    {
        return ArrayAccessor::accessArray($this->tableConfig, array('GlobalActions', 'PHPActions'));
    }

    /**
     *
     * @return array
     */
    public function getJavaScriptAssets()
    {
        $assets = array();
        if ($this->hasGlobalJavaScriptActions()) {
            foreach ($this->getGlobalJavaScriptActions() as $jsGlobalAction) {
                $assets[] = $jsGlobalAction['asset'];
            }
        }

        if ($this->hasJavaScriptActions()) {
            foreach ($this->getJavaScriptActions() as $jsAction) {
                $assets[] = $jsAction['asset'];
            }
        }

        return $assets;
    }

    /**
     *
     * @return bool
     */
    public function hasGlobalJavaScriptActions()
    {
        return ($this->getGlobalJavaScriptActions()) ? true : false;
    }

    /**
     *
     * @return array
     */
    public function getGlobalJavaScriptActions()
    {
        return ArrayAccessor::accessArray($this->tableConfig, array('GlobalActions', 'JSActions'));
    }

    /**
     *
     * @return bool
     */
    public function hasJavaScriptActions()
    {
        return ($this->getJavaScriptActions()) ? true : false;
    }

    /**
     *
     * @return array
     */
    public function getJavaScriptActions()
    {
        return ArrayAccessor::accessArray($this->tableConfig, array('Actions', 'JSActions'));
    }

    public function generateJavaSctipt()
    {

        $route = ($this->getOption('customRoute')) ? $this->getOption('customRoute') : 'wiechert_core_get_data_for_datatable';

        $appender = new Appender();
        $appender->appendIfElse($this->isForNamedTable(), array("window['url-" . $this->getTableID() . "'] = Routing.generate('" . $route . "',  {strategy: '" . $this->getReflector()->getExclusionStrategy()->getName() . "',",
                "                                                                              bundle: '" . $this->getRelatedDatatableBundleName() . "',",
                "                                                                              entity: '" . $this->getRelatedDatatableEntityName() . "',",
                "                                                                              name: '" . $this->getDatatableName() . "',",
                "                                                                              id: '" . $this->getWhereParam() . "' });"),

            array("window['url-" . $this->getTableID() . "'] = Routing.generate('" . $route . "',  {strategy: '" . $this->getReflector()->getExclusionStrategy()->getName() . "',",
                "                                                                              bundle: '" . $this->getBundleName() . "',",
                "                                                                              entity: '" . $this->getEntityName() . "'  });"));

        $appender->append(array("function makeDraggableEach(selector) {",
            "$(selector).each(function(){",
            "    $(this).draggable( { helper: 'clone',",
            "                        revert:  'invalid'} );",
            "     })",
            ";}",
            "$(document).ready(function() {"));


        $appender->append(array("window['odTable_" . $this->getTableID() . "']  = $('#" . $this->getRealTableId() . "').dataTable( {",
            "'bProcessing': true,",
            "'sPaginationType': 'full_numbers',",
            "'bRetrieve': true,"));

        $appender->appendIf(!$this->getOption('offlineMode'), array("'bServerSide': true,"));
        $appender->append(array("'fnDrawCallback': function( oSettings ) {},"));
        $appender->appendIf(!$this->getOption('offlineMode')  or
        $this->getOption('initialCall'), array("'sAjaxSource':    window['url-" . $this->getTableID() . "']  ,"));

        $appender->append(array(" 'aoColumns': ["));
        $actions = ArrayAccessor::accessArray($this->tableConfig, array('Actions'));

        if ($actions && $this->getOption('actions')) {
            $phpActions = ArrayAccessor::accessArray($actions, array('PHPActions'));
            $jsActions = ArrayAccessor::accessArray($actions, array('JSActions'));

            $appender->append(array("{'mData': 'id', 'bSortable' : false,",
                " 'mRender': function(data, type, full) {return '<div class=\"btn-group\">",
                "                                                <a class=\"btn dropdown-toggle\" data-toggle= \"dropdown\" >Action",
                "                                                <span class=\"caret\"></span></a>",
                "                                                <ul class=\"dropdown-menu\">"));


            if ($phpActions) {
                foreach ($phpActions as $phpAction) {
                    $appender->append(array("<li><a  href=\"'+Routing.generate('" . $phpAction['route'] . "',  {bundle : '" . $this->getBundleName() . "',",
                        "                                                                   entity : '" . $this->getEntityName() . "',",
                        "                                                                   id : data}) +' \">" . $phpAction['name'] . "</a></li>"));
                }
            }

            if ($jsActions) {
                foreach ($jsActions as $jsAction) {
                    $appender->append(array("<li><a onclick=\"" . $jsAction["function"] . "(\'" . $this->getRealTableId() . "\', \'Extended\', \'" . $this->repositoryName . "\',\'" . $this->getRealEntityName() . "\', \''+data+'\')\" >" . $jsAction['name'] . "</a></li>"));
                }

            }

            $appender->append(array("</ul></div>'; }},"));
        }


        foreach ($this->getBaseContext()->getSimpleMemberReflectors() as $member) {

            $appender->append(array("{'mData': '" . $member->getPath() . "'  ,",
                "'mRender': function(data, type, full) {",
                "           if(data == undefined)",
                "               return '-';",
                "           else",
                "               return data;}",
                " },"));

        }

        foreach ($this->getGraphResolveTransformer()->transform() as $referenceMember) {

            foreach ($referenceMember->getReferencedReflectionContext()->getSimpleMemberReflectors() as $simpleMember) {

                $appender->append(array("{'mData': '" . $simpleMember->getPath() . "' ,",
                    " 'mRender': function(data, type, full) {",
                    "          if(data == undefined)",
                    "              return \"-\";",
                    "          else",
                    "              return data;}",
                    "},"));

            }

        }

        $appender->append(array("] }); } );"));
        return $appender->getCode();
    }

    public function getOption($name)
    {
        return $this->options[$name];
    }

    /**
     * @return bool
     */
    private function isForNamedTable()
    {
        return ($this->getWhereParam() != null);
    }

    /**
     * @return string|null
     */
    public function getWhereParam()
    {
        return $this->whereParam;
    }

    /**
     * @param string $whereParam
     */
    public function setWhereParam($whereParam)
    {
        $this->whereParam = $whereParam;
    }


    /**
     * @return string
     */
    public function getRealTableId()
    {
        if (!$this->getOption('customId')) {
            return "dataTable_" . $this->getTableID();

        } else {
            return $this->getOption('customId');
        }

    }

    /**
     * @return string
     */
    public function generateHTMLCode()
    {
        $appender = new Appender();
        $appender->append(array('<table cellpadding="0" cellspacing="0" border="0" class="table datatable table-striped table-bordered"  id="' . $this->getRealTableId() . '">'));
        $appender->append(array('<thead> <tr>'));
        $countsPerEntity = $this->getCountPerEntityTransformer()->transform();
        $first = true;

        foreach ($countsPerEntity as $countAndLabel) {
            if ($first) {
                $appender->appendIfElse($this->getOption('actions'), array('<th  colspan="' . ($countAndLabel['count'] + 1) . '">' . $countAndLabel['label'] . '</th>'),
                    array('<th  colspan="' . ($countAndLabel['count']) . '">' . $countAndLabel['label'] . '</th>'));
                $first = false;
            } else {
                $appender->append(array('<th colspan="' . ($countAndLabel['count']) . '">' . $countAndLabel['label'] . '</th>'));

            }
        }

        $appender->append(array('</tr><tr>'));
        $appender->appendIf($this->getOption('actions'), array('<th >Action</th>'));


        foreach ($this->getBaseContext()->getSimpleMemberReflectors() as $member) {
            $appender->append(array('<th name="' . $member->getPath() . '" >' . $member->getLabel() . '</th>'));
        }


        foreach ($this->getGraphResolveTransformer()->transform() as $referenceMember) {
            foreach ($referenceMember->getReferencedReflectionContext()->getSimpleMemberReflectors() as $simpleMember) {
                $appender->append(array('<th name= "' . $simpleMember->getPath() . '"  >' . $simpleMember->getLabel() . '</th>'));
            }
        }

        $appender->append(array('</tr></thead><tbody></tbody></table>'));
        return $appender->getCode();

    }

    /**
     * @param $name
     * @param $option
     */
    public function setOption($name, $option)
    {
        $this->options[$name] = $option;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param array $options
     */
    public function mergeOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @param string $datatableName
     */
    public function setDatatableName($datatableName)
    {
        $this->datatableName = $datatableName;
    }

    /**
     * @return null|string
     */
    public function getDatatableName()
    {
        return $this->datatableName;
    }

    /**
     * @param null $relatedDatatableBundle
     */
    public function setRelatedDatatableBundleName($relatedDatatableBundleName)
    {
        $this->relatedDatatableBundleName = $relatedDatatableBundleName;
    }

    /**
     * @return null
     */
    public function getRelatedDatatableBundleName()
    {
        return ($this->relatedDatatableBundleName != null) ? $this->relatedDatatableBundleName : $this->getBundleName();

    }

    /**
     * @param null $relatedDatatableEntity
     */
    public function setRelatedDatatableEntityName($relatedDatatableEntityName)
    {
        $this->relatedDatatableEntityName = $relatedDatatableEntityName;
    }

    /**
     * @return null
     */
    public function getRelatedDatatableEntityName()
    {
        return ($this->relatedDatatableEntityName != null) ? $this->relatedDatatableEntityName : $this->getEntityName();
    }

    /**
     * @return null|string
     */
    public function getTableID()
    {
        return $this->tableID;
    }







}