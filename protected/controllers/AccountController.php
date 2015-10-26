<?php

class AccountController extends Controller
{
	/**
	 * @var User
	 */
	public $user;
	public $breadcrumbs = ['Аккаунт' => ['/account']];

	public function init()
	{
		$user_id    = Yii::app()->user->id;
		$this->user = User::model()->findByPk($user_id);
		unset($this->user->pass);

		$this->layout = 'account_layout';
	}

	/**
	 * /account
	 *
	 * @throws CException
	 */
	public function actionProfile()
	{
		$this->pageTitle   = 'Личный кабинет';
		$this->breadcrumbs = ['Аккаунт'];

		$account = [
			'values' => $this->user->attributes,
			'labels' => $this->user->attributeLabels()
		];
		unset($account['values']['id'], $account['values']['pass']);
		$this->render('profile', ['account' => $account]);
	}

	/**
	 * Page edit account: /account/edit
	 */
	public function actionEdit()
	{
		$this->pageTitle     = 'Редактирование аккаунта';
		$this->breadcrumbs[] = 'Редактирование';

		if ($attributes = Yii::app()->getRequest()->getPost(CHtml::modelName($this->user))) {
			$this->user->setAttributes($attributes);
			if ($this->user->validate() && $this->user->save()) {
				Yii::app()->user->setFlash('success', "Аккаунт изменен");

				$url = $this->createUrl('/account');
				$this->redirect($url);
			}
		}

		$this->render('edit', ['model' => $this->user]);
	}

	/**
	 * My profiles: /account/passengers
	 *
	 * @throws CException
	 */
	public function actionPassengers()
	{
		$this->pageTitle     = 'Мои профили';
		$this->breadcrumbs[] = 'Мои профили';
		$profiles            = [];
		$userProfilesModels  = Profiles::model()
									   ->findAllByAttributes(['uid' => Yii::app()->getUser()->id],
															 ['order' => 'created DESC']);
		foreach ($userProfilesModels as $p) {
			$key            = md5($p->doc_type . '::' . $p->doc_num . '::' . $p->last_name . '::' . $p->black_list);
			$profiles[$key] = $p;
		}

		$this->render('passengers', ['profiles' => $profiles]);
	}

	/**
	 * Page add profile: /account/passengers/add
	 *
	 * @throws CException
	 */
	public function actionPassengersAdd()
	{
		$this->pageTitle                  = 'Создание нового профиля';
		$this->breadcrumbs['Мои профили'] = ['/account/passengers'];
		$this->breadcrumbs[]              = 'Добавление профиля';

		$model = new Profiles();
		if ($attributes = Yii::app()->getRequest()->getPost(CHtml::modelName($model))) {
			$model->setAttributes($attributes);
			$model->uid = $this->user->id;
			if ($model->validate() && $model->save()) {
				Yii::app()->user->setFlash('success', "Профиль &laquo;" . $model->shortName() . "&raquo; создан");

				$url = $this->createUrl('/account/passengers');
				$this->redirect($url);
			}
		}

		$this->render('one_passenger', ['model' => $model]);
	}

	/**
	 * @param $id
	 *
	 * @throws CDbException
	 * @throws CHttpException
	 */
	public function actionPassengersDelete($id)
	{
		$model = $this->loadProfile($id);
		if ($model && $model->uid == $this->user->id) {
			$model->delete();

			Yii::app()->user->setFlash('success', "Профиль &laquo;" . $model->shortName() . "&raquo; удален");
			$url = $this->createUrl('/account/passengers');
			$this->redirect($url);
		}
		throw new CHttpException(404, "Страница не найдена");
	}

	/**
	 * Edit profile page: /account/passengers/edit/<id>
	 *
	 * @param $id
	 *
	 * @return string
	 * @throws CException
	 * @throws CHttpException
	 */
	public function actionPassengersEdit($id)
	{
		$this->pageTitle                  = 'Редактирование профиля';
		$this->breadcrumbs['Мои профили'] = ['/account/passengers'];
		$this->breadcrumbs[]              = 'Редактирование профиля';

		$model = $this->loadProfile($id);
//		$model->birth = date('d.m.Y', $model->birth);
		if ($model && $model->uid == $this->user->id &&
			$attributes = Yii::app()->getRequest()->getPost(CHtml::modelName($model))
		) {
			$model->setAttributes($attributes);
			if ($model->save()) {
				Yii::app()->user->setFlash('success', "Профиль &laquo;" . $model->shortName() . "&raquo; обновлен");
				$url = $this->createUrl('/account/passengers');
				$this->redirect($url);
			}
		}
		$this->render('one_passenger', ['model' => $model]);
	}

	public function actionTickets()
	{
		$this->pageTitle     = 'Мои билеты';
		$this->breadcrumbs[] = 'Мои билеты';

		$criteria            = new CDbCriteria;
		$criteria->condition = 'uid=:uid AND t.tid IS NOT NULL';
		$criteria->params    = [':uid' => Yii::app()->getUser()->id];
		$data                = Profiles::model()->with('tickets')->findAll($criteria);

		$arrData = [];
		foreach ($data as $d) {
			if (!isset($trip[$d->tickets->idTrip]))
				$trip[$d->tickets->idTrip] = Trips::model()->with('idDirection0')->findAllByPk($d->tickets->idTrip);

			$statuses  = $d->tickets->getStatuses();
			$arrData[] = [
				'id'           => $d->tid,
				'name'         => $d->last_name . ' ' . $d->name,
				'place'        => $d->tickets->place,
				'price'        => $d->tickets->price,
				'status'       => $statuses[$d->tickets->status],
				'address_from' => $d->tickets->address_from,
				'address_to'   => $d->tickets->address_to,
				'departure'    => $trip[$d->tickets->idTrip][0]->departure,
				'arrival'      => $trip[$d->tickets->idTrip][0]->arrival,
				'startPoint'   => $trip[$d->tickets->idTrip][0]->idDirection0->startPoint,
				'endPoint'     => $trip[$d->tickets->idTrip][0]->idDirection0->endPoint,
				'profileId'    => $d->id
			];
		}

		$modelData = new CArrayDataProvider(
			$arrData,
			[
				'keyField'   => 'id',
				'sort'       => [
					'attributes' => [
						'name',
						'place',
						'price',
						'status',
						'departure',
						'arrival'
					]
				],
				'pagination' => [
					'pageSize' => 20,
				],
			]
		);

		$this->render('tickets', [
			'modelData' => $modelData,
		]);
	}

	/**
	 * @param $id
	 *
	 * @return Profiles the loaded model
	 * @throws CHttpException
	 */
	protected function loadProfile($id)
	{
		$model = Profiles::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'Страница не найдена');

		return $model;
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return [
			'accessControl', // perform access control for CRUD operations
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
			['allow', // allow authenticated users to access all actions
			 'users' => ['@'],
			],
			['deny', // deny all users
			 'users' => ['*'],
			],
		];
	}
}