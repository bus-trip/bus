<?php
/**
 * Part of bus 2015
 * Created by: Alexander Sumarokov on 10.10.2015:13:47
 *
 * @var $this \UserInterface\controllers\DefaultController
 * @var $checkoutModel \UserInterface\models\Checkout
 */

Yii::app()->clientScript
	->registerScriptFile(Yii::app()->baseUrl . '/js/checkout.js', CClientScript::POS_END);

?>

<?php
/** @var $form CActiveForm */
$form = $this->beginWidget('CActiveForm'); ?>
	<div class="search-form__container">
		<?php echo $form->errorSummary($checkoutModel); ?>
		<div class="seats">
			<div class="seats__scheme">
				<p class="title-2 clearfix">Схема мест в автобусе</p>
				<?= ($checkoutModel->plane) ? CHtml::image(Yii::app()->baseUrl . "/" . Buses::UPLOAD_DIR . "/" . $checkoutModel->plane, "") : ""; ?>
			</div>
			<ul class="seats__list">

				<?php
				print $form->checkBoxList($checkoutModel, 'places', $places, [
					'class'     => '',
					'separator' => '',
					'template'  => '<li class="seats__choose">{input} {label}</li>',
				]);
				?>
			</ul>
		</div>
	</div>
	<div class="search-form__footer">
		<a class="search-form__prev-step btn btn_border" href="<?= $back ?>">Назад</a>
		<?php echo CHtml::submitButton('Продолжить', ['class' => 'search-form__next-step btn']); ?>
	</div>
<?= CHtml::activeHiddenField($checkoutModel, 'placesStep') ?>

<?php $this->endWidget();
