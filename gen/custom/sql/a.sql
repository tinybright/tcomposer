/*menu*/
CREATE TABLE `b_feature` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `owner` varchar(255) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `screenlist` text,
  `creator` bigint(20) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT '0',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `b_menu` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `displayname` varchar(100) DEFAULT NULL,
  `parentid` bigint(20) DEFAULT NULL,
  `parentname` varchar(100) DEFAULT NULL,
  `url` varchar(1000) DEFAULT NULL,
  `featureid` bigint(20) DEFAULT NULL,
  `hide` tinyint(4) DEFAULT NULL,
  `iconnormal` varchar(100) DEFAULT NULL,
  `iconactive` varchar(100) DEFAULT NULL,
  `idpath` text,
  `namepath` text,
  `level` int(11) DEFAULT NULL,
  `creator` bigint(20) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT '0',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=113155 DEFAULT CHARSET=utf8mb4;