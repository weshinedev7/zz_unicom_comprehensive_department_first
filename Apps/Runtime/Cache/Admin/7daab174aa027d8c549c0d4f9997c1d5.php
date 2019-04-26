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


            <style>#imglist img{height: 50px;}#imgshow{position: absolute; border: 1px solid #ccc; background: #333; padding: 5px; color: #fff; display: none; } </style>
            <!---->
            <div class="table_menu_list" id="imglist">
                <table class="table table-striped table-bordered table-hover" id="sample-table">
                    <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th width="150">用户</th>
                        <th width="200">评论内容</th>
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
                            <td><?php echo ($vo["time"]); ?></td>
                            <td>
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

    function deleted($id){
        var replyid=$id;
        $con=confirm('确认要删除吗?');
        if($con==false){
            return;
        }
        $.post('/admin.php/User/replydeleted',{replyid:replyid},function(data){
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