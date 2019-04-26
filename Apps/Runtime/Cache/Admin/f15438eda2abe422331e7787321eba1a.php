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



    <title>优惠券</title>
</head>

<body>
<div class="page-content clearfix">
    <div id="Member_Ratings">
        <div class="d_Confirm_Order_style">
            <div class="border clearfix" style="margin-top:20px;">
				<span class="l_f">
					<a href="/admin.php/Coupons/postcoupon"  id="cate_add" class="btn btn-warning"><i class="icon-plus"></i>添加用优惠券</a>
                    <!--<a href="javascript:ovid()" class="btn btn-danger"><i class="icon-trash"></i>批量删除</a>-->
				</span>
            </div>
            <div class="table_menu_list" id="imglist">
                <table class="table table-striped table-bordered table-hover" id="sample-table">
                    <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th width="150">优惠</th>
                        <th width="150">有效期</th>
                        <th width="100">剩余</th>
                        <th width="100">总数</th>
                        <th width="180">使用条件</th>
                        <th width="200">使用范围</th>
                        <th width="200">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><?php echo ($vo["id"]); ?></td>
                            <td>
                                <?php if($vo['preferential'] > 0): ?>优惠 <?php echo ($vo["preferential"]); ?> 元
                                    <?php else: ?>
                                    优惠 <?php echo ($vo["discount"]); ?> 折<?php endif; ?>
                            </td>
                            <td><?php echo ($vo["validityinfo"]); ?></td>
                            <td><?php echo ($vo["numberinfo"]); ?></td>
                            <td><?php echo ($vo["restinfo"]); ?></td>
                            <td><?php echo ($vo["fullinfo"]); ?></td>
                            <td><?php echo ($vo["albuminfo"]); ?></td>
                            <td>
                                <a title=""  href="/admin.php/Coupons/detail/couponid/<?php echo ($vo["id"]); ?>"  class="btn btn-xs btn-info" >详情</a>
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
        $con=confirm('确认要删除这个优惠券吗?');
        if($con==true){
            $.post('/admin.php/Coupons/deletecoupon',{id:$id},function(data){
                if(data.status>0){
                    layer.alert('成功！',{title: '提示框',icon:1},function(){
                        window.location.href="/admin.php/Coupons/index";
                        //parent.location.reload(); // 父页面刷新
                        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                        parent.layer.close(index);
                    });
                }else{
                    layer.alert('失败！',{title: '提示框',icon:2},function(){
                        window.location.href="/admin.php/Coupons/index"; // 父页面刷新
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
            $.post('/admin.php/Coupons/gettaginfo',{id:$id},function(data){
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
                $.post('/admin.php/Coupons/edittag',{tagname:tagname,id:id},function(data){
                    /*data是返回的值*/
                    if(data.status>0){
                        layer.alert('成功！',{title: '提示框',icon:1},function(){
                            window.location.href="/admin.php/Coupons/tags";
                            //parent.location.reload(); // 父页面刷新
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);
                        });
                    }else{
                        layer.alert('失败！',{title: '提示框',icon:2},function(){
                            window.location.href="/admin.php/Coupons/tags"; // 父页面刷新
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);
                        });
                    }
                });

            }
        });

    }


</script>