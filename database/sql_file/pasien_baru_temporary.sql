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

 Date: 21/11/2022 11:31:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pasien_baru_temporary
-- ----------------------------
DROP TABLE IF EXISTS `pasien_baru_temporary`;
CREATE TABLE `pasien_baru_temporary`  (
  `id_pas` bigint(20) NOT NULL AUTO_INCREMENT,
  `cust_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kodeUnik` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `isPasienBaru` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nik` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bpjs` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tanggalLahir` date NULL DEFAULT NULL,
  `no_hp` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `namaIbu` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tanggalPeriksa` date NOT NULL,
  `kodePoli` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `isGeriatri` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `caraBayar` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `masukMaster` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `masukTMCust` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nomor_referensi` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_dokter` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jam_praktek` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_rm` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_pas`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 135 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pasien_baru_temporary
-- ----------------------------
INSERT INTO `pasien_baru_temporary` VALUES (1, NULL, 'h1wLwe2', 'Y', '3518726738838887', NULL, 'Dwi Alim', '2022-10-28', NULL, 'ibu dwi', '2022-10-29', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (2, NULL, 'JurtyKd', 'Y', '3518726738838887', NULL, 'Dwi Alim', '2022-10-28', NULL, 'ibu dwi', '2022-10-30', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (3, NULL, 'rElegf4', 'Y', '1234567890426855', NULL, 'Gading Test', '1986-05-15', NULL, 'Anya', '2022-10-28', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (4, NULL, 'LxPj33C', 'Y', '2345234523452222', NULL, 'oky', '2021-10-28', NULL, 'yor', '2022-10-28', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (5, NULL, 'eQLx3kt', 'Y', '9234234872348829', NULL, 'oky', '2021-10-28', NULL, 'yor', '2022-10-28', '102', 'N', 'UMUM', 'sudah', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (6, NULL, '4NYWBm9', 'N', NULL, NULL, NULL, NULL, '085456258753', NULL, '2022-10-31', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (7, NULL, 'wVqFG02', 'Y', '8723848234823482', NULL, 'oky', '2003-10-31', NULL, 'ibuu', '2022-10-31', '111', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (8, NULL, 'EEEZsbB', 'Y', '8234992340023493', NULL, 'oky', '2022-10-10', NULL, 'ibu', '2022-10-31', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (9, NULL, '1HFzOf3', 'N', NULL, NULL, NULL, NULL, '085963214789', NULL, '2022-10-31', '102', 'N', 'BPJS', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (10, NULL, 'XwA8aGE', 'N', NULL, NULL, NULL, NULL, '085632147896', NULL, '2022-10-31', '102', 'N', 'BPJS', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (11, NULL, 'oxUpYcV', 'N', NULL, NULL, NULL, NULL, '0857693369847', NULL, '2022-10-31', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (12, NULL, 'ZXZqYgL', 'N', NULL, NULL, NULL, NULL, '089645321789', NULL, '2022-10-31', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (13, NULL, 'j7mDMvd', 'N', NULL, NULL, NULL, NULL, '089654789321', NULL, '2022-10-31', '0', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (14, NULL, 'huZ7qyo', 'N', NULL, NULL, NULL, NULL, '089632147852', NULL, '2022-10-31', '0', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (15, NULL, 'YfiUAK3', 'N', NULL, NULL, NULL, NULL, '089632147895', NULL, '2022-10-31', '0', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (16, NULL, '556NT3r', 'N', NULL, NULL, NULL, NULL, '089654123985', NULL, '2022-10-31', '0', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (17, NULL, 'KegyEXJ', 'N', NULL, NULL, NULL, NULL, '089563214756', NULL, '2022-10-31', '0', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (18, NULL, 'gF8hdux', 'Y', '8765875432123456', NULL, 'ini coba', '2001-11-01', NULL, 'ibu', '2022-11-01', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (19, NULL, 'cAMbsRI', 'N', NULL, NULL, NULL, NULL, '089898654456', NULL, '2022-11-01', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (20, NULL, '3CJxx68', 'Y', '2564896532547896', NULL, 'Yantooo', '2022-11-01', NULL, 'Tsunadeee', '2022-11-01', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (21, NULL, 'xmzU2C7', 'N', NULL, NULL, NULL, NULL, '036258974156', NULL, '2022-11-02', '102', 'N', 'BPJS', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (22, NULL, '4C5JyVT', 'Y', '2145632589657485', NULL, 'Yanto', '1990-11-02', NULL, 'Sumiah', '2022-11-02', '102', 'N', 'UMUM', 'sudah', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (23, NULL, 'goiv40x', 'N', NULL, NULL, NULL, NULL, '089652314578', NULL, '2022-11-02', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (24, NULL, 'QuuKN8D', 'N', NULL, NULL, NULL, NULL, '069874563263', NULL, '2022-11-02', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (25, NULL, '1DYtl3p', 'N', NULL, NULL, NULL, NULL, '086254123658', NULL, '2022-11-02', '102', 'N', 'UMUM', 'sudah', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (26, NULL, 'Gkml8wo', 'N', NULL, NULL, NULL, NULL, '6285111222333', NULL, '2022-11-02', '111', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (27, NULL, 'tDi0gpz', 'N', NULL, NULL, NULL, NULL, '089654235698785', NULL, '2022-11-03', '102', 'N', 'UMUM', 'sudah', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (28, NULL, 'RrbMoc7', 'N', NULL, NULL, NULL, NULL, '084563214569', NULL, '2022-11-02', '112', 'N', 'BPJS', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (29, NULL, 'xEiX72R', 'N', NULL, NULL, NULL, NULL, '0789456123456', NULL, '2022-11-09', '125', 'N', 'BPJS', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (30, NULL, 'YFV2lRg', 'Y', '1293129312912223', NULL, 'pasien', '2014-11-02', NULL, 'ibu pasien', '2022-11-02', '102', 'N', 'UMUM', 'belum', '', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (31, NULL, 'RVND8Pj', 'Y', '2394293493294293', NULL, 'pasien baru', '2000-11-02', NULL, 'ibu kandung pasien baru', '2022-11-02', '125', 'N', 'UMUM', 'belum', '', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (32, NULL, 'ZP7LFDq', 'Y', '9234823482349231', NULL, 'coba download gambar', '2010-11-02', NULL, 'ibu kandung', '2022-11-02', '102', 'N', 'UMUM', 'belum', '', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (33, NULL, 'IcrBeVr', 'N', '8123913640012311', NULL, NULL, NULL, '0548963', NULL, '2022-11-02', '102', 'N', 'BPJS', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (34, NULL, '09bHVXe', 'Y', '8123912930012311', NULL, 'pasien', '2022-11-02', NULL, 'ibu kandung pasien', '2022-11-02', '102', 'N', 'UMUM', 'belum', '', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (35, NULL, 'riccGE1', 'Y', '8234923492323433', NULL, 'Trian Oky', '2002-09-10', NULL, 'ibu kandung', '2022-11-03', '114', 'N', 'UMUM', 'belum', '', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (36, NULL, 'ZiPVYDN', 'Y', '2934523452304952', NULL, 'Trian Oky', '2002-09-08', NULL, 'ibu kandung', '2022-11-03', '111', 'N', 'UMUM', 'belum', '', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (38, NULL, 'egsW9hV', 'Y', '1234567876876855', NULL, 'Admin', '2001-10-27', NULL, 'Ibu Admin', '2022-11-03', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (39, NULL, 'Nxl6uxM', 'Y', '9123912312309123', NULL, 'Oky', '2002-09-17', NULL, 'ibu kandung', '2022-11-03', '115', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (40, NULL, 'g5kAVpA', 'Y', '0983456783459087', NULL, 'Oky Test', '1986-05-15', NULL, 'Anya', '2022-11-04', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (41, NULL, 'k8F12sL', 'Y', '0123123787345933', NULL, 'nama pasien', '2001-09-12', NULL, 'ibu kandung pasien', '2022-11-03', '106', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (42, NULL, 'lLRRWxs', 'Y', '0123123787345933', NULL, 'Oky Test', '1986-05-15', NULL, 'Anya', '2022-11-04', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (43, NULL, 'Hi85ICt', 'Y', '0123123787345933', NULL, 'Oky Test', '1986-05-15', NULL, 'Anya', '2022-11-03', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (59, NULL, 'ncEILGR', 'N', '1234348790426265', NULL, 'Admin', NULL, '081667762736', NULL, '2022-11-03', '102', 'N', 'ASURANSILAIN', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (60, NULL, '2jBnYte', 'N', '5757575757574848', NULL, NULL, NULL, '089654123658', NULL, '2022-11-04', '113', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (61, NULL, 'jPYLKkt', 'N', '5757575757574848', NULL, NULL, NULL, '085789456123', NULL, '2022-11-03', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (62, NULL, 'ZPxLNT1', 'N', '5757575757574848', NULL, 'GINAH', NULL, '085264978312', NULL, '2022-11-05', '106', 'N', 'BPJS', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (63, NULL, 'YHDCMLh', 'N', '5757575757574848', NULL, 'GINAH', NULL, '08965412365489', NULL, '2022-11-06', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (64, NULL, 'VLDCS2L', 'N', '5757575757574848', NULL, 'GINAH', NULL, '541515665', NULL, '2022-11-07', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (65, NULL, 'vmsHstP', 'Y', '0000000000000000', NULL, 'BAYI BR LAHIR', '2017-04-25', NULL, 'Ibu Pasien', '2022-11-03', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (66, NULL, 'EUi5KMh', 'N', '2349843794857645', NULL, 'ZULIATIN', NULL, '08234772384', NULL, '2022-11-04', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (67, NULL, 'nkVQEiP', 'N', '5757575757574848', NULL, 'GINAH', NULL, '54848485', NULL, '2022-11-09', '102', 'N', 'ASURANSILAIN', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (68, NULL, '3rWPcSJ', 'N', '9234568904325678', NULL, 'ZULIATIN', NULL, '00000000000', NULL, '2022-11-08', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (69, NULL, 'GcsDnAU', 'Y', '0000000000000000', NULL, 'BAYI BR LAHIR', '2017-04-25', NULL, 'ibu kandung', '2022-11-09', '102', 'N', 'UMUM', 'belum', 'belum', NULL, NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (70, NULL, '49vxqYR', 'Y', '7896545264152368', NULL, 'Juned', '1988-05-15', NULL, 'Ibu Juned', '2022-11-10', '102', 'N', 'UMUM', 'belum', 'belum', NULL, '16474', NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (71, NULL, 'FwUUEdn', 'Y', '0000000000000000', NULL, 'BAYI BR LAHIR', '2017-04-25', NULL, 'ibu kandung', '2022-11-10', '107', 'N', 'UMUM', 'belum', 'belum', NULL, '428636', NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (72, NULL, '5cQlzct', 'Y', '7896545264152399', NULL, 'JunedNew', '1988-05-15', NULL, 'Ibu JunedNew', '2022-11-10', '102', 'N', 'UMUM', 'belum', 'belum', NULL, '16474', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (73, NULL, 'lwpqCdI', 'Y', '7896545264152666', NULL, 'JunedNew', '1988-05-15', NULL, 'Ibu JunedNew', '2022-11-10', '102', 'N', 'UMUM', 'belum', 'belum', NULL, '16474', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (74, NULL, '4J3ZVTh', 'Y', '4563214562666666', NULL, 'JunedNew', '1988-05-15', NULL, 'Ibu JunedNew', '2022-11-10', '102', 'N', 'UMUM', 'belum', 'belum', NULL, '286404', '08:00-13:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (77, NULL, 's1AwNjL', 'Y', '3517191209930003', '0002067528802', 'VEKKY TRA ANGGARA', '1993-09-12', NULL, 'ibu kandung vekky', '2022-11-10', '102', 'N', 'BPJS', 'belum', 'belum', '0195R0530922B000006', '286404', '08:00-13:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (78, NULL, 'MqlDYG8', 'Y', '8745839845783475', '', 'waku waku', '2015-11-10', NULL, 'yor sama', '2022-11-10', '107', 'N', 'UMUM', 'belum', 'belum', '', '430181', '13:00-16:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (79, NULL, 'MBSJHWs', 'Y', '2389848392949845', '', 'oky boy', '2010-11-10', NULL, 'ibunya oky boy', '2022-11-10', '107', 'N', 'UMUM', 'belum', 'belum', '', '16474', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (80, NULL, 'NJz8hmd', 'Y', '0124375689764512', '', 'Test QR', '2022-11-11', NULL, 'Ibu QR', '2022-11-11', '107', 'N', 'UMUM', 'belum', 'belum', '', '16474', '07:30-11:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (81, NULL, 'WacujQj', 'N', '3517191209930003', '0002067528802', 'GINAH', NULL, '000000000000', NULL, '2022-11-11', '107', 'N', 'BPJS', 'belum', 'belum', '0195R0530922B000006', '16474', '07:30-11:00', 'W1811289871');
INSERT INTO `pasien_baru_temporary` VALUES (82, NULL, 'JTUSkBR', 'N', '3517191209930003', '0002067528802', 'VEKKY TRA ANGGARA', NULL, '000000000000', NULL, '2022-11-12', '107', 'N', 'BPJS', 'belum', 'belum', '0195R0530922B000006', '16474', '07:30-12:00', 'W1811289871');
INSERT INTO `pasien_baru_temporary` VALUES (83, NULL, 'fVKWJWr', 'N', '3517191209930003', NULL, 'VEKKY TRA ANGGARA', NULL, '000000000000', NULL, '2022-11-14', '107', 'N', 'UMUM', 'belum', 'belum', '', '16474', '07:30-14:00', 'W1811289871');
INSERT INTO `pasien_baru_temporary` VALUES (84, NULL, 'vGnxikK', 'Y', '0000000000000000', '', 'BAYI BR LAHIR', '2017-04-25', NULL, 'ibu kandung', '2022-11-12', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-12:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (85, NULL, 'Yi4sAbh', 'Y', '6789456780432456', '', 'Gani Gani', '2022-11-12', NULL, 'ibu gani', '2022-11-12', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-12:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (86, NULL, 'Q2wC4h6', 'Y', '6786546578968765', '', 'bangun tidur selfi', '2022-11-12', NULL, 'wkwk', '2022-11-12', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-12:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (87, NULL, 'KRYja7y', 'Y', '8764789834759846', '', 'asede', '2022-11-12', NULL, 'aowkoakw', '2022-11-12', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-12:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (88, NULL, 'ZYhQr6J', 'Y', '6567897654345678', '', 'caba', '2022-11-12', NULL, 'coba', '2022-11-12', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-12:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (89, NULL, 'TTTTTek', 'Y', '1928384756222299', '', 'Cuma Testing', '2022-11-12', NULL, 'ibu', '2022-11-12', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-12:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (90, NULL, 'mWViBeq', 'Y', '8787986765438756', '', 'pasien 1', '2022-11-12', NULL, 'ibu pasien', '2022-11-12', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-12:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (91, NULL, 'nKeYhqx', 'Y', '9873456783459863', '', 'pasien 2 harus e berhasil', '2022-11-12', NULL, 'harus berhasil', '2022-11-12', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-12:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (92, NULL, 'xKa2dum', 'Y', '7634567893458987', '', 'berhasil cuy', '2022-11-12', NULL, 'hahaha dikejar detlen', '2022-11-12', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-12:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (93, NULL, 'KKKKEES', 'Y', '7865437895677666', '', 'ternyata kurang validasi', '2022-11-12', NULL, 'wkwkw', '2022-11-12', '102', 'N', 'UMUM', 'sudah', 'belum', '', '286404', '08:00-13:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (94, NULL, 'TByfKMI', 'Y', '0987657898765643', '', 'coba scan', '2022-11-12', NULL, 'coba scan', '2022-11-12', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-12:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (95, NULL, 'jZgiZYW', 'Y', '9878674983459857', '', 'simapan', '2018-11-14', NULL, 'tes', '2022-11-14', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (96, NULL, 'UNIKBBB', 'Y', '8374562387669123', '', 'PASIEN TEST', '2018-11-14', NULL, 'ibu PASIEN TEST', '2022-11-14', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (97, NULL, 'dtRCraX', 'Y', '0934853458764327', '', 'Trian Oky', '2003-11-15', NULL, 'Ibu Oky', '2022-11-15', '108', 'N', 'UMUM', 'belum', 'belum', '', '16573', '15:30-17:30', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (98, NULL, 'Bllrnyq', 'Y', '9847847567894678', '', 'trian oky', '2003-11-15', NULL, 'oky', '2022-11-15', '107', 'N', 'UMUM', 'belum', 'belum', '', '428636', '08:00-13:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (99, NULL, '809TIIR', 'Y', '8765438768769874', '', 'oky', '2015-11-15', NULL, 'oky', '2022-11-15', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16476', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (100, NULL, 'IHB1SaY', 'Y', '3517191209930003', '0002067528802', 'VEKKY TRA ANGGARA', '1993-09-12', NULL, 'ibu vekky', '2022-11-15', '107', 'N', 'BPJS', 'sudah', 'belum', '0195R0530922B000006', '16476', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (101, NULL, 'sAcbdYk', 'Y', '1234567890123456', '', 'ANDIS', '1981-06-01', NULL, 'ibu andis', '2022-11-16', '110', 'N', 'ASURANSILAIN', 'sudah', 'belum', '', '16526', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (102, NULL, 'J1vmnW8', 'N', '35780461055800041', NULL, 'Heru Rohmadi', NULL, '085730476967', NULL, '2022-11-16', '119', 'N', 'ASURANSILAIN', 'belum', 'belum', '', NULL, NULL, NULL);
INSERT INTO `pasien_baru_temporary` VALUES (103, NULL, 'YtxjFrV', 'Y', '3515045803950002', '', 'Nayana', '2000-11-16', NULL, 'Ibu Saya', '2022-11-16', '111', 'N', 'UMUM', 'belum', 'belum', '', '16496', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (104, NULL, 'VTC0n4s', 'Y', '1111115555111111', '', 'Arif Budiman S', '2022-11-16', NULL, 'mistiah', '2022-11-16', '107', 'N', 'UMUM', 'belum', 'belum', '', '428636', '08:00-13:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (105, NULL, 'M6r1aVY', 'Y', '3525045803950002', '', 'Yana', '1999-11-16', NULL, 'Ibu Saya', '2022-11-16', '101', 'N', 'UMUM', 'sudah', 'belum', '', '16461', '08:00-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (106, NULL, '7BIjaW6', 'Y', '1234567891234571', '', 'yulia', '2022-11-03', NULL, 'yunj', '2022-11-16', '107', 'N', 'UMUM', 'belum', 'belum', '', '428636', '08:00-13:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (107, NULL, 'r2U8aZw', 'Y', '1234567890123467', '', 'DARREL FASYA ALRASYID', '2012-03-02', NULL, 'Ibu Darrel', '2022-11-16', '112', 'N', 'UMUM', 'belum', 'belum', '', '16500', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (108, NULL, '2pSgqeE', 'N', '35780461055800041', NULL, 'Heru Rohmadi', NULL, '000000000000', NULL, '2022-11-17', '111', 'N', 'UMUM', 'belum', 'belum', '', '16496', '07:30-14:00', 'w2211364362');
INSERT INTO `pasien_baru_temporary` VALUES (109, NULL, 'Ysk2XZT', 'N', '35780461055800041', NULL, 'Heru Rohmadi', NULL, '000000000000', NULL, '2022-11-18', '115', 'N', 'ASURANSILAIN', 'belum', 'belum', '', '16605', '07:30-11:00', 'w2211364362');
INSERT INTO `pasien_baru_temporary` VALUES (110, NULL, 'LiAF5l6', 'Y', '1234567890123654', '', 'Arif Rakhman Hadi', '2022-11-01', NULL, 'misriah', '2022-11-16', '107', 'N', 'UMUM', 'belum', 'belum', '', '428636', '08:00-13:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (111, NULL, 'u862Dmn', 'Y', '3517191209930003', '0002067528802', 'VEKKY TRA ANGGARA', '1993-09-12', NULL, 'misriah', '2022-11-16', '107', 'N', 'BPJS', 'sudah', 'belum', '0195R0530922B000006', '16476', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (112, NULL, 'H4pzLGV', 'N', '35780461055800041', NULL, 'Heru Rohmadi', NULL, '000000000000', NULL, '2022-11-19', '108', 'N', 'UMUM', 'belum', 'belum', '', '291869', '07:30-14:30', 'w2211364362');
INSERT INTO `pasien_baru_temporary` VALUES (113, NULL, 'MYCsIIf', 'N', '1234567899876543', NULL, 'FIFI NOVIASANTI', NULL, '000000000000', NULL, '2022-11-16', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16476', '07:30-14:00', 'S1212000275');
INSERT INTO `pasien_baru_temporary` VALUES (114, NULL, '6dGn7Gs', 'Y', '8734769873458734', '', 'pasien', '2011-11-16', NULL, 'pasien 1', '2022-11-16', '107', 'N', 'UMUM', 'belum', 'belum', '', '16472', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (115, NULL, 'uGjL99D', 'N', '1234567899876543', NULL, 'FIFI NOVIASANTI', NULL, '000000000000', NULL, '2022-11-17', '115', 'Y', 'ASURANSILAIN', 'belum', 'belum', '', '16605', '07:30-14:00', 'S1212000275');
INSERT INTO `pasien_baru_temporary` VALUES (116, NULL, 'rRmisMn', 'N', '1234567899876543', NULL, 'FIFI NOVIASANTI', NULL, '000000000000', NULL, '2022-11-18', '107', 'Y', 'ASURANSILAIN', 'belum', 'belum', '', '16476', '07:30-14:00', 'S1212000275');
INSERT INTO `pasien_baru_temporary` VALUES (117, NULL, 'Xq1zgpV', 'N', '1234567899876543', NULL, 'FIFI NOVIASANTI', NULL, '000000000000', NULL, '2022-11-19', '109', 'Y', 'UMUM', 'belum', 'belum', '', '16507', '07:30-12:00', 'S1212000275');
INSERT INTO `pasien_baru_temporary` VALUES (118, NULL, '7GQhqaF', 'Y', '8734567893457876', '', 'pasien 1', '2010-11-16', NULL, 'ibu pasien 1', '2022-11-16', '107', 'N', 'UMUM', 'belum', 'belum', '', '16476', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (119, NULL, 'CKiTAdb', 'Y', '9515045803950002', '', 'Pasmar', '1999-11-16', NULL, 'Ibu Pasmar', '2022-11-16', '102', 'N', 'UMUM', 'sudah', 'belum', '', '286404', '08:00-13:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (120, NULL, 'UcS5N7c', 'Y', '8734587543257898', '', 'oky', '2013-11-16', NULL, 'ibu oky', '2022-11-16', '107', 'N', 'UMUM', 'belum', 'belum', '', '16476', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (121, NULL, '6Pl778a', 'Y', '6515045803950006', '', 'Pasmar 2', '1999-11-16', NULL, 'Ibu PM2', '2022-11-16', '107', 'N', 'UMUM', 'belum', 'belum', '', '16472', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (122, NULL, '4jIbJb1', 'Y', '1364978526497562', '', 'oky coba', '2000-11-16', NULL, 'oky', '2022-11-16', '107', 'N', 'UMUM', 'belum', 'belum', '', '16474', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (123, NULL, 'iiy9tuj', 'Y', '9458239423409324', '', 'oky', '2020-11-16', NULL, 'ibunya oky', '2022-11-16', '107', 'N', 'UMUM', 'belum', 'belum', '', '16474', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (124, NULL, 'PQu1kql', 'Y', '3517191209930003', '0002067528802', 'VEKKY TRA ANGGARA', '1993-09-12', NULL, 'yunike', '2022-11-17', '107', 'N', 'BPJS', 'belum', 'belum', '0195R0530922B000006', '16476', '', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (125, NULL, 'tGAE4TS', 'Y', '8234293482349984', '', 'pasien', '2022-11-16', NULL, 'ibu', '2022-11-16', '102', 'N', 'UMUM', 'belum', 'belum', '', '16474', '', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (126, NULL, 'r4Z1e7v', 'Y', '3578046105580004', '0002083756476', 'SRI HARYATI', '1958-05-21', NULL, 'misriah', '2022-11-21', '107', 'N', 'BPJS', 'belum', 'belum', '0195R0530922B000007', '16474', '', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (127, NULL, 'mMD5FRb', 'Y', '3578046105580004', '0002083756476', 'SRI HARYATI', '1958-05-21', NULL, 'misriah ', '2022-11-16', '107', 'N', 'BPJS', 'sudah', 'belum', '0195R0530922B000007', '16476', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (128, NULL, '1nbXBke', 'Y', '3522162804860002', '0002047171994', 'AKHMAD MUKHAYAN', '1986-04-28', NULL, 'ibu kandung', '2022-11-17', '107', 'N', 'BPJS', 'sudah', 'belum', '0195R0530922B000008', '16472', '07:30-14:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (129, NULL, 'NcHA98i', 'Y', '1245780986532659', '', 'YONO', '2022-11-18', NULL, 'IBU YONO', '2022-11-18', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-11:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (130, NULL, 'qkcaxvJ', 'Y', '3333336666222598', '', 'YONO2', '2022-11-18', NULL, 'IBU YONO', '2022-11-18', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-11:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (131, NULL, '0hzb80r', 'Y', '1236589098653254', '', 'YONO3', '2022-11-18', NULL, 'YANTO', '2022-11-18', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-11:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (132, NULL, 'SuuYfhz', 'Y', '3636369865325698', '', 'OKE TEST', '2022-11-18', NULL, 'IBU OKE', '2022-11-18', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-11:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (133, NULL, 'RWus9eQ', 'Y', '3164275481956235', '', 'TEST', '2022-11-18', NULL, 'IBU TEST', '2022-11-18', '107', 'N', 'UMUM', 'sudah', 'belum', '', '16474', '07:30-11:00', NULL);
INSERT INTO `pasien_baru_temporary` VALUES (134, NULL, 'QC8GaFB', 'Y', '3527081010000004', '', 'Agung Pratama', '2000-10-10', NULL, 'Hasanah', '2022-11-18', '118', 'N', 'UMUM', 'sudah', 'belum', '', '16498', '07:30-11:00', NULL);

SET FOREIGN_KEY_CHECKS = 1;
