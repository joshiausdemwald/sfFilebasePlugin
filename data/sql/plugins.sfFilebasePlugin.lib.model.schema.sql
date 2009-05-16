
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- sf_filebase_files
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `sf_filebase_files`;


CREATE TABLE `sf_filebase_files`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`pathname` VARCHAR(255)  NOT NULL,
	`hash` VARCHAR(255)  NOT NULL,
	`comment` TEXT,
	`sf_filebase_directories_id` INTEGER,
	PRIMARY KEY (`id`),
	UNIQUE KEY `sf_filebase_files_U_1` (`pathname`),
	UNIQUE KEY `sf_filebase_files_U_2` (`hash`),
	INDEX `sf_filebase_files_FI_1` (`sf_filebase_directories_id`),
	CONSTRAINT `sf_filebase_files_FK_1`
		FOREIGN KEY (`sf_filebase_directories_id`)
		REFERENCES `sf_filebase_directories` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- sf_filebase_directories
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `sf_filebase_directories`;


CREATE TABLE `sf_filebase_directories`
(
	`sf_filebase_files_id` INTEGER(11),
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`),
	INDEX `sf_filebase_directories_FI_1` (`sf_filebase_files_id`),
	CONSTRAINT `sf_filebase_directories_FK_1`
		FOREIGN KEY (`sf_filebase_files_id`)
		REFERENCES `sf_filebase_files` (`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- sf_filebase_tags
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `sf_filebase_tags`;


CREATE TABLE `sf_filebase_tags`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`sf_filebase_file_id` INTEGER(11),
	`tag` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `sf_filebase_tags_FI_1` (`sf_filebase_file_id`),
	CONSTRAINT `sf_filebase_tags_FK_1`
		FOREIGN KEY (`sf_filebase_file_id`)
		REFERENCES `sf_filebase_files` (`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
