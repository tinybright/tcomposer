/*base*/
DROP TABLE IF EXISTS `b_base`;
CREATE TABLE `b_base` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `creatememo` bigint(20) DEFAULT NULL COMMENT '创建备注',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
DROP TABLE IF EXISTS `u_session`;
CREATE TABLE `u_session` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `token` varchar(100) DEFAULT NULL COMMENT '令牌',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
# custom
DROP TABLE IF EXISTS `b_user`;
CREATE TABLE `b_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL COMMENT '用户名',
  `pwd` varchar(100) DEFAULT NULL COMMENT '密码',
  `mobile` varchar(100) DEFAULT NULL COMMENT '手机号',
  `realname` varchar(100) DEFAULT NULL COMMENT '姓名',
  `avatar` VARCHAR(100) DEFAULT NULL COMMENT '头像',
  `idcardno` VARCHAR(100) DEFAULT NULL COMMENT '身份证号',
  `gender` VARCHAR(100) DEFAULT 'male' COMMENT '性别',
  `birthday` TIMESTAMP NULL DEFAULT NULL COMMENT '出生年月',
  `role` varchar(2000) DEFAULT NULL COMMENT '角色',
  `type` varchar(100) DEFAULT 'user' COMMENT '账号类型',
  `rights` varchar(2000) DEFAULT NULL COMMENT '权限',
  `memo` varchar(5000) DEFAULT NULL COMMENT '备注',
  `email` varchar(200) DEFAULT NULL COMMENT '邮箱',
  `wechat` VARCHAR(200) DEFAULT NULL COMMENT '微信号',
  `qq` VARCHAR(200) DEFAULT NULL COMMENT 'qq号',
  `contact` VARCHAR(200) DEFAULT NULL COMMENT '联系电话',
  `source` VARCHAR(200) DEFAULT 'pc' COMMENT '来源',
  `fip` VARCHAR(200) DEFAULT NULL COMMENT '首次IP',
  `lip` VARCHAR(200) DEFAULT NULL COMMENT '最后IP',
  `auth_idcard_front` VARCHAR(1000) DEFAULT NULL COMMENT '认证身份证正面',
  `auth_idcard_back` VARCHAR(1000) DEFAULT NULL COMMENT '认证身份证反面',
  `auth_realname` VARCHAR(100) DEFAULT NULL COMMENT '认证真实姓名',
  `auth_idcardno` VARCHAR(100) DEFAULT NULL COMMENT '认证身份证号',
  `auth_landload_status` VARCHAR(100) DEFAULT NULL COMMENT '房东认证状态',
  `auth_checker_status` VARCHAR(100) DEFAULT NULL COMMENT '检查员认证状态',
  `umtoken` VARCHAR(100) DEFAULT NULL COMMENT '友盟token',
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4;
/*INSERT INTO `b_user`(id,username,password,type,mobile,role) VALUES(1,'15972971860',${PASSWORD},'admin',15972971860,'#admin#,#mgr#,#user#,#checker#,#landlord#');*/

ALTER TABLE `b_user` ADD `allow_edit_base` VARCHAR(100) DEFAULT NULL COMMENT '允许修改基础地址' AFTER `umtoken`;

DROP TABLE IF EXISTS `b_log`;
CREATE TABLE `b_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `verb` varchar(100) DEFAULT NULL COMMENT '操作编号',
  `category` VARCHAR(1000) DEFAULT NULL COMMENT '分类',
  `objtype` varchar(100) DEFAULT NULL COMMENT '对象类型',
  `objid` bigint(20) DEFAULT NULL COMMENT '对象ID',
  `memo` text COMMENT '备注',
  `content` varchar(1000) DEFAULT NULL COMMENT '内容',
  `data` text COMMENT '额外数据',
  `code` varchar(200) DEFAULT NULL COMMENT '对象标识',
  `ip` VARCHAR(100) DEFAULT NULL COMMENT 'ip',
  `ua` TEXT COMMENT 'ua',
  `mac` VARCHAR(100) DEFAULT NULL COMMENT 'MAC',
  `extra_info` TEXT COMMENT '额外信息',
  `mobile_provider` VARCHAR(100) DEFAULT NULL COMMENT '运营商',
  `network_state` VARCHAR(100) DEFAULT NULL COMMENT '网络',
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24589 DEFAULT CHARSET=utf8mb4;

