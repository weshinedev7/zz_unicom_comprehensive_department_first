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

            K('#icon_cover').click(function() {
                editor.loadPlugin('image', function() {
                    editor.plugin.imageDialog({
                        imageUrl : K('#icon').val(),
                        clickFn : function(url, title, width, height, border, align) {
                            K('#icon').val(url);
                            $('#iconimg').attr('src','https://bidexcx.wshoto.com/'+url);
                            editor.hideDialog();
                        }
                    });
                });
            });
            K('#icon_greycover').click(function() {
                editor.loadPlugin('image', function() {
                    editor.plugin.imageDialog({
                        imageUrl : K('#grey_icon').val(),
                        clickFn : function(url, title, width, height, border, align) {
                            K('#grey_icon').val(url);
                            $('#grey_iconimg').attr('src','https://bidexcx.wshoto.com/'+url);
                            editor.hideDialog();
                        }
                    });
                });
            });

        });
    </script>

    <title>消费信息</title>
</head>

<body>
<div class="clearfix" id="add_picture">
    <div class="page_right_style" style="left:0px;width: 100%;">
        <div class="type_title">徽章</div>
        <input type="hidden" id="id" name="id" value="<?php echo ($data["id"]); ?>" />

        <div class="form form-horizontal" id="form-article-add">
            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>标题：</label>
                <div class="col-lg-8 col-md-8" >
                    <div id="f_title"><input type="text" class="input-text" value="<?php echo ($data["name"]); ?>" placeholder="" name="name" id="name"></div>
                </div>
            </div>
            <!--<div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>徽章分类：</label>
                <div class="col-lg-8 col-md-8" >
                    <select name="cate_id" class="text_add" id="cate_id">
                        <option value="">请选择分类</option>
                        <?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cate["id"]); ?>" <?php if($data['cate_id'] == $cate['id']): ?>selected="selected"<?php endif; ?>><?php echo ($cate["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </div>-->


            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>规则介绍：</label>
                <div class="col-lg-8 col-md-8" >
                    <textarea name="rule_content" id="rule_content" style="width:100% " rows="5"><?php echo ($data["rule_content"]); ?></textarea>
                </div>
            </div>


            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label">徽章图标：</label>
                <div class="col-lg-6 col-md-6" id="f_banner">
                    <input type="text" class="form-control" name="icon" id="icon" value="<?php echo ($data["cover"]); ?>" style="margin-bottom:10px;">
                    <span style="color:red">注 : 建议尺寸100*100</span>
                </div>
                <div class="col-lg-1 col-md-1">
                    <input type="button" id="icon_cover" value="选择图片" class="form-control btn btn-success" style="color:#fff;"/>
                </div>
            </div>
            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label">图标：</label>
                <div class="col-lg-6 col-md-6" id="">
                    <img src="https://bidexcx.wshoto.com/<?php echo ($data["cover"]); ?>" id="iconimg" width="100" height="100" style="border:1px solid lightgrey" alt="">
                </div>
            </div>

            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label">置灰徽章图标：</label>
                <div class="col-lg-6 col-md-6" id="">
                    <input type="text" class="form-control" name="grey_icon" id="grey_icon" value="<?php echo ($data["grey_icon"]); ?>" style="margin-bottom:10px;">
                    <span style="color:red">注 : 建议尺寸100*100</span>
                </div>
                <div class="col-lg-1 col-md-1">
                    <input type="button" id="icon_greycover" value="选择图片" class="form-control btn btn-success" style="color:#fff;"/>
                </div>
            </div>
            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label">图标：</label>
                <div class="col-lg-6 col-md-6" id="">
                    <img src="https://bidexcx.wshoto.com/<?php echo ($data["grey_icon"]); ?>" id="grey_iconimg" width="100" height="100" style="border:1px solid lightgrey" alt="">
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
            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label"><span class="c-red" style="color:red;">*&nbsp;&nbsp;</span>规则</label>
                <div class="col-lg-8 col-md-8">
                    <span class="form-control" style="color:red">注 : 以下规则任选其一,提交时以选中的为准</span>
                </div>
            </div>
            <div class="clearfix cl">
                <label class="col-lg-2 col-md-2 col-sm-12 control-label"></label>
                <div class="col-lg-8 col-md-8">
                    <div class="margin clearfix">
                        <div class="stystems_style">
                            <div class="tabbable">
                                <input type="hidden" value="<?php echo ($data["rule_id"]); ?>" id="badgetype" name="badgetype">
                                <ul class="nav nav-tabs" id="myTab">
                                    <li <?php if($data['rule_id'] == 1): ?>class="active"<?php endif; ?>>
                                        <a data-toggle="tab" class="badgetype" data-type="1" href="#complete">收听完成</a>
                                    </li>
                                    <li <?php if($data['rule_id'] == 2): ?>class="active"<?php endif; ?>>
                                        <a data-toggle="tab"  data-type="2" href="#buycount" class="dropdown-toggle badgetype">购买成功</a>
                                    </li>
                                    <li <?php if($data['rule_id'] == 3): ?>class="active"<?php endif; ?>>
                                        <a data-toggle="tab" data-type="3" href="#recommend" class="dropdown-toggle badgetype" >推广类订单数量</a>
                                    </li>
                                    <li <?php if($data['rule_id'] == 4): ?>class="active"<?php endif; ?>>
                                        <a data-toggle="tab" data-type="4" href="#durations" class="dropdown-toggle badgetype" >收听时长</a>
                                    </li>
                                    <li <?php if($data['rule_id'] == 5): ?>class="active"<?php endif; ?>>
                                        <a data-toggle="tab"  data-type="5" href="#dayscount" class="dropdown-toggle badgetype" >使用天数</a>
                                    </li>
                                    <li <?php if($data['rule_id'] == 6): ?>class="active"<?php endif; ?>>
                                        <a data-toggle="tab"  data-type="6" href="#otherbadge" class="dropdown-toggle badgetype" >获取其他徽章</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <!--收听指定音频-->
                                    <?php if($data['rule_id'] == 1): ?><div id="complete" class="tab-pane active">
                                        <?php else: ?>
                                            <div id="complete" class="tab-pane"><?php endif; ?>
                                        <div class="form-group"><label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 每日科学 </label>
                                            <div class="col-sm-9" style="margin-top: 10px">
                                                <?php if(is_array($science)): $i = 0; $__LIST__ = $science;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$audio): $mod = ($i % 2 );++$i;?><label class="middle"><input class="ace" type="checkbox" name="scienceaudio" value="<?php echo ($audio["id"]); ?>" id="<?php echo ($audio["id"]); ?>" <?php if(in_array($audio['id'],$data['rule']['science']['sespids'])): ?>checked="checked"<?php endif; ?> ><span class="lbl"> <?php echo ($audio["name"]); ?></span></label><br><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </div>
                                        </div>
                                        <div class="form-group"><label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 专辑故事 </label>
                                        <div class="col-sm-9" style="margin-top: 10px">
                                            <select name="albumid" class="text_add" id="changealbum">
                                                <option value="">请选择专辑</option>
                                                <?php if(is_array($album)): $i = 0; $__LIST__ = $album;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($albuminfo['id'] == $vo['id']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-group"><label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 单集列表</label>
                                            <div class="col-sm-9 espids"  style="margin-top: 10px">
                                                <?php if($content == ''): ?>暂无内容
                                                    <?php else: ?>
                                                    <?php echo ($content); endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <!--付款订单数量-->
                                    <?php if($data['rule_id'] == 2): ?><div id="buycount" class="tab-pane active">
                                            <?php else: ?>
                                        <div id="buycount" class="tab-pane"><?php endif; ?>
                                            <div class="clearfix cl">
                                                <label class="col-lg-3 col-md-3 col-sm-12 control-label">订单数量(已支付)</label>
                                                <div class="col-lg-7 col-md-7">
                                                    <input type="text" id="ordercounts" name="ordercounts " value="<?php echo ($data["rule"]["ordercounts"]); ?>"  class="text_time"/>
                                                </div>
                                            </div>
                                        </div>
                                    <!--分享订单数量-->
                                    <?php if($data['rule_id'] == 3): ?><div id="recommend" class="tab-pane active">
                                            <?php else: ?>
                                        <div id="recommend" class="tab-pane"><?php endif; ?>
                                            <div class="clearfix cl">
                                                <label class="col-lg-3 col-md-3 col-sm-12 control-label">推广类订单数量(已支付)</label>
                                                <div class="col-lg-7 col-md-7">
                                                    <input type="text" id="shareorders" name="shareorders " value="<?php echo ($data["rule"]["shareorders"]); ?>"  class="text_time"/>
                                                </div>
                                            </div>
                                        </div>
                                    <!--收听时长-->
                                    <?php if($data['rule_id'] == 4): ?><div id="durations" class="tab-pane active">
                                            <?php else: ?>
                                        <div id="durations" class="tab-pane"><?php endif; ?>
                                            <div class="clearfix cl">
                                                <label class="col-lg-3 col-md-3 col-sm-12 control-label">收听时长(单位:秒)</label>
                                                <div class="col-lg-7 col-md-7">
                                                    <input type="text" id="duration" name="duration " value="<?php echo ($data["rule"]["duration"]); ?>"  class="text_time"/>
                                                </div>
                                            </div>
                                        </div>
                                    <!--使用天数-->
                                    <?php if($data['rule_id'] == 5): ?><div id="dayscount" class="tab-pane active">
                                            <?php else: ?>
                                        <div id="dayscount" class="tab-pane"><?php endif; ?>
                                            <div class="clearfix cl">
                                                <label class="col-lg-3 col-md-3 col-sm-12 control-label">使用天数</label>
                                                <div class="col-lg-7 col-md-7">
                                                    连续 <input type="text" id="days" name="days " value="<?php echo ($data["rule"]["days"]); ?>"  class="text_time"/> 天
                                                </div>
                                            </div>

                                        </div>

                                   <!-- 获取其他徽章-->
                                    <?php if($data['rule_id'] == 6): ?><div id="otherbadge" class="tab-pane active">
                                            <?php else: ?>
                                        <div id="otherbadge" class="tab-pane"><?php endif; ?>
                                            <div id="otherbadge" class="tab-pane">
                                                <div class="form-group"><label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 获取其他徽章 </label>
                                                    <div class="col-sm-9" style="margin-top: 10px">
                                                        <?php if(is_array($allbadge)): $i = 0; $__LIST__ = $allbadge;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><label class="middle">
                                                                <input class="ace" name="badgeids" type="checkbox" value="<?php echo ($row["id"]); ?>" id="<?php echo ($row["id"]); ?>"<?php if(in_array($row['id'],$data['rule']['badgeids'])): ?>checked="checked"<?php endif; ?>  ><span class="lbl"> <?php echo ($row["name"]); ?></span>
                                                            </label>
                                                            <br><?php endforeach; endif; else: echo "" ;endif; ?>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="clearfix cl">
                <div class="Button_operation">
                    <button class="btn btn-primary radius" type="button" id="submit"><i class="icon-save "></i>保存</button>
                    <button class="btn btn-default radius" type="reset"  onclick="history.back()">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<script>
    $('.badgetype').click(function () {
        $('#badgetype').val($(this).data('type'));
    })

    $('#changealbum').change(function () {
        var albumid=$(this).val()
        $.post('/admin.php/Badge/getesps',{albumid:albumid},function(data){
            /*data是返回的值*/
            console.log(data)
            $('.espids').empty();
            $('.espids').append(data.esps);
            if(data.status==-1){
                layer.msg('暂无音频!',{icon:2,time:1000},function(){});
            }
        });

    })

    $('#submit').click(function () {
        var id=$("#id").val();
        var type=$('#badgetype').val();
        var name=$('#name').val();
        var rule_content=$('#rule_content').val();
        console.log(rule_content)
        var icon=$('#icon').val();
        var icon_grey=$('#grey_icon').val();
        var price=$('#price').val();
        var rule={}
        var cate_id=$('#cate_id').val();
        if(name=='' || rule_content=='' || icon=='' || icon_grey=='' || price=='' ||cate_id=='' ){
            layer.msg('请填写信息!',{icon:2,time:1000},function(){});
            return;
        }


        if (type==1){
            var scienceaudio=new Array();
            $('input:checkbox[name=scienceaudio]:checked').each(function () {
                scienceaudio.push($(this).val());
            })
            var science={sespids:scienceaudio}

            var espids=new Array();
            $('input:checkbox[name=espids]:checked').each(function () {
                espids.push($(this).val());
            })
            var album={sespids:espids}
            if(science.sespids.length==0 && album.sespids.length==0){
                layer.msg('未明确规则!',{icon:2,time:1000},function(){});
                return;
            }
            rule={science:science,album:album}
        }
        if (type==2){
            var ordercounts=$('#ordercounts').val();
            rule={ordercounts:ordercounts}
            if(ordercounts==''){
                layer.msg('未明确规则!',{icon:2,time:1000},function(){});
                return;
            }
        }
        if (type==3){
            var shareorders=$('#shareorders').val();
            rule={shareorders:shareorders}
            if(shareorders==''){
                layer.msg('未明确规则!',{icon:2,time:1000},function(){});
                return;
            }
        }
        if (type==4){
            var duration=$('#duration').val();
            rule={duration:duration}
            if(duration==''){
                layer.msg('未明确规则!',{icon:2,time:1000},function(){});
                return;
            }
        }
        if (type==5){
            var days=$('#days').val();
            rule={days:days}
            if(days==''){
                layer.msg('未明确规则!',{icon:2,time:1000},function(){});
                return;
            }
        }
        if (type==6){
            var badgeids=new Array();
            $('input:checkbox[name=badgeids]:checked').each(function () {
                badgeids.push($(this).val());
            })

            if(badgeids.length==0 ){
                layer.msg('未选择!',{icon:2,time:1000},function(){});
                return;
            }
            rule={badgeids:badgeids}
        }
        if(rule==''){
            layer.msg('未明确规则!',{icon:2,time:1000},function(){});
            return;
        }

        var data={cate_id:cate_id,id:id,rule_content:rule_content,type:type,name:name,icon:icon,icon_grey:icon_grey,price:price,rule:rule}
        $.post('/admin.php/Badge/savebadge',data,function(data){
            /*data是返回的值*/

            if(data.status==1){
                layer.alert('成功！',{title: '提示框',icon:1},function(){
                    window.location.href="/admin.php/Badge";
                    //parent.location.reload(); // 父页面刷新
                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                    parent.layer.close(index);
                });
            }else{
                layer.alert('失败！',{title: '提示框',icon:2},function(){
                    window.location.href="/admin.php/Badge"; // 父页面刷新
                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                    parent.layer.close(index);
                });
            }
        });

    })


</script>