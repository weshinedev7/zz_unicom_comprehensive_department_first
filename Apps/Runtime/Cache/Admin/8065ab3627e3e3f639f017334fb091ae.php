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
            <div class="search_style">
                <div class="title_names">搜索查询</div>
                <form action="/admin.php/User/userconsumption" method="get">
                    <ul class="search_content clearfix">
                        <li><label class="l_f">搜索查询：</label><input name="keyword" id="keyword" type="text" value="<?php echo ($keyword); ?>" class="text_add" placeholder="输入用户昵称或手机号"  style=" width:400px"/></li>
                        <li>
                            <select class="select text_add" name="usertags" id="usertags" style="width:100px;height:32px;">
                                <option value="0" >选择标签</option>
                                <?php if(is_array($tags)): $i = 0; $__LIST__ = $tags;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($usertags == $vo['id']): ?><option value="<?php echo ($vo["id"]); ?>" selected="selected"><?php echo ($vo["name"]); ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endif; endforeach; endif; else: echo "$empty" ;endif; ?>
                            </select>
                        </li>
                        <li><label class="l_f">排序依据：</label></li>
                        <li>
                            <select class="select text_add" name="ordertype" id="" style="width:100px;height:32px;">
                                <option value="" >排序依据</option>
                                    <?php if( $ordertype == 'price'): ?><option value="price" selected="selected">消费金额</option>
                                        <?php else: ?>
                                        <option value="price">消费金额</option><?php endif; ?>
                                    <?php if( $ordertype == 'province'): ?><option value="province" selected="selected">省首字母</option>
                                        <?php else: ?>
                                        <option value="province">省首字母</option><?php endif; ?>
                                    <?php if( $ordertype == 'city'): ?><option value="city" selected="selected">市首字母</option>
                                        <?php else: ?>
                                        <option value="city">市首字母</option><?php endif; ?>
                                    <?php if( $ordertype == 'area'): ?><option value="area" selected="selected">区县首字母</option>
                                        <?php else: ?>
                                        <option value="area">区县首字母</option><?php endif; ?>
                                    <?php if( $ordertype == 'authtime'): ?><option value="authtime selected="selected">授权时间</option>
                                        <?php else: ?>
                                        <option value="authtime">授权时间</option><?php endif; ?>
                            </select>
                        </li>
                        <li style="width:90px;"><button type="submit" onclick="return showAreaID()" id="search" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</button></li>

                        <!--
                        <li>
                            <label class="l_f">添加时间</label>
                            <input class="inline laydate-icon" name="starttime" id="start" style=" margin-left:10px;">&nbsp;&nbsp;&nbsp;-
                            <input class="inline laydate-icon" name="endtime" id="end" style=" margin-left:10px;">
                        </li>
                        -->
                        <!--
                                                <li style="width:90px;"><a href="/admin.php/User/index/type/0" class="btn btn-danger" style="padding:0px 10px;">重置</a></li>
                        -->
                    </ul>
                     <ul class="search_content clearfix">
                         <li><label class="l_f">选择地区：</label></li>
                        <li>
                            <select id="seachprov" name="seachprov" onChange="changeComplexProvince(this.value, sub_array, 'seachcity', 'seachdistrict');">
                            </select>&nbsp;&nbsp;
                            <select id="seachcity" name="homecity" onChange="changeCity(this.value,'seachdistrict','seachdistrict');">
                            </select>&nbsp;&nbsp;
                            <span id="seachdistrict_div"><select id="seachdistrict" name="seachdistrict">
                            </select></span>
                            <input type="hidden" name="provincename" value="">
                            <input type="hidden" name="cityname" value="">
                            <input type="hidden" name="areaname" value="">
                        </li>
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
            <div class="search_style">
                <div class="title_names">打标签</div>

                    <ul class="search_content clearfix">
                        <li style="width:90px;"><button  id="checkall" class="btn btn-warning" style="padding:0px 10px;">全选</button></li>
                        <li style="width:90px;"><button  id="uncheckall" class="btn btn-danger" style="padding:0px 10px;">取消全选</button></li>
                        <li style="width:90px;"><button onclick="edit()"  id="showtags" class="btn btn-success" style="padding:0px 10px;">打标签</button></li>

                    </ul>

            </div>
            <style>#imglist img{height: 50px;}#imgshow{position: absolute; border: 1px solid #ccc; background: #333; padding: 5px; color: #fff; display: none; } </style>
            <!---->
            <div class="table_menu_list" id="imglist">
                <table class="table table-striped table-bordered table-hover" id="sample-table">
                    <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th width="100">标签</th>
                        <th width="150">昵称</th>
                        <th width="150">手机号</th>
                        <th width="150">
                             消费金额
                        </th>
                        <th width="200">
                                省份
                        </th>
                        <th width="200">
                                城市

                        </th>
                        <th width="200">
                                区县
                        </th>
                        <th width="200">
                                授权时间
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td>

                                <input type="checkbox"  name="usercheck" value="<?php echo ($vo["id"]); ?>">
                                <?php echo ($vo["id"]); ?>
                            </td>
                            <td><?php echo ($vo["tagname"]); ?></td>
                            <td><img src="<?php echo ($vo["avatar"]); ?>" style="height:30px;"/>&nbsp;&nbsp;&nbsp;<?php echo ($vo["nickname"]); ?></td>
                            <td><?php echo ($vo["mobile"]); ?></td>
                            <td><?php echo ($vo["sumprice"]); ?></td>
                            <td><?php echo ($vo["province"]); ?></td>
                            <td><?php echo ($vo["city"]); ?></td>
                            <td><?php echo ($vo["area"]); ?></td>
                            <td><?php echo ($vo["authtime"]); ?></td>
                        </tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="pagination pagination-large"><?php echo ($pages); ?></div>
        </div>
    </div>
