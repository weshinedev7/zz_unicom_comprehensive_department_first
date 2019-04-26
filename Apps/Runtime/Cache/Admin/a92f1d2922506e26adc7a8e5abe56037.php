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

<script>
	KindEditor.ready(function(K) 
	{
		var editor = K.editor({
			allowFileManager : true,
			allowImageRemote : false
		});
		
		K('#image_pic').click(function() {
			editor.loadPlugin('image', function() {
				editor.plugin.imageDialog({
					imageUrl : K('#idcardpic').val(),
					clickFn : function(url, title, width, height, border, align) {
						K('#idcardpic').val(url);
						K("#picShow").attr('src',url);
						editor.hideDialog();
					}
				});
			});
		});
		
		K('#image_pic_e').click(function() {
			editor.loadPlugin('image', function() {
				editor.plugin.imageDialog({
					imageUrl : K('#idcardpic_e').val(),
					clickFn : function(url, title, width, height, border, align) {
						K('#idcardpic_e').val(url);
						editor.hideDialog();
					}
				});
			});
		});

	});
</script>

<title>用户列表</title>
</head>

<body>
<div class="page-content clearfix">
	<div id="Member_Ratings">
		<div class="d_Confirm_Order_style">
			<div class="search_style">
				<div class="title_names">搜索查询</div>
				<form action="/admin.php/User/index" method="get">
					<ul class="search_content clearfix">
						<li><label class="l_f">搜索查询：</label><input name="keyword" id="keyword" type="text" value="<?php echo ($keyword); ?>" class="text_add" placeholder="输入用户昵称或手机号"  style=" width:400px"/></li>
						<!--
						<li>
							<label class="l_f">添加时间</label>
							<input class="inline laydate-icon" name="starttime" id="start" style=" margin-left:10px;">&nbsp;&nbsp;&nbsp;-
							<input class="inline laydate-icon" name="endtime" id="end" style=" margin-left:10px;">
						</li>
						-->
						<li style="width:90px;"><button type="submit" id="search" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</button></li>
<!--
						<li style="width:90px;"><a href="/admin.php/User/index/type/0" class="btn btn-danger" style="padding:0px 10px;">重置</a></li>
-->
					</ul>
				</form>
			</div>
			<!---->
			<!--<div class="border clearfix">-->
				<!--<span class="l_f">-->
					<!--&lt;!&ndash;<a href="javascript:ovid()" id="member_add" class="btn btn-warning"><i class="icon-plus"></i>添加用户</a>&ndash;&gt;-->
					<!--&lt;!&ndash;<a href="javascript:ovid()" class="btn btn-danger"><i class="icon-trash"></i>批量删除</a>&ndash;&gt;-->
				<!--</span>-->
				<!--<span class="r_f">共：<b><?php echo ($count); ?></b>条</span>-->
			<!--</div> -->
			<style>#imglist img{height: 50px;}#imgshow{position: absolute; border: 1px solid #ccc; background: #333; padding: 5px; color: #fff; display: none; } </style>
			<!---->
			<div class="table_menu_list" id="imglist">
				<table class="table table-striped table-bordered table-hover" id="sample-table">
					<thead>
						<tr>
							<th width="80">ID</th>
							<th width="150">昵称</th>
							<th width="150">手机号</th>
							<th width="150">
								<a href="<?php echo U('user/index',array('keyword'=>$keyword,'ordertype'=>'name'));?>">
									性别 &nbsp;<img src="https://quanyu.xcxweshine.com//Public/down.png" style="width: 10px;height: 5px;" alt="">
								</a>
							</th>
							<th width="200">
								<a href="<?php echo U('user/index',array('keyword'=>$keyword,'ordertype'=>'province'));?>">
								省份 &nbsp;<img src="https://quanyu.xcxweshine.com//Public/down.png" style="width: 10px;height: 5px;" alt="">
								</a>
							</th>
							<th width="200">
								<a href="<?php echo U('user/index',array('keyword'=>$keyword,'ordertype'=>'city'));?>">
								城市 &nbsp;<img src="https://quanyu.xcxweshine.com//Public/down.png" style="width: 10px;height: 5px;" alt="">
								</a>
							</th>
							<th width="200">备注</th>
							<th width="180">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							<td><?php echo ($vo["id"]); ?></td>
                            <td><img src="<?php echo ($vo["avatar"]); ?>" style="height:30px;"/>&nbsp;&nbsp;&nbsp;<?php echo ($vo["nickname"]); ?></td>
							<td><?php echo ($vo["mobile"]); ?></td>
							<td>
								<?php if($vo["gender"] == 1): ?>男
								<?php else: ?>
										女<?php endif; ?>
							</td>
							<td><?php echo ($vo["province"]); ?></td>
							<td><?php echo ($vo["city"]); ?></td>
							<td><?php echo ($vo["remark"]); ?></td>
							<td>
								<a title="添加备注" onclick="edit('<?php echo ($vo["id"]); ?>')" href="javascript:;" data-remark="<?php echo ($vo["remark"]); ?>" id="addremard" class="btn btn-xs btn-info" ><i class="icon-edit bigger-120"></i></a>
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
			<label class="label_name"> 备注：</label>
			<input type="hidden" id="remarkid" value="">
			<span class="add_name">
				<input name="remark" id="remark" title="备注" id="remarkdata" type="text"  class="text_add" style=" width:350px"/>
			</span>
			<div class="prompt r_f"></div>
		</li>
	</ul>
</div>

</body>
</html>
<script>
	function edit($id){
        $.post('/admin.php/User/getinfo',{id:$id},function(data){
            $('#remark').val(data.info.remark)
			$('#remarkid').val(data.info.id)
        });
		/*添加/修改备注*/

            layer.open({
                type: 1,
                title: '添加/修改备注',
                maxmin: true,
                shadeClose: true, //点击遮罩关闭层
                area : ['800px' , ''],
                content:$('#add_style'),
                btn:['提交','取消'],
                yes:function(index,layero){

                    var remark=$('#remark').val()
					var id=$('#remarkid').val()
                    $.post('/admin.php/User/editremark',{remark:remark,id:id},function(data){
						/*data是返回的值*/

                        if(data.status==1){
                            layer.alert('成功！',{title: '提示框',icon:1},function(){
                                window.location.href="/admin.php/User";
                                //parent.location.reload(); // 父页面刷新
                                var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                parent.layer.close(index);
                            });
                        }else{
                            layer.alert('失败！',{title: '提示框',icon:2},function(){
                                window.location.href="/admin.php/User"; // 父页面刷新
                                var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                parent.layer.close(index);
                            });
                        }
                    });

                }
            });

	}


</script>