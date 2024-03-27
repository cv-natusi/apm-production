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

 Date: 21/11/2022 11:53:05
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for mst_konterpoli
-- ----------------------------
DROP TABLE IF EXISTS `mst_konterpoli`;
CREATE TABLE `mst_konterpoli`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama_konterpoli` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `user_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mst_konterpoli
-- ----------------------------
INSERT INTO `mst_konterpoli` VALUES (1, 'Counter A', '2', 'http://192.168.2.111/develop/apm-rsu/public/display/konter_poli_a');
INSERT INTO `mst_konterpoli` VALUES (2, 'Counter B', '3', 'http://192.168.2.111/develop/apm-rsu/public/display/konter_poli_b');
INSERT INTO `mst_konterpoli` VALUES (3, 'Counter C1', '4', 'http://192.168.2.111/develop/apm-rsu/public/display/konter_poli_c1');
INSERT INTO `mst_konterpoli` VALUES (4, 'Counter C2', '5', 'http://192.168.2.111/develop/apm-rsu/public/display/konter_poli_c2');
INSERT INTO `mst_konterpoli` VALUES (5, 'Counter D', '6', 'http://192.168.2.111/develop/apm-rsu/public/display/konter_poli_d');
INSERT INTO `mst_konterpoli` VALUES (6, 'Counter E', '7', 'http://192.168.2.111/develop/apm-rsu/public/display/konter_poli_e');

SET FOREIGN_KEY_CHECKS = 1;