</div>
<div class="add_menber" id="add_style" style="display:none">
    <ul class=" page-content">
        <li class="adderss">
            <label class="label_name"> 打标签：</label>
            <?php if(is_array($tags)): $i = 0; $__LIST__ = $tags;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?><label for="<?php echo ($value["id"]); ?>">
                    <input id="<?php echo ($value["id"]); ?>" value="<?php echo ($value["id"]); ?>" name="tagid" title="备注" id="remarkdata" type="checkbox"  class="text_add" /><?php echo ($value["name"]); ?>
                </label> &nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
            <div class="prompt r_f"></div>
        </li>
    </ul>
</div>

</body>
</html>
<script>

    $('#checkall').click(function () {
        $('input:checkbox[name=usercheck]').each(function () {
            $(this).prop('checked', true);
        })
    })

    $('#uncheckall').click(function () {
        $('input:checkbox[name=usercheck]').each(function () {
            $(this).prop('checked', false);
        })
    })




    function edit(){
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

                /* 以下.点击完标签.打印的时候展示*/
                var tagids=new Array();
                 $('input:checkbox[name=tagid]:checked').each(function () {
                     tagids.push($(this).val());
                 })
                var userids=new Array();
                $('input:checkbox[name=usercheck]:checked').each(function () {
                    userids.push($(this).val());
                })

                if(tagids.length==0){

                    layer.msg('请选择标签!',{icon:2,time:1000},function(){});
                    return;
                }
                if(userids.length==0){

                    layer.msg('请勾选用户!',{icon:2,time:1000},function(){});
                    return;
                }


                $.post('/admin.php/User/saveusertags',{tagids:tagids,userids:userids},function(data){
                    /*data是返回的值*/
                    console.log(data)
                    if(data.status==1){
                        layer.alert('成功！',{title: '提示框',icon:1},function(){
                            window.location.href="/admin.php/User/userconsumption";
                            //parent.location.reload(); // 父页面刷新
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);
                        });
                    }else{
                        layer.alert('失败！',{title: '提示框',icon:2},function(){
                            window.location.href="/admin.php/User/userconsumption"; // 父页面刷新
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);
                        });
                    }
                });

            }
        });

    }


</script>

<script type="text/javascript">
    $(function (){
        initComplexArea('seachprov', 'seachcity', 'seachdistrict', area_array, sub_array, "<?php echo ($searcharea["procode"]); ?>", "<?php echo ($searcharea["citycode"]); ?>","<?php echo ($searcharea["areacode"]); ?>");
    });

    //得到地区码
    function getAreaID(){
        var area = 0;
        if($("#seachdistrict").val() != "0"){
            area = $("#seachdistrict").val();
        }else if ($("#seachcity").val() != "0"){
            area = $("#seachcity").val();
        }else{
            area = $("#seachprov").val();
        }
        return area;
    }

    function showAreaID() {
        //地区码
        var areaID = getAreaID();
        //地区名
        var areaName = getAreaNamebyID(areaID) ;
    }

    //根据地区码查询地区名
    function getAreaNamebyID(areaID){
        var areaName = "";
        if(areaID.length == 2){
            areaName = area_array[areaID];
            $("input:hidden[name='provincename']").val(areaName)

        }else if(areaID.length == 4){
            var index1 = areaID.substring(0, 2);
            areaName = area_array[index1] + " " + sub_array[index1][areaID];
            $("input:hidden[name='provincename']").val(area_array[index1])
            $("input:hidden[name='cityname']").val(sub_array[index1][areaID])

        }else if(areaID.length == 6){
            var index1 = areaID.substring(0, 2);
            var index2 = areaID.substring(0, 4);
            areaName = area_array[index1] + " " + sub_array[index1][index2] + " " + sub_arr[index2][areaID];
            $("input:hidden[name='provincename']").val(area_array[index1])
            $("input:hidden[name='cityname']").val(sub_array[index1][index2])
            $("input:hidden[name='areaname']").val(sub_arr[index2][areaID])

        }

        return areaName;
    }
</script>