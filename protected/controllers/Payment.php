<?php

/**
 * Part of bus 2016
 * Created by: Alexander Sumarokov on 12.04.2016:2:10
 */
class Payment extends Controller
{
	public function actionResult() {
		$rc = Yii::app()->robokassa;

		// Коллбэк для события "оплата произведена"
		$rc->onSuccess = function($event){
			$transaction = Yii::app()->db->beginTransaction();
			// Отмечаем время оплаты счета
			$InvId = Yii::app()->request->getParam('InvId');
			$invoice = Invoice::model()->findByPk($InvId);
			$invoice->paid_at = new CDbExpression('NOW()');
			if (!$invoice->save()) {
				$transaction->rollback();
				throw new CException("Unable to mark Invoice #$InvId as paid.\n"
									 . CJSON::encode($invoice->getErrors()));
			}
			$transaction->commit();
		};

		// Коллбэк для события "отказ от оплаты"
		$rc->onFail = function($event){
			// Например, удаляем счет из базы
			$InvId = Yii::app()->request->getParam('InvId');
			Invoice::model()->findByPk($InvId)->delete();
		};

		// Обработка ответа робокассы
		$rc->result();
	}

	/*
		Сюда из робокассы редиректится пользователь 
		в случае отказа от оплаты счета.
	*/
	public function actionFailure() {
		Yii::app()->user->setFlash('global', 'Отказ от оплаты. Если вы столкнулись 
            с трудностями при внесении средств на счет, свяжитесь 
            с нашей технической поддержкой.');

		$this->redirect(['index']);
	}

	/*
		Сюда из робокассы редиректится пользователь в случае успешного проведения 
		платежа. Обратите внимание, что на этот момент робокасса возможно еще 
		не обратилась к методу actionResult() и нам неизвестно, поступили средства 
		на счет или нет.
	*/
	public function actionSuccess() {
		$InvId = Yii::app()->request->getParam('InvId');
		$invoice = Invoice::model()->findByPk($InvId);
		if ($invoice) {
			if ($invoice->paid_at) {
				// Если робокасса уже сообщила ранее, что платеж успешно принят
				Yii::app()->user->setFlash('global',
										   'Средства зачислены на ваш личный счет. Спасибо.');
			} else {
				// Если робокасса еще не отзвонилась
				Yii::app()->user->setFlash('global', 'Ваш платеж принят. Средства 
                    будут зачислены на ваш личный счет в течение нескольких минут. 
                    Спасибо.');
			}
		}

		$this->redirect(['index']);
	}
}