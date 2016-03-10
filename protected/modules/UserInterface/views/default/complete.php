<?php
/**
 * Part of bus 2015
 * Created by: Александр on 07.06.2015:21:34
 *
 * @var \UserInterface\controllers\DefaultController $this
 */

$this->pageTitle = 'Оформление закончено';
?>
<div id="checkout-complete-wrapper">
	<p>Вы можете распечатать билеты из <a href="<?= $this->createUrl('/account/tickets') ?>">личного кабинета</a>.</p>
</div>