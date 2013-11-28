<?php
namespace Giosh94mhz\GeonamesBundle\Doctrine\MySql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Giosh94mhz\GeonamesBundle\Model\Measure;

/**
 * Usage: GEO_DISTANCE_WITHIN(latOrigin, lonOrigin, latPoint, lonPoint, kilometers)
 * Returns: boolean
 *
 * @author Premi Giorgio <giosh94mhz@gmail.com>
 */
class GeoDistanceWithin extends FunctionNode
{
    const EARTH_RADIUS_KM = 6371.;
    const KM_RADIANS = 111.;

    protected $latOrigin;

    protected $lonOrigin;

    protected $latPoint;

    protected $lonPoint;

    protected $distance;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->latOrigin = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->lonOrigin = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->latPoint = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->lonPoint = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->distance = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        // Haversine Formula <= distance
        return sprintf(
            '(%F * ASIN(SQRT(POWER(SIN((%s - %s) * PI()/360), 2)+ COS(%s * PI()/180) * COS(%s * PI()/180) * POWER(SIN((%s - %s) * PI()/360), 2)))) <= %s',
            Measure::EARTH_RADIUS_KM * 2.,
            $sqlWalker->walkArithmeticPrimary($this->latOrigin),
            $sqlWalker->walkArithmeticPrimary($this->latPoint),
            $sqlWalker->walkArithmeticPrimary($this->latOrigin),
            $sqlWalker->walkArithmeticPrimary($this->latPoint),
            $sqlWalker->walkArithmeticPrimary($this->lonOrigin),
            $sqlWalker->walkArithmeticPrimary($this->lonPoint),
            $sqlWalker->walkArithmeticPrimary($this->distance)
        );
    }
}
