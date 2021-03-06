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


    <div class="search_style">
        <div class="title_names">搜索查询</div>
        <form action="/admin.php/Data/espdata" method="post">
            <ul class="search_content clearfix">
                <li><label class="l_f">类别：</label></li>
                <li>
                    <select name="type"  id="type">
                        <option value="science" <?php if($type == 'science'): ?>selected="selected"<?php endif; ?> >每日科学</option>
                        <option value="album" <?php if($type == 'album'): ?>selected="selected"<?php endif; ?>>专辑故事</option>
                    </select>
                </li>
                <div id="typechange" style="display: none;">
                    <li><label class="l_f">所属专辑：</label></li>
                    <li>
                        <select name="albumid" id="albumid">
                            <?php if(is_array($albums)): $i = 0; $__LIST__ = $albums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($albumid == $vo.id): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </li>
                </div>

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
        var type=$("#type").val();
        getdata();
        $("#type").change(function () {
            type=$(this).val();
            if(type=='science'){
                $('#typechange').css('display','none')
            }else{
                $('#typechange').css('display','block')
            }
        })
    };

    function getdata(){
        var esptype=$("#type").val()
        var albumid=$("#albumid").val()
        $.post('/admin.php/Data/espfinishdata',{esptype:esptype,albumid:albumid},function(res){
            console.log(res.data);

            var chart = Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '用户听故事完播率分布情况'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        rotation: -45  // 设置轴标签旋转角度
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '完播率 (百分比 %)'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: ' <b>{point.y:.1f} %</b>'
                },
                series: [{
                    name: '总人口',
                    data: res.data,
                    dataLabels: {
                        enabled: true,
                        rotation: 0,
                        color: '#000',
                        align: 'center',
                        format: '{point.y:.1f} %', // :.1f 为保留 1 位小数
                        y: 10
                    }
                }]
            });
        });


    }


</script>