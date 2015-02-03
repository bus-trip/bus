<?php

class Dadata
{
	public $token;

	public function address($url, $data)
	{
		$options = array(
			'http' => array(
				'method'  => 'POST',
				'header'  => array(
					'Content-type: application/json',
					'Authorization: Token ' . $this->token
				),
				'content' => json_encode($data),
			),
			'ssl'  => array('allow_self_signed' => TRUE, 'verify_peer' => FALSE),
		);

		$context = stream_context_create($options);
		$result = file_get_contents($url, FALSE, $context);

		return $result;
	}

	public function init()
	{
		return;
	}
}