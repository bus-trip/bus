<?php

class SController extends Controller
{
	public function actionSearch()
	{
		$model = new Trips;
		$model->departure = date('d.m.Y', time());
		$model->tdeparture = date('H:i', time());

		// uncomment the following code to enable ajax-based validation
		/*
		if(isset($_POST['ajax']) && $_POST['ajax']==='trips-search-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		*/

		if (isset($_POST['Trips'])) {
			$_POST['Trips'];

			$date_from = explode('.', $_POST['Trips']['departure']);

			$from = $date_from[2] . '-' . $date_from[1] . '-' . $date_from[1] . ' ' . $_POST['Trips']['tdeparture'];

			$criteria = new CDbCriteria();
			$criteria->addCondition('DATE(departure) >= :from');
			$criteria->params = array(':from' => $from);
			$criteria->order = 'departure ASC';

			$trips = Trips::model()->with('idDirection0', 'idBus0')->findAll($criteria);
			$this->render('searched_trips', array('trips' => $trips));

			// form inputs are valid, do something here
			return;
		}
		$this->render('search_form', array('model' => $model));
	}



	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}