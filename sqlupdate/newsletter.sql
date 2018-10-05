/*
Navicat MySQL Data Transfer

Source Server         : MySQL
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : bluetour

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-11-10 14:42:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `newsletter`
-- ----------------------------
DROP TABLE IF EXISTS `newsletter`;
CREATE TABLE `newsletter` (
  `nel_id` int(11) NOT NULL AUTO_INCREMENT,
  `nel_name` varchar(255) NOT NULL,
  `nel_email` varchar(255) NOT NULL,
  `nel_active` tinyint(1) NOT NULL DEFAULT '0',
  `nel_date` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of newsletter
-- ----------------------------
