<?php
namespace Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\Sqlite;

use Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\GeoDistance as GeoDistanceBase;
use Doctrine\ORM\Query\SqlWalker;
use Giosh94mhz\GeonamesBundle\Model\Measure;

/**
 * @author Giorgio Premi <giosh94mhz@gmail.com>
 */
class GeoDistance extends GeoDistanceBase
{
    public function getSql(SqlWalker $sqlWalker)
    {
        $p = $sqlWalker->getConnection()->getDatabasePlatform();

        return sprintf(
            '%F * ' . $p->getSqrtExpression('((%s - %s) * (%s - %s) + (%s - %s) * (%s - %s))'),
            Measure::RADIANS_TO_KM,
            $sqlWalker->walkArithmeticPrimary($this->latOrigin),
            $sqlWalker->walkArithmeticPrimary($this->latPoint),
            $sqlWalker->walkArithmeticPrimary($this->latOrigin),
            $sqlWalker->walkArithmeticPrimary($this->latPoint),
            $sqlWalker->walkArithmeticPrimary($this->lonOrigin),
            $sqlWalker->walkArithmeticPrimary($this->lonPoint),
            $sqlWalker->walkArithmeticPrimary($this->lonOrigin),
            $sqlWalker->walkArithmeticPrimary($this->lonPoint)
        );
    }
}
