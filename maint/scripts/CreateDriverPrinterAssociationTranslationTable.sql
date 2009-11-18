CREATE TABLE driver_printer_assoc_translation
(
	driver_id VARCHAR(50) NOT NULL,
	printer_id VARCHAR(50) NOT NULL,
	lang VARCHAR(8),
	comments TEXT,
	pcomments TEXT,
	CONSTRAINT pkey PRIMARY KEY(driver_id, printer_id, lang),
	FOREIGN KEY(driver_id, printer_id) REFERENCES driver_printer_assoc(driver_id, printer_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
