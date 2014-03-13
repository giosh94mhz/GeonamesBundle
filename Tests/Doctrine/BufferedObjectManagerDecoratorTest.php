<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Giosh94mhz\GeonamesBundle\Doctrine\BufferedObjectManagerDecorator;

class BufferedObjectManagerDecoratorTest extends \PHPUnit_Framework_TestCase
{
    protected $em;

    protected $decorator;

    public function setUp()
    {
        $this->em = $this->getMock('\Doctrine\ORM\EntityManagerInterface');
        $this->decorator = new BufferedObjectManagerDecorator($this->em);
    }

    public function testMaxBufferSize()
    {
        $this->assertEquals(
            BufferedObjectManagerDecorator::DEFAULT_BUFFER_SIZE,
            $this->decorator->getMaxBufferSize()
        );

        $size = BufferedObjectManagerDecorator::DEFAULT_BUFFER_SIZE + 1;
        $this->assertSame($this->decorator, $this->decorator->setMaxBufferSize($size));
        $this->assertEquals($size, $this->decorator->getMaxBufferSize());
    }

    public function testFind()
    {
        $className = '\stdClass';
        $id = 1;
        $found = new \stdClass();
        $this->em
            ->expects($this->once())
            ->method('find')
            ->with($className, $id)
            ->will($this->returnValue($found))
        ;
        $this->assertSame($found,  $this->decorator->find($className, $id));
    }

    public function testPersist()
    {
        $object = new \stdClass();
        $this->em
            ->expects($this->once())
            ->method('persist')
            ->with($this->identicalTo($object))
        ;
        $this->decorator->persist($object);

        $this->assertEquals(1, $this->decorator->getBufferSize());
    }

    public function testRemove()
    {
        $object = new \stdClass();
        $this->decorator->persist($object);
        $this->assertEquals(1, $this->decorator->getBufferSize());

        $this->em
            ->expects($this->once())
            ->method('remove')
            ->with($this->identicalTo($object))
        ;
        $this->decorator->remove($object);
        $this->assertEquals(1, $this->decorator->getBufferSize());
    }

    public function testMerge()
    {
        $object = new \stdClass();
        $merged = new \stdClass();
        $this->em
            ->expects($this->once())
            ->method('merge')
            ->with($this->identicalTo($object))
            ->will($this->returnValue($merged))
        ;
        $this->assertSame($merged, $this->decorator->merge($object));
        $this->assertEquals(1, $this->decorator->getBufferSize());
    }

    public function testClear()
    {
        $objectName = '\stdClass';
        $object = new \stdClass();
        $this->decorator->persist($object);
        $this->assertEquals(1, $this->decorator->getBufferSize());

        $this->em
            ->expects($this->once())
            ->method('clear')
            ->with($objectName)
        ;
        $this->decorator->clear($objectName);
        $this->assertEquals(0, $this->decorator->getBufferSize());
    }

    public function testDetach()
    {
        $object = new \stdClass();
        $this->decorator->persist($object);
        $this->assertEquals(1, $this->decorator->getBufferSize());

        $this->em
            ->expects($this->once())
            ->method('detach')
            ->with($this->identicalTo($object))
        ;
        $this->decorator->detach($object);
        $this->assertEquals(0, $this->decorator->getBufferSize());
    }

    public function testRefresh()
    {
        $object = new \stdClass();
        $this->decorator->persist($object);
        $this->assertEquals(1, $this->decorator->getBufferSize());

        $this->em
            ->expects($this->once())
            ->method('refresh')
            ->with($object)
        ;
        $this->decorator->refresh($object);
        $this->assertEquals(0, $this->decorator->getBufferSize());
    }

    public function testFlush()
    {
        $this->decorator->setMaxBufferSize(2);

        if (method_exists($this->em, 'transactional')) {
            $this->em
                ->expects($this->exactly(2))
                ->method('transactional')
                ->will($this->returnCallback('call_user_func'))
            ;
        }
        $this->em
            ->expects($this->exactly(2))
            ->method('flush')
        ;

        $this->assertEquals(0, $this->decorator->getBufferSize());

        $this->decorator->persist(new \stdClass());
        $this->assertEquals(1, $this->decorator->getBufferSize());

        $this->decorator->persist(new \stdClass());
        $this->assertEquals(0, $this->decorator->getBufferSize());

        $this->decorator->persist(new \stdClass());
        $this->assertEquals(1, $this->decorator->getBufferSize());

        $this->decorator->persist(new \stdClass());
        $this->assertEquals(0, $this->decorator->getBufferSize());
    }

    public function testGetRepository()
    {
        $className = '\stdClass';
        $repository = new \stdClass();
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($className)
            ->will($this->returnValue($repository))
        ;
        $this->assertSame($repository, $this->decorator->getRepository($className));
    }

    public function testGetClassMetadata()
    {
        $className = '\stdClass';
        $metadata = new \stdClass();
        $this->em
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with($className)
            ->will($this->returnValue($metadata))
        ;
        $this->assertSame($metadata, $this->decorator->getClassMetadata($className));
    }

    public function testGetMetadataFactory()
    {
        $metadataFactory = new \stdClass();
        $this->em
            ->expects($this->once())
            ->method('getMetadataFactory')
            ->will($this->returnValue($metadataFactory))
        ;
        $this->assertSame($metadataFactory, $this->decorator->getMetadataFactory());
    }

    public function testInitializeObject()
    {
        $object = new \stdClass();
        $this->em
            ->expects($this->once())
            ->method('initializeObject')
            ->with($object)
        ;
        $this->decorator->initializeObject($object);
    }

    public function testContains()
    {
        $object = new \stdClass();
        $this->em
            ->expects($this->once())
            ->method('contains')
            ->with($object)
            ->will($this->returnValue(true))
        ;
        $this->assertTrue($this->decorator->contains($object));
    }

    public function testMagicCall()
    {
        $object = new \stdClass();
        $this->em
            ->expects($this->once())
            ->method('copy')
            ->with($object)
            ->will($this->returnValue(new \stdClass()))
        ;
        $this->assertNotSame($object, $this->decorator->copy($object));
    }
}
