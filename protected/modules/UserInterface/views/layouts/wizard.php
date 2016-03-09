<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:26
 */

$this->beginContent('//layouts/main'); ?>
	<ul class="nav">
		<?php $this->renderPartial('UserInterface.views.default.left', ['menu' => $this->getMenu()]) ?>
	</ul>
	<div id="content">
		<div class="wrapper">
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
