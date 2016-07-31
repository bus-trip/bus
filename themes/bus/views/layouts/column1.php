<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
	<div class="search-form">
		<?= isset($this->pageTitle) && $this->pageTitle ? '<h1 class="search-form__title title">' . $this->pageTitle . '</h1>' : ''; ?>
		<div class="search-ticket__form">
			<div class="wrap">
				<?php if (Yii::app()->user->hasFlash('success')) { ?>
					<div class="wrapper">
						<div class="flash-success"><?php echo Yii::app()->user->getFlash('success'); ?></div>
					</div>
					<?php
				}
				if (Yii::app()->user->hasFlash('error')) {
					?>
					<div class="wrapper">
						<div class="flash-error"><?php echo Yii::app()->user->getFlash('error'); ?></div>
					</div>
					<?php
				}
				if (Yii::app()->user->hasFlash('notice')) {
					?>
					<div class="wrapper">
						<div class="flash-notice"><?php echo Yii::app()->user->getFlash('notice'); ?></div>
					</div>
				<?php } ?>
				<?php echo $content; ?>
			</div>
		</div>
	</div>
<?php $this->endContent(); ?>