<?php


if (!function_exists('mb_ucfirst') && function_exists('mb_substr')) {

	function mb_ucfirst($string)
	{
		$string = mb_ereg_replace("^[\ ]+", "", $string);

		// сначала всю строку переводим в нижний регист
		$string = mb_strtolower($string, "UTF-8");

		// потом первую букву в верхний регист
		$string = mb_strtoupper(mb_substr($string, 0, 1, "UTF-8"), "UTF-8") . mb_substr($string, 1, mb_strlen($string), "UTF-8");

		return $string;
	}
}

function _sort_by_departure($a, $b)
{
	$a_weight = (is_array($a) && isset($a['departure'])) ? strtotime($a['departure']) : 0;
	$b_weight = (is_array($b) && isset($b['departure'])) ? strtotime($b['departure']) : 0;
	if ($a_weight == $b_weight) {
		return 0;
	}

	return ($a_weight < $b_weight) ? -1 : 1;
}