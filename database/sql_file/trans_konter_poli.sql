/*
 Navicat Premium Data Transfer

 Source Server         : stefanus
 Source Server Type    : MySQL
 Source Server Version : 50733
 Source Host           : 192.168.2.111:3306
 Source Schema         : natusi_apm

 Target Server Type    : MySQL
 Target Server Version : 50733
 File Encoding         : 65001

 Date: 21/11/2022 11:32:12
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for trans_konter_poli
-- ----------------------------
DROP TABLE IF EXISTS `trans_konter_poli`;
CREATE TABLE `trans_konter_poli`  (
  `id_trans_konter_poli` bigint(20) NOT NULL AUTO_INCREMENT,
  `konter_poli_id` int(11) NULL DEFAULT NULL,
  `poli_id` bigint(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id_trans_konter_poli`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of trans_konter_poli
-- ----------------------------
INSERT INTO `trans_konter_poli` VALUES (1, 1, 110);
INSERT INTO `trans_konter_poli` VALUES (2, 1, 116);
INSERT INTO `trans_konter_poli` VALUES (3, 2, 101);
INSERT INTO `trans_konter_poli` VALUES (4, 2, 108);
INSERT INTO `trans_konter_poli` VALUES (5, 2, 113);
INSERT INTO `trans_konter_poli` VALUES (6, 2, 119);
INSERT INTO `trans_konter_poli` VALUES (7, 2, 120);
INSERT INTO `trans_konter_poli` VALUES (8, 2, 134);
INSERT INTO `trans_konter_poli` VALUES (9, 3, 117);
INSERT INTO `trans_konter_poli` VALUES (10, 4, 112);
INSERT INTO `trans_konter_poli` VALUES (11, 4, 115);
INSERT INTO `trans_konter_poli` VALUES (12, 4, 123);
INSERT INTO `trans_konter_poli` VALUES (13, 4, 126);
INSERT INTO `trans_konter_poli` VALUES (14, 5, 102);
INSERT INTO `trans_konter_poli` VALUES (15, 5, 107);
INSERT INTO `trans_konter_poli` VALUES (16, 5, 111);
INSERT INTO `trans_konter_poli` VALUES (17, 5, 118);
INSERT INTO `trans_konter_poli` VALUES (18, 6, 106);

SET FOREIGN_KEY_CHECKS = 1;
