CREATE TABLE driver_dependency
(
	driver_id VARCHAR(50) NOT NULL,
	required_driver VARCHAR(50) NOT NULL,
	version VARCHAR(50),
	CONSTRAINT pkey PRIMARY KEY(driver_id, required_driver),
	FOREIGN KEY(driver_id) REFERENCES driver(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;