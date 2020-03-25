<?php

namespace WMDE\VueJsTemplating\JsParsing;

class AttributeVariable implements ParsedExpression {

	/**
	 * @var string
	 */
	private $expression;

	public function __construct( $expression ) {
		$this->expression = $expression;
	}

	/**
	 * @param array $data ignored
	 *
	 * @return string as provided on construction time
	 */
	public function evaluate( array $data ) {

        preg_match('#{.*?}#', $this->expression, $matches);
        $string = $this->expression;
        if(sizeof($matches)){
            foreach ($matches as $match){
                $prop = trim(ltrim(rtrim($match,'}'), '{'));
                if(array_key_exists( $prop, $data )){
                    $string = str_replace("$$match", $data[$prop], $string);
                }else{
                    throw new RuntimeException( "Undefined variable '{$prop}'" );
                }
            }
        }

        return ltrim(rtrim($string,'`'), '`');
	}

}
