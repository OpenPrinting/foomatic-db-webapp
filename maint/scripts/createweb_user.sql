CREATE TABLE `web_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `ipaddress` varchar(255) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  `block` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;