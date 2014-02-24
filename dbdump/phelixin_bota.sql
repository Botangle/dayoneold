-- phpMyAdmin SQL Dump
-- version 2.8.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: custsql-ipg27.eigbox.net
-- Generation Time: Feb 19, 2014 at 12:50 AM
-- Server version: 5.5.32
-- PHP Version: 4.4.9
-- 
-- Database: `phelixin_bota`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `acos`
-- 

DROP TABLE IF EXISTS `acos`;
CREATE TABLE `acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=220 ;

-- 
-- Dumping data for table `acos`
-- 

INSERT INTO `acos` VALUES (1, NULL, '', NULL, 'controllers', 1, 442);
INSERT INTO `acos` VALUES (2, 1, '', NULL, 'Acl', 2, 25);
INSERT INTO `acos` VALUES (3, 2, '', NULL, 'AclActions', 3, 16);
INSERT INTO `acos` VALUES (4, 3, '', NULL, 'admin_index', 4, 5);
INSERT INTO `acos` VALUES (5, 3, '', NULL, 'admin_add', 6, 7);
INSERT INTO `acos` VALUES (6, 3, '', NULL, 'admin_edit', 8, 9);
INSERT INTO `acos` VALUES (7, 3, '', NULL, 'admin_delete', 10, 11);
INSERT INTO `acos` VALUES (8, 3, '', NULL, 'admin_move', 12, 13);
INSERT INTO `acos` VALUES (9, 3, '', NULL, 'admin_generate', 14, 15);
INSERT INTO `acos` VALUES (10, 2, '', NULL, 'AclPermissions', 17, 24);
INSERT INTO `acos` VALUES (11, 10, '', NULL, 'admin_index', 18, 19);
INSERT INTO `acos` VALUES (12, 10, '', NULL, 'admin_toggle', 20, 21);
INSERT INTO `acos` VALUES (13, 10, '', NULL, 'admin_upgrade', 22, 23);
INSERT INTO `acos` VALUES (14, 1, '', NULL, 'Blocks', 26, 55);
INSERT INTO `acos` VALUES (15, 14, '', NULL, 'Blocks', 27, 44);
INSERT INTO `acos` VALUES (16, 15, '', NULL, 'admin_toggle', 28, 29);
INSERT INTO `acos` VALUES (17, 15, '', NULL, 'admin_index', 30, 31);
INSERT INTO `acos` VALUES (18, 15, '', NULL, 'admin_add', 32, 33);
INSERT INTO `acos` VALUES (19, 15, '', NULL, 'admin_edit', 34, 35);
INSERT INTO `acos` VALUES (20, 15, '', NULL, 'admin_delete', 36, 37);
INSERT INTO `acos` VALUES (21, 15, '', NULL, 'admin_moveup', 38, 39);
INSERT INTO `acos` VALUES (22, 15, '', NULL, 'admin_movedown', 40, 41);
INSERT INTO `acos` VALUES (23, 15, '', NULL, 'admin_process', 42, 43);
INSERT INTO `acos` VALUES (24, 14, '', NULL, 'Regions', 45, 54);
INSERT INTO `acos` VALUES (25, 24, '', NULL, 'admin_index', 46, 47);
INSERT INTO `acos` VALUES (26, 24, '', NULL, 'admin_add', 48, 49);
INSERT INTO `acos` VALUES (27, 24, '', NULL, 'admin_edit', 50, 51);
INSERT INTO `acos` VALUES (28, 24, '', NULL, 'admin_delete', 52, 53);
INSERT INTO `acos` VALUES (29, 1, '', NULL, 'Comments', 56, 73);
INSERT INTO `acos` VALUES (30, 29, '', NULL, 'Comments', 57, 72);
INSERT INTO `acos` VALUES (31, 30, '', NULL, 'admin_index', 58, 59);
INSERT INTO `acos` VALUES (32, 30, '', NULL, 'admin_edit', 60, 61);
INSERT INTO `acos` VALUES (33, 30, '', NULL, 'admin_delete', 62, 63);
INSERT INTO `acos` VALUES (34, 30, '', NULL, 'admin_process', 64, 65);
INSERT INTO `acos` VALUES (35, 30, '', NULL, 'index', 66, 67);
INSERT INTO `acos` VALUES (36, 30, '', NULL, 'add', 68, 69);
INSERT INTO `acos` VALUES (37, 30, '', NULL, 'delete', 70, 71);
INSERT INTO `acos` VALUES (38, 1, '', NULL, 'Contacts', 74, 97);
INSERT INTO `acos` VALUES (39, 38, '', NULL, 'Contacts', 75, 86);
INSERT INTO `acos` VALUES (40, 39, '', NULL, 'admin_index', 76, 77);
INSERT INTO `acos` VALUES (41, 39, '', NULL, 'admin_add', 78, 79);
INSERT INTO `acos` VALUES (42, 39, '', NULL, 'admin_edit', 80, 81);
INSERT INTO `acos` VALUES (43, 39, '', NULL, 'admin_delete', 82, 83);
INSERT INTO `acos` VALUES (44, 39, '', NULL, 'view', 84, 85);
INSERT INTO `acos` VALUES (45, 38, '', NULL, 'Messages', 87, 96);
INSERT INTO `acos` VALUES (46, 45, '', NULL, 'admin_index', 88, 89);
INSERT INTO `acos` VALUES (47, 45, '', NULL, 'admin_edit', 90, 91);
INSERT INTO `acos` VALUES (48, 45, '', NULL, 'admin_delete', 92, 93);
INSERT INTO `acos` VALUES (49, 45, '', NULL, 'admin_process', 94, 95);
INSERT INTO `acos` VALUES (50, 1, '', NULL, 'Croogo', 98, 99);
INSERT INTO `acos` VALUES (51, 1, '', NULL, 'Extensions', 100, 139);
INSERT INTO `acos` VALUES (52, 51, '', NULL, 'ExtensionsLocales', 101, 112);
INSERT INTO `acos` VALUES (53, 52, '', NULL, 'admin_index', 102, 103);
INSERT INTO `acos` VALUES (54, 52, '', NULL, 'admin_activate', 104, 105);
INSERT INTO `acos` VALUES (55, 52, '', NULL, 'admin_add', 106, 107);
INSERT INTO `acos` VALUES (56, 52, '', NULL, 'admin_edit', 108, 109);
INSERT INTO `acos` VALUES (57, 52, '', NULL, 'admin_delete', 110, 111);
INSERT INTO `acos` VALUES (58, 51, '', NULL, 'ExtensionsPlugins', 113, 124);
INSERT INTO `acos` VALUES (59, 58, '', NULL, 'admin_index', 114, 115);
INSERT INTO `acos` VALUES (60, 58, '', NULL, 'admin_add', 116, 117);
INSERT INTO `acos` VALUES (61, 58, '', NULL, 'admin_delete', 118, 119);
INSERT INTO `acos` VALUES (62, 58, '', NULL, 'admin_toggle', 120, 121);
INSERT INTO `acos` VALUES (63, 58, '', NULL, 'admin_migrate', 122, 123);
INSERT INTO `acos` VALUES (64, 51, '', NULL, 'ExtensionsThemes', 125, 138);
INSERT INTO `acos` VALUES (65, 64, '', NULL, 'admin_index', 126, 127);
INSERT INTO `acos` VALUES (66, 64, '', NULL, 'admin_activate', 128, 129);
INSERT INTO `acos` VALUES (67, 64, '', NULL, 'admin_add', 130, 131);
INSERT INTO `acos` VALUES (68, 64, '', NULL, 'admin_editor', 132, 133);
INSERT INTO `acos` VALUES (69, 64, '', NULL, 'admin_save', 134, 135);
INSERT INTO `acos` VALUES (70, 64, '', NULL, 'admin_delete', 136, 137);
INSERT INTO `acos` VALUES (71, 1, '', NULL, 'FileManager', 140, 175);
INSERT INTO `acos` VALUES (72, 71, '', NULL, 'Attachments', 141, 152);
INSERT INTO `acos` VALUES (73, 72, '', NULL, 'admin_index', 142, 143);
INSERT INTO `acos` VALUES (74, 72, '', NULL, 'admin_add', 144, 145);
INSERT INTO `acos` VALUES (75, 72, '', NULL, 'admin_edit', 146, 147);
INSERT INTO `acos` VALUES (76, 72, '', NULL, 'admin_delete', 148, 149);
INSERT INTO `acos` VALUES (77, 72, '', NULL, 'admin_browse', 150, 151);
INSERT INTO `acos` VALUES (78, 71, '', NULL, 'FileManager', 153, 174);
INSERT INTO `acos` VALUES (79, 78, '', NULL, 'admin_index', 154, 155);
INSERT INTO `acos` VALUES (80, 78, '', NULL, 'admin_browse', 156, 157);
INSERT INTO `acos` VALUES (81, 78, '', NULL, 'admin_editfile', 158, 159);
INSERT INTO `acos` VALUES (82, 78, '', NULL, 'admin_upload', 160, 161);
INSERT INTO `acos` VALUES (83, 78, '', NULL, 'admin_delete_file', 162, 163);
INSERT INTO `acos` VALUES (84, 78, '', NULL, 'admin_delete_directory', 164, 165);
INSERT INTO `acos` VALUES (85, 78, '', NULL, 'admin_rename', 166, 167);
INSERT INTO `acos` VALUES (86, 78, '', NULL, 'admin_create_directory', 168, 169);
INSERT INTO `acos` VALUES (87, 78, '', NULL, 'admin_create_file', 170, 171);
INSERT INTO `acos` VALUES (88, 78, '', NULL, 'admin_chmod', 172, 173);
INSERT INTO `acos` VALUES (89, 1, '', NULL, 'Install', 176, 189);
INSERT INTO `acos` VALUES (90, 89, '', NULL, 'Install', 177, 188);
INSERT INTO `acos` VALUES (91, 90, '', NULL, 'index', 178, 179);
INSERT INTO `acos` VALUES (92, 90, '', NULL, 'database', 180, 181);
INSERT INTO `acos` VALUES (93, 90, '', NULL, 'data', 182, 183);
INSERT INTO `acos` VALUES (94, 90, '', NULL, 'adminuser', 184, 185);
INSERT INTO `acos` VALUES (95, 90, '', NULL, 'finish', 186, 187);
INSERT INTO `acos` VALUES (96, 1, '', NULL, 'Menus', 190, 219);
INSERT INTO `acos` VALUES (97, 96, '', NULL, 'Links', 191, 208);
INSERT INTO `acos` VALUES (98, 97, '', NULL, 'admin_toggle', 192, 193);
INSERT INTO `acos` VALUES (99, 97, '', NULL, 'admin_index', 194, 195);
INSERT INTO `acos` VALUES (100, 97, '', NULL, 'admin_add', 196, 197);
INSERT INTO `acos` VALUES (101, 97, '', NULL, 'admin_edit', 198, 199);
INSERT INTO `acos` VALUES (102, 97, '', NULL, 'admin_delete', 200, 201);
INSERT INTO `acos` VALUES (103, 97, '', NULL, 'admin_moveup', 202, 203);
INSERT INTO `acos` VALUES (104, 97, '', NULL, 'admin_movedown', 204, 205);
INSERT INTO `acos` VALUES (105, 97, '', NULL, 'admin_process', 206, 207);
INSERT INTO `acos` VALUES (106, 96, '', NULL, 'Menus', 209, 218);
INSERT INTO `acos` VALUES (107, 106, '', NULL, 'admin_index', 210, 211);
INSERT INTO `acos` VALUES (108, 106, '', NULL, 'admin_add', 212, 213);
INSERT INTO `acos` VALUES (109, 106, '', NULL, 'admin_edit', 214, 215);
INSERT INTO `acos` VALUES (110, 106, '', NULL, 'admin_delete', 216, 217);
INSERT INTO `acos` VALUES (111, 1, '', NULL, 'Meta', 220, 221);
INSERT INTO `acos` VALUES (112, 1, '', NULL, 'Migrations', 222, 223);
INSERT INTO `acos` VALUES (113, 1, '', NULL, 'Nodes', 224, 259);
INSERT INTO `acos` VALUES (114, 113, '', NULL, 'Nodes', 225, 258);
INSERT INTO `acos` VALUES (115, 114, '', NULL, 'admin_toggle', 226, 227);
INSERT INTO `acos` VALUES (116, 114, '', NULL, 'admin_index', 228, 229);
INSERT INTO `acos` VALUES (117, 114, '', NULL, 'admin_create', 230, 231);
INSERT INTO `acos` VALUES (118, 114, '', NULL, 'admin_add', 232, 233);
INSERT INTO `acos` VALUES (119, 114, '', NULL, 'admin_edit', 234, 235);
INSERT INTO `acos` VALUES (120, 114, '', NULL, 'admin_update_paths', 236, 237);
INSERT INTO `acos` VALUES (121, 114, '', NULL, 'admin_delete', 238, 239);
INSERT INTO `acos` VALUES (122, 114, '', NULL, 'admin_delete_meta', 240, 241);
INSERT INTO `acos` VALUES (123, 114, '', NULL, 'admin_add_meta', 242, 243);
INSERT INTO `acos` VALUES (124, 114, '', NULL, 'admin_process', 244, 245);
INSERT INTO `acos` VALUES (125, 114, '', NULL, 'index', 246, 247);
INSERT INTO `acos` VALUES (126, 114, '', NULL, 'term', 248, 249);
INSERT INTO `acos` VALUES (127, 114, '', NULL, 'promoted', 250, 251);
INSERT INTO `acos` VALUES (128, 114, '', NULL, 'search', 252, 253);
INSERT INTO `acos` VALUES (129, 114, '', NULL, 'view', 254, 255);
INSERT INTO `acos` VALUES (130, 1, '', NULL, 'Search', 260, 261);
INSERT INTO `acos` VALUES (131, 1, '', NULL, 'Settings', 262, 299);
INSERT INTO `acos` VALUES (132, 131, '', NULL, 'Languages', 263, 278);
INSERT INTO `acos` VALUES (133, 132, '', NULL, 'admin_index', 264, 265);
INSERT INTO `acos` VALUES (134, 132, '', NULL, 'admin_add', 266, 267);
INSERT INTO `acos` VALUES (135, 132, '', NULL, 'admin_edit', 268, 269);
INSERT INTO `acos` VALUES (136, 132, '', NULL, 'admin_delete', 270, 271);
INSERT INTO `acos` VALUES (137, 132, '', NULL, 'admin_moveup', 272, 273);
INSERT INTO `acos` VALUES (138, 132, '', NULL, 'admin_movedown', 274, 275);
INSERT INTO `acos` VALUES (139, 132, '', NULL, 'admin_select', 276, 277);
INSERT INTO `acos` VALUES (140, 131, '', NULL, 'Settings', 279, 298);
INSERT INTO `acos` VALUES (141, 140, '', NULL, 'admin_dashboard', 280, 281);
INSERT INTO `acos` VALUES (142, 140, '', NULL, 'admin_index', 282, 283);
INSERT INTO `acos` VALUES (143, 140, '', NULL, 'admin_view', 284, 285);
INSERT INTO `acos` VALUES (144, 140, '', NULL, 'admin_add', 286, 287);
INSERT INTO `acos` VALUES (145, 140, '', NULL, 'admin_edit', 288, 289);
INSERT INTO `acos` VALUES (146, 140, '', NULL, 'admin_delete', 290, 291);
INSERT INTO `acos` VALUES (147, 140, '', NULL, 'admin_prefix', 292, 293);
INSERT INTO `acos` VALUES (148, 140, '', NULL, 'admin_moveup', 294, 295);
INSERT INTO `acos` VALUES (149, 140, '', NULL, 'admin_movedown', 296, 297);
INSERT INTO `acos` VALUES (150, 1, '', NULL, 'Taxonomy', 300, 339);
INSERT INTO `acos` VALUES (151, 150, '', NULL, 'Terms', 301, 314);
INSERT INTO `acos` VALUES (152, 151, '', NULL, 'admin_index', 302, 303);
INSERT INTO `acos` VALUES (153, 151, '', NULL, 'admin_add', 304, 305);
INSERT INTO `acos` VALUES (154, 151, '', NULL, 'admin_edit', 306, 307);
INSERT INTO `acos` VALUES (155, 151, '', NULL, 'admin_delete', 308, 309);
INSERT INTO `acos` VALUES (156, 151, '', NULL, 'admin_moveup', 310, 311);
INSERT INTO `acos` VALUES (157, 151, '', NULL, 'admin_movedown', 312, 313);
INSERT INTO `acos` VALUES (158, 150, '', NULL, 'Types', 315, 324);
INSERT INTO `acos` VALUES (159, 158, '', NULL, 'admin_index', 316, 317);
INSERT INTO `acos` VALUES (160, 158, '', NULL, 'admin_add', 318, 319);
INSERT INTO `acos` VALUES (161, 158, '', NULL, 'admin_edit', 320, 321);
INSERT INTO `acos` VALUES (162, 158, '', NULL, 'admin_delete', 322, 323);
INSERT INTO `acos` VALUES (163, 150, '', NULL, 'Vocabularies', 325, 338);
INSERT INTO `acos` VALUES (164, 163, '', NULL, 'admin_index', 326, 327);
INSERT INTO `acos` VALUES (165, 163, '', NULL, 'admin_add', 328, 329);
INSERT INTO `acos` VALUES (166, 163, '', NULL, 'admin_edit', 330, 331);
INSERT INTO `acos` VALUES (167, 163, '', NULL, 'admin_delete', 332, 333);
INSERT INTO `acos` VALUES (168, 163, '', NULL, 'admin_moveup', 334, 335);
INSERT INTO `acos` VALUES (169, 163, '', NULL, 'admin_movedown', 336, 337);
INSERT INTO `acos` VALUES (170, 1, '', NULL, 'Ckeditor', 340, 341);
INSERT INTO `acos` VALUES (171, 1, '', NULL, 'Users', 342, 437);
INSERT INTO `acos` VALUES (172, 171, '', NULL, 'Roles', 343, 352);
INSERT INTO `acos` VALUES (173, 172, '', NULL, 'admin_index', 344, 345);
INSERT INTO `acos` VALUES (174, 172, '', NULL, 'admin_add', 346, 347);
INSERT INTO `acos` VALUES (175, 172, '', NULL, 'admin_edit', 348, 349);
INSERT INTO `acos` VALUES (176, 172, '', NULL, 'admin_delete', 350, 351);
INSERT INTO `acos` VALUES (177, 171, '', NULL, 'Users', 353, 422);
INSERT INTO `acos` VALUES (178, 177, '', NULL, 'admin_index', 354, 355);
INSERT INTO `acos` VALUES (179, 177, '', NULL, 'admin_add', 356, 357);
INSERT INTO `acos` VALUES (180, 177, '', NULL, 'admin_edit', 358, 359);
INSERT INTO `acos` VALUES (181, 177, '', NULL, 'admin_reset_password', 360, 361);
INSERT INTO `acos` VALUES (182, 177, '', NULL, 'admin_delete', 362, 363);
INSERT INTO `acos` VALUES (183, 177, '', NULL, 'admin_login', 364, 365);
INSERT INTO `acos` VALUES (184, 177, '', NULL, 'admin_logout', 366, 367);
INSERT INTO `acos` VALUES (185, 177, '', NULL, 'index', 368, 369);
INSERT INTO `acos` VALUES (186, 177, '', NULL, 'add', 370, 371);
INSERT INTO `acos` VALUES (187, 177, '', NULL, 'activate', 372, 373);
INSERT INTO `acos` VALUES (188, 177, '', NULL, 'edit', 374, 375);
INSERT INTO `acos` VALUES (189, 177, '', NULL, 'forgot', 376, 377);
INSERT INTO `acos` VALUES (190, 177, '', NULL, 'reset', 378, 379);
INSERT INTO `acos` VALUES (191, 177, '', NULL, 'login', 380, 381);
INSERT INTO `acos` VALUES (192, 177, '', NULL, 'logout', 382, 383);
INSERT INTO `acos` VALUES (193, 177, '', NULL, 'view', 384, 385);
INSERT INTO `acos` VALUES (195, 177, NULL, NULL, 'accountsetting', 386, 387);
INSERT INTO `acos` VALUES (196, 177, NULL, NULL, 'registration', 388, 389);
INSERT INTO `acos` VALUES (197, 177, NULL, NULL, 'billing', 390, 391);
INSERT INTO `acos` VALUES (198, 177, NULL, NULL, 'search', 392, 393);
INSERT INTO `acos` VALUES (199, 177, NULL, NULL, 'lessons', 394, 395);
INSERT INTO `acos` VALUES (202, 171, NULL, NULL, 'Usermessage', 429, 432);
INSERT INTO `acos` VALUES (203, 202, NULL, NULL, 'index', 430, 431);
INSERT INTO `acos` VALUES (204, 177, NULL, NULL, 'lessons_add', 400, 401);
INSERT INTO `acos` VALUES (205, 177, NULL, NULL, 'whiteboarddata', 402, 403);
INSERT INTO `acos` VALUES (206, 177, NULL, NULL, 'changelesson', 404, 405);
INSERT INTO `acos` VALUES (207, 177, NULL, NULL, 'searchstudent', 406, 407);
INSERT INTO `acos` VALUES (208, 177, NULL, NULL, 'lessonreviews', 408, 409);
INSERT INTO `acos` VALUES (209, 177, NULL, NULL, 'confirmedbytutor', 410, 411);
INSERT INTO `acos` VALUES (210, 177, NULL, NULL, 'mycalander', 412, 413);
INSERT INTO `acos` VALUES (211, 177, NULL, NULL, 'calandarevents', 414, 415);
INSERT INTO `acos` VALUES (212, 177, NULL, NULL, 'topchart', 416, 417);
INSERT INTO `acos` VALUES (213, 114, NULL, NULL, 'reportbug', 256, 257);
INSERT INTO `acos` VALUES (214, 1, NULL, NULL, 'Subject', 438, 441);
INSERT INTO `acos` VALUES (215, 214, NULL, NULL, 'search', 439, 440);
INSERT INTO `acos` VALUES (216, 177, NULL, NULL, 'calandareventsprofile', 418, 419);
INSERT INTO `acos` VALUES (217, 171, NULL, NULL, 'Invite', 433, 436);
INSERT INTO `acos` VALUES (218, 217, NULL, NULL, 'index', 434, 435);
INSERT INTO `acos` VALUES (219, 177, NULL, NULL, 'joinuser', 420, 421);

-- --------------------------------------------------------

-- 
-- Table structure for table `aros`
-- 

DROP TABLE IF EXISTS `aros`;
CREATE TABLE `aros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

