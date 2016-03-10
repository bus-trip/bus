<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:26
 */

$this->beginContent('//layouts/main'); ?>
	<div class="search-ticket">
		<?= isset($this->pageTitle) && $this->pageTitle ? '<h1 class="title title_c-white title_ta-c">' . $this->pageTitle . '</h1>' : ''; ?>
		<div class="search-ticket__form">
			<ul class="nav">
				<?php $this->renderPartial('UserInterface.views.default.left', ['menu' => $this->getMenu()]) ?>
			</ul>
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
