<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tayfun
 * Date: 01.09.13
 * Time: 15:43
 * To change this template use File | Settings | File Templates.
 */

namespace Wiechert\DataTablesBundle\Tests\Reflection;


use Wiechert\DataTablesBundle\EntityReflection\Strategies\ExtendedStrategy;
use Wiechert\DataTablesBundle\EntityReflection\Strategies\SimpleStrategy;
use \Mockery as m;

class ExclusionStrategyTest  extends \PHPUnit_Framework_TestCase {

    public function testTreeGroupExclusionStrategy()
    {
        $simpleStrategy = new SimpleStrategy();
        $extendedStrategy = new ExtendedStrategy();

        $orderReflectionContexMock = m::mock('\\Wiechert\\DataTablesBundle\\EntityReflection\\IReflectionContext');
        $orderReflectionContexMock->shouldReceive('getDepth')->andReturn(0);

        $order2ReflectionContexMock = m::mock('\\Wiechert\\DataTablesBundle\\EntityReflection\\IReflectionContext');
        $order2ReflectionContexMock->shouldReceive('getDepth')->andReturn(1);
        $userReflectionContexMock = $order2ReflectionContexMock;

        $idEntityMemberReflectorMock = m::mock('\\Wiechert\\DataTablesBundle\\EntityReflection\\Reflector\\IEntityMemberReflector');
        $idEntityMemberReflectorMock->shouldReceive('getGroups')->andReturn(array('Id'));

        $totalAmountEntityMemberReflectorMock = m::mock('\\Wiechert\\DataTablesBundle\\EntityReflection\\Reflector\\IEntityMemberReflector');
        $totalAmountEntityMemberReflectorMock->shouldReceive('getGroups')->andReturn(array('Simple'));

        $userEntityMemberReflectorMock = m::mock('\\Wiechert\\DataTablesBundle\\EntityReflection\\Reflector\\IEntityMemberReflector');
        $userEntityMemberReflectorMock->shouldReceive('getGroups')->andReturn(array('SimpleReference'));

        $positionsEntityMemberReflectorMock = m::mock('\\Wiechert\\DataTablesBundle\\EntityReflection\\Reflector\\IEntityMemberReflector');
        $positionsEntityMemberReflectorMock->shouldReceive('getGroups')->andReturn(array('ComplexeReference'));

        $usernameEntityMemberReflectorMock = m::mock('\\Wiechert\\DataTablesBundle\\EntityReflection\\Reflector\\IEntityMemberReflector');
        $usernameEntityMemberReflectorMock->shouldReceive('getGroups')->andReturn(array('Simple'));

        $categoriesEntityMemberReflectorMock = m::mock('\\Wiechert\\DataTablesBundle\\EntityReflection\\Reflector\\IEntityMemberReflector');
        $categoriesEntityMemberReflectorMock->shouldReceive('getGroups')->andReturn(array('ComplexeReference'));

        $ordersEntityMemberReflectorMock = m::mock('\\Wiechert\\DataTablesBundle\\EntityReflection\\Reflector\\IEntityMemberReflector');
        $ordersEntityMemberReflectorMock->shouldReceive('getGroups')->andReturn(array('ComplexeReference'));

        $this->assertFalse($simpleStrategy->shouldSkipProperty($idEntityMemberReflectorMock, $orderReflectionContexMock));
        $this->assertFalse($simpleStrategy->shouldSkipProperty($totalAmountEntityMemberReflectorMock, $orderReflectionContexMock));
        $this->assertTrue($simpleStrategy->shouldSkipProperty($userEntityMemberReflectorMock, $orderReflectionContexMock));
        $this->assertTrue($simpleStrategy->shouldSkipProperty($positionsEntityMemberReflectorMock, $orderReflectionContexMock));

        $this->assertFalse($simpleStrategy->shouldSkipProperty($idEntityMemberReflectorMock, $order2ReflectionContexMock));
        $this->assertFalse($simpleStrategy->shouldSkipProperty($totalAmountEntityMemberReflectorMock, $order2ReflectionContexMock));
        $this->assertTrue($simpleStrategy->shouldSkipProperty($userEntityMemberReflectorMock, $order2ReflectionContexMock));
        $this->assertTrue($simpleStrategy->shouldSkipProperty($positionsEntityMemberReflectorMock, $order2ReflectionContexMock));

        $this->assertFalse($extendedStrategy->shouldSkipProperty($idEntityMemberReflectorMock, $orderReflectionContexMock));
        $this->assertFalse($extendedStrategy->shouldSkipProperty($totalAmountEntityMemberReflectorMock, $orderReflectionContexMock));
        $this->assertFalse($extendedStrategy->shouldSkipProperty($userEntityMemberReflectorMock, $orderReflectionContexMock));
        $this->assertTrue($extendedStrategy->shouldSkipProperty($positionsEntityMemberReflectorMock, $orderReflectionContexMock));

        $this->assertFalse($extendedStrategy->shouldSkipProperty($idEntityMemberReflectorMock, $userReflectionContexMock));
        $this->assertFalse($extendedStrategy->shouldSkipProperty($usernameEntityMemberReflectorMock, $userReflectionContexMock));
        $this->assertTrue($extendedStrategy->shouldSkipProperty($categoriesEntityMemberReflectorMock, $userReflectionContexMock));
        $this->assertTrue($extendedStrategy->shouldSkipProperty($ordersEntityMemberReflectorMock, $userReflectionContexMock));
    }


    public function tearDown() {
        m::close();
    }
}