-- 
-- Dumping data for table `aros`
-- 

INSERT INTO `aros` VALUES (1, 2, 'Role', 1, 'Role-admin', 3, 6);
INSERT INTO `aros` VALUES (2, 3, 'Role', 2, 'Role-tutor', 2, 21);
INSERT INTO `aros` VALUES (3, NULL, 'Role', 3, 'Role-student', 1, 22);
INSERT INTO `aros` VALUES (4, 1, 'User', 1, 'admin', 4, 5);
INSERT INTO `aros` VALUES (5, 2, 'User', 2, 'deepak', 7, 8);
INSERT INTO `aros` VALUES (6, 2, 'User', 3, 'jain', 9, 10);
INSERT INTO `aros` VALUES (7, 2, 'User', 4, 'jaindeepak', 11, 12);
INSERT INTO `aros` VALUES (8, 2, 'User', 5, 'deepak1', 13, 14);
INSERT INTO `aros` VALUES (9, 2, 'User', 6, 'deepak2', 15, 16);
INSERT INTO `aros` VALUES (10, 2, 'User', 7, 'deepak3', 17, 18);
INSERT INTO `aros` VALUES (11, 15, 'User', 8, 'deepak4', 24, 25);
INSERT INTO `aros` VALUES (15, NULL, 'Role', 4, 'Role-t', 23, 28);
INSERT INTO `aros` VALUES (16, NULL, 'User', 9, NULL, 29, 30);
INSERT INTO `aros` VALUES (17, 2, 'User', 10, 'vikas', 19, 20);
INSERT INTO `aros` VALUES (18, 15, 'User', 11, 'vikas1', 26, 27);
INSERT INTO `aros` VALUES (19, NULL, 'User', 12, NULL, 31, 32);

