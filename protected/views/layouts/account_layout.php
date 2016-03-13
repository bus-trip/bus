<?php
/**
 * Part of bus 2015
 * Created by: Александр on 14.04.2015:2:45
 *
 * @var        $this AccountController
 * @var string $content
 */

$this->beginContent('//layouts/column1');
$url = Yii::app()->request->url;
?>
	<div id="profile-wrapper">
		<ul class="nav">
			<?php foreach ([$this->createUrl('/account')            => 'Аккаунт',
							$this->createUrl('/account/edit')       => 'Редактировать',
							$this->createUrl('/account/passengers') => 'Мои профили',
							$this->createUrl('/account/tickets')    => 'Мои билеты'] as $path => $item) {
				print '<li><a href="' . $path . '" class="link' . ($path == $url ? ' link_active' : '') . '">' . $item . '</a></li>';
			} ?>
		</ul>
		<?= $content ?>
	</div>
<?php $this->endContent();