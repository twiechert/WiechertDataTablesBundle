<?php
/**
 * User: Tayfun Wiechert
 * Date: 14.08.13
 * Time: 18:28
 */

namespace Wiechert\DataTablesBundle\EntityReflection\Reflector;


use Wiechert\DataTablesBundle\EntityReflection\IReflectionContext;

interface IEntityMemberReflector
{

    /**
     * @return mixed
     */
    public function getGroups();

    /**
     * @param array $groups
     */
    public function setGroups(array $groups);

    /**
     * @param $object
     * @return mixed
     */
    public function getValue($object);

    /**
     * @param IReflectionContext $context
     */
    public function setReferencedReflectionContext(IReflectionContext $context);

    /**
     * @return null|IReflectionContext
     */
    public function getReferencedReflectionContext();

    /**
     * @return bool
     */
    public function hasReferencedReflectionContext();

    /**
     * @param IReflectionContext $context
     */
    public function setReflectionContext(IReflectionContext $context);

    /**
     * @return IReflectionContext
     */
    public function getReflectionContext();

    /**
     * @return string
     */
    public function getPath();


}