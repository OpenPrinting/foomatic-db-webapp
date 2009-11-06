CREATE TABLE driver_support_contact
(
	driver_id VARCHAR(50) NOT NULL,
	url VARCHAR(255) NOT NULL,
	level VARCHAR(20) NOT NULL,
	description TEXT,
	CONSTRAINT pkey PRIMARY KEY(driver_id, url, level),
	FOREIGN KEY(driver_id) REFERENCES driver(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;