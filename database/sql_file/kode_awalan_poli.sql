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

 Date: 21/11/2022 11:31:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for kode_awalan_poli
-- ----------------------------
DROP TABLE IF EXISTS `kode_awalan_poli`;
CREATE TABLE `kode_awalan_poli`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kdpoli` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kdpoli_rs` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_awal` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kode_awalan_poli
-- ----------------------------
INSERT INTO `kode_awalan_poli` VALUES (1, 'ORT', '101', 'A');
INSERT INTO `kode_awalan_poli` VALUES (2, 'JIW', '102', 'C');
INSERT INTO `kode_awalan_poli` VALUES (3, 'GIG', '106', 'D');
INSERT INTO `kode_awalan_poli` VALUES (4, 'BDM', '106', 'D');
INSERT INTO `kode_awalan_poli` VALUES (5, 'KON', '106', 'D');
INSERT INTO `kode_awalan_poli` VALUES (6, 'GND', '106', 'D');
INSERT INTO `kode_awalan_poli` VALUES (7, 'ANA', '107', 'E');
INSERT INTO `kode_awalan_poli` VALUES (8, 'BED', '108', 'F');
INSERT INTO `kode_awalan_poli` VALUES (9, 'JAN', '109', 'G');
INSERT INTO `kode_awalan_poli` VALUES (10, 'KLT', '110', 'H');
INSERT INTO `kode_awalan_poli` VALUES (11, 'MAT', '111', 'I');
INSERT INTO `kode_awalan_poli` VALUES (12, 'SAR', '112', 'J');
INSERT INTO `kode_awalan_poli` VALUES (13, 'OBG', '113', 'K');
INSERT INTO `kode_awalan_poli` VALUES (14, 'UGD', '114', 'M');
INSERT INTO `kode_awalan_poli` VALUES (15, 'PAR', '115', 'N');
INSERT INTO `kode_awalan_poli` VALUES (16, 'INT', '116', 'O');
INSERT INTO `kode_awalan_poli` VALUES (17, 'IRM', '117', 'P');
INSERT INTO `kode_awalan_poli` VALUES (18, 'THT', '118', 'Q');
INSERT INTO `kode_awalan_poli` VALUES (19, 'URO', '119', 'R');
INSERT INTO `kode_awalan_poli` VALUES (20, 'BSY', '120', 'S');
INSERT INTO `kode_awalan_poli` VALUES (21, 'HDL', '121', 'TT');
INSERT INTO `kode_awalan_poli` VALUES (22, 'GIZ', '123', 'U');
INSERT INTO `kode_awalan_poli` VALUES (23, 'ALG', '124', 'V');
INSERT INTO `kode_awalan_poli` VALUES (24, 'ANU', '125', 'W');
INSERT INTO `kode_awalan_poli` VALUES (25, 'AKP', '126', 'Z');
INSERT INTO `kode_awalan_poli` VALUES (27, 'BDP', '131', 'AA');
INSERT INTO `kode_awalan_poli` VALUES (28, '017', '134', 'AB');
INSERT INTO `kode_awalan_poli` VALUES (29, '040', '137', 'AD');

SET FOREIGN_KEY_CHECKS = 1;
