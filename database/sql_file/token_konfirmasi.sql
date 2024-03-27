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

 Date: 21/11/2022 11:31:59
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for token_konfirmasi
-- ----------------------------
DROP TABLE IF EXISTS `token_konfirmasi`;
CREATE TABLE `token_konfirmasi`  (
  `id_token` bigint(20) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tanggal_token` date NOT NULL,
  `pasien_baru_temporary_id` bigint(20) NULL DEFAULT NULL,
  `sudah_print` int(11) NOT NULL COMMENT '0=belum, 1=sudah',
  `status` int(11) NOT NULL COMMENT '0=belum, 1=sudah',
  `created_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_token`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 52 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of token_konfirmasi
-- ----------------------------
INSERT INTO `token_konfirmasi` VALUES (7, 'ODskR71SqokHaqxz', '2022-11-08', 5, 0, 1, '2022-11-08 17:05:35');
INSERT INTO `token_konfirmasi` VALUES (8, 'Q0KEU3Wan4ZAaCGP', '2022-11-08', 6, 0, 1, '2022-11-08 17:05:53');
INSERT INTO `token_konfirmasi` VALUES (9, 'HhEgjk0QFISuLGwP', '2022-11-08', 7, 0, 1, '2022-11-08 17:09:40');
INSERT INTO `token_konfirmasi` VALUES (10, '0nlaWRu5MGn8Qeo4', '2022-11-08', 8, 0, 1, '2022-11-08 17:11:47');
INSERT INTO `token_konfirmasi` VALUES (11, 'aa7nQ35zFR34pDiM', '2022-11-08', 77, 0, 1, '2022-11-08 17:22:09');
INSERT INTO `token_konfirmasi` VALUES (35, 'pyjHItH2pKjMBcAi', '2022-11-10', 80, 0, 1, '2022-11-10 16:36:48');
INSERT INTO `token_konfirmasi` VALUES (36, 'ceoLJQOlOyP4uMBV', '2022-11-11', 84, 1, 1, '2022-11-11 09:06:19');
INSERT INTO `token_konfirmasi` VALUES (38, 'jWVgISjE04TeQoVe', '2022-11-12', 90, 1, 1, '2022-11-12 04:19:04');
INSERT INTO `token_konfirmasi` VALUES (39, 'j35Yw3keAfHudgv1', '2022-11-12', 91, 1, 1, '2022-11-12 07:31:06');
INSERT INTO `token_konfirmasi` VALUES (40, 'umkD8eePldvQgUgm', '2022-11-12', 94, 1, 1, '2022-11-12 07:45:26');
INSERT INTO `token_konfirmasi` VALUES (41, 'P0EhqSmKlX95GP17', '2022-11-12', 95, 1, 1, '2022-11-12 07:57:41');
INSERT INTO `token_konfirmasi` VALUES (42, 'DL4nyQorEfw7QtAd', '2022-11-14', 96, 1, 1, '2022-11-14 03:12:10');
INSERT INTO `token_konfirmasi` VALUES (43, 'I5Tm4p9jySULgFkr', '2022-11-14', 99, 1, 1, '2022-11-14 03:15:52');
INSERT INTO `token_konfirmasi` VALUES (44, 'LsNKK0YY0kg9GoR8', '2022-11-15', 100, 1, 1, '2022-11-15 06:56:10');
INSERT INTO `token_konfirmasi` VALUES (45, 'yeK54HZJMvo0n4SY', '2022-11-15', 101, 1, 1, '2022-11-15 07:01:07');
INSERT INTO `token_konfirmasi` VALUES (46, 'VVgkMfR4mvhkhTjd', '2022-11-16', 111, 1, 1, '2022-11-16 08:55:39');
INSERT INTO `token_konfirmasi` VALUES (47, 'DiqFwo0pND4cBI5u', '2022-11-16', 113, 1, 1, '2022-11-16 10:26:15');
INSERT INTO `token_konfirmasi` VALUES (48, 'Pf6HdhhADKJtEKc1', '2022-11-16', 119, 1, 1, '2022-11-16 10:34:24');
INSERT INTO `token_konfirmasi` VALUES (49, 'sGGjaC9UODWEl9dY', '2022-11-16', 127, 1, 1, '2022-11-16 11:21:12');
INSERT INTO `token_konfirmasi` VALUES (50, '7rDfJgbQmcaCj3tb', '2022-11-16', 134, 1, 1, '2022-11-16 12:21:28');
INSERT INTO `token_konfirmasi` VALUES (51, '4GvCcjzuhRgLBKkz', '2022-11-18', NULL, 0, 0, '2022-11-18 12:41:06');

SET FOREIGN_KEY_CHECKS = 1;
