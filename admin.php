<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模�?建议开发阶段开�?部署阶段注释或者设为false
define('APP_DEBUG',true);
define('DB_FIELD_CACHE',false);
define('HTML_CACHE_ON',false);
define('SHOW_ERROR_MSG',false);

define('__ROOT__','https://quanyu.xcxweshine.com/');

// 定义应用目录
define('APP_PATH','./Apps/');

// 绑定入口文件到Admin模块访问
define('BIND_MODULE','Admin');
define('PUBLIC','./Public/upload/image');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简�?