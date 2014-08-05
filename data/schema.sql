CREATE TABLE IF NOT EXISTS `withings_access_tokens` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `request_tokens` varchar(255) NOT NULL,
  `acces_tokens` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`user_id`),
  UNIQUE KEY (`user_id`,`request_tokens`),
  FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB;
