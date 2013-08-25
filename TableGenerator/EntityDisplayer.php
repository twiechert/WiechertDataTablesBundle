<?php


namespace Wiechert\DataTablesBundle\TableGenerator;

use Metadata\MetadataFactoryInterface;
use Symfony\Component\Yaml\Yaml;
use Wiechert\DataTablesBundle\Util\Appender;

class EntityDisplayer extends Displayer {
    private $entity;

    public function generateHTMLCode()
    {
        $appender = new Appender();
        $appender->append(array('<table class="table table-condensed"><tr>',
                                '<th> <h4>Property</h4></th><th><h4>Value</h4></th></tr>'));

        foreach($this->getBaseContext()->getSimpleMemberReflectors() as $member)
        {
                $appender->append(array('<tr>',
                    '  <td >'.$member->getLabel().'</td>',
                    '  <td >'.$member->getValue($this->entity).'</td>',
                    '<tr>' ));
        }

        foreach ($this->getGraphResolveTransformer()->transform() as $referenceMember) {

            $appender->append(array('<tr>',
                ' <td colspan="2" ><h5>'.$referenceMember->getLabel().'<h5></td>',
                '</tr>'));

            foreach ($referenceMember->getReferencedReflectionContext()->getSimpleMemberReflectors() as  $simpleMember) {

                $appender->append(array('<tr>',
                    ' <td>'.$simpleMember->getLabel().'</td>',
                    ' <td>'.$this->getBaseContext()->getValue($simpleMember, $this->entity).'</td>',
                    '</tr>'));

            }
        }


        $appender->append(array('</table>'));
        return $appender->getCode();

    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }


}