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

 Date: 21/11/2022 11:31:30
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pemanggilan
-- ----------------------------
DROP TABLE IF EXISTS `pemanggilan`;
CREATE TABLE `pemanggilan`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `antrian_id` int(11) NOT NULL,
  `no_antrian` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` int(11) NOT NULL,
  `dari` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 50 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pemanggilan
-- ----------------------------
INSERT INTO `pemanggilan` VALUES (1, 435, 'B019', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (2, 430, 'B014', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (3, 430, 'B014', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (4, 431, 'B015', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (5, 436, 'B020', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (6, 431, 'B015', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (7, 430, 'B014', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (8, 437, 'B021', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (9, 443, 'B001', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (10, 444, 'B002', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (11, 446, 'B003', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (12, 450, 'B005', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (13, 443, 'B001', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (14, 455, 'B002', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (15, 458, 'B005', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (16, 456, 'B003', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (17, 457, 'B004', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (18, 459, 'L001', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (19, 460, 'L002', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (20, 455, 'B002', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (21, 458, 'B005', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (22, 461, 'B006', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (23, 462, 'B007', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (24, 456, 'B003', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (25, 455, 'E002', 0, 'Counter D');
INSERT INTO `pemanggilan` VALUES (26, 454, 'E001', 0, 'Counter D');
INSERT INTO `pemanggilan` VALUES (27, 456, 'C001', 0, 'Counter D');
INSERT INTO `pemanggilan` VALUES (28, 468, 'B013', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (29, 473, 'B018', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (30, 473, 'E005', 0, 'Counter D');
INSERT INTO `pemanggilan` VALUES (31, 461, 'B006', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (32, 461, 'B006', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (33, 461, 'B006', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (34, 461, 'B006', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (35, 461, 'B006', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (36, 461, 'B006', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (37, 465, 'E004', 0, 'Counter D');
INSERT INTO `pemanggilan` VALUES (38, 476, 'B020', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (39, 461, 'B006', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (40, 462, 'B007', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (41, 470, 'B015', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (42, 461, 'B006', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (43, 461, 'B006', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (44, 461, 'B006', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (45, 462, 'B007', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (46, 461, 'B006', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (47, 471, 'B016', 0, 'loket');
INSERT INTO `pemanggilan` VALUES (48, 479, 'B001', 1, 'loket');
INSERT INTO `pemanggilan` VALUES (49, 481, 'B003', 1, 'loket');

SET FOREIGN_KEY_CHECKS = 1;