ALTER TABLE `b_log` ADD `mac` VARCHAR(100) DEFAULT NULL COMMENT 'MAC' AFTER `ua`;
ALTER TABLE `b_log` ADD `extra_info` TEXT COMMENT '额外信息' AFTER `mac`;
ALTER TABLE `b_log` ADD `mobile_provider` VARCHAR(100) DEFAULT NULL COMMENT '运营商' AFTER `extra_info`;
ALTER TABLE `b_log` ADD `network_state` VARCHAR(100) DEFAULT NULL COMMENT '网络' AFTER `mobile_provider`;
ALTER TABLE `b_log` ADD `version_name` VARCHAR(100) DEFAULT NULL COMMENT 'app版本' AFTER `network_state`;

DROP TABLE IF EXISTS `t_affiche`;
CREATE TABLE `t_affiche` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) DEFAULT NULL COMMENT '标题',
  `author` VARCHAR(100) DEFAULT NULL COMMENT '发布者',
  `pubtime` timestamp NULL DEFAULT NULL COMMENT '发布时间',
  `futurepubtime` timestamp NULL DEFAULT NULL COMMENT '定时发布时间',
  `cover` VARCHAR(1000) DEFAULT NULL COMMENT '封面',
  `content` MEDIUMTEXT DEFAULT NULL COMMENT '内容',
  `views` INT(11) DEFAULT '0' COMMENT '浏览量',
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
/*ALTER TABLE `u_room` ADD `hall` VARCHAR(100) DEFAULT NULL COMMENT '厅' AFTER `spec`;
ALTER TABLE `u_room` ADD `sub_room` VARCHAR(100) DEFAULT NULL COMMENT '室' AFTER `hall`;
ALTER TABLE `u_room` ADD `nature` VARCHAR(100) DEFAULT NULL COMMENT '房屋性质' AFTER `sub_room`;
ALTER TABLE `u_room` ADD `safe_attachment` VARCHAR(1000) DEFAULT NULL COMMENT '安全设备' AFTER `nature`;
ALTER TABLE `u_room` ADD `other_room` VARCHAR(1000) DEFAULT NULL COMMENT '自带' AFTER `safe_attachment`;
ALTER TABLE `u_room` ADD `admin_realname` VARCHAR(100) DEFAULT NULL COMMENT '管理员姓名' AFTER `other_room`;
ALTER TABLE `u_room` ADD `admin_idcardno` VARCHAR(100) DEFAULT NULL COMMENT '管理员身份证' AFTER `admin_realname`;
ALTER TABLE `u_room` ADD `admin_gender` VARCHAR(100) DEFAULT NULL COMMENT '管理员性别' AFTER `admin_idcardno`;
ALTER TABLE `u_room` ADD `admin_birthday` VARCHAR(100) DEFAULT NULL COMMENT '管理员生日' AFTER `admin_gender`;
ALTER TABLE `u_room` ADD `admin_mobile` VARCHAR(100) DEFAULT NULL COMMENT '管理员联系电话' AFTER `admin_birthday`;
ALTER TABLE `u_room` ADD `show_admin` VARCHAR(100) DEFAULT NULL COMMENT '租客可见' AFTER `admin_mobile`;
ALTER TABLE `u_room` ADD `node_door_code` VARCHAR(100) DEFAULT NULL COMMENT '房间号' AFTER `node_jcwhid`;
ALTER TABLE `u_room` ADD `rent_unit` VARCHAR(100) DEFAULT NULL COMMENT '租金结算周期' AFTER `rent`;*/

