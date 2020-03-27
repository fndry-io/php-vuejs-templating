<?php

namespace WMDE\VueJsTemplating\JsParsing;

class AttributeVariable implements ParsedExpression {

	/**
	 * @var string
	 */
	private $expression;

    /**
     * @var JsExpressionParser
     */
	private $expressionParser;

	public function __construct( $expression, JsExpressionParser $expressionParser ) {
		$this->expression = $expression;
		$this->expressionParser = $expressionParser;
	}

	/**
	 * @param array $data ignored
	 *
	 * @return string as provided on construction time
	 */
	public function evaluate( array $data ) {
        $matches = [];
        preg_match_all('/\${(.*?)}/', $this->expression, $matches);
        $string = $this->expression;
        if(isset($matches[1]) && sizeof($matches[1])){
            foreach ($matches[1] as $match){
                $value = $this->expressionParser->parse($match)->evaluate($data);
                $string = str_replace('${' . $match . '}', $value, $string);
            }
        }
        return ltrim(rtrim($string,'`'), '`');
	}

}
