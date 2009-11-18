CREATE TABLE driver_translation
(
	id VARCHAR(50) NOT NULL,
	lang VARCHAR(8),
	supplier VARCHAR(50),
	license TEXT,
	licensetext TEXT,
	licenselink TINYTEXT,
	shortdescription TEXT,
	comments TEXT,
	CONSTRAINT pkey PRIMARY KEY(id, lang),
	FOREIGN KEY(id) REFERENCES driver(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
