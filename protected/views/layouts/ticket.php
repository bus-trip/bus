<?php

$this->beginContent('//layouts/column1'); ?>
	<div class="tabs-navigation">
		<div class="tabs-menu">
			<ul class="clearfix">
				<li class="<?= Yii::app()->request->url == $this->createUrl('/Finances/finances/index') ? 'active' : '' ?>">
					<a href="<?= $this->createUrl('/Finances/finances/index') ?>"><?= Yii::t('lk_finances', 'History writedowns') ?></a>
				</li>
				<li class="<?= Yii::app()->request->url == $this->createUrl('/Finances/finances/payment') ? 'active' : '' ?>">
					<a href="<?= $this->createUrl('/Finances/finances/payment') ?>"><?= Yii::t('lk_finances', 'Payment history') ?></a>
				</li>
				<li class="<?= Yii::app()->request->url == $this->createUrl('/Finances/finances/docs') ? 'active' : '' ?>">
					<a href="<?= $this->createUrl('/Finances/finances/docs') ?>"><?= Yii::t('lk_finances', 'Documents') ?></a>
				</li>
			</ul>
		</div>
		<div class="tabs-content">
			<?= $content ?>
		</div>
	</div>
<?php $this->endContent();