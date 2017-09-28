DROP TABLE IF EXISTS `b_base`;
CREATE TABLE `b_base` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `creatememo` bigint(20) DEFAULT NULL COMMENT '创建备注',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `u_session` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `token` varchar(100) DEFAULT NULL COMMENT '令牌',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
