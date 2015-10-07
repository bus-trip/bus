<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 20.09.2015
 * Time: 20:20
 */
?>
<p/>
<form
	action="<?php echo Yii::app()->controller->createUrl("directions/addPoint", array("id" => $data["id"])); ?>"
	method="post">
	<input type="hidden" name="id" value="<?php echo $data["id"]; ?>"/>
	<table class="addPointclass">
		<tr>
			<td><input type="text" name="prevPoint" value="<?php echo $data['prevPoint']; ?>" style="width:100px; text-align: center;" disabled/></td>
			<td>&lt;</td>
			<td><input type="text" name="price1" placeholder="руб." style="width:40px; text-align: center;"/></td>
			<td>&gt;</td>
			<td><input type="text" name="newPoint" placeholder="Пункт" style="width:100px; text-align: center;"/></td>
			<td>&lt;</td>
			<td><input type="text" name="price2" placeholder="руб." style="width:50px; text-align: center;"/></td>
			<td>&gt;</td>
			<td><input type="text" name="nextPoint" value="<?php echo $data['nextPoint']; ?>" style="width:100px; text-align: center;" disabled/></td>
		</tr>
		<tr>
			<td colspan="9" style="text-align: right;"><input type="submit" name="submit" value="Добавить" onsubmit="checkFields();"/></td>
		</tr>
	</table>
</form>
<script>
	function checkFields(){

	}
</script>
<div id="addPointError">

</div>