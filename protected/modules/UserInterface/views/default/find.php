<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:35
 *
 * @var $this \UserInterface\controllers\DefaultController
 * @var $checkoutModel \UserInterface\models\Checkout
 * @var $points []
 * @var $form CActiveForm
 */

$form = $this->beginWidget('CActiveForm', [
	'id'                   => 'searchtrip-form',
	'enableAjaxValidation' => false,
]); ?>

<div class="grid grid_jc-c">
	<?php echo $form->errorSummary($checkoutModel); ?>
	<?= $form->error($checkoutModel, 'pointFrom') ?>
	<?= $form->error($checkoutModel, 'pointTo') ?>
	<div class="grid__item grid__item_xs-12 grid__item_s-3 grid__item_l-auto">
		<?= $form->dropDownList($checkoutModel, 'pointFrom', $points, ['class' => "select", 'data-placeholder' => "Откуда"]) ?>
	</div>
	<div class="grid__item grid__item_xs-12 grid__item_s-3 grid__item_l-auto">
		<?= $form->dropDownList($checkoutModel, 'pointTo', $points, ['class' => "select", 'data-placeholder' => "Куда"]) ?>
	</div>
	<div class="grid__item grid__item_xs-12 grid__item_s-3 grid__item_l-auto">
		<?php
		Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
		$this->widget('CJuiDateTimePicker', [
			'model'       => $checkoutModel,
			'attribute'   => 'date',
			'mode'        => 'date',
			'value'       => $checkoutModel->date,
			'options'     => [
				'dateFormat'  => 'dd.mm.yy',
				'changeMonth' => true,
				'changeYear'  => true,
				'minDate'     => 0,
			],
			'language'    => 'ru',
			'htmlOptions' => [
				'class' => 'input-text'
			]
		]);
		?>
		<?= $form->error($checkoutModel, 'date') ?>
	</div>
	<div class="grid__item grid__item_xs-12 grid__item_s-auto grid__item_l-auto">
		<?= CHtml::submitButton('Найти', ['id' => 'ajax-submit', 'class' => "btn"]) ?>
	</div>
</div>

<div id="trip-id"></div>

<?php $this->endWidget(); ?>

<script type="application/javascript">
	$(function () {
		$('#searchtrip-form #ajax-submit').on('click', function (e) {
			e.preventDefault();

			var form = $(this).parents('form'),
				data = form.serialize();

			$('#trip-id').html('');
			form.find('.el-error').removeClass('el-error');
			form.find('.war-error').remove();

			$.ajax({
				type: "POST",
				url: "<?=$this->createUrl('/UserInterface/default/search') ?>",
				data: data
			}).done(function (data) {
				console.log(data);

				if (data.success !== undefined) {
					$('#trip-id').html(data.success);
				} else {
					$.each(data.errors, function (id, value) {
						var el = $("[name='<?=CHtml::modelName($checkoutModel) ?>[" + id + "]']");
						el.addClass('el-error');
						el.after('<div class="war-error">' + value + '</div>');
					});
				}

			});

		})
	});
</script>