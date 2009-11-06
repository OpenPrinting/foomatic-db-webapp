CREATE TABLE driver_support_contact_translation
(
	driver_id VARCHAR(50) NOT NULL,
	url VARCHAR(255) NOT NULL,
	level VARCHAR(20) NOT NULL,
	lang VARCHAR(8),
	description TEXT,
	CONSTRAINT pkey PRIMARY KEY(driver_id, url, level, lang),
	FOREIGN KEY(driver_id, url, level) REFERENCES driver_support_contact(driver_id, url, level) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
