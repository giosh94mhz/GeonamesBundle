<?php
namespace Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\Sqlite;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\SqlWalker;
use Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\LongitudeWithin as BaseLongitudeWithin;

/**
 * Usage: LONGITUDE_WITHIN(longitude, center, distanceInKm)
 * Usage: LONGITUDE_WITHIN(longitude, [latitude, ]center, distanceInKm)
 * if latitude is not specified,
 * Returns: boolean
 *
 * @author Premi Giorgio <giosh94mhz@gmail.com>
 */
class LongitudeWithin extends BaseLongitudeWithin
{
    public function getSql(SqlWalker $sqlWalker)
    {
        return $this->getSqlWithoutLatitude($sqlWalker);
    }
}
