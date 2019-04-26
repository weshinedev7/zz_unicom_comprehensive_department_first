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
<title>订单列表</title>
<style>
    .text_input{display:block;width:40px;margin:0px auto;border:0px;background:none;text-align:center;}
    .text_input_bianji{display:block;width:40px;border:1px solid #dbdbdb;margin:0px auto;text-align:center;}
</style>
</head>

<body>
<div class="page-content clearfix">
	<div id="Member_Ratings">
		<div class="d_Confirm_Order_style">
			<div class="search_style">
				<div class="title_names">搜索查询</div>
				<form action="/admin.php/Order" method="post">
					<ul class="search_content clearfix">
                       <!-- <li>
                            <select name="status" style="height: 32px;">
                                <option value="" <?php if($status==''): ?>selected<?php endif; ?>>请选择状态</option>
                                <option value="1" <?php if($status=='1'): ?>selected<?php endif; ?>>微信支付</option>
                                <option value="2" <?php if($status=='2'): ?>selected<?php endif; ?>>已接单</option>
                                <option value="3" <?php if($status=='3'): ?>selected<?php endif; ?>>维修师傅确认</option>
                                <option value="4" <?php if($status=='4'): ?>selected<?php endif; ?>>已完成</option>
                            </select>
                        </li>-->
                        <li><label class="l_f">搜索查询：</label></li>
                        <li>
                            <select name="searchtype" style="height: 32px;">
                                <option value="nickname" <?php if($searchtype=='nickname'): ?>selected<?php endif; ?>>用户昵称</option>
                                <option value="mobile" <?php if($searchtype=='mobile'): ?>selected<?php endif; ?>>手机号</option>
                                <option value="ordersn" <?php if($searchtype=='ordersn'): ?>selected<?php endif; ?>>订单号</option>
                                <option value="expresssn" <?php if($searchtype=='expresssn'): ?>selected<?php endif; ?>>快递单号</option>
                            </select>
                        </li>
                        <li><input name="keyword" id="keyword" type="text" value="<?php echo ($keyword); ?>" class="text_add" placeholder="输入关键字"  style=" width:400px"/></li>

                        <li>
                            <select name="ordertype" style="height: 32px;">
                                <option value="" <?php if($ordertype==''): ?>selected<?php endif; ?>>请选择排序规则</option>
                                <option value="createtime" <?php if($ordertype=='createtime'): ?>selected<?php endif; ?>>下单时间</option>
                                <option value="status" <?php if($ordertype=='status'): ?>selected<?php endif; ?>>订单状态</option>
                            </select>
                        </li>
						<li style="width:90px;"><button type="submit" id="search" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</button></li>
						<li style="width:90px;"><a href="/admin.php/Order" class="btn btn-danger" style="padding:0px 10px;">重置</a></li>

					</ul>
				</form>
			</div>
            <style>
                #imglist img{height: 50px;}#imgshow{position: absolute; border: 1px solid #ccc; background: #333; padding: 5px; color: #fff; display: none; }
                .file{
                    border: none;
                    height: 40px;
                    float: right;
                    margin-top: 2.5%;
                }
                .layui-btn{
                    width: 36%;
                    float: left;
                    margin-left: 86%;
                    margin-top: -14%;
                    height: 35px;
                    border: 1px solid #ffb752;
                    color: #fff;
                    line-height: 30px;
                    border-radius: 3px;
                    background-color: #ffb752;
                }
            </style>
            <!--<span>请导入快递公司编码文件:</span>-->

            <!--<div class="border clearfix">-->
                <!--<span class="l_f">-->
                    <!--<form action="<?php echo U('Order/excelimport');?>" method="post" enctype="multipart/form-data">-->
                    <!--<input type="file" name="file" class="file" >-->
                    <!--<button class="layui-btn" lay-submit lay-filter="*" onclick="return message()">立即导入</button>-->
                    <!--</form>-->
				<!--</span>-->
            <!--</div>-->
            <style>#imglist img{height: 50px;}#imgshow{position: absolute;background: none; padding: 0px; color: #fff; display: none; } </style>
			<div class="table_menu_list" id="imglist">
                <style>
                    #sample-table p{text-align: left;padding-left:10px;}
                    #sample-table span{display:block;text-align: left;}
                </style>
				<table class="table table-striped table-bordered table-hover" id="sample-table">
					<thead>
						<tr>
							<th width="40">ID</th>
                            <th width="120">头像/昵称</th>
                            <th width="150">订单编号</th>
                            <th width="100">手机号</th>
                            <th width="120">商品内容</th>
                            <th width="200">下单时间</th>
                            <th width="80">订单状态</th>
                            <th width="200">快递单号</th>
							<th width="150">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <input type="hidden" name="id" id="id_<?php echo ($vo["id"]); ?>" value="<?php echo ($vo["id"]); ?>" />
							<td><?php echo ($vo["id"]); ?></td>
							<td>
                                <?php if($vo["avatar"] == ''): ?><span>暂无微信头像</span>
                                    <?php else: ?>
                                    <img src="<?php echo ($vo["avatar"]); ?>" style="height:30px;"/><?php endif; ?>
                                <span style="text-align:center;padding-top:10px;"><?php echo ($vo["nickname"]); ?></span>
                            </td>
                            <td><?php echo ($vo["ordersn"]); ?></td>
                            <td><?php echo ($vo["mobile"]); ?></td>
                            <td><?php echo ($vo["goodsinfo"]["name"]); ?></td>
                            <td><?php echo ($vo["createtime"]); ?></td>

                            <td>
                                <?php if($vo['status']==1): ?><span class="label label-default radius" style="text-align:center;">待付款</span><?php endif; ?>
                                <?php if($vo['status']==2): ?><span class="label label-warning radius" style="text-align:center;">已付款</span><?php endif; ?>
                                <?php if($vo['status']==3): ?><span class="label label-info radius" style="text-align:center;">已发货</span><?php endif; ?>
                                <?php if($vo['status']==4): ?><span class="label label-success radius" style="text-align:center;">已签收</span><?php endif; ?>
                            </td>
							<td><?php echo ($vo["expressno"]); ?></td>
							<td class="td-manage">
                                <!--<?php if($vo['status']=='2'): ?>-->
                                    <!--<a onClick="order_pay(this,'<?php echo ($vo["id"]); ?>')"  href="javascript:;" title="后台支付"  class="btn btn-xs btn-success"><i class="icon-ok bigger-120"></i></a>-->
                                <!--<?php endif; ?>-->
                                <!--<?php if($vo['status']=='1'): ?>-->
                                    <!--<a title="不可以更改"  class="btn btn-xs"><i class="icon-ok bigger-120"></i></a>-->
                                <!--<?php endif; ?>-->
                                <!--<?php if($vo['status']=='3'): ?>-->
                                    <!--<a title="不可以更改" class="btn btn-xs"><i class="icon-remove bigger-120"></i></a>-->
                                <!--<?php endif; ?>-->
                                <?php if($vo['status']==2): ?><a title="发货" href="javascript:;"  onclick="edit('<?php echo ($vo["id"]); ?>')" class="btn btn-xs btn-info" >订单发货</a>

                                    <?php else: ?>
                                    <a title="发货" href="#"  class="btn btn-xs btn-default" >订单发货</a><?php endif; ?>

                                <a title="删除" href="javascript:;"  onclick="del(this,'<?php echo ($vo["id"]); ?>')" class="btn btn-xs btn-warning" >删除</a>
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

                <!--<select name="expressname" id="expressname" style="height:32px;">-->
                    <!--<option value="SF">顺丰</option>-->
                    <!--<option value="YTO">圆通</option>-->
                    <!--<option value="HTKY">百世汇通</option>-->

                <!--</select>-->

            <select class="form-control" name="shippercode" id="shippercode" style="height:32px;">
                <option value="">==请选择快递公司:==</option>
                <?php if(is_array($express)): $i = 0; $__LIST__ = $express;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo['code']); ?>"><?php echo ($vo['name']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <div class="prompt r_f"></div>
        </li>
        <li class="adderss">
            <label class="label_name"> 快递单号：</label>
            <span class="add_name">
				<input name="expressno" id="expressno" title=""  type="text"  class="text_add" style=" width:350px"/>
			</span>
            <div class="prompt r_f"></div>
        </li>
    </ul>
