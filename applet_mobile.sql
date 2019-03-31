/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : localhost:3306
 Source Schema         : applet_mobile

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 31/03/2019 11:07:40
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_log`;
CREATE TABLE `admin_log`  (
  `admin_log_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '管理员登录日志id',
  `username` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ip地址',
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'ip所在地',
  `login_time` int(10) NULL DEFAULT NULL COMMENT '登陆时间',
  PRIMARY KEY (`admin_log_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 113 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu`  (
  `admin_menu_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_menu_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '栏目名称',
  `controller` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '方法',
  `parent_id` tinyint(4) NOT NULL DEFAULT 0 COMMENT '父级id',
  `sort` tinyint(4) NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`admin_menu_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 54 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台栏目表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
INSERT INTO `admin_menu` VALUES (31, '栏目管理', '', '', 0, 3, 1550470417, 1550470417);
INSERT INTO `admin_menu` VALUES (32, '后台栏目', 'AdminMenu', 'index', 31, 0, 1550470435, 1550470435);
INSERT INTO `admin_menu` VALUES (37, '小程序栏目', 'appletmenu', 'index', 31, 0, 1550470563, 1550470563);
INSERT INTO `admin_menu` VALUES (38, '商城管理', '', '', 0, 2, 1550470578, 1550470578);
INSERT INTO `admin_menu` VALUES (39, '商品分类', 'GoodsCate', 'index', 38, 0, 1550470596, 1550470596);
INSERT INTO `admin_menu` VALUES (40, '会员管理', '', '', 0, 1, 1550471094, 1550471094);
INSERT INTO `admin_menu` VALUES (41, '商城会员', 'User', 'index', 40, 0, 1550471206, 1550471206);
INSERT INTO `admin_menu` VALUES (42, '管理员管理', '', '', 0, 1, 1550471238, 1550471238);
INSERT INTO `admin_menu` VALUES (43, '后台管理员', 'AdminUser', 'index', 42, 0, 1550471254, 1550471254);
INSERT INTO `admin_menu` VALUES (44, '商品列表', 'Goods', 'index', 38, 0, 1550471391, 1550471391);
INSERT INTO `admin_menu` VALUES (45, '首页轮播图', 'SlideShow', 'index', 38, 0, 1550799598, 1550799598);
INSERT INTO `admin_menu` VALUES (46, '热销商品', 'GoodsHot', 'index', 38, 1, 1551406662, 1551406662);
INSERT INTO `admin_menu` VALUES (47, '秒杀商品', 'GoodsSkill', 'index', 38, 0, 1552302483, 1552302483);
INSERT INTO `admin_menu` VALUES (48, '订单管理', '', '', 0, 2, 1552981682, 1552981682);
INSERT INTO `admin_menu` VALUES (49, '订单列表', 'Order', 'index', 48, 0, 1552981702, 1552981702);
INSERT INTO `admin_menu` VALUES (50, '权限管理', '', '', 0, 0, 1552996871, 1552996871);
INSERT INTO `admin_menu` VALUES (51, '权限列表', 'Permission', 'index', 50, 0, 1552998298, 1552998298);
INSERT INTO `admin_menu` VALUES (52, '角色列表', 'Role', 'index', 50, 0, 1552998317, 1552998317);
INSERT INTO `admin_menu` VALUES (53, '用户角色列表', 'UserRole', 'index', 50, 0, 1552998346, 1552998346);

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user`  (
  `admin_user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_user_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `admin_user_pwd` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户密码',
  `admin_user_avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户头像',
  `admin_user_state` tinyint(1) NOT NULL DEFAULT 0 COMMENT '用户状态 0未激活 1激活',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`admin_user_id`) USING BTREE,
  UNIQUE INDEX `admin_user_name`(`admin_user_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 40 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台用户表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES (1, 'admin', '83a821140fe154ad08fce03f35006401', '', 1, 1546424597, 1546424597);

-- ----------------------------
-- Table structure for applet_menu
-- ----------------------------
DROP TABLE IF EXISTS `applet_menu`;
CREATE TABLE `applet_menu`  (
  `applet_menu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `applet_menu_title` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '栏目标题',
  `applet_menu_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '小程序栏目图标',
  `applet_menu_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '小程序栏目url',
  `applet_menu_sort` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '排序',
  `is_open` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否对外开放',
  `created_at` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_at` int(10) NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`applet_menu_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '小程序分类表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for goods
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods`  (
  `goods_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '商品名称',
  `goods_desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '商品描述',
  `goods_image_urls` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品封面图地址',
  `goods_price` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '商品定价',
  `goods_sprice` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '商品定价',
  `goods_stock` int(10) NULL DEFAULT 0 COMMENT '商品库存',
  `goods_express` decimal(19, 2) NULL DEFAULT 0.00,
  `goods_cate_id` int(11) NULL DEFAULT 0 COMMENT '商品分类',
  `create_time` int(10) NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`goods_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for goods_cate
-- ----------------------------
DROP TABLE IF EXISTS `goods_cate`;
CREATE TABLE `goods_cate`  (
  `goods_cate_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `goods_cate_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '商品分类名称',
  `parent_id` tinyint(4) NOT NULL DEFAULT 0 COMMENT '父级id',
  `sort` tinyint(4) NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`goods_cate_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 39 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品分类表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of goods_cate
-- ----------------------------
INSERT INTO `goods_cate` VALUES (33, '手机', 0, 1, 1550470736, 1550470736);
INSERT INTO `goods_cate` VALUES (34, '电脑', 0, 0, 1550715775, 1550715775);
INSERT INTO `goods_cate` VALUES (35, '衣服', 0, 0, 1551405808, 1551405808);
INSERT INTO `goods_cate` VALUES (36, '鞋子', 0, 0, 1551405815, 1551405815);
INSERT INTO `goods_cate` VALUES (37, '裤子', 0, 0, 1551405825, 1551405825);
INSERT INTO `goods_cate` VALUES (38, '帽子', 0, 0, 1551405849, 1551405849);

-- ----------------------------
-- Table structure for goods_detail_param
-- ----------------------------
DROP TABLE IF EXISTS `goods_detail_param`;
CREATE TABLE `goods_detail_param`  (
  `goods_dp_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `goods_detail` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品详情',
  `goods_param` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品参数',
  `goods_id` int(11) NULL DEFAULT 0 COMMENT '商品id',
  PRIMARY KEY (`goods_dp_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品详情及参数' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for goods_hot
-- ----------------------------
DROP TABLE IF EXISTS `goods_hot`;
CREATE TABLE `goods_hot`  (
  `goods_hot_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NULL DEFAULT 0 COMMENT '商品id',
  `sort` int(10) NULL DEFAULT 0 COMMENT '排序',
  `create_time` int(10) NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`goods_hot_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '热销商品' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for goods_skill
-- ----------------------------
DROP TABLE IF EXISTS `goods_skill`;
CREATE TABLE `goods_skill`  (
  `goods_skill_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `goods_skill_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '秒杀商品名称',
  `goods_skill_desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '秒杀商品描述',
  `goods_skill_image_urls` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '秒杀商品封面图地址',
  `goods_skill_price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '秒杀商品定价',
  `goods_skill_sprice` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '秒杀价',
  `goods_skill_stock` int(10) NOT NULL DEFAULT 0 COMMENT '秒杀商品库存',
  `goods_skill_express` decimal(19, 2) NOT NULL DEFAULT 0.00 COMMENT '快递费',
  `goods_cate_id` int(11) NOT NULL DEFAULT 0 COMMENT '商品分类',
  `goods_skill_detail` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '秒杀商品详情',
  `goods_skill_param` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '秒杀商品参数',
  `goods_skill_time` datetime NULL DEFAULT NULL COMMENT '秒杀时间',
  `is_end` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否结束',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`goods_skill_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '秒杀商品表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for goods_words
-- ----------------------------
DROP TABLE IF EXISTS `goods_words`;
CREATE TABLE `goods_words`  (
  `goods_words_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `goods_words` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '分词',
  `goods_id` int(11) NOT NULL DEFAULT 0 COMMENT '商品id',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`goods_words_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order`  (
  `order_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `goods_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '商品id',
  `goods_num` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '商品数量',
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `address_id` int(11) NOT NULL DEFAULT 0 COMMENT '收货地址id',
  `order_sn` varchar(19) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '订单号',
  `order_express` decimal(19, 2) NOT NULL DEFAULT 0.00 COMMENT '订单运费',
  `order_amount` decimal(19, 2) NOT NULL DEFAULT 0.00 COMMENT '订单总额',
  `order_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '订单状态 0-未付款 1-已付款 2-已退款 3-已发货 4-已收货',
  `order_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'pt' COMMENT '订单商品类型 skill-秒杀商品 pt-普通商品',
  `user_msg` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户留言',
  `parent_id` tinyint(4) NOT NULL DEFAULT 0 COMMENT '父子订单id ,0-父订单',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`order_id`) USING BTREE,
  INDEX `goods_id`(`goods_id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '订单' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for permission
-- ----------------------------
DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission`  (
  `permission_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `controller` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '方法',
  `method` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'GET' COMMENT '请求方法',
  `permission_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '权限名称',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`permission_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 74 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of permission
-- ----------------------------
INSERT INTO `permission` VALUES (1, 'Login', 'login', 'POST', '后台登录', 1553051785);
INSERT INTO `permission` VALUES (2, 'AdminMenu', 'index', 'GET', '后台栏目', 1553051890);
INSERT INTO `permission` VALUES (3, 'AdminMenu', 'add', 'GET', '后台栏目--添加', 1553051959);
INSERT INTO `permission` VALUES (4, 'AdminMenu', 'add', 'POST', '后台栏目--添加', 1553051972);
INSERT INTO `permission` VALUES (5, 'AdminMenu', 'update', 'GET', '后台栏目--修改', 1553052025);
INSERT INTO `permission` VALUES (6, 'AdminMenu', 'update', 'POST', '后台栏目--修改', 1553052045);
INSERT INTO `permission` VALUES (7, 'AdminMenu', 'delete', 'GET', '后台栏目--删除', 1553052079);
INSERT INTO `permission` VALUES (8, 'AdminMenu', 'sort', 'GET', '后台栏目--排序', 1553052761);
INSERT INTO `permission` VALUES (9, 'Goods', 'index', 'GET', '商品列表', 1553052930);
INSERT INTO `permission` VALUES (10, 'Goods', 'add', 'GET', '商品--添加', 1553052954);
INSERT INTO `permission` VALUES (11, 'Goods', 'add', 'POST', '商品--添加', 1553052975);
INSERT INTO `permission` VALUES (12, 'Goods', 'update', 'GET', '商品--修改', 1553052996);
INSERT INTO `permission` VALUES (13, 'Goods', 'update', 'POST', '商品--修改', 1553053011);
INSERT INTO `permission` VALUES (14, 'Goods', 'delete', 'GET', '商品--删除', 1553053079);
INSERT INTO `permission` VALUES (15, 'Goods', 'goodsList', 'GET', '获取商品列表', 1553053116);
INSERT INTO `permission` VALUES (16, 'GoodsHot', 'index', 'GET', '热销商品', 1553053216);
INSERT INTO `permission` VALUES (17, 'GoodsHot', 'add', 'GET', '热销商品--添加', 1553053238);
INSERT INTO `permission` VALUES (18, 'GoodsHot', 'add', 'POST', '热销商品--添加', 1553053256);
INSERT INTO `permission` VALUES (19, 'GoodsHot', 'update', 'GET', '热销商品--修改', 1553053285);
INSERT INTO `permission` VALUES (20, 'GoodsHot', 'update', 'POST', '热销商品--修改', 1553053301);
INSERT INTO `permission` VALUES (21, 'GoodsHot', 'delete', 'GET', '热销商品--删除', 1553053317);
INSERT INTO `permission` VALUES (22, 'GoodsHot', 'sort', 'GET', '热销商品--排序', 1553053339);
INSERT INTO `permission` VALUES (23, 'GoodsSkill', 'index', 'GET', '秒杀商品', 1553061092);
INSERT INTO `permission` VALUES (24, 'GoodsSkill', 'add', 'GET', '秒杀商品--添加', 1553061119);
INSERT INTO `permission` VALUES (25, 'GoodsSkill', 'add', 'POST', '秒杀商品--添加', 1553061149);
INSERT INTO `permission` VALUES (26, 'GoodsSkill', 'update', 'GET', '秒杀商品--修改', 1553061174);
INSERT INTO `permission` VALUES (27, 'GoodsSkill', 'update', 'POST', '秒杀商品--修改', 1553061198);
INSERT INTO `permission` VALUES (28, 'GoodsSkill', 'delete', 'GET', '秒杀商品--删除', 1553061236);
INSERT INTO `permission` VALUES (29, 'SlideShow', 'index', 'GET', '首页轮播图', 1553061282);
INSERT INTO `permission` VALUES (30, 'SlideShow', 'add', 'GET', '首页轮播图--添加', 1553061300);
INSERT INTO `permission` VALUES (31, 'SlideShow', 'add', 'POST', '首页轮播图--添加', 1553061324);
INSERT INTO `permission` VALUES (32, 'SlideShoe', 'update', 'GET', '首页轮播图--修改', 1553061361);
INSERT INTO `permission` VALUES (33, 'SlideShow', 'update', 'POST', '首页轮播图--修改', 1553061380);
INSERT INTO `permission` VALUES (34, 'SlideShow', 'delete', 'GET', '首页轮播图--删除', 1553061413);
INSERT INTO `permission` VALUES (35, 'SlideShow', 'sort', 'GET', '首页轮播图--排序', 1553061438);
INSERT INTO `permission` VALUES (36, 'GoodsCate', 'index', 'GET', '商品分类', 1553061503);
INSERT INTO `permission` VALUES (37, 'GoodsCate', 'add', 'GET', '商品分类--添加', 1553061523);
INSERT INTO `permission` VALUES (38, 'GoodsCate', 'add', 'POST', '商品分类--添加', 1553061550);
INSERT INTO `permission` VALUES (39, 'GoodsCate', 'update', 'GET', '商品分类--修改', 1553061569);
INSERT INTO `permission` VALUES (40, 'GoodsCate', 'update', 'POST', '商品分类--修改', 1553061600);
INSERT INTO `permission` VALUES (41, 'GoodsCate', 'delete', 'GET', '商品分类--删除', 1553061679);
INSERT INTO `permission` VALUES (42, 'GoodsCate', 'sort', 'GET', '商品分类--排序', 1553061704);
INSERT INTO `permission` VALUES (43, 'Order', 'index', 'GET', '订单列表', 1553061779);
INSERT INTO `permission` VALUES (44, 'User', 'index', 'GET', '商城会员', 1553062620);
INSERT INTO `permission` VALUES (45, 'User', 'state', 'GET', '商城会员--访问统计', 1553062670);
INSERT INTO `permission` VALUES (46, 'AdminUser', 'index', 'GET', '后台管理员', 1553062714);
INSERT INTO `permission` VALUES (47, 'Permission', 'index', 'GET', '权限列表', 1553062765);
INSERT INTO `permission` VALUES (48, 'Permission', 'add', 'GET', '权限--添加', 1553062802);
INSERT INTO `permission` VALUES (49, 'Permission', 'add', 'POST', '权限--添加', 1553062830);
INSERT INTO `permission` VALUES (50, 'Permission', 'delete', 'GET', '权限--删除', 1553062862);
INSERT INTO `permission` VALUES (54, 'Role', 'index', 'GET', '角色列表', 1553134465);
INSERT INTO `permission` VALUES (55, 'Role', 'add', 'GET', '角色--添加', 1553134494);
INSERT INTO `permission` VALUES (56, 'Role', 'add', 'POST', '角色--添加', 1553134504);
INSERT INTO `permission` VALUES (57, 'Role', 'update', 'GET', '角色--修改', 1553134517);
INSERT INTO `permission` VALUES (58, 'Role', 'update', 'POST', '角色--修改', 1553134569);
INSERT INTO `permission` VALUES (59, 'Role', 'delete', 'GET', '角色--删除', 1553134592);
INSERT INTO `permission` VALUES (60, 'UserRole', 'index', 'GET', '用户角色列表', 1553134737);
INSERT INTO `permission` VALUES (61, 'UserRole', 'add', 'GET', '用户角色--添加', 1553134759);
INSERT INTO `permission` VALUES (62, 'UserRole', 'add', 'POST', '用户角色--添加', 1553134775);
INSERT INTO `permission` VALUES (63, 'UserRole', 'update', 'GET', '用户角色--修改', 1553134850);
INSERT INTO `permission` VALUES (64, 'UserRole', 'update', 'POST', '用户角色--修改', 1553134871);
INSERT INTO `permission` VALUES (65, 'UserRole', 'delete', 'GET', '用户角色--删除', 1553134899);
INSERT INTO `permission` VALUES (66, 'UploadFile', 'uploadImage', 'POST', '腾讯云cos上传图片', 1553135135);
INSERT INTO `permission` VALUES (67, 'UploadFile', 'delimage', 'POST', '腾讯云cos删除图片', 1553135216);
INSERT INTO `permission` VALUES (68, 'UploadFile', 'ueditorUploadImage', 'POST', '腾讯云cos上传图片(ueditor)', 1553135330);
INSERT INTO `permission` VALUES (69, 'AdminUser', 'add', 'GET', '后台管理员--添加', 1553160719);
INSERT INTO `permission` VALUES (70, 'AdminUser', 'add', 'POST', '后台管理员--添加', 1553160729);
INSERT INTO `permission` VALUES (71, 'AdminUser', 'update', 'GET', '后台管理员--修改', 1553160745);
INSERT INTO `permission` VALUES (72, 'AdminUser', 'update', 'POST', '后台管理员--修改', 1553160761);
INSERT INTO `permission` VALUES (73, 'AdminUser', 'delete', 'GET', '后台管理员--删除', 1553160781);

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role`  (
  `role_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名称',
  `permissions` tinytext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色权限',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES (1, '超级管理员', '[\"1\", \"2\", \"3\", \"4\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"12\", \"13\", \"14\", \"15\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\", \"24\", \"25\", \"26\", \"27\", \"28\", \"29\", \"30\", \"31\", \"32\", \"33\", \"34\", \"35\", \"36\", \"37\", \"38\", \"39\", \"40\", \"41\", \"42\", \"43\", \"44\",', 1553160671, 1553160671);
INSERT INTO `role` VALUES (3, '游客', '[\"1\", \"2\", \"3\", \"5\", \"7\", \"9\", \"10\", \"12\", \"15\", \"17\", \"19\", \"23\", \"24\", \"26\", \"29\", \"30\", \"32\", \"36\", \"39\", \"40\", \"43\", \"44\", \"45\", \"46\", \"47\", \"54\", \"55\", \"61\", \"63\", \"69\", \"71\"]', 1553166719, 1553166719);

-- ----------------------------
-- Table structure for slide_show
-- ----------------------------
DROP TABLE IF EXISTS `slide_show`;
CREATE TABLE `slide_show`  (
  `slide_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `image_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '图片地址',
  `goods_id` int(11) NULL DEFAULT 0 COMMENT '商品id',
  `sort` int(10) NULL DEFAULT 0 COMMENT '排序',
  `create_time` int(10) NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`slide_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '首页轮播图' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_openid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户openid',
  `user_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `user_avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户头像',
  `user_gender` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '用户性别',
  `user_address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户微信所在地',
  `api_token` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '接口令牌',
  `login_time` int(10) NOT NULL DEFAULT 0 COMMENT '用户登录时间',
  PRIMARY KEY (`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for user_address
-- ----------------------------
DROP TABLE IF EXISTS `user_address`;
CREATE TABLE `user_address`  (
  `address_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `addressee` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '收件人',
  `mobile` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '联系电话',
  `address` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '省市区',
  `xx_address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '详细地址',
  `address_index` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '省市区',
  `is_mr` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否默认地址',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`address_id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户收获地址' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for user_login_log
-- ----------------------------
DROP TABLE IF EXISTS `user_login_log`;
CREATE TABLE `user_login_log`  (
  `login_log_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '会员id',
  `user_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `login_time` int(11) NOT NULL DEFAULT 0 COMMENT '登陆时间',
  PRIMARY KEY (`login_log_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 110 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '会员登陆日志' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for user_role
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role`  (
  `user_role_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `role_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`user_role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户角色' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of user_role
-- ----------------------------
INSERT INTO `user_role` VALUES (1, 1, 1, 1553066305, 1553066305);
INSERT INTO `user_role` VALUES (4, 39, 3, 1553166413, 1553166413);

SET FOREIGN_KEY_CHECKS = 1;
