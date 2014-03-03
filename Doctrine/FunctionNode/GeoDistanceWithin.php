<?php
namespace Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Usage: GEO_DISTANCE_WITHIN(latOrigin, lonOrigin, latPoint, lonPoint, kilometers)
 * Returns: boolean
 *
 * @author Premi Giorgio <giosh94mhz@gmail.com>
 */
class GeoDistanceWithin extends GeoDistance
{
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
        return sprintf(
            $formula = '(' . parent::getSql($sqlWalker) . ') <= %s',
            $sqlWalker->walkArithmeticPrimary($this->distance)
        );
    }
}
