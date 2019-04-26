-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2017 at 11:52 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yunqi`
--

-- --------------------------------------------------------

--
-- Table structure for table `think_admin`
--

CREATE TABLE IF NOT EXISTS `think_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` tinyint(1) NOT NULL,
  `inputtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `think_admin`
--

INSERT INTO `think_admin` (`id`, `username`, `password`, `level`, `inputtime`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 0, '2016-08-05 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `think_bond_log`
--

CREATE TABLE IF NOT EXISTS `think_bond_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `bond` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `beizhu` text,
  `inputtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `think_bond_log`
--

INSERT INTO `think_bond_log` (`id`, `username`, `bond`, `type`, `status`, `beizhu`, `inputtime`) VALUES
(1, '18761530563', '500', 0, 1, NULL, '2017-09-06 10:58:08'),
(2, '18761530563', '500', 0, 1, NULL, '2017-09-06 10:58:23'),
(3, '18761530563', '500', 0, 1, NULL, '2017-09-06 14:13:53'),
(4, '18761530563', '500', 0, 1, NULL, '2017-09-06 16:24:47'),
(6, '18761530563', '2000', 1, 2, '支付宝：18168936728', '2017-09-06 16:34:01'),
(7, '18761530563', '2500', 0, 1, NULL, '2017-09-06 16:39:53'),
(8, '18168936728', '500', 0, 1, NULL, '2017-09-06 18:11:48'),
(9, '18168936728', '500', 0, 1, NULL, '2017-09-07 10:26:06'),
(10, '18168936728', '1000', 1, 1, '您存在违规操作，管理员扣除', '2017-09-07 10:31:07'),
(11, '18168936728', '3500', 0, 1, NULL, '2017-09-07 10:37:47'),
(12, '18168936728', '3500', 1, 1, '支付宝：18168936728', '2017-09-07 10:38:27'),
(13, '18761530563', '2000', 0, 1, '提交的支付宝账号与使命认证不符！', '2017-09-07 15:06:32'),
(14, '18761530563', '4500', 1, 0, '支付宝账号：18168936728，姓名：杨定维', '2017-10-11 14:53:41'),
(15, '18761530563', '1500', 0, 1, NULL, '2017-10-11 14:54:17'),
(16, '18761530563', '1500', 1, 0, '支付宝账号：18168936728，杨定维', '2017-10-11 15:24:35'),
(17, '18761530563', '500', 0, 1, NULL, '2017-10-11 15:24:48'),
(18, '18761530563', '500', 0, 1, NULL, '2017-10-11 15:25:03'),
(19, '18761530563', '2000', 0, 1, NULL, '2017-10-12 17:05:18'),
(20, '18761530563', '35.5', 2, 1, '订单（20171012194054qsRVB）超时系统自动撤销扣款', '2017-10-18 17:29:57');

-- --------------------------------------------------------

--
-- Table structure for table `think_money_log`
--

CREATE TABLE IF NOT EXISTS `think_money_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `money` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `beizhu` text,
  `inputtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `think_money_log`
--

INSERT INTO `think_money_log` (`id`, `username`, `money`, `type`, `status`, `beizhu`, `inputtime`) VALUES
(1, '18761530563', '233', 0, 1, '完成订单（20171012193141UV7kb）', '2017-10-18 18:20:24'),
(2, '18761530563', '233', 1, 2, '支付宝：18168936728-姓名：杨定维', '2017-10-19 10:34:20'),
(3, '18761530563', '233', 0, 1, '资金不符合要求', '2017-10-19 10:38:28'),
(4, '18761530563', '233', 1, 0, '支付宝', '2017-10-19 15:48:08');

-- --------------------------------------------------------

--
-- Table structure for table `think_news`
--

CREATE TABLE IF NOT EXISTS `think_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `keysword` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `cateId` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `inputtime` datetime NOT NULL,
  `pic` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `think_news`
--

INSERT INTO `think_news` (`id`, `title`, `keysword`, `description`, `cateId`, `content`, `inputtime`, `pic`) VALUES
(1, '常见问题', '', '', 1, '常见问题', '2017-06-21 00:00:00', ''),
(8, '常见问题1', '常见问题1', '', 1, '常见问题1', '2017-09-04 18:26:08', ''),
(9, '公司简介', '公司简介', '', 8, '资料正在整理中...', '2017-09-05 10:10:49', ''),
(10, '联系我们', '', '', 8, '资料正在整理中...', '2017-09-05 10:11:16', ''),
(11, '人才招聘', '', '', 8, '资料正在整理中...', '2017-09-05 10:11:49', ''),
(12, '押金说明', '', '', 1, '资料正在整理中...', '2017-09-06 09:54:31', ''),
(13, '平台说明', '平台说明', '平台说明', 8, '资料正在整理中......', '2017-10-10 16:19:58', ''),
(14, '代练招募实力打手，优质订单率先领取', '', '', 2, '<p style="font-family:微软雅黑;color:#333333;font-size:14px;">\n	<span style="font-family:微软雅黑, &quot;">云起游戏服务网优质商户“无忧网游”因订单需求，现招募 英雄联盟、王者荣耀 实力打手。只要您有实力，海量高价订单优先发送！</span>\n</p>\n<p style="font-family:微软雅黑;color:#333333;font-size:14px;">\n	<span style="font-family:微软雅黑, &quot;">长期活动奖励；订单完成秒结算；连胜8局福利奖金；客服仲裁绝对公正。只要您有实力，“无忧网游”真诚期待您的加入！</span>\n</p>\n<p style="font-family:微软雅黑;color:#333333;font-size:14px;">\n	<span style="font-family:微软雅黑, &quot;">企业 QQ ：1037745675</span>\n</p>\n<p style="font-family:微软雅黑;color:#333333;font-size:14px;">\n	<span style="font-family:微软雅黑, &quot;">联系电话：18168936728</span>\n</p>', '2017-10-13 19:25:36', ''),
(15, '网站上线通知', '', '', 2, '<span style="color:#333333;font-family:微软雅黑, &quot;font-size:14px;background-color:#FFFFFF;">尊敬的客户：</span><br />\n<span style="color:#333333;font-family:微软雅黑, &quot;font-size:14px;background-color:#FFFFFF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;经过数月的精心策划和筹备，云起游戏服务网全新网站于</span><strong><span style="font-family:微软雅黑, &quot;color:#FF0000;">2017月11月01日0点</span></strong><span style="color:#333333;font-family:微软雅黑, &quot;font-size:14px;background-color:#FFFFFF;">正式上线。</span><br />\n<span style="color:#333333;font-family:微软雅黑, &quot;font-size:14px;background-color:#FFFFFF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;新网站从整体架构、功能模块、图片展示等方面做了全新的调整，改版后的网站内容更加丰富、结构更加清晰，简洁的浏览体验可使您快速获得想要的信息，便捷的支付通道让您感受非一般的快感。</span><br />\n<span style="color:#333333;font-family:微软雅黑, &quot;font-size:14px;background-color:#FFFFFF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;对多年来一如既往支持和帮助我们的新老客户，我们表示衷心的感谢！同时也欢迎新老客户多提宝贵意见，如果有任何疑问可致电我公司：400-888-8888。</span>', '2017-10-13 19:27:14', ''),
(16, '幸运免单活动第一期中奖名单', '', '', 2, '<p style="font-family:微软雅黑;color:#333333;font-size:14px;">\n	<span style="font-family:微软雅黑, &quot;line-height:30px;font-size:16px;background-color:#FFFFFF;">幸运免单活动第一期顺利统计完成。</span>\n</p>\n<p style="font-family:微软雅黑;color:#333333;font-size:14px;">\n	<span style="font-family:微软雅黑, &quot;"><span style="line-height:30px;font-size:16px;background-color:#FFFFFF;">上周第17070期“体彩七星彩”公布的开奖结果为：</span></span>\n</p>\n<p style="font-family:微软雅黑;color:#333333;font-size:14px;">\n	<span style="font-family:微软雅黑, &quot;"><span style="line-height:30px;font-size:16px;background-color:#FFFFFF;"><span class="c-icon c-icon-ball-red op_caipiao_ball_red c-gap-right-small" style="font-family:arial;vertical-align:middle;color:#F54646;line-height:38px;font-size:20px;background:url(&quot;">4</span><span class="c-icon c-icon-ball-red op_caipiao_ball_red c-gap-right-small" style="font-family:arial;vertical-align:middle;color:#F54646;line-height:38px;font-size:20px;background:url(&quot;">7</span><span class="c-icon c-icon-ball-red op_caipiao_ball_red c-gap-right-small" style="font-family:arial;vertical-align:middle;color:#F54646;line-height:38px;font-size:20px;background:url(&quot;">7</span><span class="c-icon c-icon-ball-red op_caipiao_ball_red c-gap-right-small" style="font-family:arial;vertical-align:middle;color:#F54646;line-height:38px;font-size:20px;background:url(&quot;">7</span><span class="c-icon c-icon-ball-red op_caipiao_ball_red c-gap-right-small" style="font-family:arial;vertical-align:middle;color:#F54646;line-height:38px;font-size:20px;background:url(&quot;">0</span><span class="c-icon c-icon-ball-red op_caipiao_ball_red c-gap-right-small" style="font-family:arial;vertical-align:middle;color:#F54646;line-height:38px;font-size:20px;background:url(&quot;">0</span><span class="c-icon c-icon-ball-red op_caipiao_ball_red c-gap-right-small" style="font-family:arial;vertical-align:middle;color:#F54646;line-height:38px;font-size:20px;background:url(&quot;">7</span></span></span>\n</p>', '2017-10-13 19:28:48', ''),
(17, '幸运免单活动第二期中奖名单', '', '', 2, '<p style="font-family:微软雅黑;color:#333333;font-size:14px;">\n	<span style="font-family:微软雅黑, &quot;line-height:30px;font-size:16px;background-color:#FFFFFF;">幸运免单活动第一期顺利统计完成。</span>\n</p>\n<p style="font-family:微软雅黑;color:#333333;font-size:14px;">\n	<span style="font-family:微软雅黑, &quot;"><span style="line-height:30px;font-size:16px;background-color:#FFFFFF;">上周第17070期“体彩七星彩”公布的开奖结果为：</span></span>\n</p>\n<p style="font-family:微软雅黑;color:#333333;font-size:14px;">\n	<span style="font-family:微软雅黑, &quot;"><span style="line-height:30px;font-size:16px;background-color:#FFFFFF;"><span class="c-icon c-icon-ball-red op_caipiao_ball_red c-gap-right-small" style="font-family:arial;vertical-align:middle;color:#F54646;line-height:38px;font-size:20px;background:url(&quot;">4</span><span class="c-icon c-icon-ball-red op_caipiao_ball_red c-gap-right-small" style="font-family:arial;vertical-align:middle;color:#F54646;line-height:38px;font-size:20px;background:url(&quot;">7</span><span class="c-icon c-icon-ball-red op_caipiao_ball_red c-gap-right-small" style="font-family:arial;vertical-align:middle;color:#F54646;line-height:38px;font-size:20px;background:url(&quot;">7</span><span class="c-icon c-icon-ball-red op_caipiao_ball_red c-gap-right-small" style="font-family:arial;vertical-align:middle;color:#F54646;line-height:38px;font-size:20px;background:url(&quot;">7</span><span class="c-icon c-icon-ball-red op_caipiao_ball_red c-gap-right-small" style="font-family:arial;vertical-align:middle;color:#F54646;line-height:38px;font-size:20px;background:url(&quot;">0</span><span class="c-icon c-icon-ball-red op_caipiao_ball_red c-gap-right-small" style="font-family:arial;vertical-align:middle;color:#F54646;line-height:38px;font-size:20px;background:url(&quot;">0</span><span class="c-icon c-icon-ball-red op_caipiao_ball_red c-gap-right-small" style="font-family:arial;vertical-align:middle;color:#F54646;line-height:38px;font-size:20px;background:url(&quot;">7</span></span></span>\n</p>', '2017-10-13 19:29:11', '');

-- --------------------------------------------------------

--
-- Table structure for table `think_newscategory`
--

CREATE TABLE IF NOT EXISTS `think_newscategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `sortid` int(11) DEFAULT NULL,
  `inputtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='新闻分类表' AUTO_INCREMENT=9 ;

--
-- Dumping data for table `think_newscategory`
--

INSERT INTO `think_newscategory` (`id`, `name`, `pid`, `sortid`, `inputtime`) VALUES
(1, '常见问题', 0, 1, '2017-06-20 17:19:10'),
(2, '网站公告', 0, 2, '2017-06-20 17:19:16'),
(6, '代练公告', 0, 6, '2017-06-21 17:47:14'),
(8, '关于我们', 0, 8, '2017-09-05 10:09:28');

-- --------------------------------------------------------

--
-- Table structure for table `think_order`
--

CREATE TABLE IF NOT EXISTS `think_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordernum` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `spic` varchar(255) DEFAULT NULL,
  `epic` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `beizhu` text,
  `inputtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `think_order`
--

INSERT INTO `think_order` (`id`, `ordernum`, `pid`, `username`, `spic`, `epic`, `status`, `beizhu`, `inputtime`) VALUES
(3, '20171012193141UV7kb', 4, '18761530563', '/yunqi/Public/upload/image/20171012/20171012133207_81139.jpg', NULL, 1, '', '2017-10-17 12:31:41'),
(4, '20171012194054qsRVB', 3, '18761530563', '/yunqi/Public/upload/image/20171013/20171013115359_26226.jpg', NULL, 2, NULL, '2017-10-12 19:40:54'),
(5, '20171019110328PaqHO', 1, '18761530563', NULL, NULL, 0, NULL, '2017-10-19 11:03:28');

-- --------------------------------------------------------

--
-- Table structure for table `think_pro`
--

CREATE TABLE IF NOT EXISTS `think_pro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `keysword` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `game` int(11) NOT NULL,
  `qu` int(11) NOT NULL,
  `fu` int(11) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `sec` varchar(255) DEFAULT NULL,
  `request` longtext NOT NULL,
  `price` varchar(255) NOT NULL,
  `bond` varchar(255) NOT NULL,
  `guser` varchar(255) NOT NULL,
  `gpass` varchar(255) NOT NULL,
  `gname` varchar(255) NOT NULL,
  `stime` datetime NOT NULL,
  `etime` datetime NOT NULL,
  `username` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `inputtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `think_pro`
--

INSERT INTO `think_pro` (`id`, `title`, `keysword`, `description`, `game`, `qu`, `fu`, `pic`, `sec`, `request`, `price`, `bond`, `guser`, `gpass`, `gname`, `stime`, `etime`, `username`, `content`, `inputtime`) VALUES
(1, '黄铜4-白银4', '黄铜4-白银4', '黄铜4-白银4', 3, 10, 28, '/tp/Public/upload/image/20171009/20171009082635_82702.jpg', '', '黄铜4-白银4', '55', '600', 'yangdingwei', '123456', 'yangdingwei', '2017-10-09 00:00:00', '2017-10-12 00:00:00', '18761530563', '黄铜4-白银4', '2017-10-09 17:16:00'),
(2, '青铜1-王者5', '', '', 1, 7, 89, '', '', '青铜1-王者5', '255', '600', '18168936728', 'yang123456', '每天的问候', '2017-10-10 00:00:00', '2017-10-13 00:00:00', '', '青铜1-王者5', '2017-10-10 16:44:50'),
(3, '王者荣耀黄金3--铂金1', '', '', 1, 8, 106, '', '', '王者荣耀黄金3--铂金1', '355', '700', 'xiaoyang', '123456', '小羊羔高', '2017-10-10 10:50:00', '2017-10-11 23:55:00', '', '王者荣耀黄金3--铂金1', '2017-10-10 16:59:43'),
(4, '测试', '', '', 1, 9, 121, '', '', '测试', '233', '500', 'yangdignwei', '123456', '杨定维', '2017-10-10 13:45:00', '2017-10-12 14:45:00', '18761530563', '测试', '2017-10-10 19:12:49');

-- --------------------------------------------------------

--
-- Table structure for table `think_procategory`
--

CREATE TABLE IF NOT EXISTS `think_procategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `sortid` int(11) DEFAULT NULL,
  `inputtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='新闻分类表' AUTO_INCREMENT=139 ;

--
-- Dumping data for table `think_procategory`
--

INSERT INTO `think_procategory` (`id`, `name`, `pid`, `sortid`, `inputtime`) VALUES
(1, '王者荣耀', 0, 1, '2017-09-07 15:45:20'),
(40, '手Q1区 王者独尊', 6, 0, '2017-10-09 00:00:00'),
(3, '英雄联盟', 0, 3, '2017-09-07 15:46:18'),
(41, '手Q2区 绝代智谋', 6, 41, '2017-10-09 00:00:00'),
(6, '安卓QQ', 1, 6, '2017-09-07 15:46:58'),
(7, '安卓微信', 1, 7, '2017-09-07 15:47:08'),
(8, '苹果QQ', 1, 8, '2017-09-07 15:47:18'),
(9, '苹果微信', 1, 9, '2017-09-07 15:47:25'),
(10, '电信', 3, 10, '2017-09-07 15:48:28'),
(11, '网通', 3, 11, '2017-09-07 15:48:38'),
(12, '全网络大区', 3, 12, '2017-09-07 15:50:01'),
(13, '艾欧尼亚', 10, 13, '2017-09-07 15:50:20'),
(14, '暗影岛', 10, 14, '2017-09-07 15:50:28'),
(15, '班德尔城', 10, 15, '2017-09-07 15:50:34'),
(16, '裁决之地', 10, 16, '2017-09-07 15:50:41'),
(17, '钢铁烈阳', 10, 17, '2017-09-07 15:50:47'),
(18, '黑色玫瑰', 10, 18, '2017-09-07 15:50:54'),
(19, '巨神峰', 10, 19, '2017-09-07 15:51:02'),
(20, '均衡教派', 10, 20, '2017-09-07 15:51:08'),
(21, '卡拉曼达', 10, 21, '2017-09-07 15:51:14'),
(22, '雷瑟守备', 10, 22, '2017-09-07 15:51:21'),
(23, '诺克萨斯', 10, 23, '2017-09-07 15:51:26'),
(24, '皮城警备', 10, 24, '2017-09-07 15:51:33'),
(25, '皮尔特沃夫', 10, 25, '2017-09-07 15:51:39'),
(26, '守望之海', 10, 26, '2017-09-07 15:51:45'),
(27, '水晶之痕', 10, 27, '2017-09-07 15:51:51'),
(28, '影流', 10, 28, '2017-09-07 15:51:59'),
(29, '战争学院', 10, 29, '2017-09-07 15:52:06'),
(30, '征服之海', 10, 30, '2017-09-07 15:52:13'),
(31, '祖安', 10, 31, '2017-09-07 15:52:22'),
(32, '比尔吉沃特', 11, 32, '2017-09-07 15:52:45'),
(33, '德玛西亚', 11, 33, '2017-09-07 15:52:55'),
(34, '弗雷尔卓德', 11, 34, '2017-09-07 15:53:01'),
(35, '无畏先锋', 11, 35, '2017-09-07 15:53:08'),
(36, '恕瑞玛', 11, 36, '2017-09-07 15:53:15'),
(37, '扭曲丛林', 11, 37, '2017-09-07 15:53:24'),
(38, '巨龙之巢', 11, 38, '2017-09-07 15:53:32'),
(39, '男爵领域', 12, 39, '2017-09-07 15:53:47'),
(42, '手Q3区 不羁之风', 6, 42, '2017-10-09 00:00:00'),
(43, '手Q4区 千金重弩', 6, 43, '2017-10-09 00:00:00'),
(44, '手Q5区 死亡绽放', 6, 44, '2017-10-09 00:00:00'),
(45, '手Q6区 最终兵器', 6, 45, '2017-10-09 00:00:00'),
(46, '手Q7区 叛逆吟游', 6, 46, '2017-10-09 00:00:00'),
(47, '手Q8区 暴走机关', 6, 47, '2017-10-09 00:00:00'),
(48, '手Q9区 欲望之月', 6, 48, '2017-10-09 00:00:00'),
(49, '手Q10区 风华绚烂', 6, 49, '2017-10-09 00:00:00'),
(50, '手Q11区 逍遥幻梦', 6, 50, '2017-10-09 00:00:00'),
(51, '手Q12区 恋之微风', 6, 51, '2017-10-09 00:00:00'),
(52, '手Q13区 和平守望', 6, 52, '2017-10-09 00:00:00'),
(53, '手Q14区 苍天龙鸣', 6, 53, '2017-10-09 00:00:00'),
(54, '手Q15区 天翔之龙', 6, 54, '2017-10-09 00:00:00'),
(55, '手Q16区 破云之龙', 6, 55, '2017-10-09 00:00:00'),
(56, '手Q17区 惊雷之龙', 6, 56, '2017-10-09 00:00:00'),
(57, '手Q18区 友情守护', 6, 57, '2017-10-09 00:00:00'),
(58, '手Q19区 豪情突进', 6, 58, '2017-10-09 00:00:00'),
(59, '手Q20区 激情迸发', 6, 59, '2017-10-09 00:00:00'),
(60, '手Q21区 正义豪腕', 6, 60, '2017-10-09 00:00:00'),
(61, '手Q22区 治愈微笑', 6, 61, '2017-10-09 00:00:00'),
(62, '手Q23区 绽放之舞', 6, 62, '2017-10-09 00:00:00'),
(63, '手Q24区 甜蜜恋风', 6, 63, '2017-10-09 00:00:00'),
(64, '手Q25区 星华缭乱', 6, 64, '2017-10-09 00:00:00'),
(65, '手Q26区 兼爱非攻', 6, 65, '2017-10-09 00:00:00'),
(66, '手Q27区 和平漫步', 6, 66, '2017-10-09 00:00:00'),
(67, '手Q28区 机关重炮', 6, 67, '2017-10-09 00:00:00'),
(68, '手Q29区 墨守成规', 6, 68, '2017-10-09 00:00:00'),
(69, '手Q30区 活力迸发', 6, 69, '2017-10-09 00:00:00'),
(70, '手Q31区 翻滚突袭', 6, 70, '2017-10-09 00:00:00'),
(71, '手Q32区 红莲爆弹', 6, 71, '2017-10-09 00:00:00'),
(72, '手Q33区 究极弩炮', 6, 72, '2017-10-09 00:00:00'),
(73, '手Q34区 诛心剑雨', 6, 73, '2017-10-09 00:00:00'),
(74, '手Q35区 灵魂冲击', 6, 74, '2017-10-09 00:00:00'),
(75, '手Q36区 偶像魅力', 6, 75, '2017-10-09 00:00:00'),
(76, '手Q37区 女王崇拜', 6, 76, '2017-10-09 00:00:00'),
(77, '手Q38区 王朝密令', 6, 77, '2017-10-09 00:00:00'),
(78, '手Q39区 圣光守护', 6, 78, '2017-10-09 00:00:00'),
(79, '微信1区 鲜血枭雄', 7, 79, '2017-10-09 00:00:00'),
(80, '微信2区 国士无双', 7, 80, '2017-10-09 00:00:00'),
(81, '微信3区 太古魔导', 7, 81, '2017-10-09 00:00:00'),
(82, '微信4区 热烈之斧', 7, 82, '2017-10-09 00:00:00'),
(83, '微信5区 双面君主', 7, 83, '2017-10-09 00:00:00'),
(84, '微信6区 万古长明', 7, 84, '2017-10-09 00:00:00'),
(85, '微信7区 洛神降临', 7, 85, '2017-10-09 00:00:00'),
(86, '微信8区 王者审判', 7, 86, '2017-10-09 00:00:00'),
(87, '微信9区 王者惩戒', 7, 87, '2017-10-09 00:00:00'),
(88, '微信10区 王者守御', 7, 88, '2017-10-09 00:00:00'),
(89, '微信11区 至尊王权', 7, 89, '2017-10-09 00:00:00'),
(90, '微信12区 火力压制', 7, 90, '2017-10-09 00:00:00'),
(91, '微信13区 无敌鲨炮', 7, 91, '2017-10-09 00:00:00'),
(92, '微信14区 自然意志', 7, 92, '2017-10-09 00:00:00'),
(93, '微信15区 庄子化蝶', 7, 93, '2017-10-09 00:00:00'),
(94, '微信16区 蝴蝶效应', 7, 94, '2017-10-09 00:00:00'),
(95, '微信17区 天人合一', 7, 95, '2017-10-09 00:00:00'),
(96, '微信18区 磁力屏障', 7, 96, '2017-10-09 00:00:00'),
(97, '微信19区 霸王护盾', 7, 97, '2017-10-09 00:00:00'),
(98, '微信20区 机关魔爪', 7, 98, '2017-10-09 00:00:00'),
(99, '手Q1区 苍天翔龙', 8, 99, '2017-10-09 00:00:00'),
(100, '手Q2区 铁血都督', 8, 100, '2017-10-09 00:00:00'),
(101, '手Q3区 野蛮之锤', 8, 101, '2017-10-09 00:00:00'),
(102, '手Q4区 正义爆轰', 8, 102, '2017-10-09 00:00:00'),
(103, '手Q5区 机关造物', 8, 103, '2017-10-09 00:00:00'),
(104, '手Q6区 名士狂歌', 8, 104, '2017-10-09 00:00:00'),
(105, '手Q7区 致命灵药', 8, 105, '2017-10-09 00:00:00'),
(106, '手Q8区 不死之炎', 8, 106, '2017-10-09 00:00:00'),
(107, '手Q9区 生命主宰', 8, 107, '2017-10-09 00:00:00'),
(108, '手Q10区 反击之镰', 8, 108, '2017-10-09 00:00:00'),
(109, '手Q11区 血之回响', 8, 109, '2017-10-09 00:00:00'),
(110, '手Q12区 死神之镰', 8, 110, '2017-10-09 00:00:00'),
(111, '手Q13区 二天一流', 8, 111, '2017-10-09 00:00:00'),
(112, '手Q14区 永生之血', 8, 112, '2017-10-09 00:00:00'),
(113, '手Q15区 君临天地', 8, 113, '2017-10-09 00:00:00'),
(114, '手Q16区 渴望之血', 8, 114, '2017-10-09 00:00:00'),
(115, '手Q17区 欲望之月', 8, 115, '2017-10-09 00:00:00'),
(116, '手Q18区 运筹帷幄', 8, 116, '2017-10-09 00:00:00'),
(117, '手Q19区 东风来袭', 8, 117, '2017-10-09 00:00:00'),
(118, '手Q20区 谋略法球', 8, 118, '2017-10-09 00:00:00'),
(119, '微信1区 绚烂刀锋', 9, 119, '2017-10-09 00:00:00'),
(120, '微信2区 智慧之拳', 9, 120, '2017-10-09 00:00:00'),
(121, '微信3区 大唐名探', 9, 121, '2017-10-09 00:00:00'),
(122, '微信4区 冰雪之华', 9, 122, '2017-10-09 00:00:00'),
(123, '微信5区 逆流之时', 9, 123, '2017-10-09 00:00:00'),
(124, '微信6区 破釜沉舟', 9, 124, '2017-10-09 00:00:00'),
(125, '微信7区 天命之女', 9, 125, '2017-10-09 00:00:00'),
(126, '微信8区 女帝辉光', 9, 126, '2017-10-09 00:00:00'),
(127, '微信9区 女帝威严', 9, 127, '2017-10-09 00:00:00'),
(128, '微信10区 生杀矛夺', 9, 128, '2017-10-09 00:00:00'),
(129, '微信11区 师道尊严', 9, 129, '2017-10-09 00:00:00'),
(130, '微信12区 圣人训诫', 9, 130, '2017-10-09 00:00:00'),
(131, '微信13区 圣人之威', 9, 131, '2017-10-09 00:00:00'),
(132, '微信14区 舍生忘死', 9, 132, '2017-10-09 00:00:00'),
(133, '微信15区 爆裂双斧', 9, 133, '2017-10-09 00:00:00'),
(134, '微信16区 激热回旋', 9, 134, '2017-10-09 00:00:00'),
(135, '微信17区 正义潜能', 9, 135, '2017-10-09 00:00:00'),
(136, '微信18区 杀意之枪', 9, 136, '2017-10-09 00:00:00'),
(137, '微信19区 无情冲锋', 9, 137, '2017-10-09 00:00:00'),
(138, '微信20区 背水一战', 9, 138, '2017-10-09 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `think_user`
--

CREATE TABLE IF NOT EXISTS `think_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `truename` varchar(255) DEFAULT NULL,
  `idcard` text CHARACTER SET utf32,
  `idcardpic` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) NOT NULL,
  `bond` varchar(255) NOT NULL,
  `money` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `inputtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `think_user`
--

INSERT INTO `think_user` (`id`, `username`, `password`, `truename`, `idcard`, `idcardpic`, `mobile`, `bond`, `money`, `status`, `inputtime`) VALUES
(3, '18761530563', '96e79218965eb72c92a549dd5a330112', '杨定维', '320924199111055270', '/tp/Public/upload/image/20170726/20170726124524_23526.jpg', '18761530563', '2964.5', '0', '1', '2017-07-26 11:01:25'),
(4, '18761530562', 'e10adc3949ba59abbe56e057f20f883e', '朱娟', '320324199201165024', '/tp/Public/upload/image/20170726/20170726132719_83267.jpg', '18761530562', '0', '0', '1', '2017-07-26 11:29:35'),
(5, '18761530561', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, NULL, '18761530561', '0', '0', '0', '2017-07-26 13:19:52'),
(6, '18168936728', 'e10adc3949ba59abbe56e057f20f883e', '杨家豪', '320924199111055270', '/tp/Public/upload/image/20170726/20170726124524_23526.jpg', '', '4500', '0', '1', '2017-09-06 18:02:19'),
(7, '18168930747', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, NULL, '18168930747', '0', '0', '0', '2017-10-19 16:47:06');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
