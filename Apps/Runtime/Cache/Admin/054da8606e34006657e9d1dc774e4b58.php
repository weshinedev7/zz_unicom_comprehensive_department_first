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
    <script charset="utf-8" src="https://quanyu.xcxweshine.com//Public/admin/js/showdate.js"></script>


    <title>概况</title>
</head>

<body>
<div class="page-content clearfix">

    <div class="table_menu_list">
            <table class="table table-striped table-bordered table-hover">
                <caption style="text-align: center ;font-size: 20px">用户趋势</caption>
                <thead>
                <tr>
                    <th width="150">新增用户</th>
                    <th width="150">累计用户</th>
                    <th width="150">付费用户</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo ($newusers); ?></td>
                        <td><?php echo ($total); ?></td>
                        <td><?php echo ($pay_total); ?></td>
                    </tr>
                </tbody>

            </table>
        </div>
    <div class="search_style">
        <div class="title_names">搜索查询</div>
        <form action="/admin.php/Data/userdata" method="post">
            <ul class="search_content clearfix">
                <li><label class="l_f">开始时间：</label></li>
                <li>
                    <input type="text" id="time_start" name="time_start" value="<?php echo ($start); ?>" onClick="return Calendar('time_start');" class="text_time"/>

                </li>
                <li><label class="l_f">结束：</label></li>
                <li>
                    <input type="text" id="time_end" name="time_end" value="<?php echo ($end); ?>" onClick="return Calendar('time_end');" class="text_time"/>

                </li>
                <li style="width:90px;">
                    <!--<button type="submit"  id="search2" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</button>-->
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
        var time_start=$('#time_start').val();
        var time_end=$('#time_end').val();
        $.post('/admin.php/Data/userdata',{time_start:time_start,time_end:time_end},function(res){
            var users=[];
            var total=[];
            var pay_total=[];
            for(var i=0;i<res.users.length;i++){
                users.push(parseFloat(res.users[i]))
            }
            for(var i=0;i<res.total.length;i++){
                total.push(parseFloat(res.total[i]))
            }
            for(var i=0;i<res.pay_total.length;i++){
                pay_total.push(parseFloat(res.pay_total[i]))
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
                        text: '日期',
                    },
                    categories:res.time
                },

                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },
                series: [{
                    name: '新增用户',
                    data: users
                }, {
                    name: '累计用户',
                    data: total
                }, {
                    name: '付费用户',
                    data: pay_total
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