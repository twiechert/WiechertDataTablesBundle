<?php
/**
 * implementations of this interface represent a context in the reflection process.
 * A context is defined of the class being reflected and the position within the reflection graph.
 * The context points to all members of the reflection class.
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */

namespace Wiechert\DataTablesBundle\EntityReflection;


use Wiechert\DataTablesBundle\EntityReflection\Reflector\EntityClassReflector;
use Wiechert\DataTablesBundle\EntityReflection\Reflector\IEntityMemberReflector;

interface IReflectionContext
{

    /**
     * Every ReflectionContext has a path relative to the BaseContext.
     * A path could be: 'position.order.user'
     *
     * @return string the path of the implementing context
     */
    public function getPath();

    /**
     * Converts the path into an array.
     * ['position', 'order', 'user']
     * @return string[]
     */
    public function getArrayPath();

    /**
     * @param string $path
     */
    public function setPath($path);

    /**
     * A ReflectionContext always has a depth, whereas the Base- or Root-Context has a depth of 0.
     * position (depth: 0) -> order (depth: 1)-> user (depth: 2)
     *                     -> product (depth: 1)
     * @return int
     */
    public function getDepth();

    /**
     * @param int $depth
     */
    public function setDepth($depth);

    /**
     * A ReflectionContext always points to one specific class (considering the position within the reflection graph via a path)
     * @return  EntityClassReflector
     */
    public function getClassReflector();

    /**
     * @param EntityClassReflector $reflector
     */
    public function setClassReflector(EntityClassReflector $reflector);

    /**
     * Entity members, that a part of the reflection, can be added with this method.
     * @param IEntityMemberReflector $reflector the reflector
     */
    public function addMemberReflector(IEntityMemberReflector $reflector);

    /**
     * @return IEntityMemberReflector[]  EntityMemberReflectors added
     */
    public function getMemberReflectors();

    /**
     * @return IEntityMemberReflector[] simple (primitive) EntityMemberReflectors added
     */
    public function getSimpleMemberReflectors();

    /**
     * @return IEntityMemberReflector[] reference (Objects) EntityMemberReflectors added
     */
    public function getReferenceMemberReflectors();

    /**
     * @return int the number of simple (primitive) EntityMemberReflectors added
     */
    public function getCountOfSimpleMemberReflectors();

    /**
     * @return int the number of reference (objects) EntityMemberReflectors added
     */
    public function getCountOfReferenceMembersReflectors();

    /**
     * Generates an array-representation of this and referenced contexts.
     * @return array
     */
    public function toArray();

    /**
     * Even though IEntityMemberReflector already defines a method for accessing an entity's value,
     * there needs to be an implementation in a ReflectionContext too.
     *
     * For the implementation in the MemberReflectors you have to pass the object that contains that member.
     * But in most cases, you only know the Root-Entity:
     *
     *  Position (19) -> Order (12) -> User (13)
     *
     * To get the user's name, you would need the user object (in a generic context, you probably only know the position).
     * Instead you can use any object in the object graph and the corresponding ReflectionContext (in most cases the root entity and the base context).
     *
     * @param IEntityMemberReflector $reflector
     * @param $object
     * @return mixed
     */
    public function getValue(IEntityMemberReflector $reflector, $object);


}