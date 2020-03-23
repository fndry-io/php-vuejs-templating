<?php

namespace WMDE\VueJsTemplating\JsParsing;

class BasicJsExpressionParser implements JsExpressionParser {

	/**
	 * @param string $expression
	 *
	 * @return ParsedExpression
     * todo add additional expressions
	 */
	public function parse( $expression ) {

		$expression = $this->normalizeExpression( $expression );
		if ( strncmp( $expression, '!', 1 ) === 0 ) {
			return new NegationOperator( $this->parse( substr( $expression, 1 ) ) );
		} elseif ( strncmp( $expression, "'", 1 ) === 0 ) {
			return new StringLiteral( substr( $expression, 1, -1 ) );
		} elseif (strpos($expression, '&&') > 0){
		    return new AndOperator(explode('&&', $expression));
        }elseif (strpos($expression, '||') > 0){
            return  new OrOperator(explode('||', $expression));
        }
		else {
			$parts = explode( '.', $expression );
			return new VariableAccess( $parts );
		}
	}

	/**
	 * @param string $expression
	 *
	 * @return string
	 */
	protected function normalizeExpression( $expression ) {
		return trim( $expression );
	}

}
