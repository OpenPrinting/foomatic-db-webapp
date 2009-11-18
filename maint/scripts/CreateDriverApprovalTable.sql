CREATE TABLE driver_approval
(
	id VARCHAR(50) NOT NULL,
	contributor VARCHAR(40),
	showentry VARCHAR(40),
	approved VARCHAR(40),
	rejected VARCHAR(40),
	approver VARCHAR(40),
	comment TEXT,
	CONSTRAINT pkey PRIMARY KEY(id),
	FOREIGN KEY(id) REFERENCES driver(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