ALTER TABLE `u_room` ADD `fname` VARCHAR(1000) DEFAULT NULL COMMENT '房间号' AFTER `show_admin`;
ALTER TABLE `u_room` ADD `fnameid` VARCHAR(100) DEFAULT NULL COMMENT '房间ID'AFTER `fname`;
ALTER TABLE `u_room` DROP `source`;
ALTER TABLE `u_room` ADD `source` VARCHAR(100) DEFAULT 'mgr' COMMENT '来源'AFTER `fnameid`;
ALTER TABLE `u_room` ADD `data_status` VARCHAR(100) DEFAULT 'normal' COMMENT '数据状态' AFTER `source`;
DROP TABLE IF EXISTS `u_room`;
CREATE TABLE `u_room` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(100) DEFAULT NULL COMMENT '编号',
  /*`area` BIGINT(20) DEFAULT NULL COMMENT '所属区域',
  `address` VARCHAR(200) DEFAULT NULL COMMENT '详细地址',*/
  `usage` VARCHAR(100) DEFAULT NULL COMMENT '用途',
  `renttype` VARCHAR(100) DEFAULT NULL COMMENT '出租类型',
  `type` VARCHAR(100) DEFAULT NULL COMMENT '功能',
  /*`childtype` VARCHAR(100) DEFAULT NULL COMMENT '子功能',*/
  `spec` VARCHAR(100) DEFAULT NULL COMMENT '厅室',

  `hall` VARCHAR(100) DEFAULT NULL COMMENT '厅',
  `sub_room` VARCHAR(100) DEFAULT NULL COMMENT '室',
  `nature` VARCHAR(100) DEFAULT NULL COMMENT '房屋性质',
  `safe_attachment` VARCHAR(1000) DEFAULT NULL COMMENT '安全设备',
  `other_room` VARCHAR(1000) DEFAULT NULL COMMENT '自带',
  `admin_realname` VARCHAR(100) DEFAULT NULL COMMENT '管理员姓名',
  `admin_idcardno` VARCHAR(100) DEFAULT NULL COMMENT '管理员身份证',
  `admin_gender` VARCHAR(100) DEFAULT NULL COMMENT '管理员性别',
  `admin_birthday` VARCHAR(100) DEFAULT NULL COMMENT '管理员生日',
  `admin_mobile` VARCHAR(100) DEFAULT NULL COMMENT '管理员联系电话',
  `show_admin` VARCHAR(100) DEFAULT NULL COMMENT '租客可见',
  `fname` VARCHAR(1000) DEFAULT NULL COMMENT '房间号',
  `fnameid` VARCHAR(100) DEFAULT NULL COMMENT '房间ID',

  `size` BIGINT(20) DEFAULT NULL COMMENT '面积',
  `paytype` VARCHAR(100) DEFAULT NULL COMMENT '支付方式',
  `rent` BIGINT(20) DEFAULT NULL COMMENT '租金',
  `rent_unit` VARCHAR(100) DEFAULT NULL COMMENT '租金结算周期',
  `views` INT(11) DEFAULT '0' COMMENT '浏览量',
  `attachment` VARCHAR(1000) DEFAULT NULL COMMENT '附属设施',
  `extraattachment` text COMMENT '其他设施',
  `memo` text COMMENT '备注',
  `photo` text COMMENT '图片',
  `lat` FLOAT DEFAULT NULL COMMENT '经度',
  `lng` FLOAT DEFAULT NULL COMMENT '纬度',
  `lord_realname` VARCHAR(100) DEFAULT NULL COMMENT '户主姓名',
  `lord_idcardno` VARCHAR(100) DEFAULT NULL COMMENT '户主身份证',
  `lord_mobile` VARCHAR(100) DEFAULT NULL COMMENT '户主电话',
  `lord_gender` VARCHAR(100) DEFAULT NULL COMMENT '户主性别',
  `lord_birthday` VARCHAR(100) DEFAULT NULL COMMENT '户主生日',
  `nodeid` VARCHAR(100) DEFAULT NULL COMMENT '地址ID',
  `nodename` VARCHAR(100) DEFAULT NULL COMMENT '地址名称',
  `node_jcwhmc` VARCHAR(100) DEFAULT NULL COMMENT '区域名称',
  `node_jcwhid` VARCHAR(100) DEFAULT NULL COMMENT '区域ID',
  `node_doorcode` VARCHAR(100) DEFAULT NULL COMMENT '房间号',
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `lastedittime` timestamp NULL DEFAULT NULL COMMENT '最后更新时间',
  `lastchecktime` timestamp NULL DEFAULT NULL COMMENT '最后检查时间',
  `show_lord` TINYINT(4) DEFAULT '1' COMMENT '是否显示房东信息',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
