<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:26
 */

$this->beginContent('//layouts/main'); ?>
	<div id="top">
		<div class="checkout-process">
			<ul class="clearfix">
				<?php $this->renderPartial('UserInterface.views.default.left', ['menu' => $this->getMenu()]) ?>
			</ul>
		</div>
		<!--side-menu end-->
	</div>
	<div id="content">
		<div class="wrapper">
			<!-- flashes -->
			<?php
			foreach (Yii::app()->user->getFlashes() as $key => $message) {
				echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
			}
			?>
			<?php if (isset($this->pageTitle) && $this->pageTitle) { ?>
				<div class="page-title">
					<?= isset($this->pageTitle) && $this->pageTitle ? '<h1>' . $this->pageTitle . '</h1>' : ''; ?>
				</div>
			<?php } ?>
			<?= $content ?>
		</div>
	</div>
<?php $this->endContent();
