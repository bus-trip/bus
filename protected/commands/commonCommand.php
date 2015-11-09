<?php

/**
 * Part of bus 2015
 * Created by: Alexander Sumarokov on 09.11.2015:22:52
 */
class commonCommand extends CConsoleCommand
{
	public function actionUnreserve()
	{
		Yii::import('application.models.TempReserve');

		$criteria            = new CDbCriteria();
		$criteria->condition = 'CONVERT(created, SIGNED INTEGER) < :created';
		$criteria->params    = array(
			':created' => time() + 15 * 60,
		);
		TempReserve::model()->deleteAll($criteria);
	}
}