<?php

class ScheduleController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{

        $query = "
            select distinct s.id, d.startPoint, d.endPoint, s.departure, s.arrival
            from schedule as s
            left join directions as d on d.id = s.idDirection
            left join trips as t on t.id = s.idTrip
            where s.idTrip = (select idTrip from schedule where id = ${id}) and d.parentId!=0
            order by s.id
        ";
        $schData = Yii::app()->db->createCommand($query)->queryAll();
        $arrschData = new CArrayDataProvider($schData, array('keyField' => 'id'));

        $query = "
            select distinct s.id, d.startPoint, d.endPoint, s.departure, s.arrival
            from schedule as s
            left join directions as d on d.id = s.idDirection
            where d.parentId = 0 and s.id=${id}
        ";
        $schData = Yii::app()->db->createCommand($query)->queryAll();
        $arrTrData = new CArrayDataProvider($schData, array('keyField' => 'id'));

        $this->render('view',array(
			'model'=>$this->loadModel($id),
            'schData'=>$arrschData,
            'trData'=>$arrTrData->rawData[0],
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Schedule;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Schedule']))
		{
			$model->attributes=$_POST['Schedule'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
        if(isset($_GET['id'])) $arrWhere = 's.id='.$_GET['id'];
        else $arrWhere = 'd.parentId=0';

        $data = Yii::app()->db->createCommand()
            ->select('s.idTrip as id,d.startPoint,d.endPoint')
            ->from('schedule s')
            ->join('directions d', 'd.id=s.idDirection')
            ->join('trips t','t.id=s.idTrip')
            ->where($arrWhere)
            ->queryAll();
        $trips['empty'] = 'Выберите рейс';
        foreach($data as $d){
            $trips[$d['id']]=$d['startPoint'].' - '.$d['endPoint'];
        }
        $trOptions = array(
            'data' => $trips,
            'selOptions' => array(
            ),
        );

        if(isset($_GET['id'])){
            $trKeys = array_keys($trips);
            $trOptions['selOptions']['options'] = array($trKeys[1] => array('selected'=>true));
        }

        $data = Directions::model()->findAll();
        $directions['empty'] = 'Выберите направление';
        foreach($data as $d){
            $directions[$d->id] = $d->startPoint.' - '.$d->endPoint;
        }
		$this->render('create',array(
			'model'=>$model,
            'trips'=>$trOptions,
            'directions'=>$directions,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Schedule']))
		{
			$model->attributes=$_POST['Schedule'];

//            Проверка допустимых условий
            $schTrip = Trips::model()->findByPk($_POST['Schedule']['idTrip']);
            if($model->departure < $schTrip->departure || $schTrip->arrival > $model->arrival){
                $this->redirect(array('update','id'=>$model->id));
            }

			if($model->save()) $this->redirect(array('view','id'=>$model->id));
		}


        $data = Yii::app()->db->createCommand()
            ->select('s.idTrip as id,dp.startPoint,dp.endPoint')
            ->from('directions d')
            ->join('schedule s', 'd.id=s.idDirection')
            ->join('directions dp','dp.id=IF( d.parentId =0, s.idDirection, d.parentId )')
            ->where('s.id='.$model->id)
            ->queryAll();

        foreach($data as $d){
            $trips[$d['id']]=$d['startPoint'].' - '.$d['endPoint'];
        }

        $trOptions = array(
            'data' => $trips,
            'selOptions' => array(
                'empty' => 'Выберите рейс',
                'options' => array(
                    strval($data[0]['id']) => array(
                        'selected' => true,
                    ),
                ),
            ),
        );

        $data = Directions::model()->findAll(array('condition'=>'parentId!=:parentId','params'=>array(':parentId'=>$model->id)));
        $directions['empty'] = 'Выберите направление';
        foreach($data as $d){
            $directions[$d->id] = $d->startPoint.' - '.$d->endPoint;
        }

        $data = Yii::app()->db->createCommand()
            ->select('id')
            ->from('schedule')
            ->where('idTrip = (select idTrip from schedule where id='.$id.')')
            ->order('MAX(TO_DAYS(arrival)-TO_DAYS(departure))')
            ->queryAll();

		$this->render('update',array(
			'model'=>$model,
            'trips'=>$trOptions,
            'directions'=>$directions,
            'idSchedule'=>$data[0]['id'],
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
        $model->status = 0;
        $model->save();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Schedule');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Schedule('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Schedule']))
			$model->attributes=$_GET['Schedule'];


        $query = "
            select distinct s.id, d.startPoint, d.endPoint, s.departure, s.arrival
            from schedule as s
            left join directions as d on d.id = s.idDirection
            where d.parentId = 0
        ";
        $schData = Yii::app()->db->createCommand($query)->queryAll();
        $arrschData = new CArrayDataProvider($schData, array('keyField' => 'id'));


		$this->render('admin',array(
			'model'=>$model,
            'schData'=>$arrschData,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Schedule the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Schedule::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Schedule $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='schedule-form')
		{
            echo "Ajax checked data";
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
