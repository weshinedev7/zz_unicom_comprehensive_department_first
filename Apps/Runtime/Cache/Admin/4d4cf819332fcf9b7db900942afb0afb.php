<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="https://quanyu.xcxweshine.com//Public/admin/assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://quanyu.xcxweshine.com//Public/admin/css/style.css"/>
    <link href="https://quanyu.xcxweshine.com//Public/admin/assets/css/codemirror.css" rel="stylesheet">
    <link rel="stylesheet" href="https://quanyu.xcxweshine.com//Public/admin/assets/css/ace.min.css"/>
    <link rel="stylesheet" href="https://quanyu.xcxweshine.com//Public/admin/assets/css/font-awesome.min.css"/>
    <!--[if IE 7]>
    <link rel="stylesheet" href="https://quanyu.xcxweshine.com//Public/admin/assets/css/font-awesome-ie7.min.css"/>
    <![endif]-->
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="https://quanyu.xcxweshine.com//Public/admin/assets/css/ace-ie.min.css"/>
    <![endif]-->
    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/js/jquery.min.js"></script>
    <!-- <![endif]-->

    <!--[if IE]>
    <script src="http://ajax.useso.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <![endif]-->

    <!--[if !IE]> -->
    <script type="text/javascript">
        window.jQuery || document.write("<script src='https://quanyu.xcxweshine.com//Public/admin/assets/js/jquery-2.0.3.min.js'>" + "<" + "/script>");
    </script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script type="text/javascript">
        window.jQuery || document.write("<script src='https://quanyu.xcxweshine.com//Public/admin/assets/js/jquery-1.10.2.min.js'>" + "<" + "/script>");
    </script>
    <![endif]-->

    <script type="text/javascript">
        if ("ontouchend" in document) document.write("<script src='https://quanyu.xcxweshine.com//Public/admin/assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
    </script>
    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/js/bootstrap.min.js"></script>
    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/js/typeahead-bs2.min.js"></script>
    <!-- page specific plugin scripts
    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/js/jquery.dataTables.min.js"></script>
    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/js/jquery.dataTables.bootstrap.js"></script>
    -->
    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/layer/layer.js" type="text/javascript"></script>
    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/laydate/laydate.js" type="text/javascript"></script>

    <link rel="stylesheet" href="https://quanyu.xcxweshine.com//Public/admin/editor/themes/default/default.css"/>
    <link rel="stylesheet" href="https://quanyu.xcxweshine.com//Public/admin/editor/plugins/code/prettify.css"/>
    <script charset="utf-8" src="https://quanyu.xcxweshine.com//Public/admin/editor/kindeditor.js"></script>
    <script charset="utf-8" src="https://quanyu.xcxweshine.com//Public/admin/editor/lang/zh_CN.js"></script>
    <script charset="utf-8" src="https://quanyu.xcxweshine.com//Public/admin/editor/plugins/code/prettify.js"></script>

    <title>活动盛事</title>

    <script>
        KindEditor.ready(function (K) {
            var editor = K.create('textarea[name="content"]', {
                cssPath: 'https://quanyu.xcxweshine.com//Public/admin/editor/plugins/code/prettify.css',
                uploadJson: 'https://quanyu.xcxweshine.com//Public/admin/editor/php/upload_json.php',
                fileManagerJson: 'https://quanyu.xcxweshine.com//Public/admin/editor/php/file_manager_json.php',
                allowFileManager: true,
                afterBlur: function () {
                    this.sync();
                }
            });
            prettyPrint();

            var editor = K.editor({
                allowFileManager: true,
                allowImageRemote: false
            });

            K('#J_selectImage').click(function () {
                editor.loadPlugin('multiimage', function () {
                    editor.plugin.multiImageDialog({
                        clickFn: function (urlList) {
                            var div = K('#J_imageView');
                            div.html('');
                            K.each(urlList, function (i, data) {
                                div.append('<img class="imglist" src="' + data.url + '">');
                            });
                            editor.hideDialog();
                        }
                    });
                });
            });

        });
    </script>
    <style>
        .imglist {
            width: 100px;
            height: 100px;
            margin: 15px;;
        }
    </style>
</head>
<body>
<div class="clearfix" id="add_picture">
    <div class="page_right_style" style="left:0px;width: 100%;">
        <div class="type_title">图片走廊</div>
        <input type="hidden" id="id" name="id" value="<?php echo ($data["id"]); ?>"/>
        <!--<input type="hidden" id="audioid" name="audioid" value="<?php echo ($audio["id"]); ?>" />-->

        <div class="form form-horizontal" id="form-article-add">
            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>标题：</label>
                <div class="col-lg-8 col-md-8">
                    <input type="button" id="J_selectImage" value="点击批量上传图片">
                </div>
            </div>


            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label">图片：</label>
                <div class="col-lg-6 col-md-6" id="J_imageView">

                </div>
            </div>


            <div class="clearfix cl">
                <div class="Button_operation">
                    <button class="btn btn-primary radius" type="button" id="f_submit"><i class="icon-save "></i>保存
                    </button>
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




        checkSubmit: function () {

            var data = [];
            $(".imglist").each(function(e){
                var src=$(this).attr('src');
                data.push(src);
            });

            $.ajax(
                {
                    url: '/admin.php/Image/saveimage',
                    data: {imglist:data},
                    type: 'POST',
                    dataType: 'json',
                    success: function (json) {

                        if (json.flag == 1) {
                            layer.msg('成功!', {icon: 1, time: 2000}, function () {
                                window.location.href = "/admin.php/Image";
                                //parent.location.reload(); // 父页面刷新
                                var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                parent.layer.close(index);
                            });
                        } else {
                            layer.msg('失败!', {icon: 2, time: 1000}, function () {
                                return false;
                            });
                        }
                    }
                }
            )

        },

    };


    $(document).ready(function () {

        $("#title").blur(Check.checkTitle);
        $("#content").blur(Check.checkContent);
        $("#f_submit").click(Check.checkSubmit);


    });
</script>
</body>
</html>