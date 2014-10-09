<?php

class TripsController extends Controller
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
			array('allow', // allow all users to perform 'index' and 'view' actions
				  'actions' => array('index', 'view'),
				  'users'   => array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				  'actions' => array('create', 'update'),
				  'users'   => array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				  'actions' => array('admin', 'delete', 'sheet', 'sheetprint', 'profiles'),
				  'users'   => array('admin'),
			),
			array('deny', // deny all users
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
		$model = new Trips;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Trips'])) {
			$model->attributes = $_POST['Trips'];
			if ($model->save()) {
				// Создаём запись в таблице расписаний (для начального и конечного пункта)
				$schData = array(
					'idTrip'      => $model->id,
					'idDirection' => $_POST['Trips']['idDirection'],
					'departure'   => $_POST['Trips']['departure'],
					'arrival'     => $_POST['Trips']['arrival'],
				);
				$schedule = new Schedule;
				$schedule->attributes = $schData;
				$schedule->save();

				$this->redirect(array('admin', 'id' => $model->id));
			}
		}

		if (isset($_POST['trips-date'])) {
			$model->departure = $_POST['trips-date'];
		}

		$data = Directions::model()
						  ->findAll(array('condition' => 'parentId=:parentId', 'params' => array(':parentId' => 0)));
		$directions = array();
		$directions['empty'] = 'Выберите направление';
		foreach ($data as $d) {
			$directions[$d->id] = $d->startPoint . " - " . $d->endPoint;
		}

		$data = Buses::model()->findAll(
			array(
				'condition' => 'status=1'
			)
		);
		$buses = array();
		$buses['empty'] = 'Выберите автобус';
		foreach ($data as $d) {
			$buses[$d->id] = $d->number . ' (Мест: ' . $d->places . ')';
		}

		$this->render('create', array(
			'model'      => $model,
			'directions' => $directions,
			'buses'      => $buses,
			'actual'     => 1,
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

		if (isset($_POST['Trips'])) {
			$model->attributes = $_POST['Trips'];
			$model->description = isset($_POST['Trips']['description']) ? $_POST['Trips']['description'] : '';
			if ($model->save()) {
				// Обновляем запись в таблице расписаний (для начального и конечного пункта)
				$schedule = Schedule::model()->findByAttributes(array('idTrip' => $model->id));
				if ($schedule) {
					if (isset($_POST['Trips']['idDirection'])) $schedule->idDirection = $_POST['Trips']['idDirection'];
					if (isset($_POST['Trips']['departure'])) $schedule->departure = $_POST['Trips']['departure'];
					if (isset($_POST['Trips']['arrival'])) $schedule->arrival = $_POST['Trips']['arrival'];
					$schedule->status = $_POST['Trips']['status'];
					$schedule->save();
				}
				$this->redirect(array('trips/admin/status/actual'));
			}
		}

		$data = Directions::model()
						  ->findAll(array('condition' => 'parentId=:parentId', 'params' => array(':parentId' => 0)));
		$directions = array();
		$directions['empty'] = 'Выберите направление';
		foreach ($data as $d) {
			$directions[$d->id] = $d->startPoint . " - " . $d->endPoint;
		}

		$data = Buses::model()->findAll(
			array(
				'condition' => 'status=1'
			)
		);
		$buses = array();
		$buses['empty'] = 'Выберите автобус';
		foreach ($data as $d) {
			$buses[$d->id] = $d->number . ' (Мест: ' . $d->places . ')';
		}

		$arrRender = array(
			'model'      => $model,
			'directions' => $directions,
			'buses'      => $buses,
		);

		$arrRender['actual'] = ($model->status == 0 || $model->arrival < date("Y-m-d H:i:s")) ? 0 : 1;

		$this->render('update', $arrRender);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 *
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		// Удаление записей расписания, связанных с данным рейсом.
		$schData = Yii::app()->db->createCommand("select id from schedule where idTrip=" . $id)->queryAll();
		foreach ($schData as $d) {
			$schModel = Schedule::model()->findByPk($d["id"]);
			$schModel->status = 0;
			$schModel->save();
		}
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
		$dataProvider = new CActiveDataProvider('Trips');
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Trips sheet
	 */

	public function actionSheet($id)
	{

		$criteria = new CDbCriteria();
		$criteria->join = 'left join trips as tr on tr.idBus=t.id';
		$criteria->condition = 'tr.id=' . $id;
		$data = Buses::model()->find($criteria);
		$bus = $data->attributes;

		$criteria = new CDbCriteria();
		$criteria->join = 'left join trips as tr on t.id=tr.idDirection';
		$criteria->condition = 'tr.id=' . $id;
		$criteria->addCondition('t.parentId=0');
		$data = Directions::model()->find($criteria);
		$direction = $data->attributes;

		$criteria = new CDbCriteria();
		$criteria->condition = 't.idTrip=' . $id . ' and (t.status = 1 or t.status = 2)';
		$criteria->join = 'left join trips as tr on tr.id = t.idTrip';
		$data = Tickets::model()->findAll($criteria);
		$tickets = array();
		foreach ($data as $d) {
			$tickets[] = $d->attributes;
		}

		$arrPlaces = array();
		for ($i = 1; $i <= $bus["places"]; $i++) {
			$arrPlaces[$i] = array(
				'place'      => $i,
				'passenger'  => '',
				'startPoint' => '',
				'endPoint'   => '',
				'phone'      => '',
				'price'      => ''
			);
		}
		$criteria = new CDbCriteria();
		$criteria->condition = 'tid=:tid';
		for ($i = 1; $i <= $bus['places']; $i++) {
			foreach ($tickets as $t) {
				if ($t["place"] == $i) {
					$criteria->params = array(':tid' => $t["id"]);
					$profile = Profiles::model()->find($criteria);
					$arrPlaces[$i] = array(
						'place'      => $i,
						'passenger'  => $profile->attributes["last_name"] . ' ' . $profile->attributes["name"] . ' ' . $profile->attributes["midle_name"],
						'startPoint' => $direction["startPoint"],
						'endPoint'   => $direction["endPoint"],
						'phone'      => $profile->phone,
						'price'      => $t["price"]
					);
				}
			}
		}

		$dataProvider = new CArrayDataProvider(
			$arrPlaces,
			array(
				'keyField'   => 'place',
				'pagination' => array(
					'pageSize' => $bus["places"],
				)
			)
		);

		$this->render(
			'sheet',
			array(
				'dataProvider' => $dataProvider,
				'dataHeader'   => array(
					'bus'       => $bus,
					'direction' => $direction,
					'trips'     => $this->loadModel($id)->attributes,
				)
			)
		);
	}

	public function actionSheetPrint($id)
	{
		$trips = $this->loadModel($id)->attributes;

		$criteria = new CDbCriteria();
		$criteria->join = 'left join trips as tr on tr.idBus=t.id';
		$criteria->condition = 'tr.id=' . $id;
		$data = Buses::model()->find($criteria);
		$bus = $data->attributes;

		$criteria = new CDbCriteria();
		$criteria->join = 'left join trips as tr on t.id=tr.idDirection';
		$criteria->condition = 'tr.id=' . $id;
		$criteria->addCondition('t.parentId=0');
		$data = Directions::model()->find($criteria);
		$direction = $data->attributes;

		$criteria = new CDbCriteria();
		$criteria->condition = 't.idTrip=' . $id . ' and (t.status = 1 or t.status = 2)';
		$criteria->join = 'left join trips as tr on tr.id = t.idTrip';
		$data = Tickets::model()->findAll($criteria);
		$tickets = array();
		foreach ($data as $d) {
			$tickets[] = $d->attributes;
		}

		$pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'P', 'cm', 'A4', TRUE, 'UTF-8');
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor("Trips operator");
		$pdf->SetTitle("Trips sheet");
		$pdf->setPrintHeader(FALSE);
		$pdf->setPrintFooter(FALSE);
		$pdf->AddPage();
		$pdf->SetFont("dejavuserif", "", 10);

		$tbl = '<h2>Рейс ' . $direction['startPoint'] . ' - ' . $direction['endPoint'] . '</h2><br/>';
		$tbl .= 'Отправление: ' . $trips['departure'] . '&nbsp;&nbsp;&nbsp;&nbsp;';
		$tbl .= 'Прибытие: ' . $trips['arrival'] . '<br/>';
		$tbl .= 'Автобус: ' . $bus['model'] . ', номер ' . $bus['number'] . '<br/><br/>';
		$tbl .= '<table width="600px" style="border:1px solid #000000; padding: 8px;">
                    <tbody>
                    <tr bgcolor="#cccccc">
                        <th width="55px" style="border-bottom: 1px solid #000000;"><strong>Место</strong></th>
                        <th style="border-bottom: 1px solid #000000;"><strong>ФИО</strong></th>
                        <th style="border-bottom: 1px solid #000000;"><strong>Посадка</strong></th>
                        <th style="border-bottom: 1px solid #000000;"><strong>Высадка</strong></th>
                        <th style="border-bottom: 1px solid #000000;"><strong>Номер телефона</strong></th>
                        <th width="80px" style="border-bottom: 1px solid #000000;"><strong>Стоимость</strong></th>
                    </tr>';
		$criteria = new CDbCriteria();
		$criteria->condition = 'id=:id';
		for ($i = 1; $i <= $bus['places']; $i++) {
			$flag = 0;
			foreach ($tickets as $t) {
				if ($t["place"] == $i) {
					$criteria->params = array(':id' => $t["idProfile"]);
					$profile = Profiles::model()->find($criteria);
					$tbl .= '<tr>';
					$tbl .= '<td width="55px" style="border-bottom: 1px solid #000000;">' . $i . '</td>';
					$tbl .= '<td style="border-bottom: 1px solid #000000;">' . $profile->attributes["last_name"] . ' ' . $profile->attributes["name"] . ' ' . $profile->attributes["midle_name"] . '</td>';
					$tbl .= '<td style="border-bottom: 1px solid #000000;">' . $direction["startPoint"] . '</td>';
					$tbl .= '<td style="border-bottom: 1px solid #000000;">' . $direction["endPoint"] . '</td>';
					$tbl .= '<td style="border-bottom: 1px solid #000000;">' . $profile->phone . '</td>';
					$tbl .= '<td style="border-bottom: 1px solid #000000;">' . $t["price"] . '</td>';
					$tbl .= '</tr>';
					$flag = 1;
				}
			}
			if (!$flag) {
				$tbl .= '<tr>';
				$tbl .= '<td width="55px" style="border-bottom: 1px solid #000000;">' . $i . '</td>';
				$tbl .= '<td style="border-bottom: 1px solid #000000;"></td>';
				$tbl .= '<td style="border-bottom: 1px solid #000000;"></td>';
				$tbl .= '<td style="border-bottom: 1px solid #000000;"></td>';
				$tbl .= '<td style="border-bottom: 1px solid #000000;"></td>';
				$tbl .= '<td style="border-bottom: 1px solid #000000;"></td>';
				$tbl .= '</tr>';
			}
		}
		$tbl .= '</tbody></table>';

		$pdf->writeHTML($tbl, TRUE, TRUE, FALSE, FALSE, '');
		$pdf->Output("trips-" . $trips['departure'] . "-" . $bus['number'] . ".pdf", "I");
	}

	public function actionProfiles($tripId, $placeId)
	{
		// Create filter model and set properties
		$filtersForm = new FiltersForm;
		$filtersForm->filters['id'] = NULL;
		if (isset($_GET['FiltersForm']))
			$filtersForm->filters = $_GET['FiltersForm'];

		// Get rawData and create dataProvider
		$rawData = Profiles::model()->findAll();
		$filteredData = $filtersForm->filter($rawData);
		$dataProvider = new CArrayDataProvider($filteredData, array(
			'pagination' => array(
				'pageSize' => Yii::app()->params['postsPerPage'],
			),
		));

		// Render
		$this->render('profiles', array(
			'tripId'       => $tripId,
			'placeId'      => $placeId,
			'filtersForm'  => $filtersForm,
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Проверка связности участков рейса
	 */
	public function actionCheck()
	{
		$node = array(
			'id'   => '',
			'sP'   => '',
			'eP'   => '',
			'next' => array(),
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Trips('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Trips']))
			$model->attributes = $_GET['Trips'];

		if (isset($_POST['trips-date'])) {
			$model->departure = $_POST['trips-date'];
		}

		$schData = Yii::app()->db->createCommand("select id from schedule where idTrip=1")->queryAll();
		foreach ($schData as $d) {
			$schModel = Schedule::model()->findByPk($d["id"]);
		}

		$query = "
            select
              t.id,
              t.departure,
              t.arrival,
              d.startPoint,
              d.endPoint,
              b.number,
              t.status
            from trips as t
            left join buses as b on b.id = t.idBus
            left join directions as d on d.id = t.idDirection
            where d.parentId = 0";
		if (isset($model->departure)) $query .= " and (t.departure between '" . $model->departure . " 00:00:00' and '" . $model->departure . " 23:59:59')";
		if (isset($_GET['status']))
			$query .= $_GET['status'] == 'actual' ? ' and (t.status=1 and t.arrival >= "' . date('Y-m-d H:i:s') . '")' : ' and (t.status=0 or t.arrival < "' . date('Y-m-d H:i:s') . '")';

		$tripsData = Yii::app()->db->createCommand($query)->queryAll();

		$arrData = array();

		foreach ($tripsData as $d) {
			if ($d['arrival'] < date('Y-m-d H:i:s') || $d['status'] == 0) {
				$d['status'] = 'Не актуален';
			} else $d['status'] = 'Актуален';
			$arrData[] = $d;
		}

		$dpTripsData = new CArrayDataProvider(
			$arrData,
			array(
				'keyField' => 'id',
				'sort'     => array(
					'attributes' => array('startPoint', 'endPoint', 'number', 'departure', 'arrival', 'status')
				)
			)
		);

		$this->render('admin', array(
			'tripsData' => $dpTripsData,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 *
	 * @param integer $id the ID of the model to be loaded
	 *
	 * @return Trips the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Trips::model()->findByPk($id);
		if ($model === NULL)
			throw new CHttpException(404, 'The requested page does not exist.');

		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 *
	 * @param Trips $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'trips-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
