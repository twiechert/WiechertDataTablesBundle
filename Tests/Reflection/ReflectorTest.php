<?php
/**
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */
namespace Wiechert\DataTablesBundle\Tests\Reflection;

use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Creation\EntityReflectionFactory;
use Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector;
use Wiechert\DataTablesBundle\Util\ArrayAccessor;

class ReflectorTest extends \PHPUnit_Framework_TestCase{


    private $combinations = array();
    public function readMemberAnnotationHandler()
    {
        $result = ArrayAccessor::accessArrayWithCallback($this->combinations, func_get_args(), true, function($key) {
                                                                                                            if(is_object($key))
                                                                                                            {
                                                                                                              return spl_object_hash($key);
                                                                                                            } else {
                                                                                                                return $key;
                                                                                                            }
                                                                                                        });
        return ($result) ? $result : null;
    }


    public function testEntityReflectionFactoryAndEntityReflectors()
    {

        $orderClassReflector = new \ReflectionClass('Wiechert\\DataTablesBundle\\Entity\\Order');
        $orderIdMemberReflector = $orderClassReflector->getProperty('id');
        $orderTotalAmountMemberReflector = $orderClassReflector->getProperty('totalamount');
        $orderUserMemberReflector = $orderClassReflector->getProperty('user');

        $categoryReflectionContexMock =  $this->getMock('Wiechert\DataTablesBundle\TableGenerator\EntityReflection\ReflectionContext',
            array('getDepth', 'getPath'));

        $categoryReflectionContexMock->expects($this->any())
            ->method('getDepth')
            ->will($this->returnValue(1));

        $categoryReflectionContexMock->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue("order."));

        $annotationreaderMock =  $this->getMock('Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reader\IAnnotationReader' , array('readMemberAnnotation'));

        $this->combinations[spl_object_hash($orderTotalAmountMemberReflector)][Reflector\Reflectable::DISPLAYNAMEANNOTATIONCLASS] = "Total Amount";
        $this->combinations[spl_object_hash($orderIdMemberReflector)][Reflector\Reflectable::DISPLAYNAMEANNOTATIONCLASS] = "Id";
        $this->combinations[spl_object_hash($orderUserMemberReflector)][Reflector\Reflectable::DISPLAYNAMEANNOTATIONCLASS] = "Creator";

        $this->combinations[spl_object_hash($orderTotalAmountMemberReflector)][Reflector\Reflectable::MANYTOONEANNOTATIONCLASS] = null;
        $this->combinations[spl_object_hash($orderTotalAmountMemberReflector)][Reflector\Reflectable::ONETOONEANNOTATIONCLASS] =  null;

        $this->combinations[spl_object_hash($orderIdMemberReflector)][Reflector\Reflectable::MANYTOONEANNOTATIONCLASS] = null;
        $this->combinations[spl_object_hash($orderIdMemberReflector)][Reflector\Reflectable::ONETOONEANNOTATIONCLASS] =  null;

        $manyToOne = Reflector\Reflectable::MANYTOONEANNOTATIONCLASS;
        $manyToOne = new $manyToOne();
        $manyToOne->targetEntity = "Wiechert\\DataTablesBundle\\Entity\\User";

        $this->combinations[spl_object_hash($orderUserMemberReflector)][Reflector\Reflectable::MANYTOONEANNOTATIONCLASS] = $manyToOne;
        $this->combinations[spl_object_hash($orderUserMemberReflector)][Reflector\Reflectable::ONETOONEANNOTATIONCLASS] =  null;


        $annotationreaderMock->expects($this->any())
                              ->method('readMemberAnnotation')
                              ->with( $this->anything(), $this->anything())
                              ->will($this->returnCallback(array($this, "readMemberAnnotationHandler")));



        $entityReflectionFactory = new EntityReflectionFactory($annotationreaderMock);

        $orderTotalAmountMemberEntityReflector = $entityReflectionFactory->createEntityReflector($orderTotalAmountMemberReflector, $categoryReflectionContexMock);
        $orderIdMemberEntityReflectorReflector = $entityReflectionFactory->createEntityReflector($orderIdMemberReflector, $categoryReflectionContexMock);
        $orderUserMemberEntityReflector = $entityReflectionFactory->createEntityReflector($orderUserMemberReflector, $categoryReflectionContexMock);

        $this->assertInstanceOf('Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\EntityPropertyReflector', $orderTotalAmountMemberEntityReflector);
        $this->assertInstanceOf('Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\EntityPropertyReflector', $orderIdMemberEntityReflectorReflector);
        $this->assertInstanceOf('Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Reflector\EntityPropertyReflector', $orderUserMemberEntityReflector);

        $this->assertEquals($orderTotalAmountMemberReflector, $orderTotalAmountMemberEntityReflector->getReflector());
        $this->assertEquals($orderIdMemberReflector, $orderIdMemberEntityReflectorReflector->getReflector());
        $this->assertEquals($orderUserMemberReflector, $orderUserMemberEntityReflector->getReflector());

     //   $this->assertEquals($orderTotalAmountMemberEntityReflector->getLabel(), 'Total Amount');
//        $this->assertEquals($orderIdMemberEntityReflectorReflector->getLabel(), 'Id');
   //     $this->assertEquals($orderUserMemberReflector->getLabel(), 'Creator');

        $this->assertEquals($orderTotalAmountMemberEntityReflector->getName(), 'totalamount');
        $this->assertEquals($orderIdMemberEntityReflectorReflector->getName(), 'id');
        $this->assertEquals($orderUserMemberEntityReflector->getName(), 'user');

        $this->assertEquals($orderTotalAmountMemberEntityReflector->getPath(), 'order.totalamount');
        $this->assertEquals($orderIdMemberEntityReflectorReflector->getPath(), 'order.id');
        $this->assertEquals($orderUserMemberEntityReflector->getPath(), 'order.user');

        $this->assertFalse($orderTotalAmountMemberEntityReflector->isSimpleReference());
        $this->assertFalse($orderIdMemberEntityReflectorReflector->isSimpleReference());
        $this->assertTrue($orderUserMemberEntityReflector->isSimpleReference());

        $this->assertEquals($orderUserMemberEntityReflector->getTargetEntityClass(), $manyToOne->targetEntity);





    }

}