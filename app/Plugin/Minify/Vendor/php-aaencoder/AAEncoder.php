<?php
/**
 * Class AAEncoder
 * @author Andrey Izman <izmanw@gmail.com>
 * @link https://github.com/mervick/php-aaencoder
 * @license MIT
 */
/**
 * Class AAEncoder
 */
class AAEncoder
{
	public static function gBytes(){
		return self::$bytes;
	}
	/**
	 * Encode any JavaScript program to Japanese style emoticons (^_^)
	 * @param string $js
	 * @param int $level [optional]
	 * @return string
	 */
	public static function encode($js, $level=0)
	{
		$result = "ﾟωﾟﾉ= /｀ｍ´）ﾉ ~┻━┻   / ['_']; o=(ﾟｰﾟ)  =_=3; c=(θ) =(ﾟｰﾟ)-(ﾟｰﾟ); " .
				"(ﾟДﾟ) =(θ)= (o^_^o)/ (o^_^o);" .
				"(ﾟДﾟ)={θ: '_' ,ﾟωﾟﾉ : ((ﾟωﾟﾉ==3) +'_') [θ] " .
				",ﾟｰﾟﾉ :(ﾟωﾟﾉ+ '_')[o^_^o -(θ)] " .
				",ﾟДﾟﾉ:((ﾟｰﾟ==3) +'_')[ﾟｰﾟ] }; (ﾟДﾟ) [θ] =((ﾟωﾟﾉ==3) +'_') [c^_^o];" .
				"(ﾟДﾟ) ['c'] = ((ﾟДﾟ)+'_') [ (ﾟｰﾟ)+(ﾟｰﾟ)-(θ) ];" .
				"(ﾟДﾟ) ['o'] = ((ﾟДﾟ)+'_') [θ];" .
				"(ﾟoﾟ)=(ﾟДﾟ) ['c']+(ﾟДﾟ) ['o']+(ﾟωﾟﾉ +'_')[θ]+ ((ﾟωﾟﾉ==3) +'_') [ﾟｰﾟ] + " .
				"((ﾟДﾟ) +'_') [(ﾟｰﾟ)+(ﾟｰﾟ)]+ ((ﾟｰﾟ==3) +'_') [θ]+" .
				"((ﾟｰﾟ==3) +'_') [(ﾟｰﾟ) - (θ)]+(ﾟДﾟ) ['c']+" .
				"((ﾟДﾟ)+'_') [(ﾟｰﾟ)+(ﾟｰﾟ)]+ (ﾟДﾟ) ['o']+" .
				"((ﾟｰﾟ==3) +'_') [θ];(ﾟДﾟ) ['_'] =(o^_^o) [ﾟoﾟ] [ﾟoﾟ];" .
				"(ﾟεﾟ)=((ﾟｰﾟ==3) +'_') [θ]+ (ﾟДﾟ) .ﾟДﾟﾉ+" .
				"((ﾟДﾟ)+'_') [(ﾟｰﾟ) + (ﾟｰﾟ)]+((ﾟｰﾟ==3) +'_') [o^_^o -θ]+" .
				"((ﾟｰﾟ==3) +'_') [θ]+ (ﾟωﾟﾉ +'_') [θ]; " .
				"(ﾟｰﾟ)+=(θ); (ﾟДﾟ)[ﾟεﾟ]='\\\\'; " .
				"(ﾟДﾟ).θﾉ=(ﾟДﾟ+ ﾟｰﾟ)[o^_^o -(θ)];" .
				"(oﾟｰﾟo)=(ﾟωﾟﾉ +'_')[c^_^o];" .
				"(ﾟДﾟ) [ﾟoﾟ]='\\\"';" .
				"(ﾟДﾟ) ['_'] ( (ﾟДﾟ) ['_'] (ﾟεﾟ+" .
				"(ﾟДﾟ)[ﾟoﾟ]+ ";
		for ($i = 0, $len = mb_strlen($js); $i < $len; $i++) {
			$code = unpack('N', mb_convert_encoding(mb_substr($js, $i, 1, 'UTF-8'), 'UCS-4BE', 'UTF-8'))[1]; // Get ASCII code
			$text = '(ﾟДﾟ)[ﾟεﾟ]+'; // This should be '\'

			if ($code <= 127) {
				$text .= preg_replace_callback('/([0-7])/', function($match) use ($level) {
					$byte = intval($match[1]);
					return ($level ? self::randomize($byte, $level) : self::$bytes[$byte]) . '+';
				}, decoct($code)); // Use self::$bytes to replace the digits in octal number
			}else {
				$hex = str_split(substr('000' . dechex($code), -4)); // convert decimal ASCII code to hex
				$text .= "(oﾟｰﾟo)+ ";   // This should be 'x'
				for ($i = 0, $len = count($hex); $i < $len; $i++) {
					$text .= self::$bytes[hexdec($hex[$i])] . '+ '; // convert hex to decimal and use that as index to find the self::$bytes
				}
			}
			$result .=  $text;
		}
		$result .= "(ﾟДﾟ)[ﾟoﾟ]) (θ)) ('_');";
		return $result;
	}
	/**
	 * @var array
	 * Note:
	 * 	o = _ = 3
	 */
	protected static $bytes = [
	"(c^_^o)",						//0
	"(θ)",							//1
	"((o^_^o) - (θ))",				//2
	"(o^_^o)",						//3
	"(ﾟｰﾟ)",							//4
	"((o^_^o) + (θ) + (θ))",			//5
	"((o^_^o) +(θ^o^θ))",			//6
	"((ﾟｰﾟ) + (o^_^o))",				//7
	"((ﾟｰﾟ) + (ﾟｰﾟ))",				//8
	"((ﾟｰﾟ) + (ﾟｰﾟ) + (θ))",			//9
	"(ﾟДﾟ) .ﾟωﾟﾉ",					//10
	"(ﾟДﾟ) .θﾉ",						//11
	"(ﾟДﾟ) ['c']",					//12
	"(ﾟДﾟ) .ﾟｰﾟﾉ",					//13
	"(ﾟДﾟ) .ﾟДﾟﾉ",					//14
	"(ﾟДﾟ) [θ]",						//15
	];
	/**
	 * @param int $byte
	 * @param int $level
	 * @return string
	 */
	protected static function randomize($byte, $level)
	{
		$random = [
		0 => [['+0', '-0'], ['+1', '-1'], ['+3', '-3'], ['+4', '-4']],
		1 => [['+1', '-0'], ['+3', '-1', '-1'], ['+4', '-3']],
		2 => [['+3', '-1'], ['+4', '-3', '+1'], ['+3', '+3', '-4']],
		3 => [['+3', '-0'], ['+4', '-3', '+1', '+1']],
		4 => [['+4', '+0'], ['+1', '+3'], ['+4', '-0']],
		5 => [['+3', '+1', '+1'], ['+4', '+1'], ['+3', '+3', '-1']],
		6 => [['+3', '+3'], ['+4', '+1', '+1'], ['+4', '+3', '-1']],
		7 => [['+3', '+4'], ['+3', '+3', '+1'], ['+4', '+4', '-1']],
		];
		while ($level--) {
			$byte = preg_replace_callback('/[0-7]/', function($match) use ($random) {
				$numbers = $random[$match[0]][mt_rand(0, count($random[$match[0]]) - 1)];
				shuffle($numbers);
				$byte = ltrim(implode('', $numbers), '+');
				return "($byte)";
			}, $byte);
		}
		$byte = str_replace('+-', '-', $byte);
		return str_replace(array_keys(self::$bytes), self::$bytes, $byte);
	}
}