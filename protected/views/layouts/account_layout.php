<?php
/**
 * Part of bus 2015
 * Created by: Александр on 14.04.2015:2:45
 *
 * @var        $this AccountController
 * @var string $content
 */

$this->beginContent('//layouts/column1_without_wrap');
$url = Yii::app()->request->url;
?>
	<div class="search-form__nav">
		<div class="form-nav">
			<?php foreach ([$this->createUrl('/account')            => 'Аккаунт',
							$this->createUrl('/account/edit')       => 'Редактировать',
							$this->createUrl('/account/passengers') => 'Мои профили',
							$this->createUrl('/account/tickets')    => 'Мои билеты'] as $path => $item) {
				print '<a href="' . $path . '" class="form-nav__item' . ($path === $url ? ' form-nav__item_active' : '') . '">' .
					'<span class="form-nav__name">' . $item . '</span></a>';
			} ?>
		</div>
	</div>
	<div class="search-form__container">
		<div class="seats-form">
			<?= $content ?>
		</div>
	</div>
<?php $this->endContent();