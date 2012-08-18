CREATE TABLE `web_notifications` (
  `web_user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL DEFAULT '',
  `printer_queue` tinyint(1) NOT NULL DEFAULT '0',
  `printer_noqueue` tinyint(1) NOT NULL DEFAULT '0',
  `driver_queue` tinyint(1) NOT NULL DEFAULT '0',
  `driver_noqueue` tinyint(1) NOT NULL,
  PRIMARY KEY (`web_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;