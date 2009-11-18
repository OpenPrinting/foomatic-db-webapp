CREATE TABLE option_constraint
(
	option_id VARCHAR(50) NOT NULL,
	choice_id VARCHAR(50),
	sense ENUM('true', 'false') NOT NULL,
	driver VARCHAR(50),
	printer VARCHAR(50),
	defval VARCHAR(1024),
	is_choice_constraint BOOL NOT NULL DEFAULT 0,
	CONSTRAINT pkey PRIMARY KEY(option_id, choice_id, driver, printer),
	FOREIGN KEY(option_id) REFERENCES options(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;