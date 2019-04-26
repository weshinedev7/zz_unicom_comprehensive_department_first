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

	<title>系统配置</title>


	<style></style>
</head>
<body>
<div class="clearfix" id="add_picture">
	<div class="page_right_style" style="left:0px;width: 100%;">
		<div class="type_title">系统配置</div>
		<input type="hidden" id="id" name="id" value="1" />
		<div class="form form-horizontal" id="form-article-add">
			<!--<div class="clearfix cl">
				<label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>Appid：</label>
				<div class="col-lg-8 col-md-8" >
					<div id="f_appid"><input type="text" class="input-text" value="<?php echo ($list[0]['appid']); ?>" placeholder="" name="appid" id="appid"></div>
				</div>
			</div>

			<div class="clearfix cl">
				<label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>Appsecret：</label>
				<div class="col-lg-8 col-md-8" >
					<div id="f_appsecret"><input type="text" class="input-text" value="<?php echo ($list[0]['appsecret']); ?>" placeholder="" name="appsecret" id="appsecret"></div>
				</div>
			</div>-->
			<div class="clearfix cl">
				<label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>搜索栏推荐：</label>
				<div class="col-lg-8 col-md-8" >
					<div id="f_appsecret"><input type="text" class="input-text" value="<?php echo ($list[0]['recommend']); ?>" placeholder="" name="recommend" id="recommend"></div>
				</div>
			</div>
			<!--<div class="clearfix cl">-->
				<!--<label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>发布活动积分：</label>-->
				<!--<div class="col-lg-8 col-md-8" >-->
					<!--<div id="f_apoint"><input type="text" class="input-text" value="<?php echo ($list[0]['apoint']); ?>" placeholder="" name="apoint" id="apoint"></div>-->
				<!--</div>-->
			<!--</div>-->

			<!--<div class="clearfix cl">-->
				<!--<label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>评论活动积分：</label>-->
				<!--<div class="col-lg-8 col-md-8" >-->
					<!--<div id="f_cpoint"><input type="text" class="input-text" value="<?php echo ($list[0]['cpoint']); ?>" placeholder="" name="cpoint" id="cpoint"></div>-->
				<!--</div>-->
			<!--</div>-->

			<!--<div class="clearfix cl">-->
				<!--<label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>评论被点赞积分：</label>-->
				<!--<div class="col-lg-8 col-md-8" >-->
					<!--<div id="f_zpoint"><input type="text" class="input-text" value="<?php echo ($list[0]['zpoint']); ?>" placeholder="" name="zpoint" id="zpoint"></div>-->
				<!--</div>-->
			<!--</div>-->

			<!--<div class="clearfix cl">-->
				<!--<label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>系统服务费：</label>-->
				<!--<div class="col-lg-8 col-md-8" >-->
					<!--<div id="f_fprice"><input type="text" class="input-text" value="<?php echo ($list[0]['fprice']); ?>" placeholder="" name="fprice" id="fprice"></div>-->
				<!--</div>-->
			<!--</div>-->

			<!--<div class="clearfix cl">-->
				<!--<label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>服务费返还比例：</label>-->
				<!--<div class="col-lg-8 col-md-8" >-->
					<!--<div id="f_percent"><input type="text" class="input-text" value="<?php echo ($list[0]['percent']); ?>" placeholder="" name="percent" id="percent"></div>-->
				<!--</div>-->
			<!--</div>-->

			<div class="clearfix cl">
				<div class="Button_operation">
					<button class="btn btn-primary radius" type="button" id="f_submit"><i class="icon-save "></i>保存</button>
					<button class="btn btn-default radius" type="reset" onclick="history.back()">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>

				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script>
    var checkResult = {};
    var Check = {

        checkSubmit : function() {
			var id = $("#id").val();
			var recommend = $("#recommend").val();
			/*var appsecret = $("#appsecret").val();
            var apoint = $("#apoint").val();
            var cpoint = $("#cpoint").val();
            var zpoint = $("#zpoint").val();
			var data = {id:id,appid:appid,appsecret:appsecret,apoint:apoint,cpoint:cpoint,zpoint:zpoint};*/
            var data = {id:id,recommend:recommend};
			$.ajax(
				{
					url:'/admin.php/Setting/save',
					data:data,
					type:'POST',
					dataType:'json',
					success:function(json){
						if(json.flag==1){
							layer.msg('修改成功!',{icon:1,time:3000},function(){
								window.location.href="/admin.php/Setting";
								//parent.location.reload(); // 父页面刷新
								var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
								parent.layer.close(index);
							});
						}else{
							layer.msg('系统配置修改失败!',{icon:2,time:1000},function(){return false;});
						}
					}
				}
			)
		}

    };


    $(document).ready(function(){

        $("#name").blur(Check.checkName);
        $("#f_submit").click(Check.checkSubmit);

    });
</script>
</body>
</html>