CREATE TABLE options
(
	id VARCHAR(50) NOT NULL,
	option_type ENUM('enum', 'bool', 'int', 'float', 'string', 'password') NOT NULL,
	shortname VARCHAR(50) NOT NULL,
	longname VARCHAR(50),
	execution ENUM('substitution', 'postscript', 'pjl', 'composite', 'forced_composite') NOT NULL,
	required BOOL DEFAULT FALSE,
	prototype VARCHAR(65536),
	option_spot VARCHAR(10),
	option_order VARCHAR(10),
	option_section VARCHAR(50),
	option_group TINYTEXT,
	comments TEXT,
	max_value INT DEFAULT NULL,
	min_value INT DEFAULT NULL,
	shortname_false VARCHAR(50),
	maxlength INT UNSIGNED DEFAULT NULL,
	allowed_chars TEXT,
	allowed_regexp TEXT,
	CONSTRAINT pkey PRIMARY KEY(id)
) ENGINE=InnoDB;
