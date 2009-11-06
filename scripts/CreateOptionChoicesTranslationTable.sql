CREATE TABLE option_choice_translation
(
	id VARCHAR(50),
	option_id VARCHAR(50),
	lang VARCHAR(8),
	longname VARCHAR(50),
	CONSTRAINT pkey PRIMARY KEY(id, option_id, lang),
	FOREIGN KEY(id) REFERENCES option_choice(id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(option_id) REFERENCES option_choice(option_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
