<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://quanyu.xcxweshine.com//Public/admin/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://quanyu.xcxweshine.com//Public/admin/css/style.css"/>
    <link href="https://quanyu.xcxweshine.com//Public/admin/assets/css/codemirror.css" rel="stylesheet">
    <link rel="stylesheet" href="https://quanyu.xcxweshine.com//Public/admin/assets/css/ace.min.css" />
    <link rel="stylesheet" href="https://quanyu.xcxweshine.com//Public/admin/assets/css/font-awesome.min.css" />
    <!--[if IE 7]>
    <link rel="stylesheet" href="https://quanyu.xcxweshine.com//Public/admin/assets/css/font-awesome-ie7.min.css" />
    <![endif]-->
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="https://quanyu.xcxweshine.com//Public/admin/assets/css/ace-ie.min.css" />
    <![endif]-->
    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/js/jquery.min.js"></script>
    <!-- <![endif]-->

    <!--[if IE]>
    <script src="http://ajax.useso.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <![endif]-->

    <!--[if !IE]> -->
    <script type="text/javascript">
        window.jQuery || document.write("<script src='https://quanyu.xcxweshine.com//Public/admin/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
    </script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script type="text/javascript">
        window.jQuery || document.write("<script src='https://quanyu.xcxweshine.com//Public/admin/assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
    </script>
    <![endif]-->

    <script type="text/javascript">
        if("ontouchend" in document) document.write("<script src='https://quanyu.xcxweshine.com//Public/admin/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>
    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/js/bootstrap.min.js"></script>
    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/js/typeahead-bs2.min.js"></script>
    <!-- page specific plugin scripts
    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/js/jquery.dataTables.min.js"></script>
    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/js/jquery.dataTables.bootstrap.js"></script>
    -->
    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/layer/layer.js" type="text/javascript"></script>
    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/laydate/laydate.js" type="text/javascript"></script>

    <link rel="stylesheet" href="https://quanyu.xcxweshine.com//Public/admin/editor/themes/default/default.css" />
    <link rel="stylesheet" href="https://quanyu.xcxweshine.com//Public/admin/editor/plugins/code/prettify.css" />
    <script charset="utf-8" src="https://quanyu.xcxweshine.com//Public/admin/editor/kindeditor.js"></script>
    <script charset="utf-8" src="https://quanyu.xcxweshine.com//Public/admin/editor/lang/zh_CN.js"></script>
    <script charset="utf-8" src="https://quanyu.xcxweshine.com//Public/admin/editor/plugins/code/prettify.js"></script>

    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/js/jquery.min.js"></script>

    <script src="https://quanyu.xcxweshine.com//Public/admin/highchars/highcharts.js"></script>
    <script charset="utf-8" src="https://quanyu.xcxweshine.com//Public/admin/js/showdate.js"></script>


    <title>概况</title>
</head>

<body>
<div class="page-content clearfix">


    <div class="search_style">
        <div class="title_names">搜索查询</div>
        <form action="/admin.php/Data/espdata" method="post">
            <ul class="search_content clearfix">
                <li><label class="l_f">类别：</label></li>
                <li>
                    <select name="type" style="width:200px">
                        <option value="science" <?php if($type == 'science'): ?>selected="selected"<?php endif; ?> >每日科学</option>
                        <option value="album" <?php if($type == 'album'): ?>selected="selected"<?php endif; ?>>专辑故事</option>
                    </select>
                </li>
                <li><label class="l_f">排序依据：</label></li>
                <li>
                    <select name="ordertype" style="width:200px">
                        <option value="" <?php if($ordertype == ''): ?>selected="selected"<?php endif; ?> >无依据</option>
                        <option value="visiter" <?php if($ordertype == 'visiter'): ?>selected="selected"<?php endif; ?> >访客数</option>
                        <option value="payuser" <?php if($ordertype == 'payuser'): ?>selected="selected"<?php endif; ?>>付费用户</option>
                        <option value="transpercent" <?php if($ordertype == 'transpercent'): ?>selected="selected"<?php endif; ?>>转化率</option>
                    </select>
                </li>
                <li style="width:90px;">
                    <!--<button type="submit"  id="search2" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</button>-->
                    <input type="submit" id="search" name="查询" class="btn btn-success" style="padding:0px 10px;">
                </li>
            </ul>

        </form>
    </div>
    <div class="table_menu_list">
        <table class="table table-striped table-bordered table-hover">
            <caption style="text-align: center ;font-size: 20px">全部音频列表</caption>
            <thead>
            <tr>
                <th width="150">名称</th>
                <th width="150">免费集完播率</th>
                <th width="150">付费集完播率</th>
                <th width="150">访客数( UV )</th>
                <th width="150">付费用户</th>
                <th width="150">转化率</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo ($vo["name"]); ?></td>
                    <td><?php echo ($vo["freeplaycent"]); ?> %</td>
                    <td><?php echo ($vo["paypercent"]); ?> %</td>
                    <td><?php echo ($vo["visitcount"]); ?></td>
                    <td><?php echo ($vo["payusercount"]); ?></td>
                    <td><?php echo ($vo["transpercent"]); ?> %</td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>

            </tbody>

        </table>
    </div>
    <div class="pagination pagination-large"><?php echo ($page); ?></div>

</div>
</body>
</html>