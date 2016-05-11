<?php
/**
 * Part of bus 2016
 * Created by: Alexander Sumarokov on 12.05.2016:1:04
 */
?>Здравствуйте!<br><br>
Вы зарегистрированы на портале <?= CHtml::encode(Yii::app()->name) ?> с логином <?= $user ?>.<br>
Вами были оформлены билеты:<br><br>
<?php foreach ($links as $link) { ?>
<a href="<?= $link ?>"><?= $link ?></a><br>
<?php }
