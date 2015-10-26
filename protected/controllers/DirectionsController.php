<?php

class DirectionsController extends Controller
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
				  'actions' => array('admin', 'delete', 'edit', 'addPoint', 'editPoint', 'deletePoint'),
				  'users'   => array('admin'),
			),
			array('deny',  // deny all users
				  'users' => array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 *
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$parentModel = new Directions;
		$pModel = new Dirpoints;
		$nModel = new Dirpoints;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Directions'])) {
			$parentModel->attributes = $_POST['Directions'];
			if ($parentModel->save()) {

				$pModel->directionId = $parentModel->id;
				$pModel->prevId = 0;
				$pModel->nextId = 0;
				$pModel->name = $parentModel->startPoint;

				$nModel->directionId = $parentModel->id;
				$nModel->prevId = 0;
				$nModel->nextId = 0;
				$nModel->name = $parentModel->endPoint;

				if ($pModel->save()) $nModel->prevId = $pModel->id;
				if ($nModel->save()) $pModel->nextId = $nModel->id;

				$pModel->save();
				$nModel->save();

				$model = new Directions();
				$model->attributes = $_POST['Directions'];
				$model->parentId = $parentModel->id;
				$model->save();

				$this->redirect(array('admin', 'id' => $model->id));
			}
		}

		$this->render('create', array(
			'model' => $parentModel,
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
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('Directions');
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all directions.
	 */
	public function actionAdmin()
	{
		$model = new Directions('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Directions']))
			$model->attributes = $_GET['Directions'];

		$data = Directions::model()->findAll(array(
												 'condition' => '(status=' . DIRTRIP_MAIN . ' or status=' . DIRTRIP_EXTEND . ') and parentId=0',
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
	 * Manages all points.
	 */
	public function actionEdit($id)
	{
		$model = new Directions('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Directions']))
			$model->attributes = $_GET['Directions'];

		$parent = Directions::model()->find(array('condition' => 'id=' . $id));

		// Пункты остановок в направлении в порядке следования.
		$dpModel = new Dirpoints();
		$dirArr = array();
		$dirPoint = Dirpoints::model()->find(
			array(
				'condition' => 'directionId=' . $id . ' and prevId = 0 and nextId != 0'
			)
		);
		array_push($dirArr, $dirPoint);
		while (
		$dirPoint = Dirpoints::model()->find(
			array(
				'condition' => 'prevId = ' . $dirPoint->id
			)
		)
		) {
			array_push($dirArr, $dirPoint);
		}
		for ($i = 0; $i < count($dirArr) - 1; $i++) {
			for ($j = $i + 1; $j < count($dirArr); $j++) {
				$data = Directions::model()
								  ->find(array('condition' => 'startPoint="' . $dirArr[$i]->name . '" and endPoint="' . $dirArr[$j]->name . '"'));
				$arrData[] = array(
					'id'         => $data->id,
					'parentId'   => $data->parentId,
					'startPoint' => $data->startPoint,
					'endPoint'   => $data->endPoint,
					'price'      => $data->price,
					'status'     => $data->status,
				);
			}
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

		$dirData = new CArrayDataProvider(
			$dirArr,
			array(
				'keyField'   => 'id',
				'sort'       => array(
					'attributes' => array(
						'id',
						'prevId',
						'nextId',
						'directionId',
						'name'
					),
				),
				'pagination' => array(
					'pageSize' => 20,
				),
			)
		);

		$this->render('edit', array(
			'model'         => $model,
			'parent'        => $parent->attributes,
			'modelData'     => $modelData,
			'dpModel'       => $dpModel,
			'dirPoints'     => $dirData,
			'dirPointsSize' => count($dirArr)
		));
	}

	public function actionAddPoint($id)
	{
		// Для нового пункта
		$prevPoint = Dirpoints::model()->findByPk($id);
		$nextPoint = Dirpoints::model()->findByPk($prevPoint->nextId);

		if (isset($_POST['newPoint'])) {
			$point = Dirpoints::model()->findByPk($id);
			$newPoint = new Dirpoints;
			$newPoint->name = $_POST['newPoint'];
			$newPoint->directionId = $point->directionId;
			$newPoint->prevId = $prevPoint->id;
			$newPoint->nextId = $nextPoint->id;
			if ($newPoint->validate()) {
				$newPoint->save();
				$prevPoint->nextId = $newPoint->id;
				$prevPoint->save();
				$nextPoint->prevId = $newPoint->id;
				$nextPoint->save();
			}

			$dirArr = array();
			$dirPoint = Dirpoints::model()->find(
				array(
					'condition' => 'directionId=' . $prevPoint->directionId . ' and prevId = 0 and nextId != 0'
				)
			);
			array_push($dirArr, $dirPoint->name);
			while ($dirPoint = Dirpoints::model()->find(
				array(
					'condition' => 'prevId = ' . $dirPoint->id
				))
			) {
				array_push($dirArr, $dirPoint->name);
			}
			for ($i = 0; $i < count($dirArr) - 1; $i++) {
				for ($j = $i + 1; $j < count($dirArr); $j++) {
					if (!($dir = Directions::model()
										   ->find(array('condition' => 'startPoint="' . $dirArr[$i] . '" and endPoint="' . $dirArr[$j] . '"')))
					) {
						$dir = new Directions();
						$dir->startPoint = $dirArr[$i];
						$dir->endPoint = $dirArr[$j];
						$dir->parentId = $point->directionId;
						$dir->status = $point->direction->status;
						if ($dir->startPoint == $prevPoint->name) $dir->price = $_POST['price1'];
						elseif ($dir->endPoint == $nextPoint->name) $dir->price = $_POST['price2'];
						else $dir->price = 0;
						if ($dir->validate()) $dir->save();
					}
				}
			}
			$this->redirect(array('directions/edit/', 'id' => $point->directionId));
		} else {
			$this->renderPartial(
				'addPoint',
				array(
					'data' => array(
						'id'        => $id,
						'prevPoint' => $prevPoint->name,
						'nextPoint' => $nextPoint->name
					)
				)
			);
		}
	}

	public function actionEditPoint($id)
	{
		$model = Dirpoints::model()->findByPk($id);
		if (isset($_POST['newName']) && !empty($_POST['newName'])) {
			$directions = Directions::model()->findAll(
				array(
					'condition' => 'parentId=' . $model->directionId . ' and (startPoint = "' . $model->name . '" or endPoint="' . $model->name . '") and status!=' . DIRTRIP_CANCELED
				)
			);
			foreach ($directions as $d) {
				$dirModel = Directions::model()->findByPk($d->id);
				if ($dirModel->startPoint == $model->name) {
					$dirModel->startPoint = $_POST['newName'];
					$dirModel->save();
				} elseif ($dirModel->endPoint == $model->name) {
					$dirModel->endPoint = $_POST['newName'];
					$dirModel->save();
				}
			}
			$model->name = $_POST['newName'];
			$model->save();
			$this->redirect(array('directions/edit/', 'id' => $model->directionId));
		} else {
			$this->renderPartial(
				'editPoint',
				array(
					'data' => array(
						'id'      => $id,
						'oldName' => $model->name,
					)
				)
			);
		}
	}

	public function actionDeletePoint($id)
	{
		$model = Dirpoints::model()->findByPk($id);
		$prevModel = Dirpoints::model()->findByPk($model->prevId);
		$nextModel = Dirpoints::model()->findByPk($model->nextId);
		$prevModel->nextId = $nextModel->id;
		$nextModel->prevId = $prevModel->id;
		$prevModel->save();
		$nextModel->save();
		$model->prevId = 0;
		$model->nextId = 0;
		$model->save();
		$directions = Directions::model()->findAll(
			array(
				'condition' => '(startPoint="' . $model->name . '" or endPoint="' . $model->name . '") and parentId=' . $model->directionId
			)
		);
		foreach ($directions as $d) {
			$d->delete();
		}
		$this->redirect(array('directions/edit/', 'id' => $model->directionId));
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
