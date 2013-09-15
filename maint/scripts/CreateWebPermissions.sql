CREATE TABLE `web_permissions` (
  `privName` varchar(45) NOT NULL,
  `title` varchar(64) NOT NULL,
  PRIMARY KEY (`privName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `web_permissions` VALUES
  ('driver_noqueue','Bypass driver upload moderation queue'),
  ('driver_queue_adm','Moderate the driver queue'),
  ('driver_upload','Upload driver tarballs'),
  ('printer_noqueue','Bypass printer upload moderation queue'),
  ('printer_upload','Allow printer uploading'),
  ('roleadmin','Manage roles, permissions, and user assignments.'),
  ('show_admin','Show link to admin UI'),
  ('notifications','Receive notifications for uploads'),
  ('driver_edit','Edit and approve printers in queue'),
  ('printer_edit','Edit and approve drivers in queue'),
  ('driver_delete','Delete drivers in queue'),
  ('printer_delete','Delete printers in queue');