<?php
namespace Wiechert\DataTablesBundle\Tests\Reflection;

use Wiechert\DataTablesBundle\Annotations\DisplayName;
use Wiechert\DataTablesBundle\Entity\Order;
use Wiechert\DataTablesBundle\EntityReflection\Creation\EntityReflectorFactory;
use Wiechert\DataTablesBundle\EntityReflection\Reflector;
use Wiechert\DataTablesBundle\Util\ArrayAccessor;
use \Mockery as m;

class ReflectorTest extends \PHPUnit_Framework_TestCase
{

    public function testEntityReflectionFactoryAndEntityReflectors()
    {
        $orderClassReflector = new \ReflectionClass('Wiechert\\DataTablesBundle\\Entity\\Order');
        $userClassReflector = new \ReflectionClass('Wiechert\\DataTablesBundle\\Entity\\User');

        $orderIdMemberReflector = $orderClassReflector->getProperty('id');
        $orderTotalAmountMemberReflector = $orderClassReflector->getProperty('totalamount');
        $orderUserMemberReflector = $orderClassReflector->getProperty('user');

        $userIdMemberReflector = $userClassReflector->getProperty('id');
        $userUsernameMemberReflector = $userClassReflector->getProperty('username');

        $orderReflectionContexMock = m::mock('\\Wiechert\\DataTablesBundle\\EntityReflection\\IReflectionContext');
        $orderReflectionContexMock->shouldReceive('getDepth')->andReturn(0);
        $orderReflectionContexMock->shouldReceive('getPath')->andReturn("order.");

        $userReflectionContexMock = m::mock('\\Wiechert\\DataTablesBundle\\EntityReflection\\IReflectionContext');
        $userReflectionContexMock->shouldReceive('getDepth')->andReturn(1);
        $userReflectionContexMock->shouldReceive('getPath')->andReturn("order.user.");

        $annotationreaderMock = m::mock('\\Wiechert\\DataTablesBundle\\EntityReflection\\Reader\\IAnnotationReader');

        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($userIdMemberReflector, Reflector\Reflectable::DISPLAYNAMEANNOTATIONCLASS)
            ->andReturn(new DisplayName(array("name" => "Id")));

        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($userUsernameMemberReflector, Reflector\Reflectable::DISPLAYNAMEANNOTATIONCLASS)
            ->andReturn(new DisplayName(array("name" => "Username")));


        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($orderTotalAmountMemberReflector, Reflector\Reflectable::DISPLAYNAMEANNOTATIONCLASS)
            ->andReturn(new DisplayName(array("name" => "Total Amount")));

        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($orderIdMemberReflector, Reflector\Reflectable::DISPLAYNAMEANNOTATIONCLASS)
            ->andReturn(new DisplayName(array("name" => "Id")));

        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($orderUserMemberReflector, Reflector\Reflectable::DISPLAYNAMEANNOTATIONCLASS)
            ->andReturn(new DisplayName(array("name" => "Creator")));

        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($userIdMemberReflector, Reflector\Reflectable::MANYTOONEANNOTATIONCLASS)
            ->andReturnNull();

        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($userIdMemberReflector, Reflector\Reflectable::ONETOONEANNOTATIONCLASS)
            ->andReturnNull();

        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($userUsernameMemberReflector, Reflector\Reflectable::MANYTOONEANNOTATIONCLASS)
            ->andReturnNull();

        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($userUsernameMemberReflector, Reflector\Reflectable::ONETOONEANNOTATIONCLASS)
            ->andReturnNull();

        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($orderTotalAmountMemberReflector, Reflector\Reflectable::MANYTOONEANNOTATIONCLASS)
            ->andReturnNull();

        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($orderTotalAmountMemberReflector, Reflector\Reflectable::ONETOONEANNOTATIONCLASS)
            ->andReturnNull();

        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($orderIdMemberReflector, Reflector\Reflectable::ONETOONEANNOTATIONCLASS)
            ->andReturnNull();

        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($orderIdMemberReflector, Reflector\Reflectable::MANYTOONEANNOTATIONCLASS)
            ->andReturnNull();

        $manyToOne = Reflector\Reflectable::MANYTOONEANNOTATIONCLASS;
        $manyToOne = new $manyToOne();
        $manyToOne->targetEntity = "Wiechert\\DataTablesBundle\\Entity\\User";

        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($orderUserMemberReflector, Reflector\Reflectable::MANYTOONEANNOTATIONCLASS)
            ->andReturn($manyToOne);

        $annotationreaderMock->shouldReceive('readMemberAnnotation')
            ->with($orderUserMemberReflector, Reflector\Reflectable::ONETOONEANNOTATIONCLASS)
            ->andReturn(null);


        $entityReflectorFactory = new EntityReflectorFactory($annotationreaderMock);

        $orderTotalAmountMemberEntityReflector = $entityReflectorFactory->createEntityReflector($orderTotalAmountMemberReflector, $orderReflectionContexMock);
        $orderIdMemberEntityReflectorReflector = $entityReflectorFactory->createEntityReflector($orderIdMemberReflector, $orderReflectionContexMock);
        $orderUserMemberEntityReflector = $entityReflectorFactory->createEntityReflector($orderUserMemberReflector, $orderReflectionContexMock);

        $userIdMemberEntityReflectorReflector = $entityReflectorFactory->createEntityReflector($userIdMemberReflector, $userReflectionContexMock);
        $userUsernameMemberEntityReflector = $entityReflectorFactory->createEntityReflector($userUsernameMemberReflector, $userReflectionContexMock);

        $this->assertInstanceOf('Wiechert\DataTablesBundle\EntityReflection\Reflector\EntityPropertyReflector', $userIdMemberEntityReflectorReflector);
        $this->assertInstanceOf('Wiechert\DataTablesBundle\EntityReflection\Reflector\EntityPropertyReflector', $userUsernameMemberEntityReflector);
        $this->assertInstanceOf('Wiechert\DataTablesBundle\EntityReflection\Reflector\EntityPropertyReflector', $orderTotalAmountMemberEntityReflector);
        $this->assertInstanceOf('Wiechert\DataTablesBundle\EntityReflection\Reflector\EntityPropertyReflector', $orderIdMemberEntityReflectorReflector);
        $this->assertInstanceOf('Wiechert\DataTablesBundle\EntityReflection\Reflector\EntityPropertyReflector', $orderUserMemberEntityReflector);

        $this->assertEquals($userIdMemberReflector, $userIdMemberEntityReflectorReflector->getReflector());
        $this->assertEquals($userUsernameMemberReflector, $userUsernameMemberEntityReflector->getReflector());
        $this->assertEquals($orderTotalAmountMemberReflector, $orderTotalAmountMemberEntityReflector->getReflector());
        $this->assertEquals($orderIdMemberReflector, $orderIdMemberEntityReflectorReflector->getReflector());
        $this->assertEquals($orderUserMemberReflector, $orderUserMemberEntityReflector->getReflector());

        $this->assertEquals('Total Amount', $orderTotalAmountMemberEntityReflector->getLabel());
        $this->assertEquals('Id', $orderIdMemberEntityReflectorReflector->getLabel());
        $this->assertEquals('Creator', $orderUserMemberEntityReflector->getLabel());
        $this->assertEquals('Id', $userIdMemberEntityReflectorReflector->getLabel());
        $this->assertEquals('Username', $userUsernameMemberEntityReflector->getLabel());

        $this->assertEquals('totalamount', $orderTotalAmountMemberEntityReflector->getName());
        $this->assertEquals('id', $orderIdMemberEntityReflectorReflector->getName());
        $this->assertEquals('user', $orderUserMemberEntityReflector->getName());
        $this->assertEquals('id', $userIdMemberEntityReflectorReflector->getName());
        $this->assertEquals('username', $userUsernameMemberEntityReflector->getName());

        $this->assertEquals('order.totalamount', $orderTotalAmountMemberEntityReflector->getPath());
        $this->assertEquals('order.id', $orderIdMemberEntityReflectorReflector->getPath());
        $this->assertEquals('order.user', $orderUserMemberEntityReflector->getPath());
        $this->assertEquals('order.user.id', $userIdMemberEntityReflectorReflector->getPath());
        $this->assertEquals('order.user.username', $userUsernameMemberEntityReflector->getPath());

        $this->assertFalse($orderTotalAmountMemberEntityReflector->isSimpleReference());
        $this->assertFalse($orderIdMemberEntityReflectorReflector->isSimpleReference());
        $this->assertFalse($userIdMemberEntityReflectorReflector->isSimpleReference());
        $this->assertFalse($userUsernameMemberEntityReflector->isSimpleReference());

        $this->assertTrue($orderUserMemberEntityReflector->isSimpleReference());

        $this->assertEquals($manyToOne->targetEntity, $orderUserMemberEntityReflector->getTargetEntityClass());


    }
    public function tearDown() {
        m::close();
    }


}
