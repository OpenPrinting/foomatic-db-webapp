CREATE TABLE `web_roles_userassign` (
  `assignID` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(32) NOT NULL,
  `roleID` int(11) NOT NULL,
  PRIMARY KEY (`assignID`),
  KEY `Index_2` (`roleID`),
  CONSTRAINT `FK_web_roles_userassign_1` FOREIGN KEY (`roleID`) REFERENCES `web_roles` (`roleID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;