</div>
</body>
</html>
<script>
    function message(){
        var res=confirm('注意 ! 考虑服务器压力,建议一次数据不超过三千条!');
        if(res==false){
            return false;
        }
        layer.msg('正在导入,请勿关闭浏览器,导入完成后将自行刷新本页面');
    }
function order_pay(obj,id){
    layer.confirm('确认要后台更改订单状态吗？',function(index){
        $.post('/admin.php/Order/pay',{id:id},function(data){
            /*data是返回的值*/
            if(data.flag==1){
                layer.msg('已更改！',{icon:1,time:1000},function(){
                    window.location.reload();
                    //parent.location.reload(); // 父页面刷新
                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                    parent.layer.close(index);
                });
            }else{
                layer.msg('更改失败！',{icon:2,time:1000},function(){
                    window.location.reload();
                    //parent.location.reload(); // 父页面刷新
                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                    parent.layer.close(index);
                });
            }
        },'json');
    });
}

/*后台取消支付*/
function order_payN(obj,id){
    layer.confirm('确认要后台更改支付状态吗？',function(index){
        $.post('/admin.php/Order/payN',{id:id},function(data){
            /*data是返回的值*/
            if(data.flag==1){
                layer.msg('支付状态已改成未支付！',{icon:1,time:1000},function(){
                    window.location.reload();
                    //parent.location.reload(); // 父页面刷新
                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                    parent.layer.close(index);
                });
            }else{
                layer.msg('修改失败！',{icon:2,time:1000},function(){
                    window.location.reload();
                    //parent.location.reload(); // 父页面刷新
                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                    parent.layer.close(index);
                });
            }
        },'json');
    });
}

