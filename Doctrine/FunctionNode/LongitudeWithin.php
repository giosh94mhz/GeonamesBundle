<?php
namespace Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Giosh94mhz\GeonamesBundle\Model\Measure;

/**
 * Usage: LONGITUDE_WITHIN(longitude, center, distanceInKm)
 * Usage: LONGITUDE_WITHIN(longitude, [latitude, ]center, distanceInKm)
 * if latitude is not specified,
 * Returns: boolean
 *
 * @author Premi Giorgio <giosh94mhz@gmail.com>
 */
class LongitudeWithin extends FunctionNode
{
    protected $longitude;

    protected $latitude;

    protected $center;

    protected $distance;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->longitude = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->center = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->distance = $parser->ArithmeticExpression();
        if ($parser->getLexer()->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->latitude = $this->center;
            $this->center = $this->distance;
            $this->distance = $parser->ArithmeticExpression();
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return $this->latitude? $this->getSqlWithLatitude($sqlWalker) : $this->getSqlWithoutLatitude($sqlWalker);
    }

    protected function getSqlWithoutLatitude(SqlWalker $sqlWalker)
    {
        $p = $sqlWalker->getConnection()->getDatabasePlatform();
        return sprintf(
            $p->getBetweenExpression('%s', '%s - %s / %F', '%s + %s / %F'),
            $sqlWalker->walkArithmeticPrimary($this->longitude),
            $sqlWalker->walkArithmeticPrimary($this->center),
            $sqlWalker->walkArithmeticPrimary($this->distance),
            Measure::RADIANS_TO_KM,
            $sqlWalker->walkArithmeticPrimary($this->center),
            $sqlWalker->walkArithmeticPrimary($this->distance),
            Measure::RADIANS_TO_KM
        );
    }

    protected function getSqlWithLatitude(SqlWalker $sqlWalker)
    {
        $p = $sqlWalker->getConnection()->getDatabasePlatform();
        return sprintf(
            $p->getBetweenExpression(
                '%s',
                '%s - %s / ABS(' . $p->getCosExpression('%s') . ' * %F)',
                '%s + %s / ABS(' . $p->getCosExpression('%s') . ' * %F)'
            ),
            $sqlWalker->walkArithmeticPrimary($this->longitude),
            $sqlWalker->walkArithmeticPrimary($this->center),
            $sqlWalker->walkArithmeticPrimary($this->distance),
            $sqlWalker->walkArithmeticPrimary($this->latitude),
            Measure::RADIANS_TO_KM,
            $sqlWalker->walkArithmeticPrimary($this->center),
            $sqlWalker->walkArithmeticPrimary($this->distance),
            $sqlWalker->walkArithmeticPrimary($this->latitude),
            Measure::RADIANS_TO_KM
        );
    }
}
