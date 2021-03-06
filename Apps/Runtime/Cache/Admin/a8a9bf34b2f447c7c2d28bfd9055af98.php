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

    <title>专辑故事</title>

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

            K('#image_cover').click(function() {
                editor.loadPlugin('image', function() {
                    editor.plugin.imageDialog({
                        imageUrl : K('#cover').val(),
                        clickFn : function(url, title, width, height, border, align) {
                            K('#cover').val(url);
                            $('#coverimg').attr('src','https://bidexcx.wshoto.com/'+url);
                            editor.hideDialog();
                        }
                    });
                });
            });
            K('#audio_cover').click(function() {
                editor.loadPlugin('insertfile', function() {
                    editor.plugin.fileDialog({
                        fileUrl : K('#audio').val(),
                        clickFn : function(url, title) {
                            K('#audio').val(url);
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
        <div class="type_title">专辑故事</div>
        <input type="hidden" id="id" name="id" value="<?php echo ($data["id"]); ?>" />

        <div class="form form-horizontal" id="form-article-add">
            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>标题：</label>
                <div class="col-lg-8 col-md-8" >
                    <div id="f_title"><input type="text" class="input-text" value="<?php echo ($data["name"]); ?>" placeholder="" name="name" id="name"></div>
                </div>
            </div>
            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>介绍：</label>
                <div class="col-lg-8 col-md-8" >
                    <textarea name="introduction" id="introduction" style="width:100% " rows="5"><?php echo ($data["introduction"]); ?></textarea>
                </div>
            </div>


            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label">封面：</label>
                <div class="col-lg-6 col-md-6" id="f_banner">
                    <input type="text" class="form-control" name="cover" id="cover" value="<?php echo ($data["cover"]); ?>" style="margin-bottom:10px;">
                    <span style="color:red">注 : 建议尺寸370*146</span>
                </div>
                <div class="col-lg-1 col-md-1">
                    <input type="button" id="image_cover" value="选择图片" class="form-control btn btn-success" style="color:#fff;"/>
                </div>
            </div>
            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label">封面：</label>
                <div class="col-lg-6 col-md-6" id="">
                    <img src="https://bidexcx.wshoto.com/<?php echo ($data["cover"]); ?>" id="coverimg" width="370px" height="146" style="border:1px solid lightgrey" alt="">
                </div>
            </div>

            <!--
                <div class="clearfix cl">
                    <label class="col-lg-2 col-md-2 col-sm-12 control-label">关键词：</label>
                    <div class="col-lg-8 col-md-8">
                        <input type="text" class="input-text" value="<?php echo ($list[0]["keysword"]); ?>" placeholder="" id="keysword" name="keysword">
                    </div>
                </div>

                <div class="clearfix cl">
                    <label class="col-lg-2 col-md-2 col-sm-12 control-label">内容摘要：</label>
                    <div class="col-lg-8 col-md-8">
                        <textarea name="description" id="description" style="width:100%;height:80px;" placeholder="说点什么...最少输入10个字符" datatype="*10-100" dragonfly="true" nullmsg="备注不能为空！" onKeyUp="textarealength(this,200)"><?php echo ($list[0]["description"]); ?></textarea>
                    </div>
                </div>-->
            <!--<div class="clearfix cl">
                <div>
                    <label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>收费方式：</label>
                    <div class="col-lg-8 col-md-8" style="padding-top: 8px;">
                        <label for="validityall">
                            <input type="radio" value="0" name="validitytype" checked="checked"  id="validityall">免费
                        </label>
                    </div>
                </div>
            </div>-->
            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>价格</label>
                <div class="col-lg-8 col-md-8">
                    <!--<label for="validitydate">
                        <input type="radio" value="1" placeholder="" name="validitytype" id="validitydate">收费
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;-->
                    <input type="text" id="price" name="price" value="<?php echo ($data["price"]); ?>"  class="text_time"/> 元 <span style="color:red">注 : (填写0为免费)</span>

                </div>
            </div>
            <!--
                        <div class="clearfix cl">
                            <label class="col-lg-2 col-md-2 col-sm-12 control-label">缩略图：</label>
                            <div class="col-lg-6 col-md-6">
                                <input type="text" class="form-control" name="pic" id="pic" value="<?php echo ($list[0]["pic"]); ?>" style="margin-bottom:10px;">
                            </div>
                            <div class="col-lg-1 col-md-1">
                                <input type="button" id="image_pic" value="选择图片" class="form-control btn btn-success" style="color:#fff;"/>
                            </div>
                        </div>-->

            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>详细内容：</label>
                <div class="col-lg-8 col-md-8 ">
                    <div id="f_content"><textarea id="content" name="content" style="height:400px;width:100%;"><?php echo ($data["content"]); ?></textarea></div>
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



        checkContent : function() {
            var contentEl = $("#content"),
                content = $.trim(contentEl.val());

            contentEl.val(content);

            if(content == ""){
                $("#f_content").removeClass().addClass("has-error");
                layer.msg("详细内容不能为空！",{icon:2,time:1000},function(){});
                return false;
            }
            else {
                $("#f_content").removeClass().addClass("has-success");
                return true;
            }
        },

        checkSubmit : function() {

            $("#content") && Check.checkContent();
            var name=$('#name').val()
            var introduction=$('#introduction').val();
            var content=$("#content").val()
            var cover = $("#cover").val();
            var price = $("#price").val();

            var id=$('#id').val()

            if(name==''){
                layer.msg("请输入标题！",{icon:2,time:1000},function(){});
                return false;
            }
            if (price==''){
                layer.msg("请填写价格！",{icon:2,time:1000},function(){});
                return false;
            }

            var data = {id:id,name:name,introduction:introduction,content:content,cover:cover,price:price};

                $.ajax(
                    {
                        url:'/admin.php/Album/savealbum',
                        data:data,
                        type:'POST',
                        dataType:'json',
                        success:function(json){

                            if(json.flag==1){
                                layer.msg('成功!',{icon:1,time:2000},function(){
                                    window.location.href="/admin.php/Album";
                                    //parent.location.reload(); // 父页面刷新
                                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                    parent.layer.close(index);
                                });
                            }else{
                                layer.msg('失败!',{icon:2,time:1000},function(){return false;});
                            }
                        }
                    }
                )

        },

    };


    $(document).ready(function(){

        $("#title").blur(Check.checkTitle);
        $("#content").blur(Check.checkContent);
        $("#f_submit").click(Check.checkSubmit);


    });
</script>
</body>
</html>