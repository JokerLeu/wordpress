<?php

/**
 * 获取文本复数形式解析器。
 * A gettext Plural-Forms parser.
 *
 * @since 4.9.0
 */
class Plural_Forms {
	/**
     * 操作符字符。
	 * Operator characters.
	 *
	 * @since 4.9.0
	 * @var string OP_CHARS Operator characters.
	 */
	const OP_CHARS = '|&><!=%?:';

	/**
     * 有效数字字符。
	 * Valid number characters.
	 *
	 * @since 4.9.0
	 * @var string NUM_CHARS Valid number characters.
	 */
	const NUM_CHARS = '0123456789';

	/**
     * 算符优先权。
	 * Operator precedence.
	 *
     * 运算符优先级从最高到最低。较高的数字表示较高的优先级，并首先执行。
	 * Operator precedence from highest to lowest. Higher numbers indicate
	 * higher precedence, and are executed first.
	 *
	 * @see https://en.wikipedia.org/wiki/Operators_in_C_and_C%2B%2B#Operator_precedence
	 *
	 * @since 4.9.0
	 * @var array $op_precedence Operator precedence from highest to lowest.
	 */
	protected static $op_precedence = array(
		'%' => 6,

		'<' => 5,
		'<=' => 5,
		'>' => 5,
		'>=' => 5,

		'==' => 4,
		'!=' => 4,

		'&&' => 3,

		'||' => 2,

		'?:' => 1,
		'?' => 1,

		'(' => 0,
		')' => 0,
	);

	/**
     * 由字符串生成的令牌。
	 * Tokens generated from the string.
	 *
	 * @since 4.9.0
	 * @var array $tokens List of tokens.
	 */
	protected $tokens = array();

	/**
     * 缓存以重复调用函数。
	 * Cache for repeated calls to the function.
	 *
	 * @since 4.9.0
	 * @var array $cache Map of $n => $result
	 */
	protected $cache = array();

	/**
     * 构造函数
	 * Constructor.
	 *
	 * @since 4.9.0
	 *
	 * @param string $str Plural function (just the bit after `plural=` from Plural-Forms)
	 */
	public function __construct( $str ) {
		$this->parse( $str );
	}

	/**
     * 将多个表单字符串解析为令牌。
	 * Parse a Plural-Forms string into tokens.
	 *
	 * Uses the shunting-yard algorithm to convert the string to Reverse Polish
	 * Notation tokens.
	 *
	 * @since 4.9.0
	 *
	 * @param string $str String to parse.
	 */
	protected function parse( $str ) {
		$pos = 0;
		$len = strlen( $str );

		// 使用调车场算法将中缀运算符转换为后缀。
        // Convert infix operators to postfix using the shunting-yard algorithm.
		$output = array();
		$stack = array();
		while ( $pos < $len ) {
			$next = substr( $str, $pos, 1 );

			switch ( $next ) {
				// 忽略空白
                // Ignore whitespace
				case ' ':
				case "\t":
					$pos++;
					break;

				// 变量（n）
                // Variable (n)
				case 'n':
					$output[] = array( 'var' );
					$pos++;
					break;

				// 圆括号
                // Parentheses
				case '(':
					$stack[] = $next;
					$pos++;
					break;

				case ')':
					$found = false;
					while ( ! empty( $stack ) ) {
						$o2 = $stack[ count( $stack ) - 1 ];
						if ( $o2 !== '(' ) {
							$output[] = array( 'op', array_pop( $stack ) );
							continue;
						}

						// Discard open paren.
						array_pop( $stack );
						$found = true;
						break;
					}

					if ( ! $found ) {
						throw new Exception( 'Mismatched parentheses' );
					}

					$pos++;
					break;

				// 算子
                // Operators
				case '|':
				case '&':
				case '>':
				case '<':
				case '!':
				case '=':
				case '%':
				case '?':
					$end_operator = strspn( $str, self::OP_CHARS, $pos );
					$operator = substr( $str, $pos, $end_operator );
					if ( ! array_key_exists( $operator, self::$op_precedence ) ) {
						throw new Exception( sprintf( 'Unknown operator "%s"', $operator ) );
					}

					while ( ! empty( $stack ) ) {
						$o2 = $stack[ count( $stack ) - 1 ];

						// Ternary is right-associative in C
						if ( $operator === '?:' || $operator === '?' ) {
							if ( self::$op_precedence[ $operator ] >= self::$op_precedence[ $o2 ] ) {
								break;
							}
						} elseif ( self::$op_precedence[ $operator ] > self::$op_precedence[ $o2 ] ) {
							break;
						}

						$output[] = array( 'op', array_pop( $stack ) );
					}
					$stack[] = $operator;

					$pos += $end_operator;
					break;

				// 三元“其他”
                // Ternary "else"
				case ':':
					$found = false;
					$s_pos = count( $stack ) - 1;
					while ( $s_pos >= 0 ) {
						$o2 = $stack[ $s_pos ];
						if ( $o2 !== '?' ) {
							$output[] = array( 'op', array_pop( $stack ) );
							$s_pos--;
							continue;
						}

						// Replace.
						$stack[ $s_pos ] = '?:';
						$found = true;
						break;
					}

					if ( ! $found ) {
						throw new Exception( 'Missing starting "?" ternary operator' );
					}
					$pos++;
					break;

				// 默认数或无效
                // Default - number or invalid
				default:
					if ( $next >= '0' && $next <= '9' ) {
						$span = strspn( $str, self::NUM_CHARS, $pos );
						$output[] = array( 'value', intval( substr( $str, $pos, $span ) ) );
						$pos += $span;
						continue;
					}

					throw new Exception( sprintf( 'Unknown symbol "%s"', $next ) );
			}
		}

		while ( ! empty( $stack ) ) {
			$o2 = array_pop( $stack );
			if ( $o2 === '(' || $o2 === ')' ) {
				throw new Exception( 'Mismatched parentheses' );
			}

			$output[] = array( 'op', $o2 );
		}

		$this->tokens = $output;
	}

