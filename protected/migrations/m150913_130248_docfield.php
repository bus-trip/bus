<?php

class m150913_130248_docfield extends CDbMigration
{
	public function up()
	{
		$this->execute("
ALTER TABLE `profiles`
	ALTER `passport` DROP DEFAULT;
ALTER TABLE `profiles`
	CHANGE COLUMN `passport` `doc_num` VARCHAR(64) NOT NULL COMMENT 'Номер документа' AFTER `middle_name`,
	ADD COLUMN `doc_type` INT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'Тип документа' AFTER `doc_num`;
ALTER TABLE `profiles`
	ADD INDEX `doc` (`doc_num`, `doc_type`);
	");
	}

	public function down()
	{
		echo "m150913_130248_docfield does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}