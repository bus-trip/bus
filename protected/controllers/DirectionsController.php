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
				  'actions' => array('admin', 'delete', 'edit', 'addPoint'),
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
												 //            'params'=>array(':status'=>1),
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

		$parent = Directions::model()->findByPk($id);

		$data = Directions::model()->findAll(
			array(
				'condition' => '(status=' . DIRTRIP_MAIN . ' or status=' . DIRTRIP_EXTEND . ') and parentId=' . $id,
			)
		);
		if (!$data) {
			$data = Directions::model()->findAll(
				array(
					'condition' => '(status=' . DIRTRIP_MAIN . ' or status=' . DIRTRIP_EXTEND . ') and parentId=0 and id=' . $id,
				)
			);
		}

		$arrData = array();
		foreach ($data as $d) {
			if ($d->parentId == 0) $arrParent[$d->id] = $d->startPoint . ' - ' . $d->endPoint;
		}
		foreach ($data as $d) {
			$arrData[] = array(
				'id'         => $d->id,
				'parentId'   => ($d->parentId != 0 ? $arrParent[$d->parentId] : $d->parentId),
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

		$dpModel = new Dirpoints();
		$dirPoints = Dirpoints::model()->findAll(
			array(
				'condition' => 'directionId='.$id
			)
		);

		$this->render('edit', array(
			'model'     => $model,
			'parent'    => $parent->attributes,
			'modelData' => $modelData,
			'dpModel' => $dpModel,
			'dirPoints' => $dirPoints,
		));
	}

	public function actionAddPoint($id, $prevPoint, $postPoint=FALSE)
	{
		if (!isset($_POST['nextPoint'])) {
			$this->renderPartial(
				'addPoint',
				array(
					'data' => array(
						'id' => $id,
						'prevPoint' => $prevPoint,
						'postPoint' => $postPoint,
					),
				)
			);
		} else {
			if (isset($_POST['nextPoint']) && !empty($_POST['nextPoint'])) {
				$dir = Directions::model()->findByPk($id);
				if($dir->parentId != 0) {
					$sPoint = $dir->startPoint;
					$points = array();
					$points[] = $dir->startPoint;
					while (($point = Directions::model()
											   ->findByAttributes(array('startPoint' => $sPoint, 'parentId' => $dir->id)))) {
						$points[] = $point->startPoint;
						if ($point->startPoint == $prevPoint) {
							// Update points with new point
							$points[] = $_POST['nextPoint'];
							$model = Directions::model()->findByPk($point->id);
							$model->endPoint = $_POST['nextPoint'];
							$model->price = $_POST['price1'];
							$model->save();
							// Add new points
							$model = new Directions;
							$model->parentId = $point->parentId;
							$model->startPoint = $_POST['nextPoint'];
							$model->endPoint = $point->endPoint;
							$model->status = $point->status;
							$model->price = $_POST['price2'];
							$model->save();
						}
						$sPoint = $point->endPoint;
					}
					$points[] = $dir->endPoint;
					$points = array_unique($points);
				} else {
					$point = Directions::model()->findByPk($_POST['id']);
					$model = new Directions;
					$model->parentId = $_POST['id'];
					$model->startPoint = $point->startPoint;
					$model->endPoint = $_POST['nextPoint'];
					$model->price = $_POST['price1'];
					$model->status = $point->status;
					$model->save();
					$model = new Directions;
					$model->parentId = $_POST['id'];
					$model->startPoint = $_POST['nextPoint'];
					$model->endPoint = $point->endPoint;
					$model->price = $_POST['price2'];
					$model->status = $point->status;
					$model->save();
				}
				$this->redirect(array('directions/edit/' . $id));
			}
		}
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