	/**
     * 得到一个数字的复数形式。
	 * Get the plural form for a number.
	 *
	 * Caches the value for repeated calls.
	 *
	 * @since 4.9.0
	 *
	 * @param int $num Number to get plural form for.
	 * @return int Plural form value.
	 */
	public function get( $num ) {
		if ( isset( $this->cache[ $num ] ) ) {
			return $this->cache[ $num ];
		}
		return $this->cache[ $num ] = $this->execute( $num );
	}

	/**
     * 执行复数形式函数。
	 * Execute the plural form function.
	 *
	 * @since 4.9.0
	 *
	 * @param int $n Variable "n" to substitute.
	 * @return int Plural form value.
	 */
	public function execute( $n ) {
		$stack = array();
		$i = 0;
		$total = count( $this->tokens );
		while ( $i < $total ) {
			$next = $this->tokens[$i];
			$i++;
			if ( $next[0] === 'var' ) {
				$stack[] = $n;
				continue;
			} elseif ( $next[0] === 'value' ) {
				$stack[] = $next[1];
				continue;
			}

			// Only operators left.
			switch ( $next[1] ) {
				case '%':
					$v2 = array_pop( $stack );
					$v1 = array_pop( $stack );
					$stack[] = $v1 % $v2;
					break;

				case '||':
					$v2 = array_pop( $stack );
					$v1 = array_pop( $stack );
					$stack[] = $v1 || $v2;
					break;

				case '&&':
					$v2 = array_pop( $stack );
					$v1 = array_pop( $stack );
					$stack[] = $v1 && $v2;
					break;

				case '<':
					$v2 = array_pop( $stack );
					$v1 = array_pop( $stack );
					$stack[] = $v1 < $v2;
					break;

				case '<=':
					$v2 = array_pop( $stack );
					$v1 = array_pop( $stack );
					$stack[] = $v1 <= $v2;
					break;

				case '>':
					$v2 = array_pop( $stack );
					$v1 = array_pop( $stack );
					$stack[] = $v1 > $v2;
					break;

				case '>=':
					$v2 = array_pop( $stack );
					$v1 = array_pop( $stack );
					$stack[] = $v1 >= $v2;
					break;

				case '!=':
					$v2 = array_pop( $stack );
					$v1 = array_pop( $stack );
					$stack[] = $v1 != $v2;
					break;

				case '==':
					$v2 = array_pop( $stack );
					$v1 = array_pop( $stack );
					$stack[] = $v1 == $v2;
					break;

				case '?:':
					$v3 = array_pop( $stack );
					$v2 = array_pop( $stack );
					$v1 = array_pop( $stack );
					$stack[] = $v1 ? $v2 : $v3;
					break;

				default:
					throw new Exception( sprintf( 'Unknown operator "%s"', $next[1] ) );
			}
		}

		if ( count( $stack ) !== 1 ) {
			throw new Exception( 'Too many values remaining on the stack' );
		}

		return (int) $stack[0];
	}
}
