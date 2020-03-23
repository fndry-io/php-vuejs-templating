<?php

namespace WMDE\VueJsTemplating\JsParsing;

use Illuminate\Support\Arr;
use RuntimeException;

class OrOperator implements ParsedExpression {

	/**
	 * @var ParsedExpression
	 */
	private $expression;

	public function __construct( array $expression ) {
		$this->expression = $expression;
	}

	/**
	 * @param array $data
	 *
	 * @throws RuntimeException
	 * @return bool
	 */
	public function evaluate( array $data ) {

        foreach ($this->expression as $expression){
            if(is_array($data)){
                if(Arr::get($data, trim($expression)))
                    return true;
            }elseif (is_object($data))
                if(object_get($data,trim($expression)))
                    return true;
        }

        return false;
	}

}
