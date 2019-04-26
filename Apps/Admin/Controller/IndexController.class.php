<?php

namespace Admin\Controller;
use Think\Controller;
use Think\Model;
ob_start();

class IndexController extends Controller {
	
    public function index()
	{
		if(session('username') != null)
		{
			//$url="/admin/user";
			//$this->redirect($url);

			$this->display('main');
		}
		else
		{
			$this->display('index');
		}
    }
    //图一：数据概况
    public function charts(){
        #todo 一 数据概况 1今日  2 昨日  3 每日平均  4 历史峰值
        $Model=new Model();
        $public=new ApiPublicController();
        $time=time();
        $date=$Model->query("select  `time` from wx_user");
        $date=$public->arrayTo1($date,"time");

        foreach ($date as &$v){
            $v=date("Y-m-d",$v);
        }
        unset ($v);
        $date=array_unique($date);
        for($i=1;$i<=4;$i++){
            switch ($i){
                case "1":
                    //今天开始时间
                    $start_time=strtotime(date("Y-m-d",time()));
                    //今天结束之间
                    $end_time=$start_time+59*60*24;
                    $list[$i]=$public->dataShow($start_time,$end_time);

                    $list[$i]["title"]="今日";
                    break;
                case "2":
                    //昨日开始时间
                    $start_time=strtotime(date("Y-m-d",strtotime("-1 day")));
                    $end_time=$start_time+59*60*24;
                    $list[$i]=$public->dataShow($start_time,$end_time);

                    $list[$i]["title"]="昨日";
                    break;
                case "3":
                    //每日平均
                    $min=strtotime(min($date));
                    $max=strtotime(max($date));
                    $diff=($max-$min)/(86400);
                    $new_users=$public->statistics(1,"authtime!='Null'","");
                    // $browsers=$public->statistics(1,"authtime='Null'","");
                    //$browsers=$browsers/$diff;

                    $new_users=round($new_users/$diff,2);

                    #todo 2019-3-13 访客数重构
                    //$browsers = $this->statistics(1, " authtime='' and time>=$start_time and time<=$end_time", "");
                    #todo 新增三个
                    #todo 游客量：进入但未授权用户，这个不用去重，访问一次算一次 tourist_log
                    $tourist=M('tourist_log')->count();
                    $tourist=$tourist/$diff;
                    #todo 浏览量：访问一次算一次，所有访问都算 visit_log
                    $visit=M('visit_log')->count();
                    $visit=$visit/$diff;
                    #todo 访客量：浏览量去重，单日每个账号无论访问多少次都只算1次 visitor_perday_log
                    $visitor_perday=M('visitor_perday_log')->count();
                    $visitor_perday=$visitor_perday/$diff;


                    $total=$public->statistics(2,"","");
                    $total=round($total/$diff,2);
                    //平均每天历史总收益
                    /*foreach ($date as $l){
                        $l=strtotime($l);
                       // $end_time=($l)+86399;
                        $history_total[]=$public->statistics(2,"  and paytime<='$time'","  and `time`<=$time");
                    }*/
                    #todo 2019-2-18
                    $history_total=$public->statistics(2,"","");
                    $history_total=round($history_total/$diff,2);
                    $list[$i]["new_users"]=$new_users;
                    //$list[$i]["browsers"]=$browsers;
                    $list[$i]["tourist"]=round($tourist,2);
                    $list[$i]["visit"]=round($visit,2);
                    $list[$i]["visitor_perday"]=round($visitor_perday,2);;

                    $list[$i]["total"]=$total;
                    $list[$i]["history_total"]=$history_total;
                    $list[$i]["title"]="每日平均";

                    break;
                case "4":

                    $date= array_filter($date);
                   
                    foreach ($date as $k=>$l2){
                        $start_time=strtotime($l2);
                        $end_time=($start_time)+86399;
                        $count_news[]=$public->statistics(1,"authtime>='$start_time' and authtime<='$end_time'","");

                        //$count_bros[]=$public->statistics(1,"time>='$start_time' and time<='$end_time'","");
                        #todo 新增三个
                        #todo 游客量：进入但未授权用户，这个不用去重，访问一次算一次 tourist_log
                        $touristarr[]=M('tourist_log')->where("time > $start_time and time<$end_time ")->count();
                        #todo 浏览量：访问一次算一次，所有访问都算 visit_log
                        $visitarr[]=M('visit_log')->where("time > $start_time and time<$end_time ")->count();
                        #todo 访客量：浏览量去重，单日每个账号无论访问多少次都只算1次 visitor_perday_log
                        $visitor_perdayarr[]=M('visitor_perday_log')->where("time > $start_time and time<$end_time ")->count();


                        $count_total[]=$public->statistics(2,"and paytime>='$start_time' and paytime <='$end_time'"," and `time`>='$start_time' and `time`<=$end_time");
                        $count_history[]=$public->statistics(2," and paytime<'$end_time'"," and `time`<'$end_time'");
                    }

                    $list[$i]["new_users"]=max($count_news);
                    #todo 2019-3-13 访客数重构
                    //$browsers = $this->statistics(1, " authtime='' and time>=$start_time and time<=$end_time", "");
                    #todo 新增三个
                    #todo 游客量：进入但未授权用户，这个不用去重，访问一次算一次 tourist_log
                   // $list[$i]["browsers"]=max($count_bros);
                    $list[$i]["tourist"]=max($touristarr);
                    $list[$i]["visit"]=max($visitarr);
                    $list[$i]["visitor_perday"]=max($visitor_perdayarr);

                    $list[$i]["total"]=max($count_total);
                    $list[$i]["history_total"]=max($count_history);
                    $list[$i]["title"]="历史峰值";

                    break;
            }
        }
        #todo 图三 用户留存率
        $time2=empty($_POST["time2"])?'1':$_POST["time2"];
        $album=M("album")->field("id,name")->order("id desc")->select();
        $science=M("science")->field("id,name")->order("id desc")->select();

        if(!empty($album) && is_array($album)){
            foreach ($album as $k=>$v){
                $album[$k]["flag"]=2;
            }
        }
        if(!empty($science) && is_array($science)){
            foreach ($science as $k=>$v){
                $science[$k]["flag"]=1;
            }
        }
        $product=array_merge($album,$science);

        if(!empty($time2)){
           switch ($time2){
               case "1":
                    //昨日开始时间
                    $start_time=strtotime(date("Y-m-d",strtotime("-1 day")));
                    $end_time=$start_time+59*60*24;
                    $product=$public->rentention($product,$start_time,$end_time);
                   break;
               case "7" :

                   $start_time=strtotime(date("Y-m-d",strtotime("-7 day")));
                   $end_time=$start_time+59*60*24*7;
                   $product=$public->rentention($product,$start_time,$end_time);
                   break;
               case "30":

                   $start_time=strtotime(date("Y-m-d",strtotime("-30 day")));
                   $end_time=$start_time+59*60*24*30;
                   $product=$public->rentention($product,$start_time,$end_time);
                   break;

           }
        }

        $this->assign("list",$list);
        $this->assign("time2",$time2);
        $this->assign("list2",$product);
        $this->display("charts");
    }
    //图二：趋势图
    public function chartdata(){
        $Model=new Model();
        $public=new ApiPublicController();
        $time=empty($_POST["time"]) ? '7' : $_POST["time"];
        $type=empty($_POST["type"]) ? 'browsers' : $_POST["type"];

        if(!empty($time)){
            switch ($time){
                case "7":
                    for($j=0;$j<=7;$j++){
                        $times[]=date("Y-m-d",strtotime("-$j day"));
                    }
                    $times=array_reverse($times);
                    break;
                case "15":
                    for($j=0;$j<=15;$j++){
                        $times[]=date("Y-m-d",strtotime("-$j day"));
                    }
                    $times=array_reverse($times);
                    break;
                case "30":
                    for($j=0;$j<=30;$j++){
                        $times[]=date("Y-m-d",strtotime("-$j day"));
                    }
                    $times=array_reverse($times);
                    break;

            }
        }
        if(!empty($type)){
            switch ($type){
                //访客数
                case "browsers":
                    if(!empty($times) && is_array($times)){
                        $total=array();
                        foreach ($times as $v){
                            $start_time=strtotime($v);
                            $end_time=($start_time)+86399;
                            $res = M("visitor_perday_log")->where("time>='$start_time' and time<='$end_time'")->count();

                            //$res=$public->statistics(1,"time>='$start_time' and time<='$end_time'","");
                            array_push($total,$res);
                        }
                    }
                    $category="访客数";
                    break;
                //新增用户
                case "newusers":
                    if(!empty($times) && is_array($times)){
                        $total=array();
                        foreach ($times as $v){
                            $start_time=strtotime($v);
                            $end_time=($start_time)+86399;
                            $res=$public->statistics(1,"authtime>='$start_time' and authtime<='$end_time'","");
                            array_push($total,$res);

                        }
                    }
                    $category="新增用户";
                    break;
                //新增收入
                case "income":
                    if(!empty($times) && is_array($times)){
                        $total=array();
                        foreach ($times as $v){
                            $start_time=strtotime($v);
                            $end_time=($start_time)+86399;
                            $res=$public->statistics(2,"and paytime>='$start_time' and paytime <='$end_time'"," and `time`>='$start_time' and `time`<=$end_time");
                            array_push($total,$res);

                        }
                    }
                    $category="新增收入";
                    break;
            }
        }
        $this->ajaxReturn(array('total'=>$total,'times'=>$times,"category"=>$category),json);
    }

