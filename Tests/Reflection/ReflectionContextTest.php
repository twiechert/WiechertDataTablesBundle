<?php
namespace Wiechert\DataTablesBundle\Tests\Reflection;

use Wiechert\DataTablesBundle\Annotations\DisplayName;
use Wiechert\DataTablesBundle\Entity\Order;
use Wiechert\DataTablesBundle\Entity\User;
use Wiechert\DataTablesBundle\EntityReflection\Creation\EntityReflectorFactory;
use Wiechert\DataTablesBundle\EntityReflection\Creation\ReflectionContextFactory;
use Wiechert\DataTablesBundle\EntityReflection\Reflector;
use Wiechert\DataTablesBundle\Util\ArrayAccessor;
use \Mockery as m;

class ReflectionContextTest extends \PHPUnit_Framework_TestCase
{


    public function testReflectionContext()
    {
        $orderClassReflector = new \ReflectionClass('Wiechert\\DataTablesBundle\\Entity\\Order');
        $userClassReflector = new \ReflectionClass('Wiechert\\DataTablesBundle\\Entity\\User');
        $orderIdMemberReflector = $orderClassReflector->getProperty('id');
        $orderTotalAmountMemberReflector = $orderClassReflector->getProperty('totalamount');
        $orderUserMemberReflector = $orderClassReflector->getProperty('user');

        $userIdMemberReflector = $userClassReflector->getProperty('id');
        $userUsernameMemberReflector = $userClassReflector->getProperty('username');

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
        $reflectionContextFactory = new ReflectionContextFactory();


        $orderEntityClassReflector = $entityReflectorFactory->createEntityReflector($orderClassReflector);
        $orderReflectionContext = $reflectionContextFactory->createBaseReflectionContext(0, $orderEntityClassReflector);

        $orderTotalAmountMemberEntityReflector = $entityReflectorFactory->createEntityReflector($orderTotalAmountMemberReflector, $orderReflectionContext);
        $orderIdMemberEntityReflectorReflector = $entityReflectorFactory->createEntityReflector($orderIdMemberReflector, $orderReflectionContext);
        $orderUserMemberEntityReflector = $entityReflectorFactory->createEntityReflector($orderUserMemberReflector, $orderReflectionContext);

        $orderReflectionContext->addMemberReflector($orderTotalAmountMemberEntityReflector);
        $orderReflectionContext->addMemberReflector($orderIdMemberEntityReflectorReflector);

        $userEntityClassReflector = $entityReflectorFactory->createEntityReflector($userClassReflector, $orderReflectionContext);
        $userReflectionContext = $reflectionContextFactory->createReflectionContext(1, $userEntityClassReflector, $orderUserMemberEntityReflector);

        $userIdMemberEntityReflectorReflector = $entityReflectorFactory->createEntityReflector($userIdMemberReflector, $userReflectionContext);
        $userUsernameMemberEntityReflector = $entityReflectorFactory->createEntityReflector($userUsernameMemberReflector, $userReflectionContext);
        $orderUserMemberEntityReflector->setReferencedReflectionContext($userReflectionContext);

        $userReflectionContext->addMemberReflector($userIdMemberEntityReflectorReflector);
        $userReflectionContext->addMemberReflector($userUsernameMemberEntityReflector);
        $orderReflectionContext->addMemberReflector($orderUserMemberEntityReflector);

        $user = new User();

        $user->setUsername('taynet');
        $order = new Order();
        $order->setTotalamount(200);
        $order->setUser($user);

        $this->assertEquals(1, $orderReflectionContext->getCountOfReferenceMembersReflectors());
        $this->assertEquals(2, $orderReflectionContext->getCountOfSimpleMemberReflectors());
        $this->assertEquals(0, $userReflectionContext->getCountOfReferenceMembersReflectors());
        $this->assertEquals(2, $userReflectionContext->getCountOfSimpleMemberReflectors());

        $this->assertEquals('user.', $userReflectionContext->getPath());
        $this->assertEquals('', $orderReflectionContext->getPath());
        $this->assertEquals('taynet', $orderReflectionContext->getValue($userUsernameMemberEntityReflector, $order));
        $this->assertEquals(200, $orderReflectionContext->getValue($orderTotalAmountMemberEntityReflector, $order));

        $this->assertEquals(array('user' => $orderUserMemberEntityReflector), $orderReflectionContext->getReferenceMemberReflectors());
        $this->assertEquals(array(), $userReflectionContext->getReferenceMemberReflectors());

        $this->assertEquals(array('totalamount' => $orderTotalAmountMemberEntityReflector, 'id' => $orderIdMemberEntityReflectorReflector), $orderReflectionContext->getSimpleMemberReflectors());
        $this->assertEquals(array('id' => $userIdMemberEntityReflectorReflector, 'username' => $userUsernameMemberEntityReflector), $userReflectionContext->getSimpleMemberReflectors());
    }


    public function tearDown() {
        m::close();
    }


}
