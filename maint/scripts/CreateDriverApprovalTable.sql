CREATE TABLE driver_approval
(
	id VARCHAR(50) NOT NULL,
	contributor VARCHAR(40),
	showentry VARCHAR(40),
	approved VARCHAR(40),
	rejected VARCHAR(40),
	approver VARCHAR(40),
	comment TEXT,
	PRIMARY KEY(id)
) ENGINE=InnoDB;
