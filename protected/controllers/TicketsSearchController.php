<?php

/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 08.06.2015
 * Time: 2:12
 */
class TicketsSearchController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				  'actions' => array('index', 'view'),
				  'users'   => array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				  'actions' => array('create', 'update'),
				  'users'   => array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				  'actions' => array('admin', 'delete', 'tripselect'),
				  'users'   => array('admin'),
			),
			array('deny',  // deny all users
				  'users' => array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$query = Directions::model()->findAll();
		$points = array();
		foreach ($query as $q) {
			if (!in_array($q->startPoint, $points)) $points[$q->startPoint] = $q->startPoint;
			if (!in_array($q->endPoint, $points)) $points[$q->endPoint] = $q->endPoint;
		}
		$indexData['points'] = $points;

		if (isset($_POST['startPoint']) && isset($_POST['endPoint']) && isset($_POST['departure'])) {
			$directions = $this->getDirections($_POST['startPoint'], $_POST['endPoint']);

			$tripsAttr = array();
			foreach ($directions as $d) {
				$criteria = new CDbCriteria();
				$criteria->condition = "idDirection=" . $d['id'] . " and departure between '" . date('Y-m-d', strtotime($_POST['departure'])) . " 00:00:00' and '" . date('Y-m-d', strtotime($_POST['departure'])) . " 23:59:59'";
				$trips = Trips::model()->findAllByAttributes(array('idDirection' => $d['id']), $criteria);
				foreach ($trips as $t) {
					$criteria->condition = "idTrip=" . $t->attributes['id'] . " and status in (" . TICKET_CONFIRMED . "," . TICKET_RESERVED . ")";
					$tickets = Tickets::model()->count($criteria);
					$bus = Buses::model()->findByPk($t->attributes['idBus']);
					/*
					 * проверку по участкам на наличие мест добавить здесь
					 */

					if ($bus->places > $tickets) {
						$tripsAttr[] = array(
							'id'          => $t->attributes['id'],
							'direction'   => $d['startPoint'] . ' - ' . $d['endPoint'],
							'departure'   => $t->attributes['departure'],
							'arrival'     => $t->attributes['arrival'],
							'description' => $t->attributes['description']
						);
					}
				}
			}

			$tripsAttr = new CArrayDataProvider($tripsAttr);

			$indexData['trips'] = $tripsAttr;
			$indexData['selPoints'] = array(
				'startPoint' => $_POST['startPoint'],
				'endPoint'   => $_POST['endPoint'],
				'departure'  => $_POST['departure']
			);
		}
		$this->render(
			'index',
			$indexData
		);
	}

	private function getDirections($startPoint, $endPoint = '')
	{
		$directions = array();
		$dirsAll = Directions::model()->findAllByAttributes(
			array('startPoint' => $startPoint),
			'status = ' . DIRTRIP_MAIN . ' or status = ' . DIRTRIP_EXTEND
		);

		foreach ($dirsAll as $ds) {
			if($ds->attributes['parentId'] != 0) $dirsByStart[] = Directions::model()->findByPk($ds->attributes['parentId'])->attributes;
			else $dirsByStart[] = Directions::model()->findByPk($ds->attributes['id'])->attributes;
		}

		foreach ($dirsByStart as $ds) {
			if (isset($endPoint)) {
				$criteria = new CDbCriteria();
				$criteria->condition = 'parentId=' . $ds['id'] . ' and endPoint = "' . $endPoint . '"';
				$dirFull = Directions::model()->findAll($criteria);
				foreach ($dirFull as $df) {
					$directions[] = $ds;
				}
			} else $directions[] = $ds;
		}

		return $directions;
	}

	private function getFreePlaces($startPoint, $endPoint, $trips)
	{
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Directions;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Directions'])) {
			$model->attributes = $_POST['Directions'];
			if ($model->save())
				$this->redirect(array('admin', 'id' => $model->id));
		}

		$data = Yii::app()->db->createCommand()
							  ->selectDistinct('id,startPoint,endPoint')
							  ->from('directions')
							  ->where('parentId=0')
							  ->queryAll();

		$parentDir = array(
			0 => 'Новое направление',
		);

		foreach ($data as $d) {
			$parentDir[$d['id']] = $d['startPoint'] . ' - ' . $d['endPoint'];
		}

		$this->render('create', array(
			'model'     => $model,
			'parentDir' => $parentDir,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Directions'])) {

			$model->attributes = $_POST['Directions'];
			if ($model->validate() && $model->save()) {
				$this->redirect(array('admin'));
			}

//            $nmodel = new Directions();
//            $nmodel->id = $id;
//			$nmodel->attributes=$_POST['Directions'];
//
//            if(array_diff($model->attributes,$nmodel->attributes)){
//                $model->status = 0;
//                $model->save();
//                unset($nmodel->id);
//			    if($nmodel->save()){
//                    if($model->parentId == 0){
//                        $criteria = new CDbCriteria();
//                        $criteria->condition = 'parentId=:parentId';
//                        $criteria->addCondition('status=:status');
//                        $criteria->params = array(
//                            ':parentId'=>$id,
//                            ':status'=>1,
//                        );
//                        $childDirs = Directions::model()->findAll($criteria);
//                        foreach($childDirs as $c){
//                            $c->parentId = $nmodel->id;
//                            $c->save();
//                        }
//                    }
//                    $this->redirect(array('admin'));
//                }
//            }
		}

		if ($model->parentId != 0) {
			$data = Yii::app()->db->createCommand()
								  ->selectDistinct('id,startPoint,endPoint')
								  ->from('directions')
								  ->where('id=(select parentId from directions where id=' . $model->id . ')')
								  ->queryAll();
			$parentDir = array(
				$data[0]['id'] => $data[0]['startPoint'] . ' - ' . $data[0]['endPoint'],
			);
		} else {
			$parentDir = array(
				'0' => $model->startPoint . ' - ' . $model->endPoint,
			);
		}

		$this->render('update', array(
			'model'     => $model,
			'parentDir' => $parentDir,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 *
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$model->status = 0;
		$model->save();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
//	public function actionIndex()
//	{
//		$dataProvider = new CActiveDataProvider('Directions');
//		$this->render('index', array(
//			'dataProvider' => $dataProvider,
//		));
//	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Directions('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Directions']))
			$model->attributes = $_GET['Directions'];

		$data = Directions::model()->findAll(array(
												 'condition' => 'status=:status',
												 'params'    => array(':status' => 1),
											 ));

		$arrData = array();
		foreach ($data as $d) {
			if ($d->parentId == 0) $arrParent[$d->id] = $d->startPoint . ' - ' . $d->endPoint;
		}
		foreach ($data as $d) {
			$arrData[] = array(
				'id'         => $d->id,
				'parentId'   => ($d->parentId != 0 ? $arrParent[$d->parentId] : ''),
				'startPoint' => $d->startPoint,
				'endPoint'   => $d->endPoint,
				'price'      => $d->price,
				'status'     => $d->status,
			);
		}
		$modelData = new CArrayDataProvider(
			$arrData,
			array(
				'keyField'   => 'id',
				'sort'       => array(
					'attributes' => array(
						'id',
						'parentId',
						'startPoint',
						'endPoint',
						'price'
					),
				),
				'pagination' => array(
					'pageSize' => 20,
				),
			)
		);

		$this->render('admin', array(
			'model'     => $model,
			'modelData' => $modelData,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 *
	 * @param integer $id the ID of the model to be loaded
	 *
	 * @return Directions the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Directions::model()->findByPk($id);
		if ($model === NULL)
			throw new CHttpException(404, 'The requested page does not exist.');

		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 *
	 * @param Directions $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'directions-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
