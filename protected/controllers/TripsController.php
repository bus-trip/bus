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
            //			'postOnly + delete', // we only allow deletion via POST request
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
                'actions' => array('index', 'view', 'deleteticket'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'sheet', 'sheetprint', 'profiles', 'createticket', 'deleteticket', 'inline', 'sprofiles'),
                'users' => array('admin'),
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
            $model->description = isset($_POST['Trips']['description']) ? $_POST['Trips']['description'] : '';
            if ($model->arrival > $model->departure && $model->departure > date('Y-m-d H:i:s')) {
                if ($model->save()) {
                    // Создаём запись в таблице расписаний (для начального и конечного пункта)
                    $schData = array(
                        'idTrip' => $model->id,
                        'idDirection' => $_POST['Trips']['idDirection'],
                        'departure' => $_POST['Trips']['departure'],
                        'arrival' => $_POST['Trips']['arrival'],
                    );
                    $schedule = new Schedule;
                    $schedule->attributes = $schData;
                    $schedule->save();

                    $this->redirect(array('admin', 'id' => $model->id));
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
//		elseif (isset($_REQUEST['trips-date'])) {
//			$model->departure = $_REQUEST['trips-date'];
//		}
//		if (isset($_REQUEST['trips-arrive'])) {
//			$model->arrival = $_REQUEST['trips-arrive'];
//		}

        $model->departure = Yii::app()->user->getState('trips-date');
        $model->arrival = Yii::app()->user->getState('trips-arrive');
        $model->idDirection = Yii::app()->user->getState('trips-dir-id');

        $data = Directions::model()->findAllByPk($model->idDirection);
        $directions = array();
//		$directions['empty'] = 'Выберите направление';
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
            'model' => $model,
            'directions' => $directions,
            'buses' => $buses,
            'actual' => 1,
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
            if ($model->arrival > $model->departure) {
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
            } else {
                $model->addError('arrival', 'Время прибытия не должно быть равно или меньше времени отправления');
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
            'model' => $model,
            'directions' => $directions,
            'buses' => $buses,
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
            $this->redirect(array('create',));
        }

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
        $criteria->condition = 't.idTrip=' . $id;
        $criteria->addNotInCondition('t.status', array(TICKET_CANCELED));
        $criteria->join = 'left join trips as tr on tr.id = t.idTrip';
        $data = Tickets::model()->findAll($criteria);
        $tickets = array();
        foreach ($data as $d) {
            $tickets[] = $d->attributes;
        }

        $arrPlaces = array();
        for ($i = 1; $i <= $bus["places"]; $i++) {
            $arrPlaces[$i] = array(
				'profile_id' => '',
				'place' => $i,
				'passport' => '',
				'last_name' => '',
				'name' => '',
				'middle_name' => '',
				'birthday' => '',
				'phone' => '',
				'startPoint' => '',
				'endPoint' => '',
				'price' => '',
				'remark' => '',
				'status' => '',
				'black_list' => '',
				'ticket_id' => '',
            );
        }
        $criteria = new CDbCriteria();
        $criteria->condition = 'tid=:tid';
        for ($i = 1; $i <= $bus['places']; $i++) {
            foreach ($tickets as $t) {
                if ($t["place"] == $i) {
                    $criteria->params = array(':tid' => $t["id"]);
                    $profile = Profiles::model()->find($criteria);
                    if (!$profile) $profile = new Profiles();
                    $arrPlaces[$i] = array(
                        'profile_id' => $profile->id,
                        'place' => $i,
                        'passport' => $profile->passport,
                        'last_name' => $profile->last_name,
                        'name' => $profile->name,
                        'middle_name' => $profile->middle_name,
                        'birthday' => $profile->birth,
                        'phone' => $profile->phone,
                        'startPoint' => $t["address_from"],
                        'endPoint' => $t["address_to"],
                        'price' => $t["price"],
						'remark' => $t["remark"],
                        'status' => $t["status"],
                        'black_list' => $profile->black_list ? '!' : '',
                        'ticket_id' => $profile->tid,
                    );
                }
            }
        }

        $dataProvider = new CArrayDataProvider(
            $arrPlaces,
            array(
                'keyField' => 'place',
                'pagination' => array(
                    'pageSize' => $bus["places"],
                )
            )
        );

        $this->render(
            'sheet',
            array(
                'dataProvider' => $dataProvider,
                'dataHeader' => array(
                    'bus' => $bus,
                    'direction' => $direction,
                    'trips' => $this->loadModel($id)->attributes,
                )
            )
        );
    }

    public function actionDeleteticket($id)
    {
        $Ticket = Tickets::model()->findByPk($id);
//        $Profile = Profiles::model()->findByAttributes(array('tid' => $id));
//        $Profile->delete();

        $Ticket->status = 0;
        $Ticket->save();
        $this->redirect(array('trips/sheet/' . $Ticket->idTrip));
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
        $pdf->SetFont("dejavuserif", "", 8);

        $tbl = '<h2>Рейс ' . $direction['startPoint'] . ' - ' . $direction['endPoint'] . '</h2><br/>';
        $tbl .= 'Отправление: ' . $trips['departure'] . '&nbsp;&nbsp;&nbsp;&nbsp;';
        $tbl .= 'Прибытие: ' . $trips['arrival'] . '<br/>';
        $tbl .= 'Автобус: ' . $bus['model'] . ', номер ' . $bus['number'] . '<br/><br/>';
        $tbl .= '<table width="600px" style="border:1px solid #000000; padding: 8px;">
                    <tbody>
                    <tr bgcolor="#cccccc">
                        <th width="30px" style="border-bottom: 1px solid #000000;"><strong>№</strong></th>
                        <th style="border-bottom: 1px solid #000000;"><strong>ФИО</strong></th>
                        <th style="border-bottom: 1px solid #000000;"><strong>Посадка</strong></th>
                        <th style="border-bottom: 1px solid #000000;"><strong>Высадка</strong></th>
                        <th style="border-bottom: 1px solid #000000;"><strong>Номер телефона</strong></th>
                        <th width="80px" style="border-bottom: 1px solid #000000;"><strong>Стоимость</strong></th>
                    </tr>';
        $criteria = new CDbCriteria();
        $criteria->condition = 'tid=:tid';
        for ($i = 1; $i <= $bus['places']; $i++) {
            $flag = 0;
            foreach ($tickets as $t) {
                if ($t["place"] == $i) {
                    $criteria->params = array(':tid' => $t["id"]);
                    $profile = Profiles::model()->find($criteria);
                    $tbl .= '<tr>';
                    $tbl .= '<td width="30px" style="border-bottom: 1px solid #000000;">' . $i . '</td>';
                    $tbl .= '<td style="border-bottom: 1px solid #000000;">' . $profile->attributes["last_name"] . ' ' . $profile->attributes["name"] . ' ' . $profile->attributes["middle_name"] . '</td>';
                    $tbl .= '<td style="border-bottom: 1px solid #000000;">' . $t["address_from"] . '</td>';
                    $tbl .= '<td style="border-bottom: 1px solid #000000;">' . $t["address_to"] . '</td>';
                    $tbl .= '<td style="border-bottom: 1px solid #000000;">' . $profile->phone . '</td>';
                    $tbl .= '<td style="border-bottom: 1px solid #000000;">' . $t["price"] . '</td>';
                    $tbl .= '</tr>';
                    $flag = 1;
                }
            }
            if (!$flag) {
                $tbl .= '<tr>';
                $tbl .= '<td width="30px" style="border-bottom: 1px solid #000000;">' . $i . '</td>';
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
        $criteria = new CDbCriteria();
        $criteria->condition = 'idTrip=:idTrip AND place=:place';
        $criteria->params = array(':idTrip' => $tripId, ':place' => $placeId);
        $criteria->addNotInCondition('t.status', array(TICKET_CANCELED));
        $Ticket = Tickets::model()->findAll($criteria);

        if (!empty($Ticket)) {
            $Ticket = $Ticket[count($Ticket) - 1]; // последний созданный профайл
            // билет создан, страница редактирования
            $Profile = Profiles::model()->findByAttributes(array('tid' => $Ticket->id));
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

            $this->render('ticket', array(
                'tripId' => $tripId,
                'placeId' => $placeId,
                'profile' => $Profile ? $Profile : new Profiles(),
                'model' => $Ticket
            ));
        } else {
            // создаем билет
            $model = new Profiles('search');
            $model->unsetAttributes(); // clear any default values
            if (isset($_GET['Profiles']))
                $model->attributes = $_GET['Profiles'];

            $this->render('profiles', array(
                'tripId' => $tripId,
                'placeId' => $placeId,
                'model' => $model,
            ));
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

        $this->render('ticket', array(
            'tripId' => $tripId,
            'placeId' => $placeId,
            'profile' => $Profile,
            'model' => $Ticket
        ));
    }

    /**
     * Проверка связности участков рейса
     */
    public function actionCheck()
    {
        $node = array(
            'id' => '',
            'sP' => '',
            'eP' => '',
            'next' => array(),
        );
    }

    function actionInline()
    {
        if (empty($_POST['tripId']) || empty($_POST['placeId'])) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        list($discount) = Yii::app()->createController('discounts');

        $tripId = $_POST['tripId'];
        $placeId = $_POST['placeId'];

        $criteria = new CDbCriteria();
        $criteria->condition = 'idTrip=:idTrip AND place=:place';
        $criteria->params = array(':idTrip' => $tripId, ':place' => $placeId);
        $criteria->addNotInCondition('t.status', array(TICKET_CANCELED));
        $Tickets = Tickets::model()->with('profiles')->with(array('idTrip0', 'idDirection0' => 'idTrip0'))->findAll($criteria);
        $Ticket = !empty($Tickets) ? $Tickets[count($Tickets) - 1] : new Tickets();
        $errors = array();
        if (!empty($_POST['data'])) { // обновляем/создаем профиль и билет
            if (!empty($Ticket->profiles)) {
                $Profile = $Ticket->profiles[count($Ticket->profiles) - 1];
            } else {
                $Profile = new Profiles();
            }

            $Profile->passport = $_POST['data']['Profiles[passport'];
            $Profile->last_name = $_POST['data']['Profiles[last_name'];
            $Profile->name = $_POST['data']['Profiles[name'];
            $Profile->middle_name = $_POST['data']['Profiles[middle_name'];
            $Profile->phone = $_POST['data']['Profiles[phone'];
            $Profile->birth = $_POST['data']['Profiles[birth'];
            if ($Profile->validate()) {

                $Ticket->idTrip = $tripId;
                $Ticket->place = $placeId;
                $Ticket->address_from = $_POST['data']['Tickets[address_from'];
                $Ticket->address_to = $_POST['data']['Tickets[address_to'];
				$Ticket->remark = $_POST['data']['Tickets[remark'];
                $Ticket->price = $_POST['data']['Tickets[price'];
                if (empty($Ticket->status)) {
                    $Ticket->status = TICKET_RESERVED;
                }

                if ($Ticket->validate() && $Ticket->save()) {
                    $Profile->tid = $Ticket->id;
                    $Profile->save();
					if($Ticket->idTrip0->idDirection0->price == $Ticket->price)
						$Ticket->price = $discount->getDiscount($Profile->id);
                    $Ticket->save();
                } else {
                    $errors = $Ticket->getErrors();
                }
            } else {
                $errors = $Profile->getErrors();
            }
        }
        $default_price = '';
        if (!$Ticket->price) {
            $criteria1 = new CDbCriteria();
            $criteria1->join = 'left join trips as tr on t.id=tr.idDirection';
            $criteria1->condition = 'tr.id=' . $tripId;
            $criteria1->addCondition('t.parentId=0');
            $Direction = Directions::model()->find($criteria1);
            $Ticket->price = $Direction->price;
            $default_price = '';
        } else {
            $default_price = $Ticket->price;
        }

        $inputs = array(
            '<input class="autocomplete" type="text" name="Profiles[passport]" maxlength="10" size="10" value="' . (!empty($Ticket->profiles) ? $Ticket->profiles[count($Ticket->profiles) - 1]->passport : '') . '" />',
            '<input class="autocomplete" type="text" name="Profiles[last_name]" size="10" value="' . (!empty($Ticket->profiles) ? $Ticket->profiles[count($Ticket->profiles) - 1]->last_name : '') . '" />',
            '<input class="autocomplete" type="text" name="Profiles[name]" size="10" value="' . (!empty($Ticket->profiles) ? $Ticket->profiles[count($Ticket->profiles) - 1]->name : '') . '" />',
            '<input class="autocomplete" type="text" name="Profiles[middle_name]" size="10" value="' . (!empty($Ticket->profiles) ? $Ticket->profiles[count($Ticket->profiles) - 1]->middle_name : '') . '" />',
            '<input class="autocomplete" type="text" name="Profiles[phone]" size="12" value="' . (!empty($Ticket->profiles) ? $Ticket->profiles[count($Ticket->profiles) - 1]->phone : '') . '" />',
            '<input type="text" name="Profiles[birth]" size="10" value="' . (!empty($Ticket->profiles) ? $Ticket->profiles[count($Ticket->profiles) - 1]->birth : '') . '" />',
            '<textarea name="Tickets[address_from]">' . $Ticket->address_from . '</textarea>',
            '<textarea name="Tickets[address_to]">' . $Ticket->address_to . '</textarea>',
			'<textarea name="Tickets[remark]">'. $Ticket->remark . '</textarea>',
            '<input type="text" name="Tickets[price]" value="' . $Ticket->price . '" />',
        );

        $inline = array(
            (string)(!empty($Ticket->profiles) ? CHtml::link($Ticket->profiles[count($Ticket->profiles) - 1]->passport, array("tickets/profile/" . $Ticket->profiles[count($Ticket->profiles) - 1]->id)) : ''),
            (string)(!empty($Ticket->profiles) ? $Ticket->profiles[count($Ticket->profiles) - 1]->last_name : ''),
            (string)(!empty($Ticket->profiles) ? $Ticket->profiles[count($Ticket->profiles) - 1]->name : ''),
            (string)(!empty($Ticket->profiles) ? $Ticket->profiles[count($Ticket->profiles) - 1]->middle_name : ''),
            (string)(!empty($Ticket->profiles) ? $Ticket->profiles[count($Ticket->profiles) - 1]->phone : ''),
            (string)(!empty($Ticket->profiles) ? $Ticket->profiles[count($Ticket->profiles) - 1]->birth : ''),
            (string)$Ticket->address_from,
            (string)$Ticket->address_to,
			(string)$Ticket->remark,
            (string)$default_price
        );

        $this->layout = FALSE;
        header('Content-type: application/json');
        echo CJavaScript::jsonEncode(array('inputs' => $inputs, 'inline' => $inline, 'errors' => $errors));
        Yii::app()->end();
    }


    public function actionSProfiles($term)
    {
        $res = array();
        if (isset($_GET['term']) && isset($_GET['field'])) {
            $field = preg_replace('#Profiles\[([^\]]*)\]#', '$1', $_GET['field']);
            $criteria = new CDbCriteria();
            $criteria->addCondition($field . ' LIKE :field');
            $criteria->params = array(
                ':field' => '%' . trim($_GET['term']) . '%',
            );
            $criteria->group = 'passport, last_name';

            $SameProfiles = Profiles::model()->findAll($criteria);
            foreach ($SameProfiles as $Profile) {
                $res[] = array(
                    'value' => $Profile->$field,
                    'info' => '(' . $Profile->shortName() . '; ' . $Profile->passport . ')',
                    'data' => array(
                        'Profiles[passport]' => $Profile->passport,
                        'Profiles[last_name]' => $Profile->last_name,
                        'Profiles[name]' => $Profile->name,
                        'Profiles[middle_name]' => $Profile->middle_name,
                        'Profiles[phone]' => $Profile->phone,
                    ),
                );
            }
        }
        echo CJSON::encode($res);
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
        if (isset($_GET['status']))
            $query .= $_GET['status'] == 'actual' ? ' and (t.status=1 and t.arrival >= "' . date('Y-m-d H:i:s') . '")' : ' and (t.status=0 or t.arrival < "' . date('Y-m-d H:i:s') . '")';
        $query .= " order by t.departure desc";

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
                'sort' => array(
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
