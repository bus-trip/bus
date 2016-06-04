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
		return [
			'accessControl', // perform access control for CRUD operations
			//			'postOnly + delete', // we only allow deletion via POST request
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
			['allow', // allow all users to perform 'index' and 'view' actions
			 'actions' => ['index', 'view', 'deleteticket'],
			 'users'   => ['*'],
			],
			['allow', // allow authenticated user to perform 'create' and 'update' actions
			 'actions' => ['create', 'update'],
			 'users'   => ['@'],
			],
			['allow', // allow admin user to perform 'admin' and 'delete' actions
			 'actions' => ['admin', 'delete', 'sheet', 'sheetprint', 'sheetfullprint', 'profiles', 'sheetpart', 'createticket', 'deleteticket', 'inline', 'sprofiles', 'selectbus', 'dadata'],
			 'users'   => ['admin'],
			],
			['deny', // deny all users
			 'users' => ['*'],
			],
		];
	}

	/**
	 * Displays a particular model.
	 *
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view', [
			'model' => $this->loadModel($id),
		]);
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
			$model->description = isset($_POST['Trips']['description']) ? $_POST['Trips']['description'] : '';
			if ($model->arrival > $model->departure && $model->departure > date('Y-m-d H:i:s')) {
				if ($model->save()) {
					// Создаём запись в таблице расписаний (для начального и конечного пункта)
					$schData = [
						'idTrip'      => $model->id,
						'idDirection' => $_POST['Trips']['idDirection'],
						'departure'   => $_POST['Trips']['departure'],
						'arrival'     => $_POST['Trips']['arrival'],
					];
//					$schedule = new Schedule;
//					$schedule->attributes = $schData;
//					$schedule->save();

					$this->redirect(['trips/admin/status/actual']);
				}
			} elseif ($model->arrival <= $model->departure) {
				$model->addError('arrival', 'Время прибытия не должно быть равно или меньше времени отправления.');
			} else {
				$model->addError('departure', 'Время отправления не может быть меньше текущего.');
			}
		}

		if (isset($_POST['trips-date'])) {
			$model->departure = $_POST['trips-date'];
		}

		$model->departure = Yii::app()->user->getState('trips-date');
		$model->arrival = Yii::app()->user->getState('trips-arrive');
		$model->idDirection = Yii::app()->user->getState('trips-dir-id');

		$data = Directions::model()
						  ->findAllByAttributes(['status' => 1, 'parentId' => 0]); //findAllByPk($model->idDirection);
		$directions = [];
		foreach ($data as $d) {
			$directions[$d->id] = $d->startPoint . " - " . $d->endPoint;
		}

		$data = Buses::model()->findAll(
			[
				'condition' => 'status=1'
			]
		);
		$buses = [];
		$buses['empty'] = 'Выберите автобус';
		foreach ($data as $d) {
			$buses[$d->id] = $d->number . ' (Мест: ' . $d->places . ')';
		}

		$this->render('create', [
			'model'      => $model,
			'directions' => $directions,
			'buses'      => $buses,
			'actual'     => DIRTRIP_EXTEND,
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

		if (isset($_POST['Trips'])) {
			$model->attributes = $_POST['Trips'];
			$model->description = isset($_POST['Trips']['description']) ? $_POST['Trips']['description'] : '';
			if ($model->arrival > $model->departure) {
				if ($model->save()) {
					// Обновляем запись в таблице расписаний (для начального и конечного пункта)
					$schedule = Schedule::model()->findByAttributes(['idTrip' => $model->id]);
					if ($schedule) {
						if (isset($_POST['Trips']['idDirection'])) $schedule->idDirection = $_POST['Trips']['idDirection'];
						if (isset($_POST['Trips']['departure'])) $schedule->departure = $_POST['Trips']['departure'];
						if (isset($_POST['Trips']['arrival'])) $schedule->arrival = $_POST['Trips']['arrival'];
						$schedule->status = $_POST['Trips']['status'];
						$schedule->save();
					}
					$this->redirect(['trips/admin/status/actual']);
				}
			} else {
				$model->addError('arrival', 'Время прибытия не должно быть равно или меньше времени отправления');
			}
		}

		$data = Directions::model()
						  ->findAll(['condition' => 'parentId=:parentId', 'params' => [':parentId' => 0]]);
		$directions = [];
		$directions['empty'] = 'Выберите направление';
		foreach ($data as $d) {
			$directions[$d->id] = $d->startPoint . " - " . $d->endPoint;
		}

		$data = Buses::model()->findAll(
			[
				'condition' => 'status=1'
			]
		);
		$buses = [];
		$buses['empty'] = 'Выберите автобус';
		foreach ($data as $d) {
			$buses[$d->id] = $d->number . ' (Мест: ' . $d->places . ')';
		}

		$arrRender = [
			'model'      => $model,
			'directions' => $directions,
			'buses'      => $buses,
		];

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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['admin']);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('Trips');
		$this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Trips sheet
	 */

	public function actionSheet($id)
	{
		$this->layout = '//layouts/column1';

		Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
		Yii::app()->clientScript->registerCssFile(
			Yii::app()->clientScript->getCoreScriptUrl() .
			'/jui/css/base/jquery-ui.css'
		);

		if ($id == 0) {
			Yii::app()->user->setState('trips-date', $_POST['trips-date']);
			Yii::app()->user->setState('trips-arrive', $_POST['trips-arrive']);
			Yii::app()->user->setState('trips-dir-id', $_POST['trips-dir-id']);
			$this->redirect(['create',]);
		}

		$criteria = new CDbCriteria();
		$criteria->join = 'left join trips as tr on tr.idBus=t.id';
		$criteria->condition = 'tr.id=' . $id;
		$data = Buses::model()->find($criteria);
		$bus = $data->attributes;

		$data = Buses::model()->findAll(['condition' => 'status=1']);
		foreach ($data as $d) {
			$buses[$d['id']] = $d['model'] . ' ' . $d['number'];
		}

		$criteria = new CDbCriteria();
		$criteria->join = 'left join trips as tr on t.id=tr.idDirection';
		$criteria->condition = 'tr.id=' . $id;
		$criteria->addCondition('t.parentId=0');
		$data = Directions::model()->find($criteria);
		$direction = $data->attributes;

		$criteria = new CDbCriteria();
		$criteria->condition = 't.idTrip=' . $id;
		$criteria->addNotInCondition('t.status', [Tickets::STATUS_CANCELED]);
		$criteria->join = 'left join trips as tr on tr.id = t.idTrip';
		$data = Tickets::model()->findAll($criteria);
		$tickets = [];
		foreach ($data as $d) {
			$tickets[] = $d->attributes;
		}

		$arrPlaces = [];
		$criteria = new CDbCriteria();
		$criteria->condition = 'tid=:tid';
		foreach ($tickets as $t) {
			$criteria->params = [':tid' => $t["id"]];
			/**    @var $profile Profiles */
			$profile = Profiles::model()->find($criteria);

			$direction = Directions::model()->findByPk($t["idDirection"]);

			if (!$profile) $profile = new Profiles();
			$arrPlaces[] = [
				'profile_id'  => $profile->id,
				'place'       => $t["place"],
				'doc_type'    => $profile->doc_type,
				'doc_num'     => $profile->doc_num,
				'last_name'   => $profile->last_name,
				'name'        => $profile->name,
				'middle_name' => $profile->middle_name,
				'birthday'    => $profile->birth,
				'phone'       => $profile->phone,
				'direction'   => $direction->startPoint . " - " . $direction->endPoint,
				'directionId' => $direction->id,
				'startPoint'  => $t["address_from"],
				'endPoint'    => $t["address_to"],
				'price'       => $t["price"],
				'remark'      => $t["remark"],
				'status'      => $t["status"],
				'black_list'  => $profile->black_list ? '!' : '',
				'ticket_id'   => $profile->tid,
			];
		}

		for ($i = 1; $i <= $bus["places"]; $i++) {
			$emptyPlace = TRUE;
			foreach ($arrPlaces as $p) {
				if ($i == $p["place"]) {
					$emptyPlace = FALSE;
					break;
				}
			}
			if ($emptyPlace) {
				array_push($arrPlaces, [
					'profile_id'  => '',
					'place'       => $i,
					'doc_type'    => '',
					'doc_num'     => '',
					'last_name'   => '',
					'name'        => '',
					'middle_name' => '',
					'birthday'    => '',
					'phone'       => '',
					'direction'   => '',
					'directionId' => '',
					'startPoint'  => '',
					'endPoint'    => '',
					'price'       => '',
					'remark'      => '',
					'status'      => '',
					'black_list'  => '',
					'ticket_id'   => '',
				]);
			}
		}

		function placeSort($a, $b)
		{
			if ($a["place"] == $b["place"]) return 0;

			return ($a["place"] < $b["place"]) ? -1 : 1;
		}

		usort($arrPlaces, "placeSort");

		$dataProvider = new CArrayDataProvider(
			$arrPlaces,
			[
				'keyField'   => 'place',
				'pagination' => [
					'pageSize' => count($arrPlaces), //$bus["places"],
				]
			]
		);

		$this->render(
			'sheet',
			[
				'dataProvider' => $dataProvider,
				'dataHeader'   => [
					'bus'       => $bus,
					'direction' => $direction,
					'trips'     => $this->loadModel($id)->attributes,
					'buses'     => $buses,
				],
				'selectDate'   => [
					'monthSelect' => isset($_POST['monthSelect']) ? $_POST['monthSelect'] : '',
					'yearSelect'  => isset($_POST['yearSelect']) ? $_POST['yearSelect'] : '',
				]
			]
		);
	}

	public function actionDeleteticket($id)
	{
		$Ticket = Tickets::model()->findByPk($id);
//        $Profile = Profiles::model()->findByAttributes(array('tid' => $id));
//        $Profile->delete();

		$Ticket->status = 0;
		$Ticket->save();
		$this->redirect(['trips/sheet/' . $Ticket->idTrip]);
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
		$tickets = [];
		foreach ($data as $d) {
			$tickets[] = $d->attributes;
		}

		$pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'L', 'cm', 'A4', TRUE, 'UTF-8');
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor("Trips operator");
		$pdf->SetTitle("Trips sheet");
		$pdf->setPrintHeader(FALSE);
		$pdf->setPrintFooter(FALSE);
		$pdf->SetFont("dejavuserif", "", 8);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->AddPage();

		$tbl = '<h2>Рейс ' . $direction['startPoint'] . ' - ' . $direction['endPoint'] . '</h2><br/>';
		$tbl .= 'Отправление: ' . date('d.m.Y H:i', strtotime($trips['departure'])) . '&nbsp;&nbsp;&nbsp;&nbsp;';
		$tbl .= 'Прибытие: ' . date('d.m.Y H:i', strtotime($trips['arrival'])) . '&nbsp;&nbsp;&nbsp;&nbsp;Автобус: ' . $bus['model'] . ', номер ' . $bus['number'] . '<br/><br/>';
		$tbl .= '<table width="950px" cellpadding="2" style="border:1px solid #000000; padding: 8px;">
                    <tbody>
                    <tr bgcolor="#cccccc">
                        <th width="20px" style="border: 1px solid #000000;"><strong>№</strong></th>
                        <th style="border: 1px solid #000000;"><strong>ФИО</strong></th>
                        <th style="border: 1px solid #000000;"><strong>Посадка</strong></th>
                        <th width="150px" style="border: 1px solid #000000;"><strong>Высадка</strong></th>
                        <th width="100px" style="border: 1px solid #000000;"><strong>Номер телефона</strong></th>
                        <th width="60px" style="border: 1px solid #000000;"><strong>ДР</strong></th>
                        <th width="70px" style="border: 1px solid #000000;"><strong>Паспорт</strong></th>
                        <th style="border: 1px solid #000000;"><strong>Примечание</strong></th>
                        <th width="40px" style="border: 1px solid #000000;"><strong>Цена</strong></th>
                    </tr>';
		$criteria = new CDbCriteria();
		$criteria->condition = 'tid=:tid';
		for ($i = 1; $i <= $bus['places']; $i++) {
			$flag = 0;
			foreach ($tickets as $t) {
				if ($t["place"] == $i) {
					$criteria->params = [':tid' => $t["id"]];
					/**    @var $profile Profiles */
					$profile = Profiles::model()->find($criteria);
					$tbl .= '<tr>';
					$tbl .= '<td width="20px" style="border-bottom: 1px solid #000000;">' . $i . '</td>';
					$tbl .= '<td style="border: 1px solid #000000;">' . $profile->attributes["last_name"] . ' ' . $profile->attributes["name"] . ' ' . $profile->attributes["middle_name"] . '</td>';
					$tbl .= '<td style="border: 1px solid #000000;">' . $t["address_from"] . '</td>';
					$tbl .= '<td style="border: 1px solid #000000;">' . $t["address_to"] . '</td>';
					$tbl .= '<td style="border: 1px solid #000000;">' . $profile->phone . '</td>';
					$tbl .= '<td style="border: 1px solid #000000;">' . $profile->birth . '</td>';
					$tbl .= '<td style="border: 1px solid #000000;">' . Profiles::getDocType($profile->doc_type) . ': ' . $profile->doc_num . '</td>';
					$tbl .= '<td style="border: 1px solid #000000;">' . $t['remark'] . '</td>';
					$tbl .= '<td style="border: 1px solid #000000;">' . $t["price"] . '</td>';
					$tbl .= '</tr>';
					$flag = 1;
				}
			}
			if (!$flag) {
				$tbl .= '<tr>';
				$tbl .= '<td width="20px" style="border: 1px solid #000000;">' . $i . '</td>';
				$tbl .= '<td style="border: 1px solid #000000;"></td>';
				$tbl .= '<td style="border: 1px solid #000000;"></td>';
				$tbl .= '<td style="border: 1px solid #000000;"></td>';
				$tbl .= '<td style="border: 1px solid #000000;"></td>';
				$tbl .= '<td style="border: 1px solid #000000;"></td>';
				$tbl .= '<td style="border: 1px solid #000000;"></td>';
				$tbl .= '<td style="border: 1px solid #000000;"></td>';
				$tbl .= '<td style="border: 1px solid #000000;"></td>';
				$tbl .= '</tr>';
			}
		}
		$tbl .= '</tbody></table>';

		$pdf->writeHTML($tbl, TRUE, TRUE, FALSE, FALSE, '');
		$pdf->lastPage();
		$pdf->Output("trips-" . $trips['departure'] . "-" . $bus['number'] . ".pdf", "I");
	}

	public function actionSheetFullPrint($id)
	{
		$trips = $this->loadModel($id)->attributes;

		$criteria = new CDbCriteria();
		$criteria->join = 'left join trips as tr on tr.idBus=t.id';
		$criteria->condition = 'tr.id=' . $id;
		$data = Buses::model()->find($criteria);
		$bus = $data->attributes;

//		$criteria = new CDbCriteria();
//		$criteria->join = 'left join trips as tr on t.id=tr.idDirection';
//		$criteria->condition = 'tr.id=' . $id;
//		$criteria->addCondition('t.parentId=0');
//		$data = Directions::model()->find($criteria);
//		$direction = $data->attributes;

		$criteria = new CDbCriteria();
		$criteria->condition = 't.idTrip=' . $id . ' and (t.status = 1 or t.status = 2)';
		$criteria->join = 'left join trips as tr on tr.id = t.idTrip';
		$data = Tickets::model()->findAll($criteria);
		$tickets = [];
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
		$pdf->SetFont("dejavuserif", "", 7);

		$tbl = '<div style="text-align: right;">Приложение №1 к договору<br/>фрахтования транспортного средства<br/>для перевозки пассажиров по заказу</div>';
		$tmp = preg_split("/[-,\ ]/", $trips['departure']);
		$tbl .= '<div style="text-align: center;">';
		$tbl .= '<h2>Список пассажиров &laquo;Транспортного средства&raquo;<br/> (определённый круг лиц) на ' . $tmp[2] . '.' . $tmp[1] . '.' . $tmp[0] . ' года</h2>';
		$tbl .= '</div>';
		$tbl .= '<table style="width: 1400px; border:1px solid #000000; padding: 8px;">
                    <tbody>
                    <tr style="font-size: 7pt; text-align: center;">
                        <th width="30px" style="border: 1px solid #000000;"><strong>№<br/>п/п</strong></th>
                        <th style="border: 1px solid #000000;"><strong>ФИО пассажира</strong></th>
                        <th width="80px" style="border: 1px solid #000000;"><strong>Серия и номер паспорта</strong></th>
                        <th width="80px" style="border: 1px solid #000000;"><strong>Дата рождения</strong></th>
                        <th width="80px" style="border: 1px solid #000000;"><strong>Отметка о присутствии в т/с</strong></th>
                    </tr>';
		$criteria = new CDbCriteria();
		$criteria->condition = 'tid=:tid';
		for ($i = 1; $i <= $bus['places']; $i++) {
			$flag = 0;
			foreach ($tickets as $t) {
				if ($t["place"] == $i) {
					$criteria->params = [':tid' => $t["id"]];
					/**    @var $profile Profiles */
					$profile = Profiles::model()->find($criteria);
					$tbl .= '<tr style="font-size: 8pt;">';
					$tbl .= '<td width="30px" style="border: 1px solid #000000;">' . $i . '</td>';
					$tbl .= '<td style="border: 1px solid #000000;">' . $profile->last_name . ' ' . $profile->name . ' ' . $profile->middle_name . '</td>';
					$tbl .= '<td width="80px" style="border: 1px solid #000000;">' . Profiles::getDocType($profile->doc_type) . ': ' . $profile->doc_num . '</td>';
					$tbl .= '<td width="80px" style="border: 1px solid #000000;">' . $profile->birth . '</td>';
					$tbl .= '<td width="80px" style="border: 1px solid #000000;"></td>';
					$tbl .= '</tr>';
					$flag = 1;
				}
			}
			if (!$flag) {
				$tbl .= '<tr>';
				$tbl .= '<td width="30px" style="border: 1px solid #000000;">' . $i . '</td>';
				$tbl .= '<td style="border: 1px solid #000000;"></td>';
				$tbl .= '<td width="80px" style="border: 1px solid #000000;"></td>';
				$tbl .= '<td width="80px" style="border: 1px solid #000000;"></td>';
				$tbl .= '<td width="80px" style="border: 1px solid #000000;"></td>';
				$tbl .= '</tr>';
			}
		}
		$tbl .= '</tbody></table>';
		$tbl .= '<div style="text-align: right; background-color: #ffffff;"><h2>&laquo;Фрахтователь&raquo;:</h2>';
		$tbl .= '<br/>__________________________________________</div>';

		$pdf->writeHTML($tbl, TRUE, TRUE, FALSE, FALSE, '');
		$pdf->Output("trips-" . $trips['departure'] . "-" . $bus['number'] . ".pdf", "I");
	}

	public function actionProfiles($tripId, $placeId, $directionId)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'idTrip=:idTrip AND place=:place AND idDirection=:direction';
		$criteria->params = [':idTrip' => $tripId, ':place' => $placeId, ':direction' => $directionId];
		$criteria->addNotInCondition('t.status', [Tickets::STATUS_CANCELED]);
		$Ticket = Tickets::model()->findAll($criteria);

		if (!empty($Ticket)) {
			$Ticket = $Ticket[count($Ticket) - 1]; // последний созданный профайл
			// билет создан, страница редактирования
			$Profile = Profiles::model()->findByAttributes(['tid' => $Ticket->id]);
			if (!$Profile) {
				$Profile = new Profiles();
				$Profile->tid = $Ticket->id;
			}
			// обработчик формы
			if (!empty($_POST['Profiles']) && !empty($_POST['Tickets'])) {
				$Ticket->attributes = $_POST['Tickets'];
				$Profile->attributes = $_POST['Profiles'];
				if ($Profile->validate() && $Profile->save() && $Ticket->validate() && $Ticket->save()) {
					Yii::app()->user->setFlash('success', "Билет #" . str_pad($Ticket->id, 4, '0', STR_PAD_LEFT) . " обновлен");

					$url = $this->createUrl('/trips/sheet/' . $tripId);
					$this->redirect($url);
				}
			}

			$this->render('ticket', [
				'tripId'  => $tripId,
				'placeId' => $placeId,
				'profile' => $Profile ? $Profile : new Profiles(),
				'model'   => $Ticket
			]);
		} else {
			// создаем билет
			$model = new Profiles('search');
			$model->unsetAttributes(); // clear any default values
			if (isset($_GET['Profiles']))
				$model->attributes = $_GET['Profiles'];

			$this->render('profiles', [
				'tripId'  => $tripId,
				'placeId' => $placeId,
				'model'   => $model,
			]);
		}
	}

	public function actionSheetPart($tripId, $placeId, $directionId)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'idTrip=:idTrip AND place=:place';
		$criteria->params = [':idTrip' => $tripId, ':place' => $placeId];
		$criteria->addNotInCondition('t.status', [Tickets::STATUS_CANCELED]);
		$Ticket = Tickets::model()->findAll($criteria);

		if (!empty($Ticket)) {
			$Ticket = $Ticket[count($Ticket) - 1]; // последний созданный профайл
			// билет создан, страница редактирования
			$Profile = Profiles::model()->findByAttributes(['tid' => $Ticket->id]);
			if (!$Profile) {
				$Profile = new Profiles();
				$Profile->tid = $Ticket->id;
			}
			// обработчик формы
			if (!empty($_POST['Profiles']) && !empty($_POST['Tickets'])) {
				$Ticket->attributes = $_POST['Tickets'];
				$Profile->attributes = $_POST['Profiles'];
				if ($Profile->validate() && $Profile->save() && $Ticket->validate() && $Ticket->save()) {
					Yii::app()->user->setFlash('success', "Билет #" . str_pad($Ticket->id, 4, '0', STR_PAD_LEFT) . " обновлен");

					$url = $this->createUrl('/trips/sheet/' . $tripId);
					$this->redirect($url);
				}
			}

			$this->render('ticket', [
				'tripId'  => $tripId,
				'placeId' => $placeId,
				'profile' => $Profile ? $Profile : new Profiles(),
				'model'   => $Ticket
			]);
		} else {
			// создаем билет
			$model = new Profiles('search');
			$model->unsetAttributes(); // clear any default values
			if (isset($_GET['Profiles']))
				$model->attributes = $_GET['Profiles'];

			$this->render('profiles', [
				'tripId'  => $tripId,
				'placeId' => $placeId,
				'model'   => $model,
			]);
		}
	}

	public function actionCreateTicket($tripId, $placeId, $profileId)
	{
		$Ticket = new Tickets();
		$Ticket->status = 1;

		$Profile = new Profiles();
		list($discount) = Yii::app()->createController('discounts');

		// обработчик формы
		if (!empty($_POST['Profiles']) && !empty($_POST['Tickets'])) {
			$Profile->attributes = $_POST['Profiles'];
			if ($Profile->validate()) {
				$Ticket->attributes = $_POST['Tickets'];
				$Ticket->idTrip = $tripId;
				$Ticket->place = $placeId;

				$criteria = new CDbCriteria();
				$criteria->join = 'join trips as tr on t.id=tr.idDirection';
				$criteria->condition = 'tr.id=' . $tripId;
				$Direction = Directions::model()->find($criteria);

				if ($Direction) {
					// Задаём скидку, пока без условий
//					$Ticket->price = $discount->getDiscount($Profile->id);
					if ($Ticket->validate() && $Ticket->save()) {
						$Profile->tid = $Ticket->id;
						$Profile->save();
						$Ticket->price = $discount->getDiscount($Profile->id);
						$Ticket->save();
						Yii::app()->user->setFlash('success', "Билет #" . str_pad($Ticket->id, 4, '0', STR_PAD_LEFT) . " забронирован");

						$url = $this->createUrl('/trips/sheet/' . $tripId);
						$this->redirect($url);
					}
				}
			}
		} elseif ($profileId != 0) {
			$Profile = Profiles::model()->findByPk($profileId);
		}

		$this->render('ticket', [
			'tripId'  => $tripId,
			'placeId' => $placeId,
			'profile' => $Profile,
			'model'   => $Ticket
		]);
	}

	/**
	 * Проверка связности участков рейса
	 */
	public function actionCheck()
	{
		$node = [
			'id'   => '',
			'sP'   => '',
			'eP'   => '',
			'next' => [],
		];
	}

	public function actionSelectbus()
	{
		if (!empty($_POST)) {
			$Trip = Trips::model()->findByPk($_POST['idTrip']);
			$Trip->idBus = $_POST['buslist'];
			if ($Trip->validate()) $Trip->save();
			$url = $this->createUrl('/trips/sheet/' . $_POST['idTrip']);
			$this->redirect($url);
		} else {
			$url = $this->createUrl('/index.php');
			$this->redirect($url);
		}
	}

	function actionInline()
	{
		if (empty($_POST['tripId']) || empty($_POST['placeId'])) {
			throw new CHttpException(404, 'The requested page does not exist.');
		}

		list($discount) = Yii::app()->createController('discounts');

		$tripId = $_POST['tripId'];

		$trip = Trips::model()->findByPk($tripId);
		$parentDir = Directions::model()->findByPk($trip->idDirection);
		$criteria = new CDbCriteria();
		$criteria->condition = 'parentId=' . $parentDir->id . ' and startPoint="' . $parentDir->startPoint . '" and endPoint="' . $parentDir->endPoint . '"';
		$direction = Directions::model()->find($criteria);

		$placeId = $_POST['placeId'];

		$tickets = array();
		if (isset($_POST['directionId'])) {
			$directionId = $_POST['directionId'];
			$criteria = new CDbCriteria();
			$criteria->condition = 'idTrip=:idTrip AND place=:place AND t.idDirection=:direction';
			$criteria->params = [':idTrip' => $tripId, ':place' => $placeId, ':direction' => $directionId];
			$criteria->addNotInCondition('t.status', [Tickets::STATUS_CANCELED]);
			$tickets = Tickets::model()->with('profiles')->with(['idTrip0', 'idDirection0' => 'idTrip0'])
							  ->findAll($criteria);
		}
		$ticket = !empty($tickets) ? $tickets[count($tickets) - 1] : new Tickets();

		if (!empty($ticket->profiles)) {
			/** @var $profile Profiles */
			$profile = $ticket->profiles[count($ticket->profiles) - 1];
		} else {
			$profile = new Profiles();
		}

		$errors = [];
		if (!empty($_POST['data'])) { // обновляем/создаем профиль и билет
			$profile->doc_type = $_POST['data']['Profiles[doc_type'];
			$profile->doc_num = $_POST['data']['Profiles[doc_num'];
			$profile->last_name = $_POST['data']['Profiles[last_name'];
			$profile->name = $_POST['data']['Profiles[name'];
			$profile->middle_name = $_POST['data']['Profiles[middle_name'];
			$profile->phone = $_POST['data']['Profiles[phone'];
			$profile->birth = $_POST['data']['Profiles[birth'];
			$profile->black_list = $_POST['data']['Profiles[black_list'];
			$profile->black_desc = $_POST['data']['Profiles[black_desc'];

			if ($profileLoad = Profiles::model()->findAllByPk($_POST['data']['Profiles[id'])) {
				switch ($profileLoad[0]->sex) {
					case 'Мужской':
						$profile->sex = Profiles::SEX_MALE;
						break;
					case 'Женский':
						$profile->sex = Profiles::SEX_FEMALE;
						break;
				}
			}

			if ($profile->validate()) {
				$ticket->idTrip = $tripId;
				$ticket->place = $placeId;
				$ticket->address_from = $_POST['data']['Tickets[address_from'];
				$ticket->address_to = $_POST['data']['Tickets[address_to'];
				$ticket->remark = $_POST['data']['Tickets[remark'];
				$ticket->price = $_POST['data']['Tickets[price'];
				if ($_POST['data']['Tickets[direction'] != 0) {
					$ticket->idDirection = $_POST['data']['Tickets[direction'];
				} else {
					$ticket->idDirection = $direction->id;
				}
				if (empty($ticket->status)) {
					$ticket->status = Tickets::STATUS_RESERVED;
				}

				if ($ticket->save()) {
					$profile->tid = $ticket->id;
					$profile->save();
					if ($ticket->idTrip0->idDirection0->price == $ticket->price)
						$ticket->price = $discount->getDiscount($profile->id);
					$ticket->save();
					Yii::app()->user->setFlash('success', "Билет #" . str_pad($ticket->id, 4, '0', STR_PAD_LEFT) . " забронирован");
				} else {
					$errors = $ticket->getErrors();
				}
			} else {
				$errors = $profile->getErrors();
			}
		}

		if (!$ticket->price) {
			$criteria1 = new CDbCriteria();
			$criteria1->join = 'left join trips as tr on t.id=tr.idDirection';
			$criteria1->condition = 'tr.id=' . $tripId;
			$criteria1->addCondition('t.parentId=0');
			$Direction = Directions::model()->find($criteria1);
			$ticket->price = $Direction->price;
			$default_price = '';
		} else {
			$default_price = $ticket->price;
		}

		$options = '';
		foreach ([Profiles::DOC_PASSPORT          => 'Паспорт РФ',
				  Profiles::DOC_BIRTH_CERTIFICATE => 'Свидетельство о рождении',
				  Profiles::DOC_FOREIGN_PASSPORT  => 'Загран паспорт',
				  Profiles::DOC_MILITARY_ID       => 'Военный билет',
				  Profiles::DOC_OTHER_PASSPORT    => 'Паспорт другой страны'
				 ] as $value => $name) {

			$options .= '<option value="' . $value . '"';
			if (!empty($ticket->profiles) && $profile->doc_type == $value) {
				$options .= ' selected="selected"';
			}
			$options .= '>' . $name . '</option>';
		}

		$trip = Trips::model()->findByPk($tripId);
		$directions = Directions::model()
								->findAll(['condition' => 'parentId=' . $trip->idDirection . ' and status!=' . DIRTRIP_CANCELED]);
		$dirOptions = '<option value="0">Выберите участок</option>';
		$dirHiddens = '';
		$direction = '';
		foreach ($directions as $d) {
			$dirOptions .= '<option value=' . $d->id;
			if ($d->id == $ticket->idDirection) {
				$dirOptions .= ' selected="selected"';
				$direction = $d->startPoint . " - " . $d->endPoint;
			}
			$dirOptions .= '>' . $d->startPoint . " - " . $d->endPoint . '</option>';
			$dirHiddens .= '<input type="hidden" id="price' . $d->id . '" value="' . $d->price . '">';
		}

		$inputs = [
			'<input class="autocomplete" type="hidden"  name="Profiles[id]" value="' . (!empty($ticket->profiles) ? $profile->id : '') . '"/>' .
			'<select class="autocomplete" name="Profiles[doc_type]">' . $options . '</select><input class="autocomplete" type="text" name="Profiles[doc_num]" maxlength="64" size="10" value="' . (!empty($ticket->profiles) ? $profile->doc_num : '') . '" />',
			'<input class="autocomplete" type="text" name="Profiles[last_name]" size="10" value="' . (!empty($ticket->profiles) ? $profile->last_name : '') . '" />',
			'<input class="autocomplete" type="text" name="Profiles[name]" size="10" value="' . (!empty($ticket->profiles) ? $profile->name : '') . '" />',
			'<input class="autocomplete" type="text" name="Profiles[middle_name]" size="10" value="' . (!empty($ticket->profiles) ? $profile->middle_name : '') . '" />',
			'<input class="autocomplete" type="text" name="Profiles[phone]" size="12" value="' . (!empty($ticket->profiles) ? $profile->phone : '') . '" />',
			'<input class="autocomplete" type="text" name="Profiles[birth]" size="10" value="' . (!empty($ticket->profiles) ? $profile->birth : '') . '" />',
			'<input class="autocomplete" type="text" name="Tickets[direction]" size="10" value="' . $direction . '" disabled/>',
			'<textarea class="autocomplete dadata" name="Tickets[address_from]">' . $ticket->address_from . '</textarea>',
			'<textarea class="autocomplete dadata" name="Tickets[address_to]">' . $ticket->address_to . '</textarea>',
			'<textarea name="Tickets[remark]">' . $ticket->remark . '</textarea>',
			'<select class="autocomplete" style="width:170px;" name="Tickets[direction]" onchange="document.getElementById(\'ticketsPrice\').value=document.getElementById(\'price\'+this.options[this.selectedIndex].value).value">' . $dirOptions . '</select>' . $dirHiddens . '<input type="text" id="ticketsPrice" name="Tickets[price]" value="' . $ticket->price . '" />',
		];

		$bl = [
			'in_bl'      => (!empty($ticket->profiles) ? $profile->black_list : 0),
			'in_bl_desc' => (!empty($ticket->profiles) ? $profile->black_desc : NULL)
		];

		$birth = '';
		if (!empty($ticket->profiles)) {
			if (strpos($profile->birth, '.') !== FALSE) {
				$birth = $profile->birth;
			} else {
				$birth = date('d.m.Y', $profile->birth);
			}
		}

		$inline = [
			(string) (!empty($ticket->profiles) ? Profiles::getDocType($profile->doc_type) . '<br>' . CHtml::link($profile->doc_num, ["tickets/profile/" . $profile->id]) : ''),
			(string) (!empty($ticket->profiles) ? $profile->last_name : ''),
			(string) (!empty($ticket->profiles) ? $profile->name : ''),
			(string) (!empty($ticket->profiles) ? $profile->middle_name : ''),
			(string) (!empty($ticket->profiles) ? $profile->phone : ''),
			$birth,
			(string) $direction,
			(string) $ticket->address_from,
			(string) $ticket->address_to,
			(string) $ticket->remark,
			(string) $default_price
		];

		$this->layout = FALSE;
		header('Content-type: application/json');
		echo CJavaScript::jsonEncode(['inputs' => $inputs, 'black_list' => $bl, 'inline' => $inline, 'errors' => $errors]);
		Yii::app()->end();
	}

	public function actionSProfiles($term)
	{
		$res = [];
		if (isset($_GET['term']) && isset($_GET['field'])) {
			$field = preg_replace('#Profiles\[([^\]]*)\]#', '$1', $_GET['field']);
			$criteria_tickets = new CDbCriteria();
			if ($field == 'birth') {
				$date_arr = explode('.', $_GET['term']);
				$term = strtotime($date_arr[2] . '-' . $date_arr[1] . '-' . $date_arr[0]);
				if (!$term) {
					echo CJSON::encode($res);
					Yii::app()->end();
				}

				$criteria_tickets->addBetweenCondition($field, $term, $term + 24 * 60 * 60);
			} else {
				$criteria_tickets->params = [':field' => '%' . trim($_GET['term']) . '%'];
				$criteria_tickets->addCondition($field . ' LIKE :field');
			}

			/** @var $ticketObjs Tickets[] */
			$ticketObjs = Tickets::model()->with('profiles')->findAll($criteria_tickets);
			$arr1 = $arr2 = [];
			foreach ($ticketObjs as $ticket) {
				if ($ticket->profiles) {
					$key = count($ticket->profiles) - 1;

					$profile = $ticket->profiles[$key];
					if ($profile->doc_type && $profile->doc_num && $profile->last_name) {
						$skey2 = md5($profile->doc_type . '::' . $profile->doc_num . '::' . $profile->last_name . '::' . $profile->black_list);
						$skey = md5($profile->doc_type . '::' . $profile->doc_num . '::' . $profile->last_name . '::' . $profile->black_list . '::' . $ticket->address_from . '::' . $ticket->address_to);
						if ($ticket->address_from || $ticket->address_to) $arr1[$skey2][] = $skey;
						else                                              $arr2[$skey2][] = $skey;

						$str_in_bl = $profile->black_list == 1 ? '; В ЧС' : '';
						$res[$skey] = [
							'value' => $profile->$field,
							'info'  => '(' . $profile->shortName() . '; ' . Profiles::getDocType($profile->doc_type) . ': ' . $profile->doc_num . ') ' . $ticket->shortAddress() . $str_in_bl,
							'data'  => [
								'Profiles[id]'          => $profile->id,
								'Profiles[doc_type]'    => $profile->doc_type,
								'Profiles[doc_num]'     => $profile->doc_num,
								'Profiles[last_name]'   => $profile->last_name,
								'Profiles[name]'        => $profile->name,
								'Profiles[middle_name]' => $profile->middle_name,
								'Profiles[phone]'       => $profile->phone,
								'Profiles[birth]'       => $profile->birth,
								'Profiles[black_list]'  => $profile->black_list,
								'Profiles[black_desc]'  => $profile->black_desc,
								'Tickets[address_from]' => $ticket->address_from,
								'Tickets[address_to]'   => $ticket->address_to,
							],
						];
					}
				}
			}
		}

		$result = [];
		foreach ($arr1 as $p => $b) {
			foreach ($b as $k) {
				if (isset($res[$k])) {
					$result[] = $res[$k];
					unset($res[$k]);
				}

				if (isset($arr2[$p])) unset($arr2[$p]);
			}
		}

		foreach ($arr2 as $p => $b) {
			$result[] = $res[$b[0]];
		}

		echo CJSON::encode($result);
		Yii::app()->end();
	}

	public function actionDadata($term)
	{
		echo Yii::app()->dadata->address("https://dadata.ru/api/v2/suggest/address", ["query" => trim($term)]);
		Yii::app()->end();
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
		if (isset($_GET['status'])) {
//			$query .= $_GET['status'] == 'actual' ? ' and (t.status=' . DIRTRIP_EXTEND . ' and t.arrival >= "' . date('Y-m-d H:i:s') . '")' : ' and (t.status=' . DIRTRIP_CANCELED . ' or t.arrival < "' . date('Y-m-d H:i:s') . '")';
			$query .= $_GET['status'] == 'actual' ? ' and t.status=' . DIRTRIP_EXTEND : ' and t.status=' . DIRTRIP_CANCELED;
		}
		$query .= " order by t.departure desc";

		$tripsData = Yii::app()->db->createCommand($query)->queryAll();

		$arrData = [];

		foreach ($tripsData as $d) {
			if ($d['arrival'] < date('Y-m-d H:i:s') || $d['status'] == 0) {
				$d['status'] = 'Не актуален';
			} else $d['status'] = 'Актуален';
			$arrData[] = $d;
		}

		$dpTripsData = new CArrayDataProvider(
			$arrData,
			[
				'keyField' => 'id',
				'sort'     => [
					'attributes' => ['startPoint', 'endPoint', 'number', 'departure', 'arrival', 'status']
				]
			]
		);

		$this->render('admin', [
			'tripsData' => $dpTripsData,
		]);
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
	public
	function loadModel($id)
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
	protected
	function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'trips-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
