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

	<title>添加文章</title>

	<script>
        KindEditor.ready(function(K)
        {
            var editor = K.create('textarea[name="content"]', {
                cssPath : 'https://quanyu.xcxweshine.com//Public/admin/editor/plugins/code/prettify.css',
                uploadJson : 'https://quanyu.xcxweshine.com//Public/admin/editor/php/upload_json.php',
                fileManagerJson : 'https://quanyu.xcxweshine.com//Public/admin/editor/php/file_manager_json.php',
                allowFileManager : true,
                afterBlur: function(){this.sync();}
            });
            prettyPrint();

            var editor = K.editor({
                allowFileManager : true,
                allowImageRemote : false
            });

            K('#image_thumb').click(function() {
                editor.loadPlugin('image', function() {
                    editor.plugin.imageDialog({
                        imageUrl : K('#url').val(),
                        clickFn : function(url, title, width, height, border, align) {
                            K('#url').val(url);
                            $('#coverimg').attr('src','https://quanyu.xcxweshine.com/'+url);

                            editor.hideDialog();
                        }
                    });
                });
            });

        });
	</script>
	<style></style>
</head>
<body>
<div class="clearfix" id="add_picture">
	<div class="page_right_style" style="left:0px;width: 100%;">
		<div class="type_title">修改轮播</div>
		<input type="hidden" id="id" name="id" value="<?php echo ($list[0]["id"]); ?>" />
		<div class="form form-horizontal" id="form-article-add">

			<!--<div class="clearfix cl">-->
				<!--<label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>活动链接：</label>-->
				<!--<div class="col-lg-8 col-md-8" >-->
					<!--<div id="f_url">-->
						<!--<select name="album_id" id="album_id" style="height:32px;">-->
							<!--<option value="0">请选择专辑</option>-->
							<!--<?php if(is_array($album)): $i = 0; $__LIST__ = $album;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>-->
								<!--<?php if($vo['id'] == $list[0]['album_id']): ?>-->
									<!--<option value="<?php echo ($vo["id"]); ?>" selected="selected"><?php echo ($vo["name"]); ?></option>-->
									<!--<?php else: ?>-->
									<!--<option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option>-->
								<!--<?php endif; ?>-->
							<!--<?php endforeach; endif; else: echo "$empty" ;endif; ?>-->
						<!--</select>-->
					<!--</div>-->
				<!--</div>-->
			</div>

			<div class="clearfix cl">
				<label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>缩略图：</label>
				<div class="col-lg-6 col-md-6 f_thumb">
					<input type="text" value="<?php echo ($list[0]["url"]); ?>" class="form-control" name="url" id="url" style="margin-bottom:10px;">
					<span style="color:red">注 : 建议尺寸375*200</span>
				</div>
				<div class="col-lg-1 col-md-1">
					<input type="button" id="image_thumb" value="选择图片" class="form-control btn btn-success" style="color:#fff;"/>
				</div>
			</div>
			<div class="clearfix cl">
				<label class="col-lg-2 col-md-2 col-sm-12 control-label">封面：</label>
				<div class="col-lg-6 col-md-6" id="">
					<img src="https://<?php echo ($_SERVER['SERVER_NAME']); ?>/<?php echo ($list[0]["url"]); ?>" id="coverimg" width="370px" height="146" style="border:1px solid lightgrey" alt="">
				</div>
			</div>
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

        checkUrl : function() {
            var urlEl = $("#url"),
                url = $.trim(urlEl.val());

            urlEl.val(url);

            if(url == ""){
                $("#f_url").removeClass().addClass("has-error");
                layer.msg("活动链接不能为空！",{icon:2,time:1000},function(){});
                return false;
            }
            else {
                $("#f_url").removeClass().addClass("has-success");
                return true;
            }
        },

        checkThumb : function() {
            var thumbEl = $("#thumb"),
                thumb = $.trim(thumbEl.val());

            thumbEl.val(thumb);

            if(thumb == ""){
                $("#f_thumb").removeClass().addClass("has-error");
                $("#f_thumb").css("border","1px solid red");
                layer.msg("请上传轮播图！",{icon:2,time:1000},function(){});
                return false;
            }
            else {
                $("#f_thumb").removeClass().addClass("has-success");
                $("#f_thumb").css("border","1px solid green");
                return true;
            }
        },

        checkSubmit : function() {

            $("#url") && Check.checkUrl();
            $("#thumb") && Check.checkThumb();

            var hasError = $("#f_url").hasClass("has-error")
                ||$("#f_thumb").hasClass("has-error");

            if (hasError) {
                layer.msg("请填写真确的轮播图信息！",{icon:2,time:1000},function(){});
                return false;
            }
            else
            {
                var id = $("#id").val();
                var url = $("#url").val();
                // var album_id = $("#album_id").val();
                var data = {id:id,url:url};
                $.ajax(
                    {
                        url:'/admin.php/Banner/save',
                        data:data,
                        type:'POST',
                        dataType:'json',
                        success:function(json){
                            if(json.flag==1){
                                layer.msg('修改成功!',{icon:1,time:3000},function(){
                                    window.location.href="/admin.php/Banner";
                                    //parent.location.reload(); // 父页面刷新
                                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                    parent.layer.close(index);
                                });
                            }else{
                                layer.msg('广告修改失败!',{icon:2,time:1000},function(){return false;});
                            }
                        }
                    }
                )
            }
        },

    };


    $(document).ready(function(){

        $("#url").blur(Check.checkUrl);
        $("#thumb").blur(Check.checkThumb);
        $("#f_submit").click(Check.checkSubmit);

    });
</script>
</body>
</html>