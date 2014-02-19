<?php
namespace Giosh94mhz\GeonamesBundle\Repository;

use Doctrine\ORM\Query\Expr\Select;

class ToponymProxyRepository extends ToponymRepository
{
    protected function createToponymQueryBuilder(array $extraSelects = array())
    {
        return $this->createQueryBuilder('proxy')
                    ->select(array_merge(array('proxy', 'toponym'), $extraSelects))
                    ->leftJoin('proxy.toponym', 'toponym');
    }
}
