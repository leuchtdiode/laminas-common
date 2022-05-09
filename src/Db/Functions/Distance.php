<?php
namespace Common\Db\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class Distance extends FunctionNode
{
	const NAME = 'DISTANCE';

	private $sourceLatitude       = null;
	private $sourceLongitude      = null;
	private $destinationLatitude  = null;
	private $destinationLongitude = null;

	/**
	 * {@inheritdoc}
	 */
	public function getSql(SqlWalker $walker): string
	{
		$formulae = '(ACOS(SIN(%s * PI() / 180) * SIN(%s * PI() / 180) + COS(%s * PI() / 180) * COS(%s * PI() / 180) * COS((%s - %s) * PI() / 180)) * 180 / PI()) * 60 * 1.1515';

		return sprintf(
			$formulae,
			$this->destinationLatitude->dispatch($walker),
			$this->sourceLatitude->dispatch($walker),
			$this->destinationLatitude->dispatch($walker),
			$this->sourceLatitude->dispatch($walker),
			$this->destinationLongitude->dispatch($walker),
			$this->sourceLongitude->dispatch($walker)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function parse(Parser $parser): void
	{
		$parser->match(Lexer::T_IDENTIFIER);
		$parser->match(Lexer::T_OPEN_PARENTHESIS);
		$this->sourceLatitude = $parser->SimpleArithmeticExpression();
		$parser->match(Lexer::T_COMMA);
		$this->sourceLongitude = $parser->SimpleArithmeticExpression();
		$parser->match(Lexer::T_COMMA);
		$this->destinationLatitude = $parser->SimpleArithmeticExpression();
		$parser->match(Lexer::T_COMMA);
		$this->destinationLongitude = $parser->SimpleArithmeticExpression();
		$parser->match(Lexer::T_CLOSE_PARENTHESIS);
	}
}