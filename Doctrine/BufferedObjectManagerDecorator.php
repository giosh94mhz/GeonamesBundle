<?php
namespace Giosh94mhz\GeonamesBundle\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BufferedObjectManagerDecorator implements ObjectManager
{
    const DEFAULT_BUFFER_SIZE = 100;

    protected $em;

    protected $objectBuffer;

    protected $maxBufferSize;

    protected $bufferSize;

    private $onTransactionalFlush;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->objectBuffer = array();
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
        $this->maxBufferSize = intval($maxBufferSize) ?: self::DEFAULT_BUFFER_SIZE;

        if ($this->bufferSize >= $this->maxBufferSize)
            $this->flush();

        return $this;
    }

    public function find($className, $id)
    {
        return $this->em->find($className, $id);
    }

    public function persist($object)
    {
        $this->em->persist($object);

        $this->buffer($object);
    }

    public function remove($object)
    {
        $this->em->remove($object);

        $this->buffer($object);
    }

    public function merge($object)
    {
        $managed = $this->em->merge($object);

        $this->buffer($managed);

        return $managed;

    }

    public function clear($objectName = null)
    {
        $this->em->clear($objectName);

        foreach ($this->objectBuffer as $object)
            if ($object instanceof $objectName)
                $this->unbuffer($object);
    }

    public function detach($object)
    {
        $this->em->detach($object);

        $this->unbuffer($object);
    }

    public function refresh($object)
    {
        $this->em->refresh($object);

        $this->unbuffer($object);
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
        $this->em->flush($this->objectBuffer);
        foreach ($this->objectBuffer as $object)
            $this->em->detach($object);

        $this->objectBuffer = array();
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

    public function initializeObject($object)
    {
        $this->em->initializeObject($object);
    }

    public function contains($object)
    {
        return $this->em->contains($object);
    }

    private function getKey($object)
    {
        return array_search($object, $this->objectBuffer, true);
    }

    private function buffer($object)
    {
        $key = $this->getKey($object);
        if ($key !== false)
            return;

        $this->objectBuffer[] =  $object;

        ++ $this->bufferSize;
        if ($this->bufferSize >= $this->maxBufferSize)
            $this->flush();
    }

    private function unbuffer($object)
    {
        $key = $this->getKey($object);
        if ($key === false)
            return;

        unset($this->objectBuffer[$key]);
        -- $this->bufferSize;
    }
}
