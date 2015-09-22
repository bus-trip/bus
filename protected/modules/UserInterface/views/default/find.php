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
<?php echo $form->errorSummary($checkoutModel); ?>

<table style="border: 1px solid #ccc; padding: 10px; width: 400px;">
	<tr>
		<td>
			<div><?= $form->labelEx($checkoutModel, 'pointFrom') ?></div>
			<div><?= $form->dropDownList($checkoutModel, 'pointFrom', $points) ?></div>

		</td>
		<td>
			<div><?= $form->labelEx($checkoutModel, 'date') ?></div>

			<div><?= $form->error($checkoutModel, 'date') ?></div>
			<?php
			Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
			$this->widget('CJuiDateTimePicker', array(
				'model'     => $checkoutModel,
				'attribute' => 'date',
				'mode'      => 'date',
				'value'     => $checkoutModel->date,
				'options'   => array(
					'dateFormat'  => 'dd.mm.yy',
					'changeMonth' => true,
					'changeYear'  => true,
					'minDate'     => 0,
				),
				'language'  => 'ru',
			));
			?>
		</td>
	</tr>
	<tr>
		<td>
			<div><?= $form->labelEx($checkoutModel, 'pointTo') ?></div>
			<div><?= $form->dropDownList($checkoutModel, 'pointTo', $points) ?></div>
			<div><?= $form->error($checkoutModel, 'pointTo') ?></div>
		</td>
		<td>
			<?= CHtml::submitButton('Найти', ['id' => 'ajax-submit']) ?>
		</td>
	</tr>
</table>

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