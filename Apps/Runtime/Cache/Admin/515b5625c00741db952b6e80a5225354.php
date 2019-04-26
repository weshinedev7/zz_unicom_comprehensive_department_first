<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

    <script src="https://quanyu.xcxweshine.com//Public/admin/assets/js/jquery.min.js"></script>

    <script src="https://quanyu.xcxweshine.com//Public/admin/highchars/highcharts.js"></script>
    <title>概况</title>
</head>

<body>
<div class="page-content clearfix">
        <div class="d_Confirm_Order_style">
            <div class="table_menu_list">
                <table class="table table-striped table-bordered table-hover">
                    <caption style="text-align: center ;font-size: 20px">数据概况</caption>
                    <thead>
                    <tr>
                        <th width="50"></th>
                        <th width="150">新增用户数</th>
                        <th width="150">游客量</th>
                        <th width="150">浏览量</th>
                        <th width="150">访客数(UV)</th>
                        <th width="150">收益(元)</th>
                        <th width="150">历史总收益(元)</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                <td><?php echo ($vo["title"]); ?></td>
                                <td><?php echo ($vo["new_users"]); ?></td>
                                <td><?php echo ($vo["tourist"]); ?></td>
                                <td><?php echo ($vo["visit"]); ?></td>
                                <td><?php echo ($vo["visitor_perday"]); ?></td>
                                <!--<td><?php echo ($vo["browsers"]); ?></td>-->
                                <td><?php echo ($vo["total"]); ?></td>
                                <td><?php echo ($vo["history_total"]); ?></td>
                            </tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
                    </tbody>
                    <tfoot>
                    <tr><td colspan="5" style="text-align: center">图一：数据概况</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>

      <div class="search_style">
        <div class="title_names">搜索查询</div>
        <form action="/admin.php/Index/charts" method="post">
            <ul class="search_content clearfix">
                <li><label class="l_f">搜索查询：</label></li>
                <li>
                    <select class="select text_add" name="time2" id="time2" style="width:100px;height:32px;">
                        <?php if($time2 == 1): ?><option value="1" selected="selected">昨日</option>
                            <?php else: ?>
                            <option value="1">昨日</option><?php endif; ?>
                        <?php if($time2 == 7 ): ?><option value="7" selected="selected">7天</option>
                            <?php else: ?>
                            <option value="7">7天</option><?php endif; ?>
                        <?php if($time2 == 30 ): ?><option value="30" selected="selected">30天</option>
                            <?php else: ?>
                            <option value="30">30天</option><?php endif; ?>
                    </select>
                </li>
                <li style="width:90px;">
                    <button type="submit"  id="search2" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</button>
                </li>
            </ul>

        </form>
          <div class="table_menu_list">
              <table class="table table-striped table-bordered table-hover">
                  <caption style="text-align: center ;font-size: 20px">用户留存率</caption>
                  <thead>
                  <tr>
                      <th width="150">专辑名称</th>
                      <th width="150">访问量（UV）</th>
                      <th width="150">浏览量（PV）</th>
                      <th width="150">新增用户数</th>
                      <th width="150">留存率</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php if(is_array($list2)): $i = 0; $__LIST__ = $list2;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                          <td><?php echo ($vo["name"]); ?></td>
                          <td><?php echo ($vo["visit"]); ?></td>
                          <td><?php echo ($vo["brows"]); ?></td>
                          <td><?php echo ($vo["newusers"]); ?></td>
                          <td><?php echo ($vo["rentention"]); ?>%</td>
                      </tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
                  </tbody>
                  <tfoot>
                  <tr><td colspan="5" style="text-align: center">图二：用户留存率</td></tr>
                  </tfoot>
              </table>
          </div>
      </div>

        <div class="search_style">
        <div class="title_names">搜索查询</div>
        <form action="/admin.php/Index/chartdata" method="post">
            <ul class="search_content clearfix">
                <li><label class="l_f">搜索查询：</label></li>
                <li>
                    <select class="select text_add" name="time" id="time" style="width:100px;height:32px;">
<!--
                        <option value="" >选择时间</option>
-->
                        <?php if($time == 7): ?><option value="week" selected="selected">最近一周</option>
                            <?php else: ?>
                            <option value="7">最近一周</option><?php endif; ?>
                        <?php if($time == 15 ): ?><option value="15" selected="selected">最近15天</option>
                            <?php else: ?>
                            <option value="15">最近15天</option><?php endif; ?>
                        <?php if($time == 30 ): ?><option value="30" selected="selected">最近30天</option>
                            <?php else: ?>
                            <option value="30">最近30天</option><?php endif; ?>
                    </select>
                </li>
                <li>
                    <select class="select text_add" name="type" id="type" style="width:100px;height:32px;">
<!--
                        <option value="" >数据类型</option>
-->
                        <?php if( $type == 'browsers'): ?><option value="browsers" selected="selected">访客数[(UV)]</option>
                            <?php else: ?>
                            <option value="browsers">访客数[(UV)]</option><?php endif; ?>
                        <?php if( $type == 'newusers'): ?><option value="newusers" selected="selected">新增用户</option>
                            <?php else: ?>
                            <option value="newusers">新增用户</option><?php endif; ?>
                        <?php if( $type == 'income'): ?><option value="income" selected="selected">新增收入</option>
                            <?php else: ?>
                            <option value="income">新增收入</option><?php endif; ?>
                    </select>
                </li>
                <li style="width:90px;">
<!--
                    <button type="submit"  id="search" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</button>
-->
                    <a onclick="getdata()" id="search" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</a>

                </li>
            </ul>

        </form>
       </div>

        <div id="container" style="min-width:400px;height:400px;"></div>

</div>


</body>
</html>

<script>
    window.onload=function(){
        getdata();
    };


    function getdata(){
        var time=$('#time').val();
        var type=$('#type').val();
        $.post('/admin.php/Index/chartdata',{time:time,type:type},function(res){
            // console.log('************************************');
            // console.log(res);
            // console.log(33333);
            var totalArr=[];
            for(var i=0;i<res.total.length;i++){
                totalArr.push(parseFloat(res.total[i]))
            }
            var chart = Highcharts.chart('container', {
                title: {
                    text: '趋势图'
                },

                yAxis: {
                    title: {
                        text: '数量'
                    }
                },
                xAxis: {
                    title: {
                        text: '图三：趋势图'
                    },
                    categories:res.times
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },
                series: [{
                    name:res.category,
                    data:totalArr
                }],
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
            });

        });


    }


</script>
<!--<script>
            $.post('',{id:id},function(data){

                    layer.msg('已删除!',{icon:1,time:1000},function(){
                        window.location.href="";

                    });

            });


</script>-->