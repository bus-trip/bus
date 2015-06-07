<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:23:26
 */

namespace UserInterface\controllers;

use CJSON;
use Profiles;
use UserInterface\components\Controller;
use Yii;

class ProfilesController extends Controller
{
	public function actionForm()
	{
		echo CJSON::encode(['form' => $this->renderPartial('item', [
			'profileModel' => new Profiles(),
			'i'            => Yii::app()
								 ->getRequest()
								 ->getQuery('i')], true)]);
		Yii::app()->end();
	}
}