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

function cdate_format($date)
{
	$date = strtotime($date);

	return date('d.m.Y - H:i', $date);
}