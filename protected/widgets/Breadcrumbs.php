<?php

Yii::import('zii.widgets.CBreadcrumbs');

class Breadcrumbs extends CBreadcrumbs
{
	public function init()
	{
		$this->homeLink = CHtml::link('Главная', '/');
	}
}