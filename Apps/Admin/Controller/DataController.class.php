<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
ob_start();
class DataController extends Controller{
    public function index(){


    }
    public function trade(){
        $start=(date("Y-m-d ",strtotime("-6day")).' 00:00:00');
        $end=(date("Y-m-d ",time()).'23:59:59');
        $month_start=date('Y-m', strtotime(date("Y-m-d")));
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("month_start",$month_start);
        $this->display("trade");

    }
    public function tradedata(){
        //默认显示近7天数据
        $start=strtotime(date("Y-m-d ",strtotime("-6day")));
        $end=strtotime(date("Y-m-d",time()));
        $time_start=!empty($_POST["time_start"]) ?strtotime($_POST["time_start"]):$start;
        $time_end=!empty($_POST["time_end"]) ? strtotime($_POST["time_end"]):$end;
        $time[]=date("Y-m-d",$time_start);
        while($time_start+86400<$time_end){
            $time[]=date("Y-m-d",$time_start+86400);
            $time_start+=86400;
        }
        $public=new ApiPublicController();
        if(!empty($time) && is_array($time)){
            foreach ($time as $v){
                $start_time=strtotime($v);
                $end_time=($start_time)+86399;
                //收入
                $total[]=$public->statistics(2," and paytime>=$start_time and paytime<=$end_time"," and time>=$start_time and time<=$end_time");
                //订单数
                $num1=M("order")->where("paytime>='$start_time' and paytime<='$end_time' and status in(2,3,4)")->count();
                $num2=M("recharge_order")->where("time >='$start_time' and time<='$end_time' and state=2")->count();
                $num[]=$num1+$num2;
            }
        }else{
            $total=array();
            $num=array();
        }
        $this->ajaxReturn(array("total"=>$total,"time"=>$time,"number"=>$num),json);

    }
    //交易构成  新付费用户  老付费用户 (指购买商品)
    public function tradeUserdata(){
        $public=new ApiPublicController();
        //默认当前月份
        if(empty($_POST["time_start"])){
             $month_start=date('Y-m-01', strtotime(date("Y-m-d")));
             $month_end=date('Y-m-d', strtotime("$month_start +1 month -1 day"));
        }else{
             $month_end=$public->getMonthDays($_POST["time_start"]);
        }
        $time_start=!empty($_POST["time_start"])?strtotime($_POST["time_start"]):strtotime($month_start);//y-m
        $time_end=strtotime($month_end);
//        $time_start=strtotime("2019-01");
//        $time_end=strtotime("2019-02");

        $openids=M("order")->where("status in(2,3,4) and paytime>$time_start and paytime<$time_end")->field(" openid,id")->group("openid")->select();
        $Model=new Model();
        $res=$Model->query("SELECT SUM(realprice) as total FROM wx_order WHERE status IN(2,3,4) AND paytime>$time_start AND paytime<$time_end");
        $total=$public->arrayTo1($res,"total");
        $total_news=0;
        if(!empty($openids) && is_array($openids)){
             foreach ($openids as $k=>$v){
                 $openid=$v["openid"];
                 $num=M("order")->where("openid='$openid'")->count();
                 if($num>1){
                     $openids[$k]["type"]=1;//老付费用户
                 }else{
                     $openids[$k]["type"]=2;//新付费用户
                 }
             }
             foreach ($openids as $k2=>$v2){
                 if($v2["type"]==2){
                     $res2=M("order")->where("id=".$v2["id"])->getField("realprice");
                     $total_news+=$res2;
                 }
             }
        }
        $total_old=$total[0]-$total_news;

        $percentage_old=round($total_old/$total[0],2);
        $percentage_new=round($total_news/$total[0],2);
        $this->ajaxreturn(array("new"=>$percentage_old,"old"=>$percentage_new,));

    }
    public function user(){
        //今日开始时间
        $today_start=strtotime(date("Y-m-d",time()));
        $today_end=$today_start+86399;
        //本月开始日期
        $month_start=(date("Y-m",time())."-01");
        $month_end=(date("Y-m",time())."-28");
        $public=new ApiPublicController();
        //新增用户
        $newusers=$public->statistics(1,"authtime>='$today_start' and authtime<='$today_end'","");
        //累计用户
        $total=$public->statistics(1,"authtime<='$today_end'","");
        //付费用户(累计)
        $pay_total=M("user")->where("paytime<='$today_end' and type=2")->count();

        $this->assign("start",$month_start);
        $this->assign("end",$month_end);
        $this->assign("newusers",$newusers);
        $this->assign("total",$total);
        $this->assign("pay_total",$pay_total);

        $this->display("user");

    }
    public function userdata(){

        $month_start=strtotime(date("Y-m",time())."-01");
        $month_end=strtotime(date("Y-m",time())."-28");
        $time_start=!empty($_POST["time_start"]) ?strtotime($_POST["time_start"]):$month_start;//y-m-d
        $time_end=!empty($_POST["time_end"])  ? strtotime($_POST["time_end"]):$month_end;//y-m-d
        
        $end=max($time_start,$time_end);
        $start=min($time_start,$time_end);
        $time[]=date("Y-m-d",$start);
        $public=new ApiPublicController();
        $Model=new Model();

        while($start+86400<$end){
            $time[]=date("Y-m-d",$start+86400);
            $start+=86400;
        }
       if(!empty($time) && is_array($time)){
            foreach ($time as $v){
                $start_time=strtotime($v);
                $end_time=($start_time)+86399;
                //新增用户
                $res=$public->statistics(1,"authtime>='$start_time' and authtime<='$end_time'","");
                $users[]=$res;
                //累计用户
                $res2=$public->statistics(1,"authtime<='$end_time'","");
                $total[]=$res2;
                //付费用户(累计)
               $pay_total[]=M("user")->where("paytime<='$end_time' and type=2")->count();
            }
       }
        $this->ajaxReturn(array('users'=>$users,"total"=>$total,"pay_total"=>$pay_total,"time"=>$time),json);

    }

