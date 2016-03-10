<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
	<div class="search-ticket">
		<?= isset($this->pageTitle) && $this->pageTitle ? '<h1 class="title title_c-white title_ta-c">' . $this->pageTitle . '</h1>' : ''; ?>
		<div class="search-ticket__form">
			<?php echo $content; ?>
		</div>
	</div>
<?php $this->endContent(); ?>