<?php
namespace Giosh94mhz\GeonamesBundle\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BulkInsertObjectManagerDecorator implements ObjectManager
{
    const DEFAULT_BUFFER_SIZE = 100;

    protected $em;

    protected $entityBuffer;

    protected $maxBufferSize;

    protected $bufferSize;

    private $onTransactionalFlush;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->entityBuffer = array();
        $this->bufferSize = 0;
        $this->maxBufferSize = self::DEFAULT_BUFFER_SIZE;
        $this->onTransactionalFlush = false;
    }

    public function getBufferSize()
    {
        return $this->bufferSize;
    }

    public function getMaxBufferSize()
    {
        return $this->maxBufferSize;
    }

    public function setMaxBufferSize($maxBufferSize)
    {
        $this->maxBufferSize = intval($maxBufferSize) ?  : self::DEFAULT_BUFFER_SIZE;

        if ($this->bufferSize >= $this->maxBufferSize)
            $this->flush();

        return $this;
    }

    public function find($className, $id)
    {
        return $this->em->find($className, $id);
    }

    public function persist($entity)
    {
        $this->entityBuffer[] = $entity;

        $this->em->persist($entity);

        ++ $this->bufferSize;
        if ($this->bufferSize >= $this->maxBufferSize)
            $this->flush();
    }

    public function remove($object)
    {
        $this->em->remove($object);
    }

    public function merge($object)
    {
        return $this->em->merge($object);
    }

    public function clear($objectName = null)
    {
        $this->em->clear($objectName);
    }

    public function detach($object)
    {
        $this->em->detach($object);
        // TODO: remove from buffer
    }

    public function refresh($object)
    {
        $this->em->refresh($object);
    }

    public function flush()
    {
        /*
         * If transactional callback is available re-enter flush with onTransactionalFlush = true
         * and then call realFlush (this trick is required for compatibility with PHP 5.3+)
         */
        if (! $this->onTransactionalFlush && method_exists($this->em, 'transactional')) {
            $callback = array($this, 'flush');
            $onTransaction = &$this->onTransactionalFlush;

            $this->em->transactional(function () use ($callback, &$onTransaction) {
                $onTransaction = true;
                call_user_func($callback);
                $onTransaction = false;
            });
        } else {
            $this->realFlush();
        }
    }

    private function realFlush()
    {
        // flush and forget
        $this->em->flush($this->entityBuffer);
        foreach ($this->entityBuffer as $entity)
            $this->em->detach($entity);

        $this->entityBuffer = array();
        $this->bufferSize = 0;
    }

    public function getRepository($className)
    {
        return $this->em->getRepository($className);
    }

    public function getClassMetadata($className)
    {
        return $this->em->getClassMetadata($className);
    }

    public function getMetadataFactory()
    {
        return $this->em->getMetadataFactory();
    }

    public function initializeObject($obj)
    {
        return $this->em->initializeObject($obj);
    }

    public function contains($object)
    {
        return $this->em->contains($object);
    }
}
