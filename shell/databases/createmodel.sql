-- 主表
CREATE TABLE `$basic_table` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `catid` smallint(5) unsigned NOT NULL default '0',
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `title` varchar(80) NOT NULL default '',
  `thumb` char(100) NOT NULL default '',
  `keywords` varchar(40) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `content` text NOT NULL default '',
  `url` char(100) NOT NULL default '',
  `listorder` tinyint(3) unsigned NOT NULL default '0',
  `status` tinyint(2) unsigned NOT NULL default '1',
  `uid` smallint(5) unsigned NOT NULL default '0',
  `createtime` varchar(32) NOT NULL default '0',
  `updatetime` varchar(32) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `status` (`status`,`listorder`,`id`),
  KEY `listorder` (`catid`,`status`,`listorder`,`id`),
  KEY `catid` (`catid`,`status`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of $table_$table_model_field
-- ----------------------------
INSERT INTO `$table_model_field` (`modelid`, `field`, `name`, `defaultvalue`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`,`iscore`, `isunique`, `isbase`, `issearch`,`isfulltext`,  `listorder`, `status`,`css`)VALUES ($modelid,'catid', '栏目', '', '1', '6', '', '请选择栏目', 'smallint', '', '', '1', '0', '1', '1', '0', '1', '1','');
INSERT INTO `$table_model_field` (`modelid`, `field`, `name`, `defaultvalue`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`,`iscore`, `isunique`, `isbase`, `issearch`,`isfulltext`,  `listorder`, `status`,`css`)VALUES ($modelid,'typeid', '类别', '','0', '0', '', '', 'smallint', '', '', '1','0', '1', '1', '0', '2', '1','');
INSERT INTO `$table_model_field` (`modelid`, `field`, `name`, `defaultvalue`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`,`iscore`, `isunique`, `isbase`, `issearch`,`isfulltext`,  `listorder`, `status`,`css`)VALUES ($modelid,'title', '标题', '', '1', '80', '', '请输入标题', 'varchar', '', '', '1', '0', '1', '1', '1', '3', '1','');
INSERT INTO `$table_model_field` (`modelid`, `field`, `name`, `defaultvalue`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`,`iscore`, `isunique`, `isbase`, `issearch`,`isfulltext`,  `listorder`, `status`,`css`)VALUES ($modelid,'thumb', '缩略图', '', '0', '100', '', '', 'char', '', '', '0', '0', '0', '0', '0', '4', '1','');
INSERT INTO `$table_model_field` (`modelid`, `field`, `name`, `defaultvalue`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`,`iscore`, `isunique`, `isbase`, `issearch`,`isfulltext`,  `listorder`, `status`,`css`)VALUES ($modelid,'keywords', '关键词', '','0', '40', '', '', 'varchar', '', '', '1', '0', '1', '1', '1', '5', '1','');
INSERT INTO `$table_model_field` (`modelid`, `field`, `name`, `defaultvalue`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`,`iscore`, `isunique`, `isbase`, `issearch`,`isfulltext`,  `listorder`, `status`,`css`)VALUES ($modelid,'description', '摘要', '', '0', '255', '', '', 'varchar', '', '', '1', '0', '1', '0', '1', '6', '1','');
INSERT INTO `$table_model_field` (`modelid`, `field`, `name`, `defaultvalue`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`,`iscore`, `isunique`, `isbase`, `issearch`,`isfulltext`,  `listorder`, `status`,`css`)VALUES ($modelid,'content', '内容', '', '1', '999999', '', '内容不能为空', 'text', '', '', '0','0', '1', '0', '1', '7', '1','');
INSERT INTO `$table_model_field` (`modelid`, `field`, `name`, `defaultvalue`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`,`iscore`, `isunique`, `isbase`, `issearch`,`isfulltext`,  `listorder`, `status`,`css`)VALUES ($modelid,'url', 'URL', '', '0', '100', '', '', 'char', '', '', '0', '0', '1', '0', '0', '8', '1','');
INSERT INTO `$table_model_field` (`modelid`, `field`, `name`, `defaultvalue`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`,`iscore`, `isunique`, `isbase`, `issearch`,`isfulltext`,  `listorder`, `status`,`css`)VALUES ($modelid,'listorder', '排序', '','0', '6', '', '', 'tinyint', '', '', '0', '0', '1', '0', '0', '9', '1','');
INSERT INTO `$table_model_field` (`modelid`, `field`, `name`, `defaultvalue`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`,`iscore`, `isunique`, `isbase`, `issearch`,`isfulltext`,  `listorder`, `status`,`css`)VALUES ($modelid,'status', '状态', '','0', '2', '', '', 'tinyint', '', '', '1', '0', '1', '0', '0', '10', '1','');
INSERT INTO `$table_model_field` (`modelid`, `field`, `name`, `defaultvalue`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`,`iscore`, `isunique`, `isbase`, `issearch`,`isfulltext`,  `listorder`, `status`,`css`)VALUES ($modelid,'uid', '用户id', '','1', '6', '', '', 'smallint', '', '', '1', '0', '1', '1', '0', '11', '1','');
INSERT INTO `$table_model_field` (`modelid`, `field`, `name`, `defaultvalue`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`,`iscore`, `isunique`, `isbase`, `issearch`,`isfulltext`,  `listorder`, `status`,`css`)VALUES ($modelid,'createtime', '创建时间', '','0', '0', '', '', 'varchar', '', '', '1', '0', '1', '0', '0', '12', '1','');
INSERT INTO `$table_model_field` (`modelid`, `field`, `name`, `defaultvalue`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`,`iscore`, `isunique`, `isbase`, `issearch`,`isfulltext`,  `listorder`, `status`,`css`)VALUES ($modelid,'updatetime', '更新时间', '','0', '0', '', '', 'varchar', '', '', '1', '0', '1', '0', '0', '13', '1','');