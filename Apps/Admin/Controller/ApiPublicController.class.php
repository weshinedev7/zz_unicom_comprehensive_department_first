<?php
namespace Admin\Controller;
use Think\Controller;
use Think\getID3;
use Think\Model;
use Think\Imagick;

/**/

class ApiPublicController extends Controller {
    public function is_error($data){
        if (empty($data) || !is_array($data) || !array_key_exists('errno', $data) || ( array_key_exists('errno', $data) && $data['errno'] == 0 )) {
            return false;
        } else {
            return true;
        }
    }

    public function error($errno, $message = ''){
        return array( 'errno' => $errno, 'message' => $message, );
    }

    public function getcurl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $content = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status == 404) {
            return "";
        }
        curl_close($ch);
        return $content;
    }

    public function postcurl($url, $postdata){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $content = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status == 404) {
            return "";
        }
        curl_close($ch);
        return $content;
    }

    //多图片加域名（绝对路径）
    public function prefix_img($array){
        if ($array && is_array($array)) {

            foreach ($array as &$l) {
                if (!empty($l["cover"])) {
                    $l["cover"] = "https://{$_SERVER['SERVER_NAME']}" . '/' . $l["cover"];
                }
                if (!empty($l["grey_icon"])) {
                    $l["grey_icon"] = "https://{$_SERVER['SERVER_NAME']}" . '/' . $l["grey_icon"];
                }
            }
            unset($l);
            return $array;
        } else {
            return $array;
        }
    }

    public function dump2file($content, $filename = 'debuglog'){
        // $content = "\r\n================".time()."=========\r\n".$content;
        // file_put_contents('jeff_log.txt', $content,FILE_APPEND);
        $filename = $filename ? $filename : 'debuglog';
        $import_data = print_r($content, 1);
        $import_data = "================" . date('Y-m-d H:i:s') . "---" . md5(time() . mt_rand(1, 1000000)) . "================\r\n" . $import_data . "\r\n";
        file_put_contents($filename . '.txt', $import_data, FILE_APPEND);
    }

    /**
     * 对象 转 数组
     *
     * @param object $obj 对象
     * @return array
     */
    public function object_to_array($obj){
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (gettype($v) == 'resource') {
                return;
            }
            if (gettype($v) == 'object' || gettype($v) == 'array') {
                $obj[$k] = (array)$this->object_to_array($v);
            }
        }

        return $obj;
    }

    //处理昵称是图片
    public function filterEmoji($str){
        $str = preg_replace_callback('/./u', function(array $match){
            return strlen($match[0]) >= 4 ? '' : $match[0];
        }, $str);

        return $str;
    }

    //二维数组变一维(某个键)
    public function arrayTo1($array, $key){
        if (is_array($array)) {
            foreach ($array as $v) {
                if (!empty($key)) {
                    $arr1[] = ( $v["$key"] );
                } else {
                    $arr1 = $v;
                }
            }
            return $arr1;
        }
    }

    //收听总数

    /**
     * @param $type 1 每日科学  2 专辑故事
     * @param $isparent 是否是父级商品 1：是 0：否
     * @param  $product_id  子集id
     * @param $parent_id 父集id
     */
    public function listens($type, $isparent, $son_id, $parent_id){
        if ($type == "1") {
            $condition = "product_id=$parent_id and type=1";
        }
        //单集
        if ($type == "2" && $isparent == "0") {
            $condition = "product_id=$son_id and type=2";
        }
        //专辑
        if ($type == "2" && $isparent == "1") {
            $condition = "parent_id=$parent_id and type=2";
        }
        $listens = M("audio_play_log")->where($condition)->count();
        return $listens;
    }

    //检查是否为null
    public function checkNull($array){
        if (!empty($array) && is_array($array)) {
            foreach ($array as &$v) {
                if (empty($v)) {
                    $v = '';
                }
            }
            unset ($v);
            return $array;
        }
    }

    //选择商品表
    public function selectTable($type){
        switch ($type) {
            case "1":
                $table = "wx_science";
                break;
            case "2":
                $table = "wx_album";
                break;
            case "3":
                $table = "wx_badge";
                break;
        }
        return $table;
    }
    //时间戳转换

    /**
     * @param $array 二维数组
     */
    public function strotoDate($array){
        if ($array && is_array($array)) {
            foreach ($array as $k => $v) {
                if ($v["validity"] == "NAN") {
                    $array[$k]["validity"] = "永久有效";
                } else {
                    $array[$k]["validity"] = date("Y.m.d", $v["validity"]);
                    $array[$k]["back_validity"] = $v["validity"];
                }
            }
        }
        return $array;
    }

    //我的优惠券列表
    public function myCoupons($conditon, $orderby){
        $Model = new Model();
        $coupons = $Model->table(array( "wx_user_coupons" => "a", "wx_coupons" => "b" ))->where($conditon)->field("a.id as usercoupons_id,a.usetime,b.*")->order($orderby)->select();
        if ($coupons) {
            $coupons = $this->strotoDate($coupons);
        } else {
            $coupons = array();
        }
        return $coupons;
    }

    //从地址中获取省市区
    public function divAddress($address){
        //$address=$_POST["address"];
        preg_match('/(.*?(省|自治区|特别行政区))/', $address, $matches);
        if (count($matches) > 1) {
            $province = $matches[count($matches) - 2];
            $address = str_replace($province, '', $address);
        }
        preg_match('/(.*?(市|自治州|地区|区划|县))/', $address, $matches);
        if (count($matches) > 1) {
            $city = $matches[count($matches) - 2];
            $address = str_replace($city, '', $address);
        }
        preg_match('/(.*?(区|县|镇|乡|街道))/', $address, $matches);
        if (count($matches) > 1) {
            $area = $matches[count($matches) - 2];
            $address = str_replace($area, '', $address);
        }
        $address = array( 'province' => isset($province) ? $province : '', 'city' => isset($city) ? $city : '', 'area' => isset($area) ? $area : '', );
        if (empty($address["province"])) {
            $address["province"] = $address["city"];
        }
        if (empty($address["city"])) {
            $address["city"] = $address["province"];
        }
        return $address;
    }

    //一维数组逆向
    public function reverse($array){
        if (!empty($array) && is_array($array)) {
            for ($i = count($array) - 1; $i >= 0; $i--) {
                $newAarray[] = $array[$i];
            }
            return $newAarray;
        } else {
            return $array;
        }

    }

    //获取音频时长
    public function getDuration($fileUrl){
        vendor('getid3.getid3');
        $getID3 = new \getID3();    //实例化类
        $ThisFileInfo = $getID3->analyze("$fileUrl");   //分析文件
        $time = $ThisFileInfo['playtime_seconds'];      //获取mp3的长度信息
        return $time;
    }
    //连续收听的天数

    /**
     * @param $logs 该用户收听记录
     * @return array
     */
    // 1号   2   3  4  6  8
    public function continousDay($logs){

        $logs = $this->arrayTo1($logs, "time");
        foreach ($logs as &$l) {
            $l = strtotime(date("Y-m-d", $l));
        }
        unset($l);
        $logs_unique = array_unique($logs);
        $serial = 1;
        //$logs_unique=array("1546272000","1546358400","1546444800","1546531200","1546704000","1546876800");
        foreach ($logs_unique as $k => $v) {
            //第一条播放记录时间+1 day
            $next_day = strtotime("+1 day", $v);
            if (in_array($next_day, $logs_unique)) {
                $serial++;
                continue;
            } else {
                $serial_array[] = $serial;
                $serial = 1;
                continue;
            }

        }
        $res = array_unique($serial_array);
        return $res;


    }

    //生成徽章领取记录
    public function addBadge_log($badgeid, $openid){
        $data = array( "badgeid" => $badgeid, "createtime" => time(), "has_show" => 0, "openid" => $openid );
        M("user_badge")->add($data);

    }

    //徽章规则转换为文字
    public function ruleContent($list){
        $Model = new Model();
        $rule_id = $list["rule_id"];
        $rule = $list["rule"];
        switch ($rule_id) {
            //收听指定音频
            case "1":
                foreach ($rule as $k => $v) {
                    if ($k == "science") {
                        $rule_simple[$k]["title"] = "收听每日科学：";
                        foreach ($v as $v2) {
                            foreach ($v2 as $v3) {
                                $rule_simple[$k]["name"][] = $Model->table(array( "wx_science" => "a", "wx_science_audio" => "b" ))->where("a.id=b.science_id and b.id=" . $v3)->getField("a.name");

                            }
                        }
                    }
                    if ($k == "album") {
                        $rule_simple[$k]["title"] = "收听专辑故事:";
                        foreach ($v as $v2) {
                            foreach ($v2 as $v3) {
                                $res = $Model->table(array( "wx_album" => "a", "wx_album_episode" => "b" ))->where("a.id=b.album_id and b.id=" . $v3)->field("a.name,b.name as episode")->find();
                                $rule_simple[$k]["name"][] = $res["name"] . ":" . $res["episode"];

                            }

                        }
                    }
                }
                $list["rule"] = $rule_simple;
                break;
            //订单数量
            case "2":
                $list["rule"] = "支付成功订单数量为:" . $list["rule"]["ordercounts"];
                break;
            //分享订单数量
            case "3":
                $list["rule"] = "分享成功订单数量为:" . $list["rule"]["shareorders"];
                break;
            //收听时长
            case "4":
                $list["rule"] = "收听时长:" . $list["rule"]["duration"];
                break;
            //连续使用天数
            case "5":
                $list["rule"] = "连续使用天数:" . $list["rule"]["days"];
                break;
            case "6";
                foreach ($rule as $v) {
                    foreach ($v as $v2) {
                        $name[] = M("badge")->where("id=" . $v2)->getField("name");
                    }
                }
                $list["rule"] = $name;

                break;
        }
        return $list;

    }

    // 后台数据概况
    public function dataShow($start_time, $end_time){
        $Model = new Model();
        $time = time();
        //新增用户数
        $newUsers = $this->statistics(1, " authtime<=$end_time and authtime >=$start_time ", "");

        #todo 2019-3-13 访客数重构
        //$browsers = $this->statistics(1, " authtime='' and time>=$start_time and time<=$end_time", "");
        #todo 新增三个
        #todo 游客量：进入但未授权用户，这个不用去重，访问一次算一次 tourist_log
        $tourist=M('tourist_log')->where("time > $start_time and time<$end_time ")->count();
        #todo 浏览量：访问一次算一次，所有访问都算 visit_log
        $visit=M('visit_log')->where("time > $start_time and time<$end_time ")->count();
        #todo 访客量：浏览量去重，单日每个账号无论访问多少次都只算1次 visitor_perday_log
        $visitor_perday=M('visitor_perday_log')->where("time > $start_time and time<$end_time ")->count();


        //历史总收益
        $history_total = $this->statistics(2, " and paytime<=$time", " and `time`<=$time");
        //收益
        $total = $this->statistics(2, "  and paytime<=$end_time and paytime>=$start_time", " and time<=$end_time and time>=$start_time");

        $list["new_users"] = $newUsers;
        #todo 2019-3-13 访客数重构
        #todo 新增三个
        //$list["browsers"] = $browsers;
        $list["tourist"] = $tourist;
        $list["visit"] = $visit;
        $list["visitor_perday"] = $visitor_perday;

        $list["total"] = $total;
        $list["history_total"] = $history_total;
        return $list;
    }

    /**
     * @param $flag 1 用户访客数    2 收益
     * @param $condition1
     * @param $condtion2
     * @return int
     */
    public function statistics($flag, $condition1, $condtion2){
        $Model = new Model();
        if ($flag == 1) {
            $total = M("user")->where($condition1)->count();
        }
        if ($flag == 2) {
            //收益
            $money = $Model->query("select sum(realprice) as total from wx_order where status in(2,3,4)" . $condition1);
            $recharge = $Model->query("select sum(realprice) as total from wx_recharge_order where state=2 " . $condtion2);

            $money = $this->arrayTo1($money, "total");
            $recharge = $this->arrayTo1($recharge, "total");
            $total = $money[0] + $recharge[0];

        }
        return $total ? $total : 0;
    }

    // 用户留存率图
    public function rentention($product, $start_time, $end_time){
        if (!empty($product) && is_array($product)) {
            foreach ($product as $k => $v) {
                #2019-3-12 浏览量.每日去重album_visit_log
                $product[$k]["visit"] = M("album_visit_log")->where("album_id=" . $v["id"] . " and flag=" . $v["flag"] . " and time >='$start_time' and time<='$end_time'")->count();
                $product[$k]["brows"] = M("album_log")->where("album_id=" . $v["id"] . " and flag=" . $v["flag"] . " and time >='$start_time' and time<='$end_time' and type=1")->count();
                $product[$k]["newusers"] = M("album_log")->where("album_id=" . $v["id"] . " and flag=" . $v["flag"] . " and time >='$start_time' and time<='$end_time' and type=2")->count();
                $product[$k]["rentention"] = round($product[$k]["newusers"] / $product[$k]["brows"] * 100, 2);

            }
            return $product;
        } else {
            return $product;
        }

    }
    //物流模块

    /**
     * Json方式 查询订单物流轨迹
     */
    public function getOrderTracesByJson($orderCode, $shipperCode, $logisticCode, $eBusinessID, $appKey, $requstUrl){
        $requestData = "{'OrderCode':'$orderCode','ShipperCode':'$shipperCode','LogisticCode':'$logisticCode'}";
        $datas = array( 'EBusinessID' => $eBusinessID, 'RequestType' => '1002', 'RequestData' => ( urlencode($requestData) ), 'DataType' => '2', );
        $datas['DataSign'] = $this->encrypt($requestData, $appKey);
        $result = $this->sendPost($requstUrl, $datas);

        //根据公司业务处理返回的信息......

        return $result;
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    public function sendPost($url, $datas){
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if (empty($url_info['port'])) {
            $url_info['port'] = 80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader .= "Host:" . $url_info['host'] . "\r\n";
        $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader .= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader .= "Connection:close\r\n\r\n";
        $httpheader .= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (( $header = @fgets($fd) ) && ( $header == "\r\n" || $header == "\n" )) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets .= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    public function encrypt($data, $appkey){
        return urlencode(base64_encode(md5($data . $appkey)));
    }

    //下个月开始时间:开始年月 y-m  2019-01
    public function nextMonth($tmp_date){
        //切割出年份
        $tmp_year = substr($tmp_date, 0, 4);
        //切割出月份
        $tmp_mon = substr($tmp_date, 4, 2);
        $tmp_nextmonth = mktime(0, 0, 0, $tmp_mon + 1, 1, $tmp_year);
        //得到当前月的下一个月 
        $month_end = strtotime(date("Y-m", $tmp_nextmonth));
        return $month_end;
    }

    //获取该日期所在月份的第一天和最后一天
    function getMonthDays($date){
        $timestamp = strtotime($date);
        $arr = getdate($timestamp);
        if ($arr['mon'] == 12) {
            $year = $arr['year'] + 1;
            $month = $arr['mon'] - 11;
            $firstday = $year . '-0' . $month . '-01';
            $lastday = date('Y-m-d', strtotime("$firstday  -1 day"));
        } else {
            $firstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . ( date('m', $timestamp) + 1 ) . '-01'));
            $lastday = date('Y-m-d', strtotime("$firstday -1 day"));
        }
        return array( $firstday, $lastday );
        // return $lastday;
    }

    public function monthRange(){
        $y = date("Y", time()); //年
        $m = date("m", time()); //月
        $t0 = date('t'); // 本月一共有几天
        $start_month = mktime(0, 0, 0, $m, 1, $y); // 创建本月开始时间
        $end_month = mktime(23, 59, 59, $m, $t0, $y); // 创建本月结束时间
        return array( $start_month, $end_month );
    }

    public function dump($var, $echo = true, $label = null, $strict = true){
        $label = ( $label === null ) ? '' : rtrim($label) . ' ';
        if (!$strict) {
            if (ini_get('html_errors')) {
                $output = print_r($var, true);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            } else {
                $output = $label . print_r($var, true);
            }
        } else {
            ob_start();
            var_dump($var);
            $output = ob_get_clean();
            if (!extension_loaded('xdebug')) {
                $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            }
        }
        if ($echo) {
            echo( $output );
            return null;
        }
        return $output;
    }

    /**
     * [将Base64图片转换为本地图片并保存]
     * @param  [Base64] $base64_image_content [要保存的Base64]
     * @param  [目录] $path [要保存的路径]
     */
    function base64_image_content($base64_image_content, $path){
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $new_file = $path . "/" . date('Ymd', time()) . "/";
            if (!file_exists($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . time() . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                return '/' . $new_file;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function mkdirs($path){
        if (!is_dir($path)) {
            $this->mkdirs(dirname($path));
            mkdir($path, 0777, true);
        }
        return is_dir($path);
    }

    #2019-2-12
    public function finishstatus($type, $list){
        if (empty($list)) {
            return '';
        }
        $returnlist = array();
        if ($type == 'science') {
            foreach ($list as $row) {
                $playcount = M('audio_play_log')->where('type=1 and product_id=' . $row['id'])->count();
                $finishcount = M('audio_play_log')->where('type=1 and finishtime>0 and product_id=' . $row['id'])->count();
                $returnlist[] = array( $row['name'], round($finishcount / $playcount, 2) * 100 );
            }
        } elseif ($type == 'album') {
            foreach ($list as $row) {
                $playcount = M('audio_play_log')->where('type=2 and product_id=' . $row['id'])->count();
                $finishcount = M('audio_play_log')->where('type=2 and finishtime>0 and product_id=' . $row['id'])->count();
                $returnlist[] = array( $row['name'], round($finishcount / $playcount, 2) * 100 );
            }
        }
        return $returnlist;
    }

    #2019-2-12 全部音频列表
    public function allaudiolist($type, $list){
        /*      免费集完播率      isfree=1       freeplaycent
                付费集完播率      isfree=0        paypercent
                访客数           wx_album_log   visitcount
                付费用户         购买用户        payusercount
                转化率           付费用户/访客   transpercent
        */
        if (empty($list)) {
            return '';
        }
        $returnlist = array();
        if ($type == 'science') {
            foreach ($list as $row) {
                $temp = array();
                $playcount = M('audio_play_log')->where('type=1 and product_id=' . $row['audioid'])->count();
                $finishcount = M('audio_play_log')->where('type=1 and finishtime>0 and product_id=' . $row['audioid'])->count();
                $finishercent = round($finishcount / $playcount, 2) * 100;
                if ($row['isfree'] == 1) {
                    $temp['visitcount'] = M('album_log')->where('flag=1 and album_id=' . $row['id'])->count();
                    $temp['paypercent'] = 0;
                    $temp['payusercount'] = 0;
                    $temp['transpercent'] = 0;
                    $temp['freeplaycent'] = $finishercent;
                } else {
                    $temp['visitcount'] = M('album_log')->where('flag=1 and album_id=' . $row['id'])->count();
                    $temp['paypercent'] = $finishercent;
                    $temp['payusercount'] = M('order')->where('type=1 and goodsid=' . $row['id'])->count();;
                    $temp['transpercent'] = round($temp['payusercount'] / $temp['visitcount'], 2) * 100;
                    $temp['freeplaycent'] = 0;
                }
                $temp['name'] = $row['name'];
                $returnlist[] = $temp;
                unset($temp);

            }

        } else if ($type == 'album') {

            foreach ($list as $row) {
                $temp = array();
                #免费的音频
                $freeaudiores = M('album_episode')->where('isfree = 1 and album_id=' . $row['id'])->field('id')->select();
                $freeaudio = '';
                foreach ($freeaudiores as $v) {
                    $freeaudio .= $v['id'] . ',';
                }
                $freeaudio = !empty(trim($freeaudio, ',')) ? trim($freeaudio, ',') : 0;
                #付费的音频
                $payaudiores = M('album_episode')->where('isfree = 0 and album_id=' . $row['id'])->field('id')->select();
                $payaudio = '';
                foreach ($payaudiores as $v) {
                    $payaudio .= $v['id'] . ',';
                }
                $payaudio = !empty(trim($payaudio, ',')) ? trim($payaudio, ',') : 0;
                #所有音频
                $allaudio = $freeaudio . ',' . $payaudio;
                $allaudio = trim($allaudio, ',');

                #免费集完播率      isfree=1       freeplaycent
                $freeplaycount = M('audio_play_log')->where('type=2 and product_id in (' . $freeaudio . ')')->count();
                $freefinishcount = M('audio_play_log')->where('type=2 and finishtime>0 and product_id in (' . $freeaudio . ' )')->count();
                $temp['freeplaycent'] = round($freefinishcount / $freeplaycount, 2) * 100;
                #付费集完播率      isfree=0        paypercent
                $payplaycount = M('audio_play_log')->where('type=2 and product_id in (' . $payaudio . ')')->count();
                $payfinishcount = M('audio_play_log')->where('type=2 and finishtime>0 and product_id in (' . $payaudio . ' )')->count();
                $temp['paypercent'] = round($payplaycount / $payfinishcount, 2) * 100;
                #访客数           wx_album_log   visitcount
                $temp['visitcount'] = M('album_log')->where('flag=2 and album_id=' . $row['id'])->count();
                #付费用户         购买用户        payusercount
                $temp['payusercount'] = M('order')->where('type=2 and goodsid=' . $row['id'])->count();;
                #转化率           付费用户/访客   transpercent
                $temp['transpercent'] = round($temp['payusercount'] / $temp['visitcount'], 2) * 100;
                $temp['name'] = $row['name'];

                $returnlist[] = $temp;

                unset($temp);

            }

        }

        return $returnlist;
    }

    //对emoji表情转反义
    public function emoji_decode($str){
        $strDecode = preg_replace_callback('|\[\[EMOJI:(.*?)\]\]|', function($matches){
            return rawurldecode($matches[1]);
        }, $str);
        return $strDecode;
    }

    /**
     * 冒泡排序:n个数比较n-1次
     * @param $arr
     * @return mixed
     */
    public function bubblesort($arr, $key){
        for ($i = 0; $i < count($arr) - 1; $i++) {// n个数比较n-1次   i=0{ j=0,1,2,3
            for ($j = 0; $j < count($arr) - 1 - $i; $j++) {           //i=1 {j=0,1,2
                if (!empty($key)) {
                    if ($arr[$j][$key] < $arr[$j + 1][$key]) {
                        $temp = $arr[$j + 1][$key];
                        $arr[$j + 1][$key] = $arr[$j][$key];
                        $arr[$j][$key] = $temp;
                    }
                } else {
                    if ($arr[$j] < $arr[$j + 1]) {
                        $temp = $arr[$j + 1];
                        $arr[$j + 1] = $arr[$j];
                        $arr[$j] = $temp;
                    }
                }
            }
        }
        return $arr;
    }

    //排名：循环数组，追加rank值。默认数组已经从大到小的顺序排列的，那么第一个就是第一名，当相邻两个数字一样deep不追加，否则追加1
    public function rank($arr, $key){
        if (!empty($arr) && is_array($arr)) {
            $p = 1;
            foreach ($arr as $k => $v) {

                if ($k == 0) {
                    $arr[$k]["rank"] = 1;
                } else {
                    if ($arr[$k - 1][$key] == $arr[$k][$key]) {
                        $arr[$k]["rank"] = $p;
                    } else {
                        $arr[$k]["rank"] = ++$p;
                    }
                }
            }
        }
        return $arr;

    }

    public function emoji_encode($nickname){
        $str_encode = "";
        $length = mb_strlen($nickname, "utf-8");
        for ($i = 0; $i < $length; $i++) {
            $tmpstr = mb_substr($nickname, $i, 1, "utf-8");
            if (strlen($tmpstr) >= 4) {
                $str_encode .= rawurlencode($tmpstr) ;
            } else {
                $str_encode .= $tmpstr;
            }
        }
        return $str_encode;
    }
    //音频id
    public function get_comment($id,$openid){
        //2019-02-26 评论
        $Model=new Model();
        $comments=$Model->table(array("wx_comment"=>"a","wx_user"=>"b"))->where("a.openid=b.openid and  a.album_epsid=$id")->field("a.id,a.openid,a.content,a.time,b.avatar,b.nickname")->order("time desc")->select();
        $comments_number=count($comments);
        if($comments && is_array($comments)){
            foreach ($comments as $k=>$v){
                $comments[$k]["thumbs"]=M("thumbs")->where("comment_id=".$v["id"]." and deleted=0")->count();
                $comments[$k]["time"]=date("Y-m-d H:i:s",$comments[$k]["time"]);
                $comments[$k]["nickname"]=!empty($v["nickname"])?rawurldecode($v["nickname"]):"";
                //回复
                //                $reply=$Model->table(array("wx_comment_reply"=>"a","wx_user"=>"b"))->where("a.openid=b.openid and a.comment_id=".$v["id"])->field("a.id,b.nickname,a.content")->order("a.time desc")->select();
                $reply=M("comment_reply")->where("comment_id=".$v["id"])->field("id,openid,content")->order("time desc")->select();
                if(!empty($reply)){
                    foreach ($reply as $k2=>$v2){
                        if(empty($v2["openid"])){
                            $reply[$k2]["nickname"]="管理员";
                        }else{
                            $openid_reply=$v2["openid"];
                            $nickname=M("user")->where("openid='$openid_reply'")->getField("nickname");
                            $reply[$k2]["nickname"]=!empty($nickname)?rawurldecode($nickname):"";
                        }
                    }
                    $comments[$k]["reply"]=$reply;
                }else{
                    $comments[$k]["reply"]=array();
                }
                //判断当前用户是否对该评论点赞
                $res=M("thumbs")->where("openid='$openid' and comment_id=".$v["id"]." and deleted=0")->find();
                if($res){
                    $comments[$k]["is_thumb"]=1;
                }else{
                    $comments[$k]["is_thumb"]=0;
                }
            }
            return $comments;
        }else{
            return array();
        }
    }



}