ALTER TABLE `u_check` ADD `ntctime` TIMESTAMP NULL DEFAULT NULL COMMENT '通知时间' AFTER `photo`;
DROP TABLE IF EXISTS `u_check`;
CREATE TABLE `u_check` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `result` VARCHAR(100) DEFAULT NULL COMMENT '检查结果',
  `resultmemo` VARCHAR(1000) DEFAULT NULL COMMENT '检查备注',
  `correctresult` VARCHAR(100) DEFAULT NULL COMMENT '上期整改结果',
  `correction` VARCHAR(1000) DEFAULT NULL COMMENT '整改内容',
  `roomid` BIGINT(20) DEFAULT NULL COMMENT '房间ID',
  `checktime` TIMESTAMP NULL DEFAULT NULL COMMENT '检查时间',
  `checker` BIGINT(20) DEFAULT NULL COMMENT '检查者ID',
  `checkid` BIGINT(20) DEFAULT NULL COMMENT '根节点ID',
  `replycheckid` BIGINT(20) DEFAULT NULL COMMENT '父节点ID',
  `replyuid` BIGINT(20) DEFAULT NULL COMMENT '回复用户ID',
  `memo` VARCHAR(1000) DEFAULT NULL COMMENT '描述',
  `photo` VARCHAR(1111) DEFAULT NULL COMMENT '图片',
  `ntctime` TIMESTAMP NULL DEFAULT NULL COMMENT '通知时间',
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
DROP TABLE IF EXISTS `u_access`;
CREATE TABLE `u_access` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `roomid` BIGINT(20) DEFAULT NULL COMMENT '房间ID',
  `realname` VARCHAR(100) DEFAULT NULL COMMENT '用户姓名',
  `nodeid` VARCHAR(100) DEFAULT NULL COMMENT '标准地址ID',
  `idcardno` VARCHAR(100) DEFAULT NULL COMMENT '身份证号',
  `triggertime` TIMESTAMP NULL DEFAULT NULL COMMENT '触发时间',
  `action` VARCHAR(100) DEFAULT NULL COMMENT '动作',
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

