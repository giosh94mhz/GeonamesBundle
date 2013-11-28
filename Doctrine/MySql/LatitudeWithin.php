<?php
namespace Giosh94mhz\GeonamesBundle\Doctrine\MySql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Giosh94mhz\GeonamesBundle\Model\Measure;

/**
 * Usage: LATITUDE_WITHIN(latitude, center, kilometers)
 * Returns: boolean
 *
 * @author Premi Giorgio <giosh94mhz@gmail.com>
 */
class LatitudeWithin extends FunctionNode
{
    protected $latitude;

    protected $center;

    protected $distance;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->latitude = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->center = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->distance = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf(
            '(%s BETWEEN %s - (%s / %F) AND %s + (%s / %F))',
            $sqlWalker->walkArithmeticPrimary($this->latitude),
            $sqlWalker->walkArithmeticPrimary($this->center),
            $sqlWalker->walkArithmeticPrimary($this->distance),
            Measure::RADIANS_TO_KM,
            $sqlWalker->walkArithmeticPrimary($this->center),
            $sqlWalker->walkArithmeticPrimary($this->distance),
            Measure::RADIANS_TO_KM
        );
    }
}
