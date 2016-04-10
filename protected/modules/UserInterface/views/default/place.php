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

<div class="grid grid_jc-c">
	<?php echo $form->errorSummary($checkoutModel); ?>
	<div class="grid__item grid__item_xs-12 grid__item_s-12 grid__item_m-auto">
		<div class="img-scheme">
			<?= ($checkoutModel->plane) ? CHtml::image(Yii::app()->baseUrl . "/" . Buses::UPLOAD_DIR . "/" . $checkoutModel->plane, "") : ""; ?>
		</div>
	</div>
	<?php $places = array_chunk($places, 8, true); ?>
	<div class="grid__item grid__item_xs-12 grid__item_s-auto">
		<?
		$list1 = $form->checkBoxList($checkoutModel, 'places', $places[0], [
			'class'     => 'input-checkbox',
			'separator' => '',
			'template'  => '<div class="input-container">{input} {label}</div>',
		]);

		print preg_replace('#(id="[^"]*)"([^>]*)(value=")([^"]*)("[^<]*<label for=")([^"]*)"#iUs', '$1_$4"$2$3$4$5$6_$4"', $list1);

		?>
	</div>
	<?php if (isset($places[1])) { ?>
		<div class="grid__item grid__item_xs-12 grid__item_s-auto">
			<?= $form->checkBoxList($checkoutModel, 'places', $places[1], [
				'class'        => 'input-checkbox',
				'separator'    => '',
				'uncheckValue' => null,
				'template'     => '<div class="input-container">{input} {label}</div>',
			]) ?>
		</div>
	<?php } ?>
</div>


<?= CHtml::activeHiddenField($checkoutModel, 'placesStep') ?>

<div class="grid grid_jc-sb">
	<div class="grid__item grid__item_xs-auto">
		<a href="<?= $back ?>" class="btn btn_br-blue" title="Назад">Назад</a>
	</div>
	<div class="grid__item grid__item_xs-auto">
		<?php echo CHtml::submitButton('Продолжить', ['class' => 'btn']); ?>
	</div>
</div>
<?php $this->endWidget(); ?>



