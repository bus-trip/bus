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
		return [
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		];
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return [
			['allow',  // allow all users to perform 'index' and 'view' actions
				  'actions' => ['index', 'view'],
				  'users'   => ['*'],
			],
			['allow', // allow authenticated user to perform 'create' and 'update' actions
				  'actions' => ['create', 'update'],
				  'users'   => ['@'],
			],
			['allow', // allow admin user to perform 'admin' and 'delete' actions
				  'actions' => ['admin', 'delete', 'tripselect'],
				  'users'   => ['admin'],
			],
			['deny',  // deny all users
				  'users' => ['*'],
			],
		];
	}

	public function actionIndex()
	{
		$query = Dirpoints::model()->findAll();
		$points = [];
		foreach ($query as $q) {
			if ($q->direction->status != DIRTRIP_CANCELED) {
				$points[$q->name] = $q->name;
			}
		}
		ksort($points);
		$indexData['points'] = $points;

		if (isset($_POST['startPoint']) && isset($_POST['endPoint']) && isset($_POST['departure'])) {
			$directions = $this->getDirections($_POST['startPoint'], $_POST['endPoint']);
			$tripsAttr = [];
			foreach ($directions as $d) {
				$criteria = new CDbCriteria();
				$criteria->condition = "idDirection=" . $d['id'] . " and departure between '" . date('Y-m-d', strtotime($_POST['departure'])) . " 00:00:00' and '" . date('Y-m-d', strtotime($_POST['departure'])) . " 23:59:59'";
				$trips = Trips::model()->findAllByAttributes(['idDirection' => $d['id']], $criteria);
				foreach ($trips as $t) {
					$freePlaces = $this->getFreePlaces($t->id, $_POST['startPoint'], $_POST['endPoint']);
					if ($freePlaces) {
						$tripsAttr[] = [
							'id'          => $t->attributes['id'],
							'trip'        => $_POST['startPoint'] . " - " . $_POST['endPoint'],
							'direction'   => $d['startPoint'] . ' - ' . $d['endPoint'],
							'departure'   => $t->attributes['departure'],
							'arrival'     => $t->attributes['arrival'],
							'description' => $t->attributes['description'],
							'places'      => $freePlaces
						];
					}
				}
			}

			$tripsAttr = new CArrayDataProvider($tripsAttr);

			$indexData['trips'] = $tripsAttr;
			$indexData['selPoints'] = [
				'startPoint' => $_POST['startPoint'],
				'endPoint'   => $_POST['endPoint'],
				'departure'  => $_POST['departure']
			];
		}
		$this->render(
			'index',
			$indexData
		);
	}

	private function getDirections($startPoint, $endPoint)
	{
		$directions = array();
		$dirsAll = Directions::model()->findAll(
			array(
				'condition' => 'status != ' . DIRTRIP_CANCELED . ' and parentId !=0 and startPoint = "' . $startPoint . '" and endPoint="' . $endPoint . '"',
				'group'     => 'parentId'
			)
		);
		$dirsByStart = array();
		foreach ($dirsAll as $ds) {
			$parentDir = Directions::model()->findAll(
				array('condition' => 'id=' . $ds->parentId . ' and status != ' . DIRTRIP_CANCELED)
			);
			if (!empty($parentDir)) $dirsByStart[] = $parentDir[0]->attributes;
		}
		if (!empty($dirsByStart)) {
			foreach ($dirsByStart as $ds) {
				$points = $this->getStationsByDirectionId($ds['id']);
				if (in_array($startPoint, $points) && in_array($endPoint, $points) && array_search($startPoint, $points) < array_search($endPoint, $points)) $directions[] = $ds;
			}
		}

		return $directions;
	}

	private function getStationsByDirectionId($id)
	{
		$points = [];
		$point = Dirpoints::model()->findByAttributes(
			[
				'directionId' => $id,
				'prevId'      => 0
			]
		);
		$points[] = $point->name;
		while ($point = Dirpoints::model()->find(['condition' => 'directionId=' . $id . ' and prevId=' . $point->id])) {
			$points[] = $point->name;
		}

		return $points;
	}

	private function getFreePlaces($tripId, $startPoint, $endPoint)
	{
		$trip = Trips::model()->findByPk($tripId);
		$points = $this->getStationsByDirectionId($trip->idDirection);

		$dirPoints = [];
		foreach ($points as $p) {
			$dirPoints[$p] = 0;
		}

		$allDirs = Directions::model()
							 ->findAll(['condition' => 'parentId=' . $trip->idDirection . ' and status!=' . DIRTRIP_CANCELED]);
		$dirArr = [];
		foreach ($allDirs as $d) {
			$tickets = Tickets::model()
							  ->count(['condition' => 'idTrip=' . $tripId . ' and idDirection=' . $d['id'] . ' and status!=' . Tickets::STATUS_CANCELED]);
			$dirArr[] = [
				'startPoint' => $d['startPoint'],
				'endPoint'   => $d['endPoint'],
				'tickets'    => $tickets
			];
		}

		foreach ($dirArr as $d) {
			if ($d['tickets'] > 0) {
				$i = FALSE;
				foreach ($dirPoints as $k => $p) {
					if ($k == $d['startPoint']) {
						$i = TRUE;
					}
					if ($i && $k != $d['endPoint']) {
						$dirPoints[$k]+=$d['tickets'];
					}
					if ($k == $d['endPoint']) {
						$i = FALSE;
					}
				}
			}
		}

		print_r($dirPoints);
		print '<br/>';

		$allPlaces = $trip->idBus0->places;
		$i = FALSE;
		$maxFreePlace = 0;
		foreach ($dirPoints as $k => $p) {
			if ($k == $startPoint) {
				$i = TRUE;
			}
			if ($i) {
				if ($p > $maxFreePlace) $maxFreePlace = $p;
			}
			if ($k == $endPoint) {
				$i = FALSE;
			}
		}

		return $allPlaces - $maxFreePlace;
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
				$this->redirect(['admin', 'id' => $model->id]);
		}

		$data = Yii::app()->db->createCommand()
							  ->selectDistinct('id,startPoint,endPoint')
							  ->from('directions')
							  ->where('parentId=0')
							  ->queryAll();

		$parentDir = [
			0 => 'Новое направление',
		];

		foreach ($data as $d) {
			$parentDir[$d['id']] = $d['startPoint'] . ' - ' . $d['endPoint'];
		}

		$this->render('create', [
			'model'     => $model,
			'parentDir' => $parentDir,
		]);
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
				$this->redirect(['admin']);
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
			$parentDir = [
				$data[0]['id'] => $data[0]['startPoint'] . ' - ' . $data[0]['endPoint'],
			];
		} else {
			$parentDir = [
				'0' => $model->startPoint . ' - ' . $model->endPoint,
			];
		}

		$this->render('update', [
			'model'     => $model,
			'parentDir' => $parentDir,
		]);
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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['admin']);
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

		$data = Directions::model()->findAll([
												 'condition' => 'status=:status',
												 'params'    => [':status' => 1],
											 ]);

		$arrData = [];
		foreach ($data as $d) {
			if ($d->parentId == 0) $arrParent[$d->id] = $d->startPoint . ' - ' . $d->endPoint;
		}
		foreach ($data as $d) {
			$arrData[] = [
				'id'         => $d->id,
				'parentId'   => ($d->parentId != 0 ? $arrParent[$d->parentId] : ''),
				'startPoint' => $d->startPoint,
				'endPoint'   => $d->endPoint,
				'price'      => $d->price,
				'status'     => $d->status,
			];
		}
		$modelData = new CArrayDataProvider(
			$arrData,
			[
				'keyField'   => 'id',
				'sort'       => [
					'attributes' => [
						'id',
						'parentId',
						'startPoint',
						'endPoint',
						'price'
					],
				],
				'pagination' => [
					'pageSize' => 20,
				],
			]
		);

		$this->render('admin', [
			'model'     => $model,
			'modelData' => $modelData,
		]);
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
