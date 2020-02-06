/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : swoole_api

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2020-02-06 16:32:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for video
-- ----------------------------
DROP TABLE IF EXISTS `video`;
CREATE TABLE `video` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  `cate_id` int(11) DEFAULT '0',
  `image` varchar(200) DEFAULT '',
  `url` varchar(200) DEFAULT '',
  `type` tinyint(7) DEFAULT '0',
  `content` text,
  `uploader` varchar(200) DEFAULT '',
  `status` tinyint(7) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of video
-- ----------------------------
INSERT INTO `video` VALUES ('1', '中国飞机开往菲律宾', '0', '', '', '0', null, '', '0', null, null);
INSERT INTO `video` VALUES ('2', '美国研发出来疫苗', '0', '', '', '0', null, '', '0', null, null);
INSERT INTO `video` VALUES ('3', '中国和美国世纪对决', '0', '', '', '0', null, '', '0', null, null);
