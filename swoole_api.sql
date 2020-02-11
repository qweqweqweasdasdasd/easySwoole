/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : swoole_api

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2020-02-11 13:19:55
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
  `video_id` varchar(150) DEFAULT '',
  `duration` int(50) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of video
-- ----------------------------
INSERT INTO `video` VALUES ('1', '中国飞机开往菲律宾', '1', '', '', '0', null, '', '1', '1581302113', null, null, '120');
INSERT INTO `video` VALUES ('2', '美国研发出来疫苗', '2', '', '', '0', null, '', '1', '1581302113', null, null, '150');
INSERT INTO `video` VALUES ('3', '中国和美国世纪对决', '1', '', '', '0', null, '', '1', '1581302113', null, null, '90');
INSERT INTO `video` VALUES ('4', '日本是一个强大的国家', '1', '', '', '0', null, '', '1', '1581302113', null, '', '110');
INSERT INTO `video` VALUES ('5', '中国是一个专职的国家', '2', '', '', '0', null, '', '1', '1581302113', null, '', '220');
