CREATE TABLE margin
(
	driver_id VARCHAR(50),
	printer_id VARCHAR(50),
	margin_type ENUM('general', 'exception') NOT NULL,
	pagesize VARCHAR(50),
	margin_unit ENUM('pt', 'in', 'mm', 'cm'),
	margin_absolute BOOL DEFAULT FALSE,
	margin_top FLOAT,
	margin_left FLOAT,
	margin_right FLOAT,
	margin_bottom FLOAT,
	CONSTRAINT pkey PRIMARY KEY(driver_id, printer_id, margin_type, pagesize)
) ENGINE=InnoDB;