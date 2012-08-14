CREATE TABLE `web_roles` (
  `roleID` int(11) NOT NULL AUTO_INCREMENT,
  `roleName` varchar(64) NOT NULL,
  PRIMARY KEY (`roleID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `web_roles` VALUES
  (1,'Uploader'),
  (2,'Trusted Uploader'),
  (3,'Administrator'),
  (4,'Printer Uploader');