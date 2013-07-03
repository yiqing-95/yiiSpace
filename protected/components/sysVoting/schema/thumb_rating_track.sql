
CREATE TABLE `photo_thumb_vote` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `object_id` INT(11) UNSIGNED NOT NULL,
  `value` TINYINT(1) UNSIGNED NOT NULL,
  `ip` VARCHAR(255) DEFAULT NULL,
  `create_time` INT(11) UNSIGNED NOT NULL,
  `uid` INT(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
   KEY `only_once` (`object_id`,`ip`,`uid`)
) ENGINE=INNODB DEFAULT CHARSET=utf8