/*用户-删除*/
function del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		$.post('/admin.php/Order/del',{id:id},function(data){
			if(data.flag==1){
				layer.msg('已删除!',{icon:1,time:1000},function(){
					window.location.href="/admin.php/Order";
					//parent.location.reload(); // 父页面刷新
					var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
					parent.layer.close(index);
				});
			}else{
				layer.msg('删除失败!',{icon:2,time:1000},function(){
					window.location.href="/admin.php/Order";
					//parent.location.reload(); // 父页面刷新
					var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
					parent.layer.close(index);
				});
			}
		});
	});
}

function edit($id){
    /*添加/修改备注*/
    var id=$id;
    layer.open({
        type: 1,
        title: '订单发货',
        maxmin: true,
        shadeClose: true, //点击遮罩关闭层
        area : ['800px' , ''],
        content:$('#add_style'),
        btn:['提交','取消'],
        yes:function(index,layero){

            var shippercode=$('#shippercode').val()
            var expressno=$('#expressno').val()

            $.post('/admin.php/Order/edit',{id:id,shippercode:shippercode,expressno:expressno},function(data){
                /*data是返回的值*/
                if(data.status>0){
                    layer.alert('成功！',{title: '提示框',icon:1},function(){
                        window.location.href="/admin.php/Order";
                        //parent.location.reload(); // 父页面刷新
                        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                        parent.layer.close(index);
                    });
                }else{
                    layer.alert('失败！',{title: '提示框',icon:2},function(){
                        window.location.href="/admin.php/Order"; // 父页面刷新
                        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                        parent.layer.close(index);
                    });
                }
            });

        }
    });

}


</script>