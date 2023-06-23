<?php

class HtmlUtility {

	static function getColorRGBByHex($hex, $alpha= false) {
		$hex= str_replace('#', '', $hex);
		if (strlen($hex) == 6) {
			$rgb ['r']= hexdec(substr($hex, 0, 2));
			$rgb ['g']= hexdec(substr($hex, 2, 2));
			$rgb ['b']= hexdec(substr($hex, 4, 2));
		} else if (strlen($hex) == 3) {
			$rgb ['r']= hexdec(str_repeat(substr($hex, 0, 1), 2));
			$rgb ['g']= hexdec(str_repeat(substr($hex, 1, 1), 2));
			$rgb ['b']= hexdec(str_repeat(substr($hex, 2, 1), 2));
		} else {
			$rgb ['r']= '0';
			$rgb ['g']= '0';
			$rgb ['b']= '0';
		}
		if ($alpha) {
			$rgb ['a']= $alpha;
		}
		return $rgb;
	}
}