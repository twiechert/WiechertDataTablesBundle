<?php
/**
 * Reflectable elements (classes, properties and methods) have to implement this interface.
 * User: Tayfun Wiechert
 * Date: 13.08.13
 * Time: 18:00
 */

namespace Wiechert\DataTablesBundle\EntityReflection\Reflector;


interface Reflectable
{

    const GROUPANNOTATIONCLASS = "JMS\\Serializer\\Annotation\\Groups";
    const DISPLAYNAMEANNOTATIONCLASS = "Wiechert\\DataTablesBundle\\Annotations\\DisplayName";
    const MANYTOONEANNOTATIONCLASS = "Doctrine\\ORM\\Mapping\\ManyToOne";
    const ONETOONEANNOTATIONCLASS = "Doctrine\\ORM\\Mapping\\OneToOne";
    const MANYTOMANYANNOTATIONCLASS = "Doctrine\\ORM\\Mapping\\ManyToMany";
    const ONETOMANYANNOTATIONCLASS = "Doctrine\\ORM\\Mapping\\OneToMany";


    // *-to-Many-Relations are ComplexReferences and not implemented yet!

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return \Reflector
     */
    public function getReflector();

    /**
     * @param \Reflector $reflector
     */
    public function setReflector(\Reflector $reflector);


}