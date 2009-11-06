CREATE TABLE driver_printer_assoc
(
	driver_id VARCHAR(50) NOT NULL,
	printer_id VARCHAR(50) NOT NULL,
	comments TEXT,
	max_res_x SMALLINT UNSIGNED,
	max_res_y SMALLINT UNSIGNED,
	color BOOL,
	text TINYINT UNSIGNED,
	lineart TINYINT UNSIGNED,
	graphics TINYINT UNSIGNED,
	photo TINYINT UNSIGNED,
	load_time TINYINT UNSIGNED,
	speed TINYINT UNSIGNED,
	ppd TINYTEXT,
	ppdentry TEXT NULL,
	pcomments TEXT,
	fromdriver BOOL NOT NULL DEFAULT 0,
	fromprinter BOOL NOT NULL DEFAULT 0,
	CONSTRAINT pkey PRIMARY KEY(driver_id, printer_id)
) ENGINE=InnoDB;