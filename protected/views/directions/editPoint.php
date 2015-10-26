<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 25.10.2015
 * Time: 17:09
 */
?>
<form
	action="<?php echo Yii::app()->controller->createUrl("directions/editPoint", array("id" => $data["id"])); ?>"
	method="post">
	<input type="hidden" name="id" value="<?php echo $data["id"]; ?>"/>
	<table class="addPointclass">
		<tr>
			<td style="text-align: center;">Старое название: <?php echo $data['oldName']; ?></td>
		</tr>
		<tr>
			<td style="text-align: center;"><input type="text" name="newName" placeholder="Новое название" style="width:180px; text-align: center;"/></td>
		</tr>
		<tr>
			<td style="text-align: center;"><input type="submit" name="submit" value="Изменить" onsubmit="checkFields();"/></td>
		</tr>
	</table>
</form>
<script>
	function checkFields(){

	}
</script>
<div id="addPointError">

</div>