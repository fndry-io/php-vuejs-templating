<?php

namespace WMDE\VueJsTemplating\JsParsing;

use RuntimeException;

class VariableAccess implements ParsedExpression {

	/**
	 * @var string[]
	 */
	private $pathParts;

	public function __construct( array $pathParts ) {
		$this->pathParts = $pathParts;
	}

	/**
	 * @param array $data
	 *
	 * @throws RuntimeException when a path element cannot be found in the array
	 * @return mixed
	 */
	public function evaluate( array $data ) {
		$value = $data;
		foreach ( $this->pathParts as $key ) {
			if ( !array_key_exists( $key, $value ) ) {
			    if(strpos($key, '${')){
                    preg_match('#{.*?}#', $key, $matches);
				    $string = $key;
                    if(sizeof($matches)){
                        foreach ($matches as $match){
                            $prop = trim(ltrim(rtrim($match,'}'), '{'));
                            if(array_key_exists( $prop, $value )){
                                $string = str_replace("$$match", $value[$prop], $string);
                            }else{
                                throw new RuntimeException( "Undefined variable '{$prop}'" );
                            }
                        }
                    }
                    $value = ltrim(rtrim($string,'`'), '`');
                }else{
                    $expression = implode( '.', $this->pathParts );

                    throw new RuntimeException( "Undefined variable '{$expression}'" );
                }

			}else{
                $value = $value[$key];
            }
		}
		return $value;
	}

}
