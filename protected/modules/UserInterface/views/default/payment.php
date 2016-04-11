<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:35
 */

/**
 * @var Invoice $invoice
 */

?>

<p style="text-align: left; margin-bottom: 20px;"><b>Перед переходом к терминалу оплаты проверьте правильность
		данных: </b></p>

<p style="text-align: left; margin-bottom: 20px;">
	Описание: <b><?= $invoice->description ?></b><br>
	Сумма: <b><?= $invoice->amount ?> руб.</b></p>

<?php
/** @var $form CActiveForm */
$form = $this->beginWidget('CActiveForm'); ?>

<?= CHtml::activeHiddenField($invoice, 'amount') ?>
<?= CHtml::activeHiddenField($invoice, 'user_id') ?>

<div class="grid grid_jc-sb">
	<div class="grid__item grid__item_xs-auto">
		<a href="<?= $back ?>" class="btn btn_br-blue" title="Назад">Назад</a>
	</div>
	<div class="grid__item grid__item_xs-auto">
		<?php echo CHtml::submitButton('Перейти к оплате', ['class' => 'btn']); ?>
	</div>
</div>
<?php $this->endWidget(); ?>