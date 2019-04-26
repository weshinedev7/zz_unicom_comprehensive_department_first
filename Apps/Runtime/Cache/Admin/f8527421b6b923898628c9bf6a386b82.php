<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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



    <title>每日科学</title>
</head>

<body>
<div class="page-content clearfix">
    <div id="Member_Ratings">
        <div class="d_Confirm_Order_style">

            <div class="search_style">
                <div class="title_names">搜索查询</div>
                <form action="/admin.php/Strategy/index" method="get">
                    <ul class="search_content clearfix">
                        <li><label class="l_f">搜索查询：</label><input name="keyword" id="keyword" type="text" value="<?php echo ($keyword); ?>" class="text_add" placeholder="输入标题名称"  style=" width:400px"/></li>

                        <li style="width:90px;"><button type="submit"  id="search" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</button></li>

                        <!--
                        <li>
                            <label class="l_f">添加时间</label>
                            <input class="inline laydate-icon" name="starttime" id="start" style=" margin-left:10px;">&nbsp;&nbsp;&nbsp;-
                            <input class="inline laydate-icon" name="endtime" id="end" style=" margin-left:10px;">
                        </li>
                        -->
                        <!--
                                                <li style="width:90px;"><a href="/admin.php/Strategy/index/type/0" class="btn btn-danger" style="padding:0px 10px;">重置</a></li>
                        -->
                    </ul>

                </form>
            </div>
            <div class="border clearfix" style="margin-top:20px;">
				<span class="l_f">
					<a href="/admin.php/Strategy/poststrategy"  id="cate_add" class="btn btn-warning"><i class="icon-plus"></i>新建</a>
                    <!--<a href="javascript:ovid()" class="btn btn-danger"><i class="icon-trash"></i>批量删除</a>-->
				</span>
            </div>
            <div class="table_menu_list" id="imglist">
                <table class="table table-striped table-bordered table-hover" id="sample-table">
                    <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th width="200">封面</th>
                        <th width="300">标题</th>
                        <th width="100">更新时间</th>

                        <th width="200">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><?php echo ($vo["id"]); ?></td>
                            <td>
                            <img width="150px" src="<?php echo ($vo["cover"]); ?>" alt="">
                            </td>
                            <td><?php echo ($vo["name"]); ?></td>
                            <td><?php echo ($vo["time"]); ?></td>

                            <td>
                                <a title=""  href="/admin.php/Strategy/poststrategy/id/<?php echo ($vo["id"]); ?>"  class="btn btn-xs btn-info" >编辑</a>
                                <a title="" onclick="deleted('<?php echo ($vo["id"]); ?>')" href="javascript:;"  class="btn btn-xs btn-warning" >删除</a>
                            </td>
                        </tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="pagination pagination-large"><?php echo ($page); ?></div>

        </div>
    </div>
</div>


</body>
</html>
<script>
    function deleted($id){
        $con=confirm('确认要删除这条信息吗?');
        if($con==true){
            $.post('/admin.php/Strategy/deletestrategy',{id:$id},function(data){
                if(data.status>0){
                    layer.alert('成功！',{title: '提示框',icon:1},function(){
                        window.location.href="/admin.php/Strategy/index";
                        //parent.location.reload(); // 父页面刷新
                        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                        parent.layer.close(index);
                    });
                }else{
                    layer.alert('失败！',{title: '提示框',icon:2},function(){
                        window.location.href="/admin.php/Strategy/index"; // 父页面刷新
                        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                        parent.layer.close(index);
                    });
                }
            });
        }
        console.log($con);
    }
    function edit($id){
        $('#tagname').val('')
        $('#tagid').val('')
        if ($id >0){
            $.post('/admin.php/Strategy/gettaginfo',{id:$id},function(data){
                $('#tagname').val(data.info.name)
                $('#tagid').val(data.info.id)
            });
        }

        /*添加/修改备注*/

        layer.open({
            type: 1,
            title: '添加/修改用户标签',
            maxmin: true,
            shadeClose: true, //点击遮罩关闭层
            area : ['800px' , ''],
            content:$('#add_style'),
            btn:['提交','取消'],
            yes:function(index,layero){

                var tagname=$('#tagname').val()
                var id=$('#tagid').val()
                $.post('/admin.php/Strategy/edittag',{tagname:tagname,id:id},function(data){
                    /*data是返回的值*/
                    if(data.status>0){
                        layer.alert('成功！',{title: '提示框',icon:1},function(){
                            window.location.href="/admin.php/Strategy/tags";
                            //parent.location.reload(); // 父页面刷新
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);
                        });
                    }else{
                        layer.alert('失败！',{title: '提示框',icon:2},function(){
                            window.location.href="/admin.php/Strategy/tags"; // 父页面刷新
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);
                        });
                    }
                });

            }
        });

    }


</script>