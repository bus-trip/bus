<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 24.08.2015
 * Time: 17:12
 */

?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ticket.css"/>

<div class="bt_div">
	<div>
		<div class="orgLogo">
			Спринт - Тур
		</div>
		<div class="orgInfo">
			<?php echo $organization['info']; ?>
		</div>
	</div>
	<hr/>
	<div class="orgContacts">
		<?php echo $organization['contacts']; ?>
	</div>
	<hr/>
	<div class="ticketId">
		<div>
			Билет №<?php echo $ticketId; ?>
		</div>
	</div>
	<div class="ticketInfo">
		<div>
			Ф.И.О.
		</div>
		<div class="empty">
			<?php echo $name; ?>
		</div>
	</div>
	<div class="ticketInfo">
		<div>
			Дата и место рождения
		</div>
		<div class="empty">
			<?php echo $birthDate; ?>
		</div>
	</div>
	<div class="ticketInfo">
		<div>
			Наименование документа
		</div>
		<div class="empty">

		</div>
	</div>
	<div class="ticketInfo">
		<div>
			Серия и номер
		</div>
		<div class="empty">
			<?php echo $passport; ?>
		</div>
	</div>
	<div class="ticketInfo">
		<div>
			Маршрут
		</div>
		<div class="empty">
			<?php echo $direction; ?>
		</div>
	</div>
	<div class="ticketInfo">
		<div>
			Дата отправления
		</div>
		<div class="empty">
			<?php echo $departure; ?>
		</div>
	</div>
	<div class="departureTime">
		<div>
			Время отправления
		</div>
		<div class="depTime">
			<?php echo $departureTime; ?>
		</div>
		<div>
			Место
		</div>
		<div class="tPlace">
			<?php echo $place; ?>
		</div>
	</div>
	<div class="ticketInfo">
		<div>
			Дата прибытия
		</div>
		<div class="empty">
			<?php echo $arrival; ?>
		</div>
	</div>
	<div class="ticketInfo">
		<div>
			Время прибытия
		</div>
		<div class="empty">
			<?php echo $arrivalTime; ?>
		</div>
	</div>
	<div class="ticketInfo">
		<div>
			Тип, № автобуса
		</div>
		<div class="empty">
			<?php echo $bus; ?>
		</div>
	</div>
	<div class="ticketInfo">
		<div>
			Стоимость билета
		</div>
		<div class="empty">
			<?php echo $price; ?>
		</div>
	</div>
	<div class="saleDate">
		<div>
			Дата продажи
		</div>
		<div class="sDate">
			<?php echo date("d.m.Y"); ?>
		</div>
		<div>
			кассир
		</div>
		<div class="authograph">

		</div>
	</div>
</div>
<?php
	if($pageBreak){
		echo '<div style="height: 1px; page-break-inside: avoid; page-break-after:always; margin: 0;"></div>';
	}
?>