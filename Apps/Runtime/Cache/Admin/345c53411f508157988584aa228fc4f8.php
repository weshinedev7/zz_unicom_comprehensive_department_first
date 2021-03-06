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
    <!--<script charset="utf-8" src="https://quanyu.xcxweshine.com//Public/admin/js/showdate.js"></script>-->
    <script src="https://quanyu.xcxweshine.com//Public/admin/js/laydate/laydate.js"></script>
    <title>概况</title>

</head>

<body>
<div class="page-content clearfix">

    <div class="search_style">
        <div class="title_names">搜索查询</div>
        <form action="/admin.php/Data/tradedata" method="post">
            <ul class="search_content clearfix">
                <li><label class="l_f">开始时间：</label></li>
                <li>
                    <input type="text" id="time_start" name="time_start" value="<?php echo ($start); ?>"  class="text_time"/>

                </li>
                <li><label class="l_f">结束：</label></li>
                <li>
                    <input type="text" id="time_end" name="time_end" value="<?php echo ($end); ?>"  class="text_time"/>

                </li>

                <li style="width:90px;">
                    <!--<button type="submit"  id="search2" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</button>-->
                    <a onclick="getdata()" id="search" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</a>
                </li>
            </ul>

        </form>
    </div>
    <div id="container" style="min-width:400px;height:400px;"></div>
    <div class="search_style">
        <div class="title_names">搜索查询订单趋势</div>
        <form action="/admin.php/Data/tradedata" method="post">
            <ul class="search_content clearfix">
                <li><label class="l_f">开始时间：</label></li>
                <li>
                    <input type="text" id="time_start2" name="time_start2" value="<?php echo ($start); ?>"  class="text_time"/>

                </li>
                <li><label class="l_f">结束：</label></li>
                <li>
                    <input type="text" id="time_end2" name="time_end2" value="<?php echo ($end); ?>"  class="text_time"/>

                </li>

                <li style="width:90px;">
                    <!--<button type="submit"  id="search2" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</button>-->
                    <a onclick="getdata2()" id="search2" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</a>
                </li>
            </ul>

        </form>
    </div>
    <div id="container2" style="min-width:400px;height:400px;"></div>
    <div class="search_style">
        <div class="title_names">搜索查询</div>
        <form action="/admin.php/Data/tradedata" method="post">
            <ul class="search_content clearfix">
                <li><label class="l_f">时间：</label></li>
                <li>
                    <input type="text" id="time_start3" name="time_start3" value="<?php echo ($month_start); ?>"  class="text_time"/>

                </li>
                <!--<li><label class="l_f">结束：</label></li>-->
                <!--<li>-->
                    <!--<input type="text" id="time_end3" name="time_end3" value="<?php echo ($end); ?>"  class="text_time"/>-->

                <!--</li>-->

                <li style="width:90px;">
                    <!--<button type="submit"  id="search2" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</button>-->
                    <a onclick="getdata3()" id="search3" class="btn btn-success" style="padding:0px 10px;"><i class="icon-search"></i>查询</a>
                </li>
            </ul>

        </form>
    </div>
    <div id="container3" style="min-width:200px;height:400px"></div>

</div>

</body>
</html>
<script>
    window.onload=function(){
        //时间选择器
        laydate.render({
            elem: '#time_start'
            ,type: 'datetime'
        });
        laydate.render({
            elem: '#time_end'
            ,type: 'datetime'
        });
        laydate.render({
            elem: '#time_start2'
            ,type: 'datetime'
        });
        laydate.render({
            elem: '#time_end2'
            ,type: 'datetime'
        });
        laydate.render({
            elem: '#time_start3'
            ,type: 'month'
        });
        getdata();
        getdata2();
        getdata3();
    };

    function getdata(){
        var time_start=$('#time_start').val();
        var time_end=$('#time_end').val();
        $.post('/admin.php/Data/tradedata',{time_start:time_start,time_end:time_end},function(res){
            var totalArr=[];
            for(var i=0;i<res.total.length;i++){
                totalArr.push(parseFloat(res.total[i]))
            }
            var chart = Highcharts.chart('container', {
                title: {
                    text: '收入趋势'
                },

                yAxis: {
                    title: {
                        text: '金额'
                    }
                },
                xAxis: {
                    title: {
                        text: '日期'
                    },
                    categories:res.time
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },
                series: [{
                    name:"",
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
    function getdata2(){
        var time_start=$('#time_start2').val();
        var time_end=$('#time_end2').val();
        $.post('/admin.php/Data/tradedata',{time_start:time_start,time_end:time_end},function(res){
            var totalArr=[];
            /*console.log('数量')*/

            for(var i=0;i<res.number.length;i++){
                totalArr.push(parseFloat(res.number[i]))
            }
            //console.log(totalArr);
            var chart = Highcharts.chart('container2', {
                title: {
                    text: '订单趋势'
                },

                yAxis: {
                    title: {
                        text: '数量'
                    }
                },
                xAxis: {
                    title: {
                        text: '日期'
                    },
                    categories:res.time
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },
                series: [{
                    name:"",
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
    function getdata3() {
        var time_start=$('#time_start3').val();
        $.post("/admin.php/Data/tradeUserdata",{time_start:time_start},function (res) {
            Highcharts.chart('container3', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: '交易构成'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                },
                series: [{
                    name: 'Brands',
                    colorByPoint: true,
                    data: [{
                        name: '新付费用户',
                        y:res.new,
                        sliced: true,
                        selected: true
                    }, {
                        name: '老付费用户',
                        y: res.old
                    }]
                }]
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