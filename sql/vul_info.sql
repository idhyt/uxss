/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : vul_info

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-04-16 09:23:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for uxss
-- ----------------------------
DROP TABLE IF EXISTS `uxss`;
CREATE TABLE `uxss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `poc_1` tinyint(1) NOT NULL,
  `poc_2` tinyint(1) NOT NULL,
  `poc_3` tinyint(1) NOT NULL,
  `poc_4` tinyint(1) NOT NULL,
  `poc_5` tinyint(1) NOT NULL,
  `poc_6` tinyint(1) NOT NULL,
  `user_agent` text NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
