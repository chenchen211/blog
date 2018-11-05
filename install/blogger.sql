/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : blogger

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2018-04-12 22:33:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for b_article
-- ----------------------------
DROP TABLE IF EXISTS `b_article`;
CREATE TABLE `b_article` (
  `a_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '//id',
  `a_reid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '//是否回复帖子',
  `a_username` varchar(20) NOT NULL COMMENT '//发帖人',
  `a_type` tinyint(2) unsigned NOT NULL COMMENT '//发帖类型',
  `a_check` tinyint(1) NOT NULL DEFAULT '2' COMMENT '//是否加密 1-加密  2-不加密',
  `a_title` varchar(40) NOT NULL COMMENT '//帖子标题',
  `a_keyword` varchar(20) NOT NULL COMMENT '//关键词',
  `a_content` text NOT NULL COMMENT '//帖子内容',
  `a_readcount` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '//阅读量',
  `a_commendcount` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '//评论量',
  `a_nice` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '//精华帖',
  `a_last_modify_date` datetime NOT NULL COMMENT '//最后修改时间',
  `a_date` datetime NOT NULL COMMENT '//发帖时间',
  `a_tagid` int(3) NOT NULL COMMENT '//标签id',
  PRIMARY KEY (`a_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of b_article
-- ----------------------------
INSERT INTO `b_article` VALUES ('3', '0', '木木', '8', '1', '我爱PHP', 'PHP', '<p>php好学 很好学 非常好学 超级棒 世界上最好的语言</p>', '4', '0', '0', '0000-00-00 00:00:00', '2018-03-12 14:17:20', '8');
INSERT INTO `b_article` VALUES ('4', '0', '木木', '8', '1', '我爱PHP', 'PHP', '<p>php好学 很好学 非常好学 超级棒 世界上最好的语言</p>', '4', '0', '0', '0000-00-00 00:00:00', '2018-03-12 14:17:20', '8');
INSERT INTO `b_article` VALUES ('5', '0', '木木', '8', '2', '我爱PHP', 'PHP', '<p>php好学 很好学 非常好学 超级棒 世界上最好的语言</p>', '7', '0', '0', '0000-00-00 00:00:00', '2018-03-12 14:17:20', '8');
INSERT INTO `b_article` VALUES ('6', '0', '木木', '8', '1', '我爱PHP', 'PHP', '<p>php好学 很好学 非常好学 超级棒 世界上最好的语言</p>', '4', '0', '0', '0000-00-00 00:00:00', '2018-03-12 14:17:20', '8');
INSERT INTO `b_article` VALUES ('7', '0', '大鱼', '2', '2', '我爱美容', '美容', '<p>美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容美容</p>', '3', '0', '0', '0000-00-00 00:00:00', '2018-03-12 14:57:49', '2');
INSERT INTO `b_article` VALUES ('9', '0', '小鱼', '4', '2', '我是一只鱼', '安安', '<p>我是一只鱼我是一只鱼我是一只鱼我是一只鱼我是一只鱼我是一只鱼我是一只鱼我是一只鱼我是一只鱼我是一只鱼我是一只鱼</p>', '1', '0', '0', '0000-00-00 00:00:00', '2018-03-12 16:06:57', '4');

-- ----------------------------
-- Table structure for b_flower
-- ----------------------------
DROP TABLE IF EXISTS `b_flower`;
CREATE TABLE `b_flower` (
  `f_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '//ID',
  `f_touser` varchar(20) NOT NULL COMMENT '//收花者',
  `f_fromuser` varchar(20) NOT NULL COMMENT '//送花着',
  `f_flower` mediumint(8) unsigned NOT NULL COMMENT '//花朵个数',
  `f_content` varchar(200) NOT NULL COMMENT '//感言',
  `f_date` datetime NOT NULL COMMENT '//时间',
  PRIMARY KEY (`f_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of b_flower
-- ----------------------------

-- ----------------------------
-- Table structure for b_friend
-- ----------------------------
DROP TABLE IF EXISTS `b_friend`;
CREATE TABLE `b_friend` (
  `f_id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '//ID',
  `f_fromuser` varchar(20) NOT NULL COMMENT '//添加的人',
  `f_touser` varchar(20) NOT NULL COMMENT '//被添加的好友',
  `f_content` varchar(200) NOT NULL COMMENT '//请求内容',
  `f_state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '//验证',
  `f_date` datetime NOT NULL COMMENT '//添加时间',
  PRIMARY KEY (`f_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of b_friend
-- ----------------------------
INSERT INTO `b_friend` VALUES ('1', '小鱼', '木木', '我非常想和你交朋友！', '1', '2018-03-12 14:18:34');
INSERT INTO `b_friend` VALUES ('2', '小鱼', '大鱼', '我非常想和你交朋友！', '1', '2018-03-12 14:58:38');

-- ----------------------------
-- Table structure for b_message
-- ----------------------------
DROP TABLE IF EXISTS `b_message`;
CREATE TABLE `b_message` (
  `m_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '//ID',
  `m_touser` varchar(20) NOT NULL COMMENT '//收信人',
  `m_fromuser` varchar(20) NOT NULL COMMENT '//发信人',
  `m_content` varchar(200) NOT NULL COMMENT '//发信内容',
  `m_state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '//短信状态',
  `m_date` datetime NOT NULL COMMENT '//发送时间',
  PRIMARY KEY (`m_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of b_message
-- ----------------------------
INSERT INTO `b_message` VALUES ('1', '木木', '小鱼', '哎呀 ，你今天怎么没发博客啊', '1', '2018-04-12 22:29:38');
INSERT INTO `b_message` VALUES ('2', '木木', '小鱼', '今天郑州下雨了，你那边下雨了吗？', '0', '2018-04-12 22:31:47');

-- ----------------------------
-- Table structure for b_system
-- ----------------------------
DROP TABLE IF EXISTS `b_system`;
CREATE TABLE `b_system` (
  `s_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '//ID',
  `s_webname` varchar(20) NOT NULL COMMENT '//网站名称',
  `s_skin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '//网站皮肤',
  `s_string` varchar(200) NOT NULL COMMENT '//网站敏感字符串',
  `s_code` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '//是否启用验证码',
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of b_system
-- ----------------------------
INSERT INTO `b_system` VALUES ('1', '涂鸦博客', '3', '他妈的|NND|草|操|垃圾|淫荡|贱货|SB|sb|jb|JB|法轮功|小泉', '1');

-- ----------------------------
-- Table structure for b_tags
-- ----------------------------
DROP TABLE IF EXISTS `b_tags`;
CREATE TABLE `b_tags` (
  `tag_id` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '//标签id',
  `tag_name` varchar(10) NOT NULL COMMENT '//标签名称',
  `tag_state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '标签状态 0-正常 1-异常',
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of b_tags
-- ----------------------------
INSERT INTO `b_tags` VALUES ('1', '物流', '0');
INSERT INTO `b_tags` VALUES ('2', '美容', '0');
INSERT INTO `b_tags` VALUES ('3', '兴趣', '0');
INSERT INTO `b_tags` VALUES ('4', '心情', '0');
INSERT INTO `b_tags` VALUES ('5', '爱好', '0');
INSERT INTO `b_tags` VALUES ('7', '技术', '0');
INSERT INTO `b_tags` VALUES ('8', 'PHP', '0');

-- ----------------------------
-- Table structure for b_user
-- ----------------------------
DROP TABLE IF EXISTS `b_user`;
CREATE TABLE `b_user` (
  `u_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '//用户自动编号',
  `u_state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '//用户状态  0-正常 1-异常',
  `u_username` varchar(20) NOT NULL COMMENT '//用户名',
  `u_password` char(40) NOT NULL COMMENT '//密码',
  `u_question` varchar(20) NOT NULL COMMENT '//密码提示',
  `u_answer` char(60) NOT NULL COMMENT '//密码回答',
  `u_email` varchar(40) DEFAULT NULL COMMENT '//邮件',
  `u_qq` varchar(10) DEFAULT NULL COMMENT '//QQ',
  `u_sex` char(1) NOT NULL COMMENT '//性别',
  `u_face` char(40) NOT NULL COMMENT '//头像',
  `u_level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '//会员等级',
  `u_post_time` varchar(20) NOT NULL DEFAULT '0' COMMENT '//发帖的时间戳',
  `u_article_time` varchar(20) NOT NULL DEFAULT '0' COMMENT '//回帖的时间戳',
  `u_reg_time` datetime NOT NULL COMMENT '//注册时间',
  `u_last_time` datetime NOT NULL COMMENT '//最后登录的时间',
  `u_last_ip` varchar(20) NOT NULL COMMENT '//最后登录的IP',
  `u_login_count` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '//登录次数',
  `u_pwdm` char(40) NOT NULL COMMENT '//未加密密码',
  `u_modify` datetime NOT NULL COMMENT '//修改时间',
  PRIMARY KEY (`u_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of b_user
-- ----------------------------
INSERT INTO `b_user` VALUES ('1', '0', '木鱼', 'e10adc3949ba59abbe56e057f20f883e', '我家狗狗', '花花', '110@qq.com', '132421', '男', 'face/chinajoy.jpg', '2', '1520219151', '1520586253', '2018-03-01 20:56:35', '2018-03-13 13:40:03', '::1', '56', '123456', '0000-00-00 00:00:00');
INSERT INTO `b_user` VALUES ('2', '0', '小鱼', 'e10adc3949ba59abbe56e057f20f883e', '我家的狗狗', '花花', '110@qq.com', '1111316904', '女', 'face/chinajoy.jpg', '0', '1520842017', '0', '2018-03-12 14:15:06', '2018-04-12 22:31:12', '::1', '3', '123456', '0000-00-00 00:00:00');
INSERT INTO `b_user` VALUES ('3', '0', '木木', 'e10adc3949ba59abbe56e057f20f883e', '我家的狗狗', '123456', '12334@qq.com', '33423131', '男', 'face/m17.gif', '0', '1520835440', '0', '2018-03-12 14:16:10', '2018-04-12 22:32:10', '::1', '6', '123456', '0000-00-00 00:00:00');
INSERT INTO `b_user` VALUES ('4', '0', '大鱼', 'e10adc3949ba59abbe56e057f20f883e', '我家的狗狗', '123456', '12334@qq.com', '33423131', '男', 'face/m09.gif', '0', '1520837869', '0', '2018-03-12 14:51:54', '2018-03-12 14:52:08', '::1', '1', '123456', '0000-00-00 00:00:00');