ALTER TABLE `t_renter` ADD `fnameid` VARCHAR(100) DEFAULT NULL COMMENT '房间号ID' AFTER `nodename`;
ALTER TABLE `t_renter` ADD `fname` VARCHAR(100) DEFAULT NULL COMMENT '房间号' AFTER `fnameid`;
DROP TABLE IF EXISTS `t_renter`;
CREATE TABLE `t_renter` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nodeid` VARCHAR(100) DEFAULT NULL COMMENT '标准地址ID',
  `nodename` VARCHAR(100) DEFAULT NULL COMMENT '标准地址名称',
  `fnameid` VARCHAR(100) DEFAULT NULL COMMENT '房间号ID',
  `fname` VARCHAR(100) DEFAULT NULL COMMENT '房间号',
  `rent_realname` VARCHAR(100) DEFAULT NULL COMMENT '姓名',
  `rent_idcardno` VARCHAR(100) DEFAULT NULL COMMENT '身份证',
  `rent_gender` VARCHAR(100) DEFAULT 'male' COMMENT '性别',
  `rent_birthday` TIMESTAMP NULL DEFAULT NULL COMMENT '生日',
  `rent_native` VARCHAR(100) DEFAULT NULL COMMENT '籍贯',
  `rent_mobile` VARCHAR(100) DEFAULT NULL COMMENT '电话',
  `rent_joined` TIMESTAMP NULL DEFAULT NULL COMMENT '入住时间',
  `lord_realname` VARCHAR(100) DEFAULT NULL COMMENT '房东姓名',
  `lord_mobile` VARCHAR(100) DEFAULT NULL COMMENT '房东电话',
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
ALTER TABLE `t_renter` ADD `source` VARCHAR(100) DEFAULT NULL COMMENT '来源' AFTER `fname`;
ALTER TABLE `t_renter` ADD `decoded` tinyint(4) DEFAULT '0' COMMENT '是否识别' AFTER `source`;
ALTER TABLE `t_renter` ADD `import_memo` VARCHAR(1000) DEFAULT NULL COMMENT '导入备注' AFTER `source`;
ALTER TABLE `t_renter` ADD `import_data_addr` VARCHAR(1000) DEFAULT NULL COMMENT '原始地址' AFTER `import_memo`;
ALTER TABLE `t_renter` ADD `import_data_date` VARCHAR(100) DEFAULT NULL COMMENT '原始入住时间' AFTER `import_data_addr`;


ALTER TABLE `u_check_pending` ADD `fname` VARCHAR(1000) DEFAULT NULL COMMENT '房间号' AFTER `checker`;
ALTER TABLE `u_check_pending` ADD `fnameid` VARCHAR(100) DEFAULT NULL COMMENT '房间ID'AFTER `fname`;

ALTER TABLE `u_check_pending` ADD `remindtime` TIMESTAMP NULL DEFAULT NULL COMMENT '提醒时间' AFTER `deadline`;
ALTER TABLE `u_check_pending` ADD `donetime` TIMESTAMP NULL DEFAULT NULL COMMENT '实际检查时间' AFTER `remindtime`;
DROP TABLE IF EXISTS `u_check_pending`;
CREATE TABLE `u_check_pending` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `roomid` BIGINT(20) DEFAULT NULL COMMENT '房间ID',
  `checker` BIGINT(20) DEFAULT NULL COMMENT '检查员',
  `fname` VARCHAR(1000) DEFAULT NULL COMMENT '房间地址',
  `fnameid` VARCHAR(100) DEFAULT NULL COMMENT '房间ID',
  `nodeid` VARCHAR(100) DEFAULT NULL COMMENT '门牌地址ID',
  `node_jcwhid` VARCHAR(100) DEFAULT NULL COMMENT '区域ID',
  `deadline` TIMESTAMP NULL DEFAULT NULL COMMENT '检查截止',
  `remindtime` TIMESTAMP NULL DEFAULT NULL COMMENT '提醒时间',
  `donetime` TIMESTAMP NULL DEFAULT NULL COMMENT '实际检查时间',
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
DROP TABLE IF EXISTS `r_zone_user`;
CREATE TABLE `r_zone_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(100) DEFAULT NULL COMMENT '区域代号',
  `nodeid` VARCHAR(100) DEFAULT NULL COMMENT '区域ID',
  `nodename` VARCHAR(100) DEFAULT NULL COMMENT '区域名称',
  `uid` BIGINT(20) DEFAULT NULL COMMENT '检查员ID',
  `uids` VARCHAR(1000) DEFAULT NULL COMMENT '检查员的ID列表',
  `des` text DEFAULT NULL COMMENT '区域描述',
  `memo` text DEFAULT NULL COMMENT '区域备注',
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
DROP TABLE IF EXISTS `u_notice`;
CREATE TABLE `u_notice` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(1000) DEFAULT NULL,
  `content` varchar(5000) DEFAULT NULL,
  `type` VARCHAR(100) DEFAULT NULL,
  `objtype` int(11) DEFAULT NULL COMMENT '关联对象类型',
  `objid` bigint(20) DEFAULT NULL COMMENT '关联对象ID',
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69278 DEFAULT CHARSET=utf8mb4;
DROP TABLE IF EXISTS `b_wxuser`;
CREATE TABLE `b_wxuser` (
  `openid` varchar(100) NOT NULL COMMENT 'oepnid',
  `nickname` varchar(100) DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(500) DEFAULT NULL COMMENT '头像',
  `sex` tinyint(4) DEFAULT NULL COMMENT '性别',
  `country` varchar(50) DEFAULT NULL COMMENT '国家',
  `province` varchar(50) DEFAULT NULL COMMENT '省',
  `city` varchar(50) DEFAULT NULL COMMENT '市',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
DROP TABLE IF EXISTS `b_config`;
CREATE TABLE `b_config` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL COMMENT '名称',
  `key` varchar(200) NOT NULL COMMENT '键',
  `config` varchar(1000) DEFAULT NULL COMMENT '值',
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
DROP TABLE IF EXISTS `b_captcha`;
CREATE TABLE `b_captcha` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(100) DEFAULT NULL COMMENT '手机号',
  `type` VARCHAR(100) DEFAULT NULL COMMENT '类型',
  `captcha` varchar(100) DEFAULT NULL COMMENT '验证码',
  `deadline` datetime DEFAULT NULL COMMENT '有效时间',
  `lastsend` timestamp NULL DEFAULT NULL COMMENT '最后一次发送时间',
  `ip` bigint(20) DEFAULT NULL COMMENT 'IP地址',
  `uuid` varchar(100) DEFAULT NULL COMMENT '编号',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=288 DEFAULT CHARSET=utf8;
ALTER TABLE `b_node` ADD `CS` VARCHAR(100) DEFAULT NULL COMMENT '层数' AFTER `DLH`;
ALTER TABLE `b_node` ADD `DYH` VARCHAR(100) DEFAULT NULL  COMMENT '单元' AFTER `CS`;
ALTER TABLE `b_node` ADD `FJNAME` VARCHAR(100) DEFAULT NULL COMMENT '房间号' AFTER `DYH`;
ALTER TABLE `b_node` ADD `CS_ID` VARCHAR(100) DEFAULT NULL COMMENT '层数ID' AFTER `FJNAME`;
ALTER TABLE `b_node` ADD `DYH_ID` VARCHAR(100) DEFAULT NULL  COMMENT '单元ID' AFTER `CS_ID`;
ALTER TABLE `b_node` ADD `mpname` VARCHAR(100) DEFAULT NULL  COMMENT '门牌地址' AFTER `DYH_ID`;
ALTER TABLE `b_node` ADD `source` TINYINT(4) DEFAULT NULL COMMENT '来源' AFTER `parentid`;
ALTER TABLE `b_node` ADD `qrcode` VARCHAR(100) DEFAULT NULL COMMENT '二维码' AFTER `source`;
ALTER TABLE `b_node` ADD `shortname` VARCHAR(100) DEFAULT NULL COMMENT '简称' AFTER `qrcode`;

UPDATE b_node SET shortname = FJNAME WHERE `level` = 25;
UPDATE b_node SET shortname = CS WHERE `level` = 24;
UPDATE b_node SET shortname = DYH WHERE `level` = 23;

DROP TABLE IF EXISTS `b_node`;
CREATE TABLE `b_node` (
  /*`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,*/
  `BZDDDM` VARCHAR(100) NOT NULL COMMENT '标准地点代码',
  `JZWMC` VARCHAR(100) DEFAULT NULL COMMENT '建筑物名称',
  `JZWDZ` VARCHAR(100) DEFAULT NULL COMMENT '建筑物地址',
  `PCSDM` VARCHAR(100) DEFAULT NULL COMMENT '派出所ID',
  `PCSMC` VARCHAR(100) DEFAULT NULL COMMENT '派出所名称',
  `JCWHID` VARCHAR(100) DEFAULT NULL COMMENT '区域ID',
  `JCWHMC` VARCHAR(100) DEFAULT NULL COMMENT '区域名称',
  `XQID` VARCHAR(100) DEFAULT NULL COMMENT '小区ID',
  `XQ` VARCHAR(100) DEFAULT NULL COMMENT '小区名称',
  `XQMC` VARCHAR(100) DEFAULT NULL COMMENT '小区名称',
  `MPH` VARCHAR(100) DEFAULT NULL COMMENT '门牌',
  `DLH` VARCHAR(100) DEFAULT NULL COMMENT '楼栋',
  `CS` VARCHAR(100) DEFAULT NULL COMMENT '层数',
  `DYH` VARCHAR(100) DEFAULT NULL  COMMENT '单元',
  `FJNAME` VARCHAR(100) DEFAULT NULL COMMENT '房间号',
  `CS_ID` VARCHAR(100) DEFAULT NULL COMMENT '层数ID',
  `DYH_ID` VARCHAR(100) DEFAULT NULL  COMMENT '单元ID',
  `mpname` VARCHAR(100) DEFAULT NULL COMMENT '门牌地址',
  `X` VARCHAR(100) DEFAULT NULL COMMENT 'lat',
  `Y` VARCHAR(100) DEFAULT NULL COMMENT 'lng',
  `mp_dl_mode` VARCHAR(100) DEFAULT 'mp_dl' COMMENT '门牌栋楼模式',
  `level` VARCHAR(100) DEFAULT '20' COMMENT '层级',
  `parentid` VARCHAR(100) DEFAULT '-1' COMMENT '父级ID',
  `source` TINYINT(4) DEFAULT NULL COMMENT '来源',
  `qrcode` VARCHAR(100) DEFAULT NULL COMMENT '二维码',
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `creatememo` VARCHAR(100) DEFAULT 'init' COMMENT '创建备注',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT 'normal' COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`BZDDDM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `b_node` ADD `mpid` VARCHAR(100) DEFAULT NULL COMMENT '门牌地址ID' AFTER `qrcode`;
DROP TABLE IF EXISTS `b_config`;
CREATE TABLE `b_config` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL COMMENT '名称',
  `key` varchar(200) DEFAULT NULL COMMENT '键',
  `config` varchar(1000) DEFAULT NULL COMMENT '设置',
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `creatememo` VARCHAR(100) DEFAULT 'init' COMMENT '创建备注',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
DROP TABLE IF EXISTS `t_ntc`;
CREATE TABLE `t_ntc` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,

  `objtype` VARCHAR(100) DEFAULT NULL COMMENT '对象类型',
  `objids` TEXT COMMENT '对象ID',

  `title` VARCHAR(1000) DEFAULT NULL COMMENT '标题',
  `content` VARCHAR(1000) DEFAULT NULL COMMENT '内容',

  `repeat` INT(11) DEFAULT '0' COMMENT '重复次数',
  `sendret` VARCHAR(100) DEFAULT NULL COMMENT '发送结果',
  `sendmsg` TEXT COMMENT '发送详情',
  `receiver_mobile` VARCHAR(100) DEFAULT NULL COMMENT '接受者手机',
  `receiver_id` BIGINT(20) DEFAULT NULL COMMENT '接收者ID',

  `instant` TINYINT(4) DEFAULT '1' COMMENT '是否即时发送',
  `isbatch` TINYINT(4) DEFAULT '2' COMMENT '是否打包发送',

  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `b_app_log`;
CREATE TABLE `b_app_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `download_url` VARCHAR(1000) DEFAULT NULL COMMENT '下载地址',
  `memo` VARCHAR(5000) DEFAULT NULL COMMENT '更新内容',
  `version_name` VARCHAR(100) DEFAULT NULL COMMENT '版本号',
  `version_code` VARCHAR(100) DEFAULT NULL COMMENT '构建号',
  `creator` bigint(20) DEFAULT NULL COMMENT '创建者ID',
  `creatememo` VARCHAR(100) DEFAULT 'init' COMMENT '创建备注',
  `created` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `status` varchar(100) DEFAULT 'normal' COMMENT '状态',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `u_report_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `domainid` bigint(20) DEFAULT '1',
  `type` varchar(100) DEFAULT NULL,
  `querydata` varchar(1000) DEFAULT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `memo` varchar(1000) DEFAULT NULL,
  `fileid` bigint(20) DEFAULT NULL,
  `filepath` varchar(2000) DEFAULT NULL,
  `filecreated` datetime DEFAULT NULL,
  `creator` bigint(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT '0',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

