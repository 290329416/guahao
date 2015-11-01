SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";



--
-- 表的结构 `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `catid` bigint(20) unsigned NOT NULL  COMMENT '主键',
  `catname` varchar(40) NOT NULL COMMENT '栏目名称',
  `parentid` bigint(20) NOT NULL COMMENT '栏目id',
  `description` varchar(240) DEFAULT '' COMMENT '描述',
  `orderid` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `showtype` tinyint(1) unsigned NOT NULL,
  `icon` varchar(64) DEFAULT '' COMMENT '栏目图片',
  `en` varchar(20) NOT NULL DEFAULT '' COMMENT '英文缩写',
  `keyword` varchar(512) NOT NULL DEFAULT '' COMMENT '关键词',
  `catpath` varchar(16) DEFAULT '' COMMENT '频道目录名称',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  ;

-- --------------------------------------------------------



--
-- 表的结构 `hits`
--

CREATE TABLE IF NOT EXISTS `hits` (
  `aid` bigint(20) unsigned NOT NULL,
  `views` int(10) unsigned NOT NULL COMMENT '点击数',
  `catid` bigint(20) unsigned NOT NULL COMMENT '栏目id',
  KEY `views` (`views`),
  KEY `aid` (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='点击数';

-- --------------------------------------------------------



--
-- 表的结构 `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` bigint(20) unsigned NOT NULL  COMMENT '主键',
  `catid` bigint(20) unsigned NOT NULL COMMENT '栏目id',
  `title` varchar(80) NOT NULL COMMENT '标题',
  `keywords` char(40) DEFAULT NULL COMMENT '关键词',
  `description` varchar(240) NOT NULL COMMENT '描述',
  `attribute` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1:推荐, 2:精华',
  `listorder` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `uid` bigint(20) unsigned NOT NULL COMMENT '发布人id',
  `username` char(20) DEFAULT NULL COMMENT '用户名',
  `url` char(100) DEFAULT NULL COMMENT '链接地址',
  `islink` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '外部链接',
  `inputtime` int(10) unsigned NOT NULL COMMENT '添加时间',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '状态：0退稿，1~4审核状态 99通过',
  PRIMARY KEY (`id`),
  KEY `attribute` (`attribute`,`inputtime`,`status`),
  KEY `catid` (`catid`,`attribute`,`listorder`,`inputtime`,`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='文章主表'  ;

-- --------------------------------------------------------

--
-- 表的结构 `news_data`
--

CREATE TABLE IF NOT EXISTS `news_data` (
  `id` bigint(20) unsigned NOT NULL COMMENT '文章id',
  `content` text COMMENT '文章内容',
  `defaultpic` varchar(256) DEFAULT NULL,
  `fromweb` varchar(48) NOT NULL COMMENT '文章来源',
  `filename` varchar(256) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章从表';

-- --------------------------------------------------------

--
-- 表的结构 `privileges`
--

CREATE TABLE IF NOT EXISTS `privileges` (
  `privilege_id` int(11) NOT NULL AUTO_INCREMENT,
  `privilege_name` varchar(10) DEFAULT NULL,
  `privilege_description` varchar(100) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL,
  `add_time` datetime DEFAULT NULL,
  PRIMARY KEY (`privilege_id`),
  KEY `index_menu_id` (`menu_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------


--
-- 表的结构 `uploadfile`
--

CREATE TABLE IF NOT EXISTS `uploadfile` (
  `id` varchar(32) NOT NULL COMMENT '文章id',
  `aid` varchar(32) NOT NULL COMMENT '文章id',
  `uploadtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `filepath` varchar(48) NOT NULL COMMENT '路径',
  KEY `id` (`id`),
  KEY `aid` (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='上传文件';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