-- --------------------------------------------------------

-- 
-- Table structure for table `aros_acos`
-- 

DROP TABLE IF EXISTS `aros_acos`;
CREATE TABLE `aros_acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `_read` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `_update` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `_delete` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=99 ;

-- 
-- Dumping data for table `aros_acos`
-- 

INSERT INTO `aros_acos` VALUES (1, 3, 35, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (2, 3, 36, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (3, 2, 37, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (4, 3, 44, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (5, 3, 125, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (6, 3, 126, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (7, 3, 127, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (8, 3, 128, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (9, 3, 129, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (10, 2, 185, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (11, 3, 186, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (12, 3, 187, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (13, 2, 188, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (14, 3, 189, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (15, 3, 190, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (16, 3, 191, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (17, 2, 192, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (18, 2, 193, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (19, 3, 183, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (20, 3, 185, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (25, 3, 193, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (26, 3, 192, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (27, 2, 173, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (28, 2, 174, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (29, 2, 175, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (30, 2, 176, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (31, 3, 176, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (32, 3, 175, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (33, 3, 174, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (34, 3, 173, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (35, 3, 188, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (36, 2, 196, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (37, 2, 195, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (38, 3, 195, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (39, 3, 196, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (40, 2, 197, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (41, 3, 197, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (42, 15, 197, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (43, 15, 196, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (44, 15, 195, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (45, 15, 193, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (46, 2, 198, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (47, 15, 198, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (48, 3, 198, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (49, 2, 199, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (50, 15, 199, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (51, 2, 200, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (52, 3, 200, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (53, 15, 200, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (54, 3, 201, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (55, 15, 192, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (56, 15, 191, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (57, 15, 185, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (58, 2, 202, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (59, 3, 202, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (60, 15, 202, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (61, 2, 204, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (62, 2, 205, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (63, 15, 205, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (64, 2, 206, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (65, 15, 206, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (66, 2, 207, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (67, 15, 207, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (68, 15, 208, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (69, 2, 209, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (70, 15, 209, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (71, 2, 210, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (72, 2, 211, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (73, 3, 212, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (74, 2, 212, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (75, 15, 212, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (76, 15, 129, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (77, 15, 128, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (78, 15, 125, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (79, 15, 126, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (80, 15, 127, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (81, 2, 213, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (82, 3, 213, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (83, 15, 213, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (84, 2, 215, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (85, 3, 215, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (86, 15, 215, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (87, 3, 211, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (88, 15, 211, '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES (89, 2, 216, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (90, 3, 216, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (91, 15, 216, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (92, 3, 205, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (93, 2, 218, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (94, 15, 218, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (95, 3, 218, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (96, 2, 219, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (97, 3, 219, '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES (98, 15, 219, '1', '1', '1', '1');

-- --------------------------------------------------------

-- 
-- Table structure for table `blocks`
-- 

DROP TABLE IF EXISTS `blocks`;
CREATE TABLE `blocks` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `region_id` int(20) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `show_title` tinyint(1) NOT NULL DEFAULT '1',
  `class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `weight` int(11) DEFAULT NULL,
  `element` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visibility_roles` text COLLATE utf8_unicode_ci,
  `visibility_paths` text COLLATE utf8_unicode_ci,
  `visibility_php` text COLLATE utf8_unicode_ci,
  `params` text COLLATE utf8_unicode_ci,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `block_alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

-- 
-- Dumping data for table `blocks`
-- 

INSERT INTO `blocks` VALUES (3, 4, 'About', 'about', 'This is the content of your block. Can be modified in admin panel.', 1, '', 1, 2, '', '', '', '', '', '2009-12-20 03:07:39', '2009-07-26 17:13:14');
INSERT INTO `blocks` VALUES (5, 4, 'Meta', 'meta', '[menu:meta]', 1, '', 1, 6, '', '', '', '', '', '2009-12-22 05:17:39', '2009-09-12 06:36:22');
INSERT INTO `blocks` VALUES (6, 4, 'Blogroll', 'blogroll', '[menu:blogroll]', 1, '', 1, 4, '', '', '', '', '', '2009-12-20 03:07:33', '2009-09-12 23:33:27');
INSERT INTO `blocks` VALUES (7, 4, 'Categories', 'categories', '[vocabulary:categories type="blog"]', 1, '', 1, 3, '', '', '', '', '', '2009-12-20 03:07:36', '2009-10-03 16:52:50');
INSERT INTO `blocks` VALUES (8, 4, 'Search', 'search', '', 0, '', 1, 1, 'Nodes.search', '', '', '', '', '2009-12-20 03:07:39', '2009-12-20 03:07:27');
INSERT INTO `blocks` VALUES (9, 4, 'Recent Posts', 'recent_posts', '[node:recent_posts conditions="Node.type:blog" order="Node.id DESC" limit="5"]', 1, '', 1, 5, '', '', '', '', '', '2010-04-08 21:09:31', '2009-12-22 05:17:32');

-- --------------------------------------------------------

-- 
-- Table structure for table `categories`
-- 

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

-- 
-- Dumping data for table `categories`
-- 

