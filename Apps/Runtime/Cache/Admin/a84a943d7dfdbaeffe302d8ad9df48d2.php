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

    <script src="https://quanyu.xcxweshine.com//Public/admin/js/Area.js" type="text/javascript"></script>
    <script src="https://quanyu.xcxweshine.com//Public/admin/js/AreaData_min.js" type="text/javascript"></script>
    <title>消费信息</title>
</head>

<body>
<div class="page-content clearfix">
    <div id="Member_Ratings">
        <div class="d_Confirm_Order_style">
            <div class="search_style">
                <div class="title_names">搜索查询</div>
                <form action="/admin.php/User/comment" method="get">
                    <ul class="search_content clearfix">
                        <li><label class="l_f">搜索查询：</label><input name="keyword" id="keyword" type="text" value="<?php echo ($keyword); ?>" class="text_add" placeholder="输入用户昵称"  style=" width:400px"/></li>
                        <li><label class="l_f">排序依据：</label></li>
                        <li>
                            <select class="select text_add" name="ordertype" id="" style="width:100px;height:32px;">
                                <option value="" >排序依据</option>
                                <?php if( $ordertype == 'comefrom'): ?><option value="comefrom" selected="selected">来源</option>
                                    <?php else: ?>
                                    <option value="comefrom">来源</option><?php endif; ?>
                                <?php if( $ordertype == 'createtime'): ?><option value="createtime" selected="selected">评论时间</option>
                                    <?php else: ?>
                                    <option value="createtime">评论时间</option><?php endif; ?>
                            </select>
                        </li>
                        <li style="width:90px;"><button type="submit" onclick="return showAreaID()" id="search" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</button></li>

                        <!--
                        <li>
                            <label class="l_f">添加时间</label>
                            <input class="inline laydate-icon" name="starttime" id="start" style=" margin-left:10px;">&nbsp;&nbsp;&nbsp;-
                            <input class="inline laydate-icon" name="endtime" id="end" style=" margin-left:10px;">
                        </li>
                        -->
                        <!--
                                                <li style="width:90px;"><a href="/admin.php/User/index/type/0" class="btn btn-danger" style="padding:0px 10px;">重置</a></li>
                        -->
                    </ul>

                </form>
            </div>

            <style>#imglist img{height: 50px;}#imgshow{position: absolute; border: 1px solid #ccc; background: #333; padding: 5px; color: #fff; display: none; } </style>
            <!---->
            <div class="table_menu_list" id="imglist">
                <table class="table table-striped table-bordered table-hover" id="sample-table">
                    <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th width="150">用户</th>
                        <th width="200">评论内容</th>
                        <th width="200">专辑</th>
                        <th width="150">来源</th>
                        <th width="200">
                            评论时间
                        </th>

                        <th width="200">
                            操作
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td>
                                <?php echo ($vo["id"]); ?>
                            </td>
                            <td><img src="<?php echo ($vo["avatar"]); ?>" style="height:30px;"/>&nbsp;&nbsp;&nbsp;<?php echo ($vo["nickname"]); ?></td>
                            <td><?php echo ($vo["content"]); ?></td>
                            <td><?php echo ($vo["albumname"]); ?></td>
                            <td><?php echo ($vo["episodename"]); ?></td>
                            <td><?php echo ($vo["time"]); ?></td>
                            <td>
                                <a title="回复列表" href="/admin.php/User/commentreplylist/id/<?php echo ($vo["id"]); ?>"  class="btn btn-xs btn-success" >回复列表</a>
                                <a title="回复" onclick="edit('<?php echo ($vo["id"]); ?>')" href="javascript:;"  class="btn btn-xs btn-info" >回复</a>
                                <a title="删除" onclick="deleted('<?php echo ($vo["id"]); ?>')" href="javascript:;"   class="btn btn-xs btn-danger" >删除</a>
                            </td>
                        </tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="pagination pagination-large"><?php echo ($page); ?></div>
        </div>
    </div>
</div>
<div class="add_menber" id="add_style" style="display:none">
    <ul class=" page-content">
        <li class="adderss">
            <label class="label_name"> 回复：</label>
            <span class="add_name">
                <textarea style="width: 400px;height: 150px;" name="remark" id="commentreply" title="回复评论"  class="text_add" style=" width:350px"></textarea>
			</span>
            <div class="prompt r_f"></div>
        </li>
    </ul>
</div>

</body>
</html>
<script>


    function edit($id){
        /*添加/修改备注*/
        var commentid=$id;
        layer.open({
            type: 1,
            title: '添加/修改备注',
            maxmin: true,
            shadeClose: true, //点击遮罩关闭层
            area : ['800px' , '300px'],
            content:$('#add_style'),
            btn:['提交','取消'],
            yes:function(index,layero){

                var content=$('#commentreply').val()
                if(content=='' || content==null){
                    layer.msg('请填写内容!',{icon:2,time:1000},function(){});
                    return;
                }

                $.post('/admin.php/User/savecommentreply',{commentid:commentid,content:content},function(data){
                    /*data是返回的值*/
                    console.log(data)

                    if(data.status==1){
                        layer.alert('成功！',{title: '提示框',icon:1},function(){
                            window.location.href="/admin.php/User/comment";
                            //parent.location.reload(); // 父页面刷新
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);
                        });
                    }else{
                        layer.alert('失败！',{title: '提示框',icon:2},function(){
                            window.location.href="/admin.php/User/comment"; // 父页面刷新
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);
                        });
                    }
                });

            }
        });

    }
    function deleted($id){
        var commentid=$id;
        $con=confirm('确认要删除吗?');
        if($con==false){
            return;
        }
        $.post('/admin.php/User/commentdeleted',{commentid:commentid},function(data){
            /*data是返回的值*/
            console.log(data)

            if(data.status==1){
                layer.alert('成功！',{title: '提示框',icon:1},function(){
                    window.location.href="/admin.php/User/comment";
                    //parent.location.reload(); // 父页面刷新
                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                    parent.layer.close(index);
                });
            }else{
                layer.alert('失败！',{title: '提示框',icon:2},function(){
                    window.location.href="/admin.php/User/comment"; // 父页面刷新
                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                    parent.layer.close(index);
                });
            }
        });
    }

</script>