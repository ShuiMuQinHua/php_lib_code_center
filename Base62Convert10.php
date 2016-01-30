<?php
 
class Base62 {
	 
	static $CHARS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	static $BASE = 62;
	 
	/**
	* Converts a base 10 number to base 62.
	* 
	* @param int $val   Decimal number
	* @return string    Number converted to specified base
	*/
	public static function encode($val) {
		$str = '';
		do {	
			$m = bcmod($val, self::$BASE);
			$str = self::$CHARS[$m] . $str;
			$val = bcdiv(bcsub($val, $m), self::$BASE);
		} while(bccomp($val,0) > 0);
		return $str; 
	}
	
	/**	 
	* Convert a number from base 62 to base 10
	* 	 
	* @param string $str   Number 
	* @return int    Number converted to base 10
	*/
	public static function decode($str) {
		$char_numbers = array_flip(str_split(self::$CHARS));
		$len = strlen($str);
		$val = 0;
		
		for($i = 0; $i < $len; ++$i) {
			$val = bcadd($val, bcmul($char_numbers[$str[$i]], bcpow(self::$BASE, $len-$i-1)));
		}
		return $val;
	} 
}