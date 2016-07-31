<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:26
 */

$this->beginContent('//layouts/main'); ?>
	<div class="search-form">
		<div class="search-ticket__form">
			<?= isset($this->pageTitle) && $this->pageTitle ? '<h1 class="search-form__title title">' . $this->pageTitle . '</h1>' : ''; ?>
			<div class="search-form__nav">
				<div class="form-nav">
					<?php $this->renderPartial('UserInterface.views.default.left', ['menu' => $this->getMenu()]) ?>
				</div>
			</div>
			<!-- flashes -->
			<?php
			foreach (Yii::app()->user->getFlashes() as $key => $message) {
				echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
			}
			?>
			<?= $content ?>
		</div>
	</div>
<?php $this->endContent();
