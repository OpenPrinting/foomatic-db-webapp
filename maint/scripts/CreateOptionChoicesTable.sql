CREATE TABLE option_choice
(
	id VARCHAR(50),
	option_id VARCHAR(50),
	longname VARCHAR(50),
	shortname VARCHAR(50),
	driverval TINYTEXT,
	CONSTRAINT pkey PRIMARY KEY(id, option_id),
	FOREIGN KEY(option_id) REFERENCES options(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;