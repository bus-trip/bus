<?php

class SController extends Controller
{
	public function actionSearch()
	{
		$model = new Trips;
		$model->departure = date('d.m.Y', time());
		$model->tdeparture = date('H:i', time());
		$model->places = 1;
		$model->startPoint = '';
		$model->endPoint = '';

		// uncomment the following code to enable ajax-based validation
		/*
		if(isset($_POST['ajax']) && $_POST['ajax']==='trips-search-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		*/

		if (isset($_GET['Trips'])) {

			if (empty($_GET['Trips']['startPoint']) || empty($_GET['Trips']['endPoint']) || empty($_GET['Trips']['departure'])) {
				// throw Exception ....
			}
			$date_from = explode('.', $_GET['Trips']['departure']);
			$from = $date_from[2] . '-' . $date_from[1] . '-' . $date_from[1] . ' ' . $_GET['Trips']['tdeparture'];

			$criteria = new CDbCriteria();
			$criteria->addCondition('DATE(departure) >= :from');
			$criteria->addCondition('startPoint = "' . htmlspecialchars($_GET['Trips']['startPoint']) . '"');
			$criteria->addCondition('endPoint = "' . htmlspecialchars($_GET['Trips']['endPoint']) . '"');
			$criteria->addCondition('idBus0.status = 1');
			$criteria->params = array(':from' => $from);
			$criteria->order = 'departure ASC';

			$trips = Trips::model()->with('idDirection0', 'idBus0')->findAll($criteria);
			$this->render('searched_trips', array('trips'      => $trips,
												  'departure'  => $_GET['Trips']['departure'] . ' - ' . $_GET['Trips']['tdeparture'],
												  'startPoint' => $_GET['Trips']['startPoint'],
												  'endPoint'   => $_GET['Trips']['endPoint']));

			// form inputs are valid, do something here
			return;
		}
		$this->render('search_form', array('model' => $model));
	}

	public function actionStartPoint($term)
	{
		$res = array();
		if ($term) {
			$qtxt = "SELECT startPoint FROM directions WHERE startPoint LIKE :startPoint GROUP BY startPoint";
			$command = Yii::app()->db->createCommand($qtxt);
			$command->bindValue(":startPoint", $term . '%', PDO::PARAM_STR);
			$res = $command->queryColumn();
		}

		echo CJSON::encode($res);
		Yii::app()->end();
	}

	public function actionEndPoint($term)
	{
		$res = array();
		if ($term) {
			$qtxt = "SELECT endPoint FROM directions WHERE endPoint LIKE :endPoint GROUP BY endPoint";
			$command = Yii::app()->db->createCommand($qtxt);
			$command->bindValue(":endPoint", $term . '%', PDO::PARAM_STR);
			$res = $command->queryColumn();
		}

		echo CJSON::encode($res);
		Yii::app()->end();
	}

	public function actionTrip()
	{

		var_dump($_POST);
		$this->render('trip');
	}

	public function actionTicket()
	{

		$this->render('ticket');
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