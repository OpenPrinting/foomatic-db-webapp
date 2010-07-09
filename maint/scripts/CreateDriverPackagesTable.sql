CREATE TABLE driver_package
(
	driver_id VARCHAR(50) NOT NULL,
	scope ENUM('general', 'gui', 'printer', 'scanner', 'fax') NOT NULL,
	fingerprint VARCHAR(767),
	name TEXT,
	CONSTRAINT pkey PRIMARY KEY(driver_id, scope, fingerprint),
	FOREIGN KEY(driver_id) REFERENCES driver(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