INSERT INTO `categories` VALUES (1, NULL, NULL, NULL, 'd', 0);
INSERT INTO `categories` VALUES (2, NULL, NULL, NULL, 'd', 0);
INSERT INTO `categories` VALUES (3, NULL, NULL, NULL, 'dsasdf', 1);
INSERT INTO `categories` VALUES (4, NULL, NULL, NULL, 'ewewe', 1);
INSERT INTO `categories` VALUES (5, NULL, NULL, NULL, 'a', 1);
INSERT INTO `categories` VALUES (6, NULL, NULL, NULL, 'fsdafsda', 1);
INSERT INTO `categories` VALUES (7, NULL, NULL, NULL, 'ddsd', 0);
INSERT INTO `categories` VALUES (8, NULL, NULL, NULL, 'dsfaf', 0);
INSERT INTO `categories` VALUES (9, NULL, NULL, NULL, 'yyyyy', 1);
INSERT INTO `categories` VALUES (10, NULL, NULL, NULL, 'ddddd', 1);
INSERT INTO `categories` VALUES (13, NULL, NULL, NULL, 'zzz', 1);
INSERT INTO `categories` VALUES (14, NULL, NULL, NULL, '3dffsa', 0);
INSERT INTO `categories` VALUES (15, 6, NULL, NULL, 'm.com', 1);
INSERT INTO `categories` VALUES (16, 13, NULL, NULL, 'b.com', 1);
INSERT INTO `categories` VALUES (17, 13, NULL, NULL, 'b.com', 1);
INSERT INTO `categories` VALUES (18, 13, NULL, NULL, 'b.com', 1);
INSERT INTO `categories` VALUES (19, 9, NULL, NULL, 'dddsadfasdf sdfsd fasdaf', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `cometchat`
-- 

DROP TABLE IF EXISTS `cometchat`;
CREATE TABLE `cometchat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` int(10) unsigned NOT NULL,
  `to` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  `sent` int(10) unsigned NOT NULL DEFAULT '0',
  `read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `direction` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `to` (`to`),
  KEY `from` (`from`),
  KEY `direction` (`direction`),
  KEY `read` (`read`),
  KEY `sent` (`sent`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

-- 
-- Dumping data for table `cometchat`
-- 

INSERT INTO `cometchat` VALUES (1, 4, 8, 'hi', 1391109642, 1, 0);
INSERT INTO `cometchat` VALUES (2, 8, 4, 'hello', 1391109655, 1, 0);
INSERT INTO `cometchat` VALUES (3, 8, 1, 'hii', 1391277677, 1, 0);
INSERT INTO `cometchat` VALUES (4, 8, 1, 'heeloooo', 1391277684, 1, 0);
INSERT INTO `cometchat` VALUES (5, 8, 1, 'r u there?', 1391277688, 1, 0);
INSERT INTO `cometchat` VALUES (6, 4, 8, 'hello', 1391411084, 1, 0);
INSERT INTO `cometchat` VALUES (7, 4, 8, 'hllllooooooooooooooo', 1391422842, 1, 0);
INSERT INTO `cometchat` VALUES (8, 4, 8, 'has shared his/her whiteboard with you. <a href=''javascript:void(0);'' onclick="javascript:jqcc.ccwhiteboard.accept(''4'',''1391423273'');">Click here to view</a> or simply ignore this message.', 1391423274, 1, 1);
INSERT INTO `cometchat` VALUES (9, 4, 8, 'has sent you an audio/video chat request. <a token =''T1==cGFydG5lcl9pZD0zNDg1MDEmc2RrX3ZlcnNpb249dGJwaHAtdjAuOTEuMjAxMS0wMi0xNCZzaWc9YjY3MTg1YThlY2YyMTVhNWVhYTYyNDg3NTFhN2YwZGU4OTk4MjEyNjpzZXNzaW9uX2lkPTFfTVg0ek5EZzFNREYtTVRBMkxqSXhOUzR4TmpNdU1qSi1UVzl1SUVabFlpQXdNeUF3TWpveU9Eb3hOaUJRVTFRZ01qQXhOSDR3TGpNM016WTJOVGMxZmcmY3JlYXRlX3RpbWU9MTM5MTQyMzI5NiZyb2xlPXB1Ymxpc2hlciZub25jZT0xMzkxNDIzMjk2LjgwMzgxMTQxNDY2NDA4'' href=''javascript:void(0);'' onclick="javascript:jqcc.ccavchat.accept(''4'',''1_MX4zNDg1MDF-MTA2LjIxNS4xNjMuMjJ-TW9uIEZlYiAwMyAwMjoyODoxNiBQU1QgMjAxNH4wLjM3MzY2NTc1fg'');">Click here to accept it</a> or simply ignore this message.', 1391423296, 1, 1);
INSERT INTO `cometchat` VALUES (10, 4, 8, 'has successfully sent an audio/video chat request.', 1391423296, 1, 2);
INSERT INTO `cometchat` VALUES (11, 4, 8, 'has shared his/her screen with you. <a href=''javascript:void(0);'' onclick="javascript:jqcc.ccscreenshare.accept(''4'',''1391423305'');">Click here to view his/her screen</a> or simply ignore this message.', 1391423306, 1, 1);
INSERT INTO `cometchat` VALUES (12, 4, 8, 'has successfully shared his/her screen.', 1391423306, 1, 2);
INSERT INTO `cometchat` VALUES (13, 4, 8, 'has sent you a handwritten message<br/><a href="/demos/botangle/app/webroot/cometchat/plugins/handwrite/uploads/2cd20b5f6e6e86f779b98cbde7872457.jpg" target="_blank" style="display:inline-block;margin-bottom:3px;margin-top:3px;"><img src="/demos/botangle/app/webroot/cometchat/plugins/handwrite/uploads/2cd20b5f6e6e86f779b98cbde7872457.jpg" border="0" style="padding:0px;display: inline-block;border:1px solid #666;" height="90"></a>', 1391423410, 1, 1);
INSERT INTO `cometchat` VALUES (14, 4, 8, 'has successfully sent a handwritten message<br/><a href="/demos/botangle/app/webroot/cometchat/plugins/handwrite/uploads/2cd20b5f6e6e86f779b98cbde7872457.jpg" target="_blank" style="display:inline-block;margin-bottom:3px;margin-top:3px;"><img src="/demos/botangle/app/webroot/cometchat/plugins/handwrite/uploads/2cd20b5f6e6e86f779b98cbde7872457.jpg" border="0" style="padding:0px;display: inline-block;border:1px solid #666;" height="90"></a>', 1391423410, 1, 2);
INSERT INTO `cometchat` VALUES (15, 8, 4, 'is now viewing your whiteboard.', 1391423484, 1, 1);
INSERT INTO `cometchat` VALUES (16, 8, 4, 'hi', 1391455610, 1, 0);
INSERT INTO `cometchat` VALUES (17, 9, 10, 'hey student', 1391481172, 0, 0);
INSERT INTO `cometchat` VALUES (18, 4, 8, 'Hello sir', 1391621509, 1, 0);
INSERT INTO `cometchat` VALUES (19, 4, 8, 'How are you?', 1391621511, 1, 0);
INSERT INTO `cometchat` VALUES (20, 4, 8, 'can you solve this question?', 1391621522, 1, 0);
INSERT INTO `cometchat` VALUES (21, 4, 8, 'Hello, i want to test this out just for a sec.', 1391709201, 1, 0);
INSERT INTO `cometchat` VALUES (22, 8, 4, 'Hi', 1391967838, 1, 0);
INSERT INTO `cometchat` VALUES (23, 8, 4, 'Deepak', 1391967839, 1, 0);
INSERT INTO `cometchat` VALUES (24, 8, 4, 'How are you?', 1391967842, 1, 0);
INSERT INTO `cometchat` VALUES (25, 4, 8, 'hi', 1391968070, 1, 0);
INSERT INTO `cometchat` VALUES (26, 4, 8, 'fine', 1391968073, 1, 0);
INSERT INTO `cometchat` VALUES (27, 4, 8, 'hiii', 1392573627, 1, 0);
INSERT INTO `cometchat` VALUES (28, 8, 4, 'hi sir', 1392574142, 1, 0);
INSERT INTO `cometchat` VALUES (29, 4, 8, 'yaar', 1392574152, 1, 0);
INSERT INTO `cometchat` VALUES (30, 4, 8, 'abhi chal raha hai', 1392574157, 1, 0);
INSERT INTO `cometchat` VALUES (31, 8, 4, 'ha i see you write', 1392574175, 1, 0);
INSERT INTO `cometchat` VALUES (32, 8, 4, 'with green coloro', 1392574178, 1, 0);
INSERT INTO `cometchat` VALUES (33, 4, 8, 'hummm', 1392574186, 1, 0);
INSERT INTO `cometchat` VALUES (34, 4, 8, 'abhi drowing allow hia', 1392574192, 1, 0);
INSERT INTO `cometchat` VALUES (35, 4, 8, 'wahi problem hai', 1392574198, 1, 0);
INSERT INTO `cometchat` VALUES (36, 8, 4, 'hmm', 1392574198, 1, 0);
INSERT INTO `cometchat` VALUES (37, 4, 8, 'ok', 1392574202, 1, 0);
INSERT INTO `cometchat` VALUES (38, 4, 8, 'hello', 1392620379, 1, 0);
INSERT INTO `cometchat` VALUES (39, 8, 4, 'hi', 1392621223, 1, 0);
INSERT INTO `cometchat` VALUES (40, 8, 4, 'How are you?', 1392621226, 1, 0);
INSERT INTO `cometchat` VALUES (41, 4, 8, 'Doing well yourself?', 1392621249, 1, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `cometchat_announcements`
-- 

DROP TABLE IF EXISTS `cometchat_announcements`;
CREATE TABLE `cometchat_announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `announcement` text NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `to` int(10) NOT NULL,
  `recd` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `to` (`to`),
  KEY `time` (`time`),
  KEY `to_id` (`to`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5000 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5000 ;

-- 
-- Dumping data for table `cometchat_announcements`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `cometchat_block`
-- 

DROP TABLE IF EXISTS `cometchat_block`;
CREATE TABLE `cometchat_block` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fromid` int(10) unsigned NOT NULL,
  `toid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fromid` (`fromid`),
  KEY `toid` (`toid`),
  KEY `fromid_toid` (`fromid`,`toid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `cometchat_block`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `cometchat_chatroommessages`
-- 

DROP TABLE IF EXISTS `cometchat_chatroommessages`;
CREATE TABLE `cometchat_chatroommessages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `chatroomid` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  `sent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `chatroomid` (`chatroomid`),
  KEY `sent` (`sent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `cometchat_chatroommessages`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `cometchat_chatrooms`
-- 

DROP TABLE IF EXISTS `cometchat_chatrooms`;
CREATE TABLE `cometchat_chatrooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `lastactivity` int(10) unsigned NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `vidsession` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lastactivity` (`lastactivity`),
  KEY `createdby` (`createdby`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `cometchat_chatrooms`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `cometchat_chatrooms_users`
-- 

DROP TABLE IF EXISTS `cometchat_chatrooms_users`;
CREATE TABLE `cometchat_chatrooms_users` (
  `userid` int(10) unsigned NOT NULL,
  `chatroomid` int(10) unsigned NOT NULL,
  `lastactivity` int(10) unsigned NOT NULL,
  `isbanned` int(1) DEFAULT '0',
  PRIMARY KEY (`userid`,`chatroomid`) USING BTREE,
  KEY `chatroomid` (`chatroomid`),
  KEY `lastactivity` (`lastactivity`),
  KEY `userid` (`userid`),
  KEY `userid_chatroomid` (`chatroomid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `cometchat_chatrooms_users`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `cometchat_comethistory`
-- 

DROP TABLE IF EXISTS `cometchat_comethistory`;
CREATE TABLE `cometchat_comethistory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `sent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel` (`channel`),
  KEY `sent` (`sent`),
  KEY `channel_sent` (`channel`,`sent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `cometchat_comethistory`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `cometchat_games`
-- 

DROP TABLE IF EXISTS `cometchat_games`;
CREATE TABLE `cometchat_games` (
  `userid` int(10) unsigned NOT NULL,
  `score` int(10) unsigned DEFAULT NULL,
  `games` int(10) unsigned DEFAULT NULL,
  `recentlist` text,
  `highscorelist` text,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `cometchat_games`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `cometchat_guests`
-- 

DROP TABLE IF EXISTS `cometchat_guests`;
CREATE TABLE `cometchat_guests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastactivity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lastactivity` (`lastactivity`)
) ENGINE=InnoDB AUTO_INCREMENT=10000001 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10000001 ;

-- 
-- Dumping data for table `cometchat_guests`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `cometchat_status`
-- 

DROP TABLE IF EXISTS `cometchat_status`;
CREATE TABLE `cometchat_status` (
  `userid` int(10) unsigned NOT NULL,
  `message` text,
  `status` enum('available','away','busy','invisible','offline') DEFAULT NULL,
  `typingto` int(10) unsigned DEFAULT NULL,
  `typingtime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`userid`),
  KEY `typingto` (`typingto`),
  KEY `typingtime` (`typingtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `cometchat_status`
-- 

INSERT INTO `cometchat_status` VALUES (1, NULL, 'available', NULL, NULL);
INSERT INTO `cometchat_status` VALUES (4, NULL, 'available', NULL, NULL);
INSERT INTO `cometchat_status` VALUES (8, NULL, 'available', NULL, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `cometchat_videochatsessions`
-- 

DROP TABLE IF EXISTS `cometchat_videochatsessions`;
CREATE TABLE `cometchat_videochatsessions` (
  `username` varchar(255) NOT NULL,
  `identity` varchar(255) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`username`),
  KEY `username` (`username`),
  KEY `identity` (`identity`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `cometchat_videochatsessions`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `comments`
-- 

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `parent_id` int(20) DEFAULT NULL,
  `node_id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL DEFAULT '0',
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `notify` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `comment_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'comment',
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `comments`
-- 

INSERT INTO `comments` VALUES (1, NULL, 1, 0, 'Mr Croogo', 'email@example.com', 'http://www.croogo.org', '127.0.0.1', '', 'Hi, this is the first comment.', NULL, 1, 0, 'blog', 'comment', 1, 2, '2009-12-25 12:00:00', '2009-12-25 12:00:00');

-- --------------------------------------------------------

-- 
-- Table structure for table `contacts`
-- 

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `address2` text COLLATE utf8_unicode_ci,
  `state` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postcode` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message_status` tinyint(1) NOT NULL DEFAULT '1',
  `message_archive` tinyint(1) NOT NULL DEFAULT '1',
  `message_count` int(11) NOT NULL DEFAULT '0',
  `message_spam_protection` tinyint(1) NOT NULL DEFAULT '0',
  `message_captcha` tinyint(1) NOT NULL DEFAULT '0',
  `message_notify` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `contacts`
-- 

INSERT INTO `contacts` VALUES (1, 'Contact', 'contact', '', '', '', '', '', '', '', '', '', '', 'you@your-site.com', 1, 0, 0, 0, 0, 1, 1, '2009-10-07 22:07:49', '2009-09-16 01:45:17');

-- --------------------------------------------------------

-- 
-- Table structure for table `invites`
-- 

DROP TABLE IF EXISTS `invites`;
CREATE TABLE `invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invited_by` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `invited_date` datetime NOT NULL,
  `invited_link` varchar(255) NOT NULL,
  `linkused_or_not` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- 
-- Dumping data for table `invites`
-- 

INSERT INTO `invites` VALUES (1, 4, 'soganideepak241979@gmail.com', 'deepak', 'dssdfs dsdsdf sdf<br /> <a href="/demos/botangle//users/joinuser/NA==">click here</a> to join ', '2014-02-08 13:59:52', '/demos/botangle//users/joinuser/NA==', 0);
INSERT INTO `invites` VALUES (2, 4, 'soganideepak241979@gmail.com', 'deepak', 'dssdfs dsdsdf sdf<br /> <a href="/demos/botangle//users/joinuser/NA==">click here</a> to join ', '2014-02-08 14:03:10', '/demos/botangle//users/joinuser/NA==', 0);
INSERT INTO `invites` VALUES (3, 4, 'soganideepak241979@gmail.com', 'jain', 'hjhjl<br /> <a href="/demos/botangle//users/joinuser/NA==">click here</a> to join ', '2014-02-08 14:09:44', '/demos/botangle//users/joinuser/NA==', 0);
INSERT INTO `invites` VALUES (4, 4, 'soganideepak241979@gmail.com', 'jain', 'dsfsdffsdsdafsadf<br /> <a href="/demos/botangle/users/joinuser/NA==">click here</a> to join ', '2014-02-08 14:14:26', '/demos/botangle/users/joinuser/NA==', 0);
INSERT INTO `invites` VALUES (5, 4, 'soganideepak241979@gmail.com', 'deepak', 'sfd sadfds sdaf sadf<br /> <a href="/demos/botangle/users/joinuser/NA==">click here</a> to join ', '2014-02-09 12:30:25', '/demos/botangle/users/joinuser/NA==', 0);
INSERT INTO `invites` VALUES (6, 4, 'soganideepak241979@gmail.com', 'deepak', 'fsdfsdafad fadsffsdfsdafad fadsffsdfsdafad fadsffsdfsdafad fadsffsdfsdafad fadsffsdfsdafad fadsffsdfsdafad fadsffsdfsdafad fadsffsdfsdafad fadsffsdfsdafad fadsffsdfsdafad fadsf<br /> <a href="http://www.phelixportfolio.com/demo/botangle/users/joinuser/NA==">click here</a> to join ', '2014-02-09 12:34:33', 'http://www.phelixportfolio.com/demo/botangle/users/joinuser/NA==', 0);
INSERT INTO `invites` VALUES (7, 4, 'soganideepak241979@gmail.com', 'deepak', 'sdfsd fsdf sdf sdf sadf sadf asdf<br /> <a href="http://www.phelixportfolio.com/demo/botangle/users/joinuser/NA==">click here</a> to join ', '2014-02-09 12:48:03', 'http://www.phelixportfolio.com/demo/botangle/users/joinuser/NA==', 0);
INSERT INTO `invites` VALUES (8, 4, 'soganideepak241979@gmail.com', 'deepak', 'asfdsadf asdfsadf<br /> <a href="http://www.phelixportfolio.com/demo/botangle/users/joinuser/NA==">click here</a> to join ', '2014-02-09 12:50:28', 'http://www.phelixportfolio.com/demo/botangle/users/joinuser/NA==', 0);
INSERT INTO `invites` VALUES (9, 4, 'soganideepak241979@gmail.com', 'deepak', 'sdfsdf sdf<br /> <a href="http://www.phelixportfolio.com/demo/botangle/users/joinuser/NA==">click here</a> to join ', '2014-02-09 12:52:21', 'http://www.phelixportfolio.com/demo/botangle/users/joinuser/NA==', 0);
INSERT INTO `invites` VALUES (10, 4, 'soganideepak241979@gmail.com', 'deepak', 'sdfsdf sdf<br /> <a href="http://www.phelixportfolio.com/demo/botangle/users/joinuser/NA==">click here</a> to join ', '2014-02-09 12:55:09', 'http://www.phelixportfolio.com/demo/botangle/users/joinuser/NA==', 0);
INSERT INTO `invites` VALUES (11, 4, 'soganideepak241979@gmail.com', 'deepak', 'fsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaf<br /> <a href="http://www.phelixportfolio.com/demo/botangle/users/joinuser/NA==">click here</a> to join ', '2014-02-09 13:03:56', 'http://www.phelixportfolio.com/demo/botangle/users/joinuser/NA==', 0);
INSERT INTO `invites` VALUES (12, 4, 'soganideepak241979@gmail.com', 'deepak', 'fsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaffsad fsdaf sadfsa dfsaf<br /> <a href="http://www.phelixportfolio.com/demo/botangle/users/joinuser/NA==">click here</a> to join ', '2014-02-09 13:04:24', 'http://www.phelixportfolio.com/demo/botangle/users/joinuser/NA==', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `languages`
-- 

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `native` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `weight` int(11) DEFAULT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `languages`
-- 

INSERT INTO `languages` VALUES (1, 'English', 'English', 'eng', 1, 1, '2009-11-02 21:37:38', '2009-11-02 20:52:00');

-- --------------------------------------------------------

-- 
-- Table structure for table `lessons`
-- 

DROP TABLE IF EXISTS `lessons`;
CREATE TABLE `lessons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` int(11) NOT NULL,
  `tutor` varchar(255) NOT NULL,
  `lesson_date` date NOT NULL,
  `lesson_time` time NOT NULL,
  `ampm` varchar(2) NOT NULL,
  `duration` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `repet` enum('signle','daily','weekly') NOT NULL,
  `notes` text NOT NULL,
  `add_date` datetime NOT NULL,
  `readlesson` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `readlessontutor` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `twiddlameetingid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `lessons`
-- 

INSERT INTO `lessons` VALUES (1, 4, '8', '2014-02-16', '06:20:00', '', '1', 'maths', '', 'I have created a lesson for you. please confirmed it.', '2014-02-16 00:00:00', 0, 0, 1, 1, 1500393);
INSERT INTO `lessons` VALUES (2, 4, '8', '2014-02-16', '12:50:00', '', '1', 'm.com ', '', 'Hello', '2014-02-17 00:00:00', 0, 0, 1, 2, 1501124);

-- --------------------------------------------------------

-- 
-- Table structure for table `links`
-- 

DROP TABLE IF EXISTS `links`;
CREATE TABLE `links` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `parent_id` int(20) DEFAULT NULL,
  `menu_id` int(20) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `target` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  `visibility_roles` text COLLATE utf8_unicode_ci,
  `params` text COLLATE utf8_unicode_ci,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

-- 
-- Dumping data for table `links`
-- 

INSERT INTO `links` VALUES (5, NULL, 4, 'About', 'about', '', 'plugin:nodes/controller:nodes/action:view/type:page/slug:about', '', '', 1, 3, 4, '', '', '2009-10-06 23:14:21', '2009-08-19 12:23:33');
INSERT INTO `links` VALUES (6, NULL, 4, 'Contact', 'contact', '', 'plugin:contacts/controller:contacts/action:view/contact', '', '', 1, 5, 6, '', '', '2009-10-06 23:14:45', '2009-08-19 12:34:56');
INSERT INTO `links` VALUES (7, NULL, 3, 'Home', 'home', '', '/', '', '', 1, 5, 6, '', '', '2009-10-06 21:17:06', '2009-09-06 21:32:54');
INSERT INTO `links` VALUES (8, NULL, 3, 'About', 'about', '', '/about', '', '', 1, 7, 10, '', '', '2009-09-12 03:45:53', '2009-09-06 21:34:57');
INSERT INTO `links` VALUES (9, 8, 3, 'Child link', 'child-link', '', '#', '', '', 0, 8, 9, '', '', '2009-10-06 23:13:06', '2009-09-12 03:52:23');
INSERT INTO `links` VALUES (10, NULL, 5, 'Site Admin', 'site-admin', '', '/admin', '', '', 1, 1, 2, '', '', '2009-09-12 06:34:09', '2009-09-12 06:34:09');
INSERT INTO `links` VALUES (11, NULL, 5, 'Log out', 'log-out', '', '/plugin:users/controller:users/action:logout', '', '', 1, 7, 8, '["1","2"]', '', '2009-09-12 06:35:22', '2009-09-12 06:34:41');
INSERT INTO `links` VALUES (12, NULL, 6, 'Croogo', 'croogo', '', 'http://www.croogo.org', '', '', 1, 3, 4, '', '', '2009-09-12 23:31:59', '2009-09-12 23:31:59');
INSERT INTO `links` VALUES (14, NULL, 6, 'CakePHP', 'cakephp', '', 'http://www.cakephp.org', '', '', 1, 1, 2, '', '', '2009-10-07 03:25:25', '2009-09-12 23:38:43');
INSERT INTO `links` VALUES (15, NULL, 3, 'Contact', 'contact', '', '/plugin:contacts/controller:contacts/action:view/contact', '', '', 1, 11, 12, '', '', '2009-09-16 07:54:13', '2009-09-16 07:53:33');
INSERT INTO `links` VALUES (16, NULL, 5, 'Entries (RSS)', 'entries-rss', '', '/promoted.rss', '', '', 1, 3, 4, '', '', '2009-10-27 17:46:22', '2009-10-27 17:46:22');
INSERT INTO `links` VALUES (17, NULL, 5, 'Comments (RSS)', 'comments-rss', '', '/comments.rss', '', '', 1, 5, 6, '', '', '2009-10-27 17:46:54', '2009-10-27 17:46:54');

-- --------------------------------------------------------

-- 
-- Table structure for table `medias`
-- 

DROP TABLE IF EXISTS `medias`;
CREATE TABLE `medias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `date` datetime NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `medias`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `menus`
-- 

DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `weight` int(11) DEFAULT NULL,
  `link_count` int(11) NOT NULL,
  `params` text COLLATE utf8_unicode_ci,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menu_alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- 
-- Dumping data for table `menus`
-- 

INSERT INTO `menus` VALUES (3, 'Main Menu', 'main', '', '', 1, NULL, 4, '', '2009-08-19 12:21:06', '2009-07-22 01:49:53');
INSERT INTO `menus` VALUES (4, 'Footer', 'footer', '', '', 1, NULL, 2, '', '2009-08-19 12:22:42', '2009-08-19 12:22:42');
INSERT INTO `menus` VALUES (5, 'Meta', 'meta', '', '', 1, NULL, 4, '', '2009-09-12 06:33:29', '2009-09-12 06:33:29');
INSERT INTO `menus` VALUES (6, 'Blogroll', 'blogroll', '', '', 1, NULL, 2, '', '2009-09-12 23:30:24', '2009-09-12 23:30:24');

-- --------------------------------------------------------

-- 
-- Table structure for table `messages`
-- 

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8_unicode_ci,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `message_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `messages`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `meta`
-- 

DROP TABLE IF EXISTS `meta`;
CREATE TABLE `meta` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Node',
  `foreign_key` int(20) DEFAULT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `weight` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `meta`
-- 

INSERT INTO `meta` VALUES (1, 'Node', 1, 'meta_keywords', 'key1, key2', NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `news`
-- 

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `date` datetime NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `news`
-- 

INSERT INTO `news` VALUES (1, 'sfsaf', 'sda fsadf asdfasdf asdfsdfs dfsdf sadfsadf', '2014-02-05 18:46:29', NULL, 1);
INSERT INTO `news` VALUES (2, 'sdaf23423423', ' dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf dsf adf', '2014-02-05 19:53:07', NULL, 1);
INSERT INTO `news` VALUES (3, '23', 'dsafsadsfasdf asddsafsadsfasdf asddsafsadsfasdf asddsafsadsfasdf asddsafsadsfasdf asddsafsadsfasdf asddsafsadsfasdf asddsafsadsfasdf asddsafsadsfasdf asddsafsadsfasdf asddsafsadsfasdf asddsafsadsfasdf asd', '2014-02-05 14:36:47', 'Penguins.jpg', 1);
INSERT INTO `news` VALUES (4, 'sdaf23423423', 'fsdafsadfdfsdfsdf sdfsd f', '2014-02-05 20:25:59', '024.jpg', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `nodes`
-- 

DROP TABLE IF EXISTS `nodes`;
CREATE TABLE `nodes` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `parent_id` int(20) DEFAULT NULL,
  `user_id` int(20) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `mime_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_status` int(1) NOT NULL DEFAULT '1',
  `comment_count` int(11) DEFAULT '0',
  `promote` tinyint(1) NOT NULL DEFAULT '0',
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `terms` text COLLATE utf8_unicode_ci,
  `sticky` tinyint(1) NOT NULL DEFAULT '0',
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  `visibility_roles` text COLLATE utf8_unicode_ci,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'node',
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- 
-- Dumping data for table `nodes`
-- 

INSERT INTO `nodes` VALUES (1, NULL, 1, 'Hello World', 'hello-world', '<p>Welcome to Croogo. This is your first post. You can edit or delete it from the admin panel.</p>', '', 1, '', 2, 1, 1, '/blog/hello-world', '{"1":"uncategorized"}', 0, 1, 2, '', 'blog', '2009-12-25 11:00:00', '2009-12-25 11:00:00');
INSERT INTO `nodes` VALUES (2, NULL, 1, 'About', 'about', '<div class="span9">\r\n<h2 class="page-title">About Botangle</h2>\r\n\r\n<div class="StaticPageRight-Block">\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20">Who we are?</p>\r\n\r\n<p>Test....Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n\r\n<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.</p>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20">Who we are?</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n\r\n<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.</p>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20">Who we are?</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n\r\n<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.</p>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20">Who we are?</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n\r\n<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.</p>\r\n</div>\r\n</div>\r\n</div>\r\n', '', 1, '', 0, 0, 0, '/about', '', 0, 1, 2, '', 'page', '2014-01-03 12:49:08', '2009-12-25 22:00:00');
INSERT INTO `nodes` VALUES (3, NULL, 1, 'privacy', 'privacy', '<div class="span9">\r\n<h2 class="page-title">Privacy Policy</h2>\r\n\r\n<div class="StaticPageRight-Block">\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20">Who we are?</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n\r\n<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.</p>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20">Who we are?</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n\r\n<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.</p>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20">Who we are?</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n\r\n<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.</p>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20">Who we are?</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n\r\n<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.</p>\r\n</div>\r\n</div>\r\n</div>\r\n', '', 1, NULL, 1, 0, 0, '/privacy', NULL, 0, 3, 4, '', 'page', '2014-01-02 13:15:43', '2013-12-26 19:27:57');
INSERT INTO `nodes` VALUES (4, NULL, 1, 'faq', 'faq', '<div class="span9">\r\n<h2 class="page-title">Frequently Asked Questions</h2>\r\n\r\n<div class="StaticPageRight-Block">\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20">Getting Started with</p>\r\n\r\n<p class="FontStyle16">Q: <a href="#">How can i Sign Up in Botangle?</a></p>\r\n\r\n<p class="FontStyle16">Q: <a href="#">How can i Sign Up in Botangle?</a></p>\r\n\r\n<p class="FontStyle16">Q: <a href="#">How can i Sign Up in Botangle?</a></p>\r\n&nbsp;\r\n\r\n<p class="FontStyle20">My Account Queries</p>\r\n\r\n<p class="FontStyle16">Q: <a href="#">How can i manage my Botangle Account?</a></p>\r\n\r\n<p class="FontStyle16">Q: <a href="#">How can i manage my Botangle Account?</a></p>\r\n\r\n<p class="FontStyle16">Q: <a href="#">How can i manage my Botangle Account?</a></p>\r\n\r\n<p class="FontStyle16">Q: <a href="#">How can i manage my Botangle Account?</a></p>\r\n&nbsp;\r\n\r\n<p class="FontStyle20">Membership/ Charges Queries</p>\r\n\r\n<p class="FontStyle16">Q: <a href="#">How can i manage my Botangle Account?</a></p>\r\n\r\n<p class="FontStyle16">Q: <a href="#">How can i manage my Botangle Account?</a></p>\r\n\r\n<p class="FontStyle16">Q: <a href="#">How can i manage my Botangle Account?</a></p>\r\n\r\n<p class="FontStyle16">Q: <a href="#">How can i manage my Botangle Account?</a></p>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20 color1">Q: How can i Sign Up in Botangle? <span class="toplink"><a href="#" title="top">TOP</a></span></p>\r\n\r\n<p><strong>Ans:</strong>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20 color1">Q: How can i manage my Botangle Account? <span class="toplink"><a href="#" title="top">TOP</a></span></p>\r\n\r\n<p><strong>Ans:</strong>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20 color1">Q: Bontange membership Query? <span class="toplink"><a href="#" title="top">TOP</a></span></p>\r\n\r\n<p><strong>Ans:</strong>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20 color1">Q: Stdent account info? <span class="toplink"><a href="#" title="top">TOP</a></span></p>\r\n\r\n<p><strong>Ans:</strong>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20 color1">Q: Tutor Account Info? <span class="toplink"><a href="#" title="top">TOP</a></span></p>\r\n\r\n<p><strong>Ans:</strong>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n</div>\r\n</div>\r\n</div>\r\n', '', 1, NULL, 1, 0, 0, '/faq', NULL, 0, 5, 6, '', 'page', '2014-01-02 13:17:59', '2014-01-02 13:16:49');
INSERT INTO `nodes` VALUES (5, NULL, 1, 'terms', 'terms', '<div class="span9">\r\n<h2 class="page-title">Terms &amp; Conditions</h2>\r\n\r\n<div class="StaticPageRight-Block">\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20">Who we are?</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n\r\n<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.</p>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20">Who we are?</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n\r\n<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.</p>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20">Who we are?</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n\r\n<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.</p>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20">Who we are?</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n\r\n<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.</p>\r\n</div>\r\n</div>\r\n</div>\r\n', '', 1, NULL, 1, 0, 0, '/terms', NULL, 0, 7, 8, '', 'page', '2014-01-02 13:20:14', '2014-01-02 13:19:05');
INSERT INTO `nodes` VALUES (6, NULL, 1, 'Contact Us', 'contact-us', '<div class="span9">\r\n<h2 class="page-title">Contact Us</h2>\r\n\r\n<div class="StaticPageRight-Block">\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20">We&#39;re here to help!</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n</div>\r\n\r\n<div class="row-fluid ">\r\n<div class="Get-in-Touch offset5">\r\n<p class="FontStyle20"><strong>Get in touch with us:</strong></p>\r\n</div>\r\n</div>\r\n\r\n<div class="row-fluid ">\r\n<div class="Social-Boxs Social-Email span3">\r\n<p class="FontStyle20"><a href="#">Email Us</a></p>\r\n</div>\r\n\r\n<div class="Social-Boxs Social-FB span3">\r\n<p class="FontStyle20"><a href="#">Facebook Us</a></p>\r\n</div>\r\n\r\n<div class="Social-Boxs Social-Tweet span3">\r\n<p class="FontStyle20"><a href="#">Follow Us</a></p>\r\n</div>\r\n\r\n<div class="Social-Boxs Social-Linkedin span3">\r\n<p class="FontStyle20"><a href="#">LinkedIn</a></p>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<p class="FontStyle20 offset5">Send us a Quick Message!</p>\r\n\r\n<form class="form-inline form-horizontal" role="form">\r\n<div class="row-fluid">\r\n<div class="form-group span6"><label class="sr-only" for="your_name">Your Name</label> <input class="form-control textbox1" id="your_name" placeholder="Your Name" type="text" /></div>\r\n\r\n<div class="form-group span6"><label class="sr-only" for="emial">Your Email Address</label> <input class="form-control textbox1" id="emial" placeholder="Your Email Address" type="email" /></div>\r\n</div>\r\n\r\n<div class="row-fluid marT10">\r\n<div class="span12 form-group"><label class="sr-only" for="category">Select Category</label> <input class="form-control textbox1" id="category" placeholder="Select Category" type="text" /></div>\r\n</div>\r\n\r\n<div class="row-fluid">\r\n<div class="span12 form-group marT10"><label class="sr-only" for="message">Your Message</label><textarea class="textarea" id="select-subject" placeholder="Your Message" rows="3"></textarea></div>\r\n</div>\r\n\r\n<div class="row-fluid marT10">\r\n<div class="span12 "><button class="btn btn-primary" type="submit">Submit</button></div>\r\n</div>\r\n</form>\r\n</div>\r\n</div>\r\n</div>\r\n', '', 1, NULL, 1, 0, 0, '/contact-us', NULL, 0, 9, 10, '', 'page', '2014-01-03 05:14:24', '0000-00-00 00:00:00');
INSERT INTO `nodes` VALUES (7, NULL, 1, 'update', 'update', '<div class="span9">\r\n<h2 class="page-title">Updates</h2>\r\n\r\n<div class="StaticPageRight-Block">\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="media" src="images/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. <a href="#">Read More</a></p>\r\n&nbsp;\r\n\r\n<p>Posted on: feb 12, 2013</p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="media" src="images/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. <a href="#">Read More</a></p>\r\n&nbsp;\r\n\r\n<p>Posted on: feb 12, 2013</p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="media" src="images/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. <a href="#">Read More</a></p>\r\n&nbsp;\r\n\r\n<p>Posted on: feb 12, 2013</p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="media" src="images/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. <a href="#">Read More</a></p>\r\n&nbsp;\r\n\r\n<p>Posted on: feb 12, 2013</p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="media" src="images/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. <a href="#">Read More</a></p>\r\n&nbsp;\r\n\r\n<p>Posted on: feb 12, 2013</p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="media" src="images/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. <a href="#">Read More</a></p>\r\n&nbsp;\r\n\r\n<p>Posted on: feb 12, 2013</p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="media" src="images/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. <a href="#">Read More</a></p>\r\n&nbsp;\r\n\r\n<p>Posted on: feb 12, 2013</p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="media" src="images/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. <a href="#">Read More</a></p>\r\n&nbsp;\r\n\r\n<p>Posted on: feb 12, 2013</p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n', '', 1, NULL, 1, 0, 0, '/page/update', NULL, 0, 11, 12, '["3"]', 'page', '2014-02-18 07:22:38', '2014-01-03 05:25:33');
INSERT INTO `nodes` VALUES (8, NULL, 1, 'Testimonials', 'testimonials', '<div class="span9">\r\n<h2 class="page-title">Testimonial</h2>\r\n\r\n<div class="StaticPageRight-Block">\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n', '', 1, NULL, 1, 0, 0, '/page/testimonials', NULL, 0, 13, 14, '', 'page', '2014-02-18 07:17:48', '2014-02-18 07:15:33');
INSERT INTO `nodes` VALUES (9, NULL, 0, 'media-1', 'media-1.jpg', '', NULL, 0, 'image/jpeg', 1, 0, 0, '/uploads/media-1.jpg', NULL, 0, 1, 2, NULL, 'attachment', '2014-02-18 07:16:27', '2014-02-18 07:16:27');
INSERT INTO `nodes` VALUES (10, NULL, 1, 'Updates', 'updates', '<div class="span9">\r\n<h2 class="page-title">Updates</h2>\r\n\r\n<div class="StaticPageRight-Block">\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n', '', 1, NULL, 1, 0, 0, '/page/updates', NULL, 0, 15, 16, '', 'page', '2014-02-18 07:23:26', '2014-02-18 07:20:01');
INSERT INTO `nodes` VALUES (11, NULL, 1, 'Media', 'media', '<div class="span9">\r\n<h2 class="page-title">Media</h2>\r\n\r\n<div class="StaticPageRight-Block">\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class="PageLeft-Block">\r\n<div class="row-fluid">\r\n<div class="span3 media-img"><a href="#"><img alt="" src="/demos/botangle/uploads/media-1.jpg" /></a></div>\r\n\r\n<div class="span9 media-text">\r\n<p class="FontStyle20"><a href="#">Top 100 stidents of the year</a></p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n&nbsp;\r\n\r\n<p>link: <a href="#">www.duisauterure.com/id=345?newcat234</a></p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n', '', 1, NULL, 1, 0, 0, '/page/media', NULL, 0, 17, 18, '', 'page', '2014-02-18 07:23:15', '2014-02-18 07:20:32');

-- --------------------------------------------------------

-- 
-- Table structure for table `nodes_taxonomies`
-- 

DROP TABLE IF EXISTS `nodes_taxonomies`;
CREATE TABLE `nodes_taxonomies` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `node_id` int(20) NOT NULL DEFAULT '0',
  `taxonomy_id` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `nodes_taxonomies`
-- 

INSERT INTO `nodes_taxonomies` VALUES (1, 1, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `regions`
-- 

DROP TABLE IF EXISTS `regions`;
CREATE TABLE `regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `block_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `region_alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

-- 
-- Dumping data for table `regions`
-- 

INSERT INTO `regions` VALUES (3, 'none', 'none', '', 0);
INSERT INTO `regions` VALUES (4, 'right', 'right', '', 6);
INSERT INTO `regions` VALUES (6, 'left', 'left', '', 0);
INSERT INTO `regions` VALUES (7, 'header', 'header', '', 0);
INSERT INTO `regions` VALUES (8, 'footer', 'footer', '', 0);
INSERT INTO `regions` VALUES (9, 'region1', 'region1', '', 0);
INSERT INTO `regions` VALUES (10, 'region2', 'region2', '', 0);
INSERT INTO `regions` VALUES (11, 'region3', 'region3', '', 0);
INSERT INTO `regions` VALUES (12, 'region4', 'region4', '', 0);
INSERT INTO `regions` VALUES (13, 'region5', 'region5', '', 0);
INSERT INTO `regions` VALUES (14, 'region6', 'region6', '', 0);
INSERT INTO `regions` VALUES (15, 'region7', 'region7', '', 0);
INSERT INTO `regions` VALUES (16, 'region8', 'region8', '', 0);
INSERT INTO `regions` VALUES (17, 'region9', 'region9', '', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `reviews`
-- 

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rating` int(11) NOT NULL,
  `reviews` text NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `rate_by` int(11) NOT NULL,
  `rate_to` int(11) NOT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `reviews`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `roles`
-- 

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `roles`
-- 

INSERT INTO `roles` VALUES (1, 'Admin', 'admin', '2009-04-05 00:10:34', '2009-04-05 00:10:34');
INSERT INTO `roles` VALUES (2, 'Tutor', 'tutor', '2009-04-05 00:10:50', '2009-04-06 05:20:38');
INSERT INTO `roles` VALUES (3, 'Student', 'student', '2009-04-05 00:12:38', '2009-04-07 01:41:45');

-- --------------------------------------------------------

-- 
-- Table structure for table `roles_users`
-- 

DROP TABLE IF EXISTS `roles_users`;
CREATE TABLE `roles_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `granted_by` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pk_role_users` (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `roles_users`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `schema_migrations`
-- 

DROP TABLE IF EXISTS `schema_migrations`;
CREATE TABLE `schema_migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- 
-- Dumping data for table `schema_migrations`
-- 

INSERT INTO `schema_migrations` VALUES (1, 'InitMigrations', 'Migrations', '2013-12-18 00:39:26');
INSERT INTO `schema_migrations` VALUES (2, 'ConvertVersionToClassNames', 'Migrations', '2013-12-18 00:39:26');
INSERT INTO `schema_migrations` VALUES (3, 'IncreaseClassNameLength', 'Migrations', '2013-12-18 00:39:26');
INSERT INTO `schema_migrations` VALUES (4, 'FirstMigrationSettings', 'Settings', '2013-12-18 00:39:27');
INSERT INTO `schema_migrations` VALUES (5, 'FirstMigrationAcl', 'Acl', '2013-12-18 00:39:27');
INSERT INTO `schema_migrations` VALUES (6, 'FirstMigrationBlocks', 'Blocks', '2013-12-18 00:39:27');
INSERT INTO `schema_migrations` VALUES (7, 'FirstMigrationComments', 'Comments', '2013-12-18 00:39:27');
INSERT INTO `schema_migrations` VALUES (8, 'FirstMigrationContacts', 'Contacts', '2013-12-18 00:39:28');
INSERT INTO `schema_migrations` VALUES (9, 'FirstMigrationMenus', 'Menus', '2013-12-18 00:39:28');
INSERT INTO `schema_migrations` VALUES (10, 'FirstMigrationMeta', 'Meta', '2013-12-18 00:39:28');
INSERT INTO `schema_migrations` VALUES (11, 'FirstMigrationNodes', 'Nodes', '2013-12-18 00:39:28');
INSERT INTO `schema_migrations` VALUES (12, 'FirstMigrationTaxonomy', 'Taxonomy', '2013-12-18 00:39:29');
INSERT INTO `schema_migrations` VALUES (13, 'FirstMigrationUsers', 'Users', '2013-12-18 00:39:29');

-- --------------------------------------------------------

-- 
-- Table structure for table `settings`
-- 

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `key` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `input_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text',
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  `weight` int(11) DEFAULT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=40 ;

-- 
-- Dumping data for table `settings`
-- 

INSERT INTO `settings` VALUES (6, 'Site.title', 'Botangle', '', '', '', 1, 1, '');
INSERT INTO `settings` VALUES (7, 'Site.tagline', '', '', '', 'textarea', 1, 2, '');
INSERT INTO `settings` VALUES (8, 'Site.email', 'you@your-site.com', '', '', '', 1, 3, '');
INSERT INTO `settings` VALUES (9, 'Site.status', '1', '', '', 'checkbox', 1, 6, '');
INSERT INTO `settings` VALUES (12, 'Meta.robots', 'index, follow', '', '', '', 1, 6, '');
INSERT INTO `settings` VALUES (13, 'Meta.keywords', 'croogo, Croogo', '', '', 'textarea', 1, 7, '');
INSERT INTO `settings` VALUES (14, 'Meta.description', 'Croogo - A CakePHP powered Content Management System', '', '', 'textarea', 1, 8, '');
INSERT INTO `settings` VALUES (15, 'Meta.generator', 'Croogo - Content Management System', '', '', '', 0, 9, '');
INSERT INTO `settings` VALUES (16, 'Service.akismet_key', 'your-key', '', '', '', 1, 11, '');
INSERT INTO `settings` VALUES (17, 'Service.recaptcha_public_key', 'your-public-key', '', '', '', 1, 12, '');
INSERT INTO `settings` VALUES (18, 'Service.recaptcha_private_key', 'your-private-key', '', '', '', 1, 13, '');
INSERT INTO `settings` VALUES (19, 'Service.akismet_url', 'http://your-blog.com', '', '', '', 1, 10, '');
INSERT INTO `settings` VALUES (20, 'Site.theme', '', '', '', '', 0, 14, '');
INSERT INTO `settings` VALUES (21, 'Site.feed_url', '', '', '', '', 0, 15, '');
INSERT INTO `settings` VALUES (22, 'Reading.nodes_per_page', '5', '', '', '', 1, 16, '');
INSERT INTO `settings` VALUES (23, 'Writing.wysiwyg', '1', 'Enable WYSIWYG editor', '', 'checkbox', 1, 17, '');
INSERT INTO `settings` VALUES (24, 'Comment.level', '1', '', 'levels deep (threaded comments)', '', 1, 18, '');
INSERT INTO `settings` VALUES (25, 'Comment.feed_limit', '10', '', 'number of comments to show in feed', '', 1, 19, '');
INSERT INTO `settings` VALUES (26, 'Site.locale', 'eng', '', '', 'text', 0, 20, '');
INSERT INTO `settings` VALUES (27, 'Reading.date_time_format', 'D, M d Y H:i:s', '', '', '', 1, 21, '');
INSERT INTO `settings` VALUES (28, 'Comment.date_time_format', 'M d, Y', '', '', '', 1, 22, '');
INSERT INTO `settings` VALUES (29, 'Site.timezone', '0', '', 'zero (0) for GMT', '', 1, 4, '');
INSERT INTO `settings` VALUES (32, 'Hook.bootstraps', 'Settings,Comments,Contacts,Nodes,Meta,Menus,Users,Blocks,Taxonomy,FileManager,Wysiwyg,Ckeditor', '', '', '', 0, 23, '');
INSERT INTO `settings` VALUES (33, 'Comment.email_notification', '1', 'Enable email notification', '', 'checkbox', 1, 24, '');
INSERT INTO `settings` VALUES (34, 'Access Control.multiRole', '0', 'Enable Multiple Roles', '', 'checkbox', 1, 25, '');
INSERT INTO `settings` VALUES (35, 'Access Control.rowLevel', '0', 'Row Level Access Control', '', 'checkbox', 1, 26, '');
INSERT INTO `settings` VALUES (36, 'Access Control.autoLoginDuration', '+1 week', '"Remember Me" Duration', 'Eg: +1 day, +1 week. Leave empty to disable.', 'text', 1, 27, '');
INSERT INTO `settings` VALUES (37, 'Access Control.models', '', 'Models with Row Level Acl', 'Select models to activate Row Level Access Control on', 'multiple', 1, 26, 'multiple=checkbox\noptions={"Nodes.Node": "Node", "Blocks.Block": "Block", "Menus.Menu": "Menu", "Menus.Link": "Link"}');
INSERT INTO `settings` VALUES (38, 'Croogo.installed', '1', '', '', '', 0, 28, '');
INSERT INTO `settings` VALUES (39, 'Croogo.version', '1.5.5', '', '', '', 0, 29, '');

-- --------------------------------------------------------

-- 
-- Table structure for table `taxonomies`
-- 

DROP TABLE IF EXISTS `taxonomies`;
CREATE TABLE `taxonomies` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `parent_id` int(20) DEFAULT NULL,
  `term_id` int(10) NOT NULL,
  `vocabulary_id` int(10) NOT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `taxonomies`
-- 

INSERT INTO `taxonomies` VALUES (1, NULL, 1, 1, 1, 2);
INSERT INTO `taxonomies` VALUES (2, NULL, 2, 1, 3, 4);
INSERT INTO `taxonomies` VALUES (3, NULL, 3, 2, 1, 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `terms`
-- 

DROP TABLE IF EXISTS `terms`;
CREATE TABLE `terms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `terms`
-- 

INSERT INTO `terms` VALUES (1, 'Uncategorized', 'uncategorized', '', '2009-07-22 03:38:43', '2009-07-22 03:34:56');
INSERT INTO `terms` VALUES (2, 'Announcements', 'announcements', '', '2010-05-16 23:57:06', '2009-07-22 03:45:37');
INSERT INTO `terms` VALUES (3, 'mytag', 'mytag', '', '2009-08-26 14:42:43', '2009-08-26 14:42:43');

-- --------------------------------------------------------

-- 
-- Table structure for table `types`
-- 

DROP TABLE IF EXISTS `types`;
CREATE TABLE `types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `format_show_author` tinyint(1) NOT NULL DEFAULT '1',
  `format_show_date` tinyint(1) NOT NULL DEFAULT '1',
  `comment_status` int(1) NOT NULL DEFAULT '1',
  `comment_approve` tinyint(1) NOT NULL DEFAULT '1',
  `comment_spam_protection` tinyint(1) NOT NULL DEFAULT '0',
  `comment_captcha` tinyint(1) NOT NULL DEFAULT '0',
  `params` text COLLATE utf8_unicode_ci,
  `plugin` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `types`
-- 

INSERT INTO `types` VALUES (1, 'Page', 'page', 'A page is a simple method for creating and displaying information that rarely changes, such as an "About us" section of a website. By default, a page entry does not allow visitor comments.', 0, 0, 0, 1, 0, 0, '', '', '2009-09-09 00:23:24', '2009-09-02 18:06:27');
INSERT INTO `types` VALUES (2, 'Blog', 'blog', 'A blog entry is a single post to an online journal, or blog.', 1, 1, 2, 1, 0, 0, '', '', '2009-09-15 12:15:43', '2009-09-02 18:20:44');
INSERT INTO `types` VALUES (4, 'Node', 'node', 'Default content type.', 1, 1, 2, 1, 0, 0, '', '', '2009-10-06 21:53:15', '2009-09-05 23:51:56');

-- --------------------------------------------------------

-- 
-- Table structure for table `types_vocabularies`
-- 

DROP TABLE IF EXISTS `types_vocabularies`;
CREATE TABLE `types_vocabularies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type_id` int(10) NOT NULL,
  `vocabulary_id` int(10) NOT NULL,
  `weight` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=33 ;

-- 
-- Dumping data for table `types_vocabularies`
-- 

INSERT INTO `types_vocabularies` VALUES (24, 4, 1, NULL);
INSERT INTO `types_vocabularies` VALUES (25, 4, 2, NULL);
INSERT INTO `types_vocabularies` VALUES (30, 2, 1, NULL);
INSERT INTO `types_vocabularies` VALUES (31, 2, 2, NULL);
INSERT INTO `types_vocabularies` VALUES (32, 1, 3, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `user_rates`
-- 

DROP TABLE IF EXISTS `user_rates`;
CREATE TABLE `user_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `price_type` varchar(10) NOT NULL,
  `rate` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `user_rates`
-- 

INSERT INTO `user_rates` VALUES (1, 4, 'permin', 201);

-- --------------------------------------------------------

-- 
-- Table structure for table `usermessages`
-- 

DROP TABLE IF EXISTS `usermessages`;
CREATE TABLE `usermessages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `send_to` int(11) NOT NULL,
  `sent_from` int(11) NOT NULL,
  `body` text NOT NULL,
  `attached_files` varchar(255) NOT NULL,
  `readmessage` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- 
-- Dumping data for table `usermessages`
-- 

INSERT INTO `usermessages` VALUES (1, 4, 8, 'Hi sir\r\ncan you please create a message for me\r\n', '', 1, 1, '2014-02-16 12:48:07');
INSERT INTO `usermessages` VALUES (2, 8, 4, 'Hi, Ok deepak', '', 1, 1, '2014-02-16 12:49:23');
INSERT INTO `usermessages` VALUES (3, 4, 8, 'Hi sir\r\nplease go to lesson section and create lesson for today date', '', 1, 1, '2014-02-16 12:50:18');
INSERT INTO `usermessages` VALUES (4, 0, 4, ' Our Lesson is setup now. Please click here to read.', '', 0, 4, '2014-02-16 12:54:43');
INSERT INTO `usermessages` VALUES (5, 4, 8, 'Hi, Deepak, plase create alesson for me.', '', 1, 1, '2014-02-17 01:52:22');
INSERT INTO `usermessages` VALUES (6, 8, 4, 'hello', '', 1, 1, '2014-02-17 02:00:13');
INSERT INTO `usermessages` VALUES (7, 8, 4, 'hello', '', 1, 1, '2014-02-17 02:00:45');
INSERT INTO `usermessages` VALUES (8, 8, 4, 'nope', '', 1, 1, '2014-02-17 02:02:50');
INSERT INTO `usermessages` VALUES (9, 0, 4, ' Our Lesson is setup now. Please click here to read.', '', 0, 9, '2014-02-17 02:18:38');
INSERT INTO `usermessages` VALUES (10, 9, 1, 'hi', '', 1, 10, '2014-02-18 08:02:14');

-- --------------------------------------------------------

-- 
-- Table structure for table `userpoints`
-- 

DROP TABLE IF EXISTS `userpoints`;
CREATE TABLE `userpoints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `trophyamountlesson` varchar(20) NOT NULL,
  `paid_or_not` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `userpoints`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `username` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activation_key` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8_unicode_ci,
  `timezone` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `qualification` text COLLATE utf8_unicode_ci NOT NULL,
  `teaching_experience` text COLLATE utf8_unicode_ci NOT NULL,
  `extracurricular_interests` text COLLATE utf8_unicode_ci NOT NULL,
  `university` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `other_experience` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expertise` text COLLATE utf8_unicode_ci NOT NULL,
  `aboutme` text COLLATE utf8_unicode_ci NOT NULL,
  `profilepic` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  `is_online` tinyint(1) NOT NULL,
  `lastactivity` int(11) DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` VALUES (1, 1, 'admin', '03d01661940815fd4744b471cba382e11e32fd7d', 'admin', '', '', NULL, '977619abaf61d3a01a07e9c8aa3ccce0', NULL, NULL, '0', '', '', '', '', '', '', '', '', '', 1, '2014-02-01 13:12:08', '2013-12-18 00:40:15', 0, 1392729218, 0);
INSERT INTO `users` VALUES (2, 2, 'deepak', '03d01661940815fd4744b471cba382e11e32fd7d', 'jain', '', 'test@test.com', NULL, 'ec0ae615f26023e039fd8b8153fd3774', NULL, NULL, '0', 'deepak', '123456789', '', '', '', '', '', '', '', 1, '2014-02-15 22:49:09', '2013-12-31 19:26:38', 1, 1392522718, 1);
INSERT INTO `users` VALUES (3, 2, 'jain', '03d01661940815fd4744b471cba382e11e32fd7d', 'deepak', '', 'testing@test.com', NULL, '0edb999de3886d62afff35719becf65e', NULL, NULL, '0', 'hindi', 'm.com', '', '', '', '', '', '', '', 1, '2013-12-31 19:31:15', '2013-12-31 19:31:15', 0, 0, 0);
INSERT INTO `users` VALUES (4, 2, 'jaindeepak', '03d01661940815fd4744b471cba382e11e32fd7d', 'deepak', '', 'testingstudent@student.com', NULL, '2f6ea0cf26902ac3b89a98ce7396234b', NULL, NULL, '0', 'B.Com,M.com', 'MBA in Business management', '8 years', 'Music, Teaching, sports', '', '', 'English, Maths', '', '016.png', 1, '2014-02-18 02:06:12', '2014-01-01 18:24:52', 1, 1392721571, 1);
INSERT INTO `users` VALUES (5, 2, 'ram', '03d01661940815fd4744b471cba382e11e32fd7d', 'Sharma', '', 'ramphoolgangwal@gmail.com', '', '055185961b8f7b3a9341a0da6e953d7d', NULL, NULL, '0', '', '', '', '', '', '', '', '', '', 1, '2014-01-03 05:31:25', '2014-01-02 13:07:59', 0, 0, 0);
INSERT INTO `users` VALUES (6, 2, 'Erik', '7ad7875b8e064d47b0efa887256458513168997e', 'Finman', '', 'erikejf@gmail.com', NULL, 'd1f5c899f373c4564415b6f975516545', NULL, NULL, '0', '', '', '', '', '', '', '', '', '', 1, '2014-02-07 14:26:43', '2014-01-03 12:40:42', 0, 0, 1);
INSERT INTO `users` VALUES (8, 4, 'deepak4', '10dcf793b1eccb1ba0d4615a814014381d7b6592', 'jain', '', 'test12@Test.com', NULL, '325a6e58f2e139e5a9f3588d35cc5c24', NULL, NULL, '0', '', '', '', ' My Interests:\r\n My Interests:\r\n My Interests:\r\n My Interests:\r\n', '', '', '', ' About Me:\r\n About Me:\r\n About Me:\r\n About Me:\r\n', '002.jpg', 1, '2014-02-18 00:12:51', '2014-01-12 12:38:59', 1, 1392714767, 0);
INSERT INTO `users` VALUES (9, 2, 'Ezekiel', '20a0fe060c2fc61e79088fe26ed2b27876f0f33b', 'Carsella', '', 'bookntech@outlook.com', NULL, 'ddc22a48c4b1f646f57774fb52967a48', NULL, NULL, '0', 'Creative Writing', 'Published author', 'None', 'Journalism, basketball,', '', '', 'Journalism, English, History', '', '', 1, '2014-02-03 21:31:54', '2014-02-03 19:09:19', 1, 1391481167, 0);
INSERT INTO `users` VALUES (10, 3, 'Sam', '20a0fe060c2fc61e79088fe26ed2b27876f0f33b', 'Gall', '', 'spartananoles@gmail.com', NULL, '41a197d0bccbd64f061cd4c5538ab189', NULL, NULL, '0', '', '', '', '', '', '', '', '', '', 1, '2014-02-03 19:34:25', '2014-02-03 19:32:34', 1, 1391481111, 0);
INSERT INTO `users` VALUES (11, 3, 'Jake', '8fa91a808713bdacca23d55bab543ab5028a1e74', 'Merz', '', 'jmerzian@gmail.com', NULL, 'e7165695caca60d34d97e10c5be75e43', NULL, NULL, '0', '', '', '', '', '', '', '', '', '', 1, '2014-02-03 21:45:25', '2014-02-03 21:43:05', 0, 0, 0);
INSERT INTO `users` VALUES (12, 3, 'Scott', '5fc4d81f332b17b190a6fb6b3b364edaae203487', 'James', '', 's+botanglestudent@r3s7.com', NULL, '715d1a035d8d8b5adab7dd141f331bb9', NULL, NULL, '0', '', '', '', '', '', '', '', '', '', 1, '2014-02-04 12:37:21', '2014-02-04 12:31:40', 0, 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `vocabularies`
-- 

DROP TABLE IF EXISTS `vocabularies`;
CREATE TABLE `vocabularies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `multiple` tinyint(1) NOT NULL DEFAULT '0',
  `tags` tinyint(1) NOT NULL DEFAULT '0',
  `plugin` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vocabulary_alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `vocabularies`
-- 

INSERT INTO `vocabularies` VALUES (1, 'Categories', 'categories', '', 0, 1, 0, '', 1, '2010-05-17 20:03:11', '2009-07-22 02:16:21');
INSERT INTO `vocabularies` VALUES (2, 'Tags', 'tags', '', 0, 1, 0, '', 2, '2010-05-17 20:03:11', '2009-07-22 02:16:34');
INSERT INTO `vocabularies` VALUES (3, 'Faq', 'faq', 'faq', 0, 0, 0, NULL, NULL, '2013-12-23 19:23:48', '2013-12-23 19:23:48');