    /*2019-2-12 完播率*/
    public function espfinish(){
        $type=!empty($_POST['type']) ? trim($_POST['type']) : 'science' ;
        //if ($type=='album'){
            $albums=M('album')->field('id,name')->select();
            $albumid=!empty($_POST['albumid']) ? trim($_POST['albumid']) : $albums[0]['id'] ;
            $this->assign('albumid',$albumid);
            $this->assign('albums',$albums);
        //}
        $this->assign('type',$type);
        $this->display('espfinish');
    }
    /*2019-2-12 完播率柱状图数据*/
    public function espfinishdata(){
        $Model=new Model();
        $esptype=trim($_POST['esptype']);
        $albumid=trim($_POST['albumid']);
        $public=new ApiPublicController();
        if ($esptype=='science'){
            $res=$Model->table(array("wx_science"=>"a","wx_science_audio"=>"b"))->where("a.id=b.science_id ")->field("a.name,b.id ")->select();
            $returnlist=$public->finishstatus('science',$res);

        }else if ($esptype=='album'){
            $albumaudios=M('album_episode')->where('album_id='.$albumid)->field('id,name')->select();
            $returnlist=$public->finishstatus('album',$albumaudios);

        }
        $this->ajaxReturn(array('data'=>$returnlist,json));

    }

    public function espdata(){
        $_GPC = array_merge($_POST, $_GET);
        $Model=new Model();
        /*      免费集完播率      isfree=1       freeplaycent
               付费集完播率      isfree=0        paypercent
               访客数           wx_album_log   visitcount
               付费用户         购买用户        payusercount
               转化率           付费用户/访客   transpercent
       */
        $public=new ApiPublicController();
        $type=!empty($_GPC['type']) ? trim($_GPC['type']) : 'science' ;
        $ordertype=!empty($_GPC['ordertype']) ? trim($_GPC['ordertype']) : '';

        if ($type=='science'){
            $count=$Model->table(array("wx_science"=>"a","wx_science_audio"=>"b"))->where("a.id=b.science_id ")->field("a.name,b.id ")->select();
            $Page = new \Think\Page(count($count),10);
            $show = $Page->show();
            $firstRow=$Page->firstRow;
            $listRows=$Page->listRows;
            $list=$Model->table(array("wx_science"=>"a","wx_science_audio"=>"b"))->where("a.id=b.science_id ")->field("a.name,b.id as audioid ,a.id,b.isfree ")->order('id desc')->limit($firstRow.' , '.$listRows)->select();

        }else{
            $count=M('album')->field('id,name')->select();
            $Page = new \Think\Page(count($count),10);
            $show = $Page->show();
            $firstRow=$Page->firstRow;
            $listRows=$Page->listRows;
            $list=M('album')->field('id,name')->limit($firstRow.' , '.$listRows)->select();
        }
        $returnlist=$public->allaudiolist($type,$list);


        if (!empty($ordertype)){
            switch ($ordertype){
                case 'visiter':
                    $ordername='visitcount';
                    break;
                case 'payuser':
                    $ordername='payusercount';
                    break;
                case 'transpercent':
                    $ordername='transpercent';
                    break;
                default :
                    $ordername='visitcount';
            }
            $arrSort = array();
            $sort = array(
                'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                'field'     => $ordername,       //排序字段
            );
            foreach($returnlist AS $uniqid => $v){
                foreach($v AS $key=>$value){
                    $arrSort[$key][$uniqid] = $value;
                }
            }
            if($sort['direction']){
                array_multisort($arrSort[$sort['field']], constant($sort['direction']), $returnlist);
            }
        }

        $this->assign('list',$returnlist);
        $this->assign('type',$type);
        $this->assign('ordertype',$ordertype);
        $this->assign('page',$show);
        $this->display('espdata');
    }
}