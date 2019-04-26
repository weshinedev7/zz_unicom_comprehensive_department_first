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
<title>轮播图管理</title>
</head>

<body>
<div class="page-content clearfix">
	<div id="Member_Ratings">
		<div class="d_Confirm_Order_style">
			<!--<div class="search_style">-->
				<!--<div class="title_names">搜索查询</div>-->
				<!--<form action="/admin.php/Banner/search" method="post">-->
					<!--<ul class="search_content clearfix">-->
						<!--<li><label class="l_f">搜索查询：</label><input name="keyword" id="keyword" type="text" value="<?php echo ($keyword); ?>" class="text_add" placeholder="输入关键字"  style=" width:400px"/></li>-->
						<!--<li style="width:90px;"><button type="submit" id="search" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</button></li>-->
						<!--<li style="width:90px;"><a href="/admin.php/Banner" class="btn btn-danger" style="padding:0px 10px;">重置</a></li>-->
					<!--</ul>-->
				<!--</form>-->
			<!--</div>-->
			<!---->
			<div class="border clearfix">
				<span class="l_f">
					<a href="/admin.php/Banner/add" class="btn btn-warning"><i class="icon-plus"></i>添加轮播图</a>
					<!--<a href="javascript:ovid()" class="btn btn-danger"><i class="icon-trash"></i>批量删除</a>-->
				</span>
				<span class="r_f">共：<b><?php echo ($count); ?></b>条</span>
			</div>
			<!---->
			<div class="table_menu_list">
				<table class="table table-striped table-bordered table-hover" id="sample-table">
					<thead>
						<tr>
							<th width="80">ID</th>
							<th width="80">图片</th>
							<!--<th width="200">关联专辑</th>-->
							<th width="100">添加时间</th>
							<th width="80">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							<td><?php echo ($vo["id"]); ?></td>
							<td><img src="<?php echo ($vo["url"]); ?>" width="100px" /></td>
							<!--<td><?php echo ($vo["name"]); ?></td>-->
                            <td><?php echo ($vo["time"]); ?></td>
							<td class="td-manage">
								<a title="编辑" href="/admin.php/Banner/edit/i/<?php echo ($vo["id"]); ?>"  class="btn btn-xs btn-info" ><i class="icon-edit bigger-120"></i></a> 
								<a title="删除" href="javascript:;"  onclick="del(this,'<?php echo ($vo["id"]); ?>')" class="btn btn-xs btn-warning" ><i class="icon-trash  bigger-120"></i></a>
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
jQuery(function($){
	var oTable1 = $('#sample-table').dataTable( {
	"aaSorting": [[ 1, "desc" ]],//默认第几个排序
	"bStateSave": true,//状态保存
	"aoColumnDefs": [
	  //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
	  {"orderable":false,"aTargets":[0,8,9]}// 制定列不参与排序
	] } );

	$('table th input:checkbox').on('click' , function(){
		var that = this;
		$(this).closest('table').find('tr > td:first-child input:checkbox')
		.each(function(){
			this.checked = that.checked;
			$(this).closest('tr').toggleClass('selected');
		});
			
	});

	$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
	function tooltip_placement(context, source) {
		var $source = $(source);
		var $parent = $source.closest('table')
		var off1 = $parent.offset();
		var w1 = $parent.width();

		var off2 = $source.offset();
		var w2 = $source.width();

		if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
		return 'left';
	}
})

$('#search').on('click' , function(){
		if($("#keyword").val()=="")
		{
			layer.msg('请输入关键字',{icon:2,time:1000});
			return false;
		}
	});
/*用户-删除*/
function del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		$.post('/admin.php/Banner/del',{id:id},function(data){
			if(data.flag==1){
				layer.msg('已删除!',{icon:1,time:1000},function(){
					window.location.href="/admin.php/Banner";
					//parent.location.reload(); // 父页面刷新
					var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
					parent.layer.close(index);
				});
			}else{
				layer.msg('删除失败!',{icon:2,time:1000},function(){
					window.location.href="/admin.php/Banner";
					//parent.location.reload(); // 父页面刷新
					var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
					parent.layer.close(index);
				});
			}
		});
	});
}
</script>