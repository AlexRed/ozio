 
CREATE TABLE IF NOT EXISTS `#__ozio_setup` (
	`id`       INT(11)     NOT NULL AUTO_INCREMENT,
	`client_id` MEDIUMTEXT NOT NULL,
	`client_secret` MEDIUMTEXT NOT NULL,
	`user_id` MEDIUMTEXT,
	`status` VARCHAR(255) NOT NULL,
	`refresh_token` MEDIUMTEXT,
	`access_token` MEDIUMTEXT,
	
	
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
 