<?php

namespace WMDE\VueJsTemplating\JsParsing;

use Illuminate\Support\Arr;
use RuntimeException;

class EqualityOperator implements ParsedExpression {

	/**
	 * @var ParsedExpression
	 */
	private $expression;
	private $sign;

	public function __construct( array $expression, string $sign) {
		$this->expression = $expression;
		$this->sign = $sign;
	}

	/**
	 * @param array $data
	 *
	 * @throws RuntimeException
	 * @return bool
	 */
	public function evaluate( array $data ) {

        $val = $data[trim($this->expression[0])];

        $comp = trim($this->expression[1]);
        $tenary = strpos($comp, '?');

        if($tenary){
            $exp = explode('?', $comp);
            $comp = trim($exp[0]);
            $tenary = explode(':', trim($exp[1]));
        }

        if(is_int($val)){
            $comp = (int) $comp;
        }

        $eval = false;

	    switch ($this->sign){
            case 'equality':
                $eval = $comp == $val;
            case 'identity':
                $eval = $comp === $val;
        }

        if($tenary && sizeof($tenary) === 2){
            if($eval)
                return $this->trim($tenary[0], "'");

            return  $this->trim($tenary[1], "'");
        }

		return $eval;
	}

	private function trim($var, $symbol)
    {
        return ltrim(rtrim($var, $symbol), $symbol);
    }
}
