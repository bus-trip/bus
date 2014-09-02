<?php

class m140822_172422_i extends CDbMigration
{
	public function up()
	{
        $this->execute("CREATE TABLE IF NOT EXISTS `buses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(10) NOT NULL,
  `places` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `number` (`number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `directions`
--

CREATE TABLE IF NOT EXISTS `directions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) NOT NULL,
  `startPoint` varchar(255) NOT NULL,
  `endPoint` varchar(255) NOT NULL,
  `departure` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `arrival` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `price` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Структура таблицы `passengers`
--

CREATE TABLE IF NOT EXISTS `passengers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `sex` varchar(3) NOT NULL,
  `passport` varchar(15) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `passport` (`passport`),
  KEY `name` (`name`,`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPassenger` int(11) NOT NULL,
  `idDirection` int(11) NOT NULL,
  `idTrip` int(11) NOT NULL,
  `idBus` int(11) NOT NULL,
  `place` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idPassenger` (`idPassenger`),
  KEY `idDirection` (`idDirection`),
  KEY `idTrip` (`idTrip`),
  KEY `idBus` (`idBus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
");
	}

	public function down()
	{
		echo "m140822_172422_i does not support migration down.\n";
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