	public function loginout()
	{
        session('username',null);
		@$arr['flag']=1;
		@$this->ajaxReturn($arr,json);
	}
	
	public function home()
	{
		if(session('username') != null)
		{
			$Model = new Model();
			$this->display('home');
		}
	}
	
	
	public function check()
	{
		$username=$_POST["username"];
		$password=md5($_POST["password"]);
		$veriCode=$_POST["veriCode"];
		$arr['flag']=0;
		if(!check_verify($veriCode)){
			$arr['flag']=2;
			$this->ajaxReturn($arr,json);
		}
		else
		{
			$Model = new Model(); // 实例化一个model对象 没有对应任何数据表
			$password_correct = $Model->query("select password from wx_admin where username='$username'");
			
			if($password==$password_correct[0]["password"])
			{
				session('username',$username);
				$arr['flag']=1;
				$this->ajaxReturn($arr,json);
			}
			else
			{
				$this->ajaxReturn($arr,json);
			}
		}
	}
	
	/**
	 * 
	 * 验证码生成
	 */
	public function veriCode(){
        ob_clean();
		$Verify = new \Think\Verify();
		$Verify->fontSize = 18;
		$Verify->length   = 4;
		$Verify->useNoise = false;
		$Verify->codeSet = '0123456789';
		$Verify->imageW = 150;
		$Verify->imageH = 32;
		//$Verify->expire = 600;
		$Verify->entry();
	}
    /**
     * 浏览器友好的变量输出
     * @param mixed $var 变量
     * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
     * @param string $label 标签 默认为空
     * @param boolean $strict 是否严谨 默认为true
     * @return void|string
     */
    function dump($var, $echo = true, $label = null, $strict = true){
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

}