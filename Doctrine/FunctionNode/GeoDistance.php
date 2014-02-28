<?php
namespace Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Giosh94mhz\GeonamesBundle\Model\Measure;

/**
 * Usage: GEO_DISTANCE(latOrigin, lonOrigin, latPoint, lonPoint)
 * Returns: distance in KM
 *
 * Formulae adapted from: http://www.scribd.com/doc/2569355/Geo-Distance-Search-with-MySQL
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @author Premi Giorgio <giosh94mhz@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class GeoDistance extends FunctionNode
{
    protected $latOrigin;

    protected $lonOrigin;

    protected $latPoint;

    protected $lonPoint;

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
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        $p = $sqlWalker->getConnection()->getDatabasePlatform();

        $pi = $p->getPiExpression();

        // %F * ASIN(SQRT(POWER(SIN((%s - %s) * PI()/360), 2) + COS(%s * PI()/180) * COS(%s * PI()/180) * POWER(SIN((%s - %s) * PI()/360), 2)))
        $formula = "%F * ({$pi} / 360 - " .  $p->getAcosExpression(
            $p->getSqrtExpression(
                '('
                    . $p->getSinExpression("(%s - %s)  * {$pi} / 360")
                    . ' * '
                    . $p->getSinExpression("(%s - %s)  * {$pi} / 360")
                . ') + ('
                    . $p->getCosExpression("%s * {$pi} / 180")
                    . ' * '
                    . $p->getCosExpression("%s * {$pi} / 180")
                    . ' * '
                    . $p->getSinExpression("(%s - %s)  * {$pi} / 360")
                    . ' * '
                    . $p->getSinExpression("(%s - %s)  * {$pi} / 360")
                . ')'
            )
        ) . ')';

        return sprintf(
            $formula,
            Measure::EARTH_RADIUS_KM * 2.,
            $sqlWalker->walkArithmeticPrimary($this->latOrigin),
            $sqlWalker->walkArithmeticPrimary($this->latPoint),
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
