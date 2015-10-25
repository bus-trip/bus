<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:22
 *
 * @var WizardEvent $event
 */
?>
<div id="wizard-wrapper" class="clearfix">
	<?php $this->renderPartial($checkoutModel->scenario, compact('checkoutModel', 'profileModels', 'userProfiles', 'saved', 'trip', 'points', 'selPoints', 'places', 'prices')); ?>

	<?php if ($checkoutModel->scenario != 'find') { ?>
		<div class="back">
			<a href="<?= $back ?>" class="btn-back" title="Назад">Назад</a>
		</div>
	<?php } ?>
</div>