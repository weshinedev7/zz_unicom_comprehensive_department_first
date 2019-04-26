<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Think\Exception;


class OrderController extends Controller{

    public function index()
    {

    }


    public function WxPay(){
        vendor('Wxpay.wxpay');
        $Model = new Model();
        $setting=$Model->query("select * from wx_setting where id = 1");
        $setting=$setting[0];
        $appid = $setting['appid'];
        $mch_id = "1505076851";
        $key = "ganzhousuweiganzhousuweiganzhous";
        $openid = $_POST["openid"];
        $total_fee =  $setting["fprice"] * 100;
        $out_trade_no = $mch_id . time();
        $body = '微信支付';
        $weixinpay = new \WeixinPay($appid, $openid, $mch_id, $key, $out_trade_no, $body, $total_fee);
        $return = $weixinpay->pay();
        $arr = array(
            'code'=>1,
            'payresult'=>$return,
            'ordernumber'=>$out_trade_no,
        );
        echo json_encode($arr);
    }

    public function Insert(){

        date_default_timezone_set("Asia/Shanghai");
        $Model = new Model();
        $data["img"]=$_POST["img"];
        $data["username"]=$_POST["username"];
        $data["address"]=$_POST["address"];
        $data["price"]=$_POST["wprice"];
        $data["fprice"]=$_POST["fprice"];
        $data["servicename"]=$_POST["servicename"];
        $data["content"]=$_POST["content"];
        $data["lat"]=$_POST["lat"];
        $data["lon"]=$_POST["lon"];
        $data["phone"]=$_POST["phone"];
        $data["openid"]=$_POST["openid"];
        $data["status"]=1;
        $data["score"]=0;
        $data["yuanjiansub"]=$_POST["yuanjiansub"];
        $data["inputtime"] = date('Y-m-d H:i:s',time());

        $Order=M("order");
        if($Order->create($data))
        {
            $result=$Order->add($data);

            if($result)
            {
                $this->ajaxReturn(array("status"=>"success","msg"=>"发布成功！"));
            }
            else
            {
                $this->ajaxReturn(array("status"=>"error","msg"=>"发布失败！"));
            }
        }
        else
        {
            $this->ajaxReturn(array("status"=>"error","msg"=>"提交信息错误！"));
        }
    }


    /*
     * 0表示未支付
     * 1表示已微信支付
     * 2表示已被维修工接单
     * 3表示维修工确认完成
     * 4表示客户确认完成即此单完成
     * 5表示申请返工
     * */
    public function GetMySend(){
        $Model = new Model();
        $openid=$_POST["openid"];
        $list=$Model->query("select * from wx_order where openid='$openid' order by id desc");
        for($i=0;$i<count($list);$i++){
            $list[$i]["img"]=str_replace("https://weixiu.wshoto.com.","https://weixiu.wshoto.com",$list[$i]["img"]);
            $list[$i]["thumb"]=explode(",",$list[$i]["img"]);
            $list[$i]["thumb"]=$list[$i]["thumb"][0];
        }

        $this->ajaxReturn($list,json);
    }

    public function GetService()
    {
        $Model = new Model();
        $id=$_POST["id"];
        $list=$Model->query("select a.*,b.nickname,b.name as bname,b.avatar,b.phone as bphone from wx_order as a left join wx_user as b on a.useropenid=b.openid where a.id='$id'");
        for($i=0;$i<count($list);$i++){
            $list[$i]["img"]=str_replace("https://weixiu.wshoto.com.","https://weixiu.wshoto.com",$list[$i]["img"]);
            $list[$i]["thumb"]=explode(",",$list[$i]["img"]);
            $list[$i]["service"]=explode(",",$list[$i]["yuanjiansub"]);
            for($j=0;$j<count($list[$i]["service"]);$j++){
                $serviceName[]=$Model->query("select a.title,b.name from wx_service as a left join wx_servicecategory as b on a.cateId=b.id where a.id=".$list[$i]["service"][$j]);
            }
            $list[$i]["serviceName"]=$serviceName;
            $serviceName="";
        }

        $name=$list[0]["servicename"];
        $listService=$Model->query("select a.*,b.name from wx_service as a left join wx_servicecategory as b on a.cateId=b.id where b.name='$name' order by a.id desc ");


        $arr=array("list"=>$list,"listService"=>$listService);

        $this->ajaxReturn($arr,json);

    }


    public function MySumbitOk(){

        $id=$_POST["id"];
        $data["status"]=2;
        $data["useropenid"]=$_POST["openid"];
        $Order=M("order");
        if($Order->create($data))
        {
            $result=$Order->where("id=$id")->save($data);

            if($result)
            {
                $this->ajaxReturn(array("flag"=>1));
            }
            else
            {
                $this->ajaxReturn(array("flag"=>0));
            }
        }
        else
        {
            $this->ajaxReturn(array("flag"=>3));
        }
    }

    public function MySumbitOkT(){

        date_default_timezone_set("Asia/Shanghai");
        $Model = new Model();
        $id=$_POST["id"];
        $data["status"]=4;

        $list=$Model->query("select a.useropenid,a.fprice,b.money from wx_order as a left join wx_user as b on a.useropenid=b.openid where a.id=$id");

        $percent=$Model->query("select * from wx_setting where id=1");
        $percent=$percent[0]["percent"];

        $openid=$list[0]["useropenid"];
        $userData["money"]=$list[0]["money"]+($list[0]["fprice"]*$percent);

        $log["openid"]=$list[0]["useropenid"];
        $log["orderid"]=$id;
        $log["money"]=$list[0]["fprice"]*$percent;
        $log["oldmoney"]=$list[0]["money"];
        $log["type"]=0;
        $log["status"]=1;
        $log["inputtime"]=date('Y-m-d H:i:s',time());

        $User=M("user");
        $Log=M("log");
        $User->where("openid='$openid'")->save($userData);
        $Log->add($log);

        $Order=M("order");
        if($Order->create($data))
        {
            $result=$Order->where("id=$id")->save($data);

            if($result)
            {
                $this->ajaxReturn(array("flag"=>1));
            }
            else
            {
                $this->ajaxReturn(array("flag"=>0));
            }
        }
        else
        {
            $this->ajaxReturn(array("flag"=>3));
        }
    }

    public function MySumbitOkF(){

        $id=$_POST["id"];
        $data["status"]=4;
        $data["fstatus"]=2;

        $Model = new Model();
        $list=$Model->query("select a.useropenid,a.fprice,b.money from wx_order as a left join wx_user as b on a.useropenid=b.openid where a.id=$id");

        $percent=$Model->query("select * from wx_setting where id=1");
        $percent=$percent[0]["percent"];

        $openid=$list[0]["useropenid"];
        $userData["money"]=$list[0]["money"]+($list[0]["fprice"]*$percent);

        $log["openid"]=$list[0]["useropenid"];
        $log["orderid"]=$id;
        $log["money"]=$list[0]["fprice"]*$percent;
        $log["oldmoney"]=$list[0]["money"];
        $log["type"]=0;
        $log["status"]=1;
        $log["inputtime"]=date('Y-m-d H:i:s',time());

        $User=M("user");
        $Log=M("log");
        $User->where("openid='$openid'")->save($userData);
        $Log->add($log);

        $Order=M("order");
        if($Order->create($data))
        {
            $result=$Order->where("id=$id")->save($data);

            if($result)
            {
                $this->ajaxReturn(array("flag"=>1));
            }
            else
            {
                $this->ajaxReturn(array("flag"=>0));
            }
        }
        else
        {
            $this->ajaxReturn(array("flag"=>3));
        }
    }

    public function SumbitOkF(){

        $id=$_POST["id"];
        $data["fstatus"]=1;
        $Order=M("order");
        if($Order->create($data))
        {
            $result=$Order->where("id=$id")->save($data);

            if($result)
            {
                $this->ajaxReturn(array("flag"=>1));
            }
            else
            {
                $this->ajaxReturn(array("flag"=>0));
            }
        }
        else
        {
            $this->ajaxReturn(array("flag"=>3));
        }
    }

    public function Fangong(){

        $id=$_POST["id"];
        $data["fangong"]=$_POST["content"];
        $Order=M("order");
        if($Order->create($data))
        {
            $result=$Order->where("id=$id")->save($data);

            if($result)
            {
                $this->ajaxReturn(array("flag"=>1));
            }
            else
            {
                $this->ajaxReturn(array("flag"=>0));
            }
        }
        else
        {
            $this->ajaxReturn(array("flag"=>3));
        }
    }

    public function Pingjia(){
        $id=$_POST["id"];
        $data["pingjia"]=$_POST["content"];
        $data["score"]=$_POST["score"];
        $Order=M("order");
        if($Order->create($data))
        {
            $result=$Order->where("id=$id")->save($data);

            if($result)
            {
                $this->ajaxReturn(array("flag"=>1));
            }
            else
            {
                $this->ajaxReturn(array("flag"=>0));
            }
        }
        else
        {
            $this->ajaxReturn(array("flag"=>3));
        }
    }

    public function SumbitOk(){

        $id=$_POST["id"];
        $data["status"]=3;
        $Order=M("order");
        if($Order->create($data))
        {
            $result=$Order->where("id=$id")->save($data);

            if($result)
            {
                $this->ajaxReturn(array("flag"=>1));
            }
            else
            {
                $this->ajaxReturn(array("flag"=>0));
            }
        }
        else
        {
            $this->ajaxReturn(array("flag"=>3));
        }
    }

    public function GetServiceOrder()
    {
        $Model = new Model();
        $id=$_POST["id"];
        $list=$Model->query("select a.*,b.nickname,b.avatar from wx_order as a left join wx_user as b on a.openid=b.openid where a.id='$id'");
        for($i=0;$i<count($list);$i++){
            $list[$i]["thumb"]=explode(",",$list[$i]["img"]);
            $list[$i]["service"]=explode(",",$list[$i]["yuanjiansub"]);
            for($j=0;$j<count($list[$i]["service"]);$j++){
                $servicenamesub[]=$Model->query("select a.title,b.name from wx_service as a left join wx_servicecategory as b on a.cateId=b.id where a.id=".$list[$i]["service"][$j]);
            }
            $list[$i]["servicenamesub"]=$servicenamesub;
            $servicenamesub="";
        }

        $name=$list[0]["servicename"];
        $listService=$Model->query("select a.*,b.name from wx_service as a left join wx_servicecategory as b on a.cateId=b.id where b.name='$name' order by a.id desc ");


        $arr=array("list"=>$list,"listService"=>$listService);

        $this->ajaxReturn($arr,json);

    }


    public function UpdateService(){

        date_default_timezone_set("Asia/Shanghai");
        $Model = new Model();
        $id=$_POST["id"];
        $data["img"]=$_POST["img"];
        $data["username"]=$_POST["username"];
        $data["address"]=$_POST["address"];
        $data["price"]=$_POST["price"];
        $data["fprice"]=$_POST["fprice"];
        $data["content"]=$_POST["content"];
        $data["phone"]=$_POST["phone"];
        $data["openid"]=$_POST["openid"];
        $data["yuanjiansub"]=$_POST["yuanjiansub"];
        $data["inputtime"] = date('Y-m-d H:i:s',time());

        $Order=M("order");
        if($Order->create($data))
        {
            $result=$Order->where("id=$id")->save($data);

            if($result)
            {
                $this->ajaxReturn(array("status"=>"success","msg"=>"修改成功！"));
            }
            else
            {
                $this->ajaxReturn(array("status"=>"error","msg"=>"没有做出修改！"));
            }
        }
        else
        {
            $this->ajaxReturn(array("status"=>"error","msg"=>"修改失败！"));
        }
    }

    public function GetMyOrder(){
        $Model = new Model();
        $openid=$_POST["openid"];
        $list=$Model->query("select * from wx_order where useropenid='$openid' order by id desc");
        for($i=0;$i<count($list);$i++){
            $list[$i]["thumb"]=explode(",",$list[$i]["img"]);
            $list[$i]["thumb"]=$list[$i]["thumb"][0];
        }

        $this->ajaxReturn($list,json);
    }

    public function UpdateUser(){
        $data["pic"]=$_POST["img"];
        $data["introduce"]=$_POST["introduce"];
        $data["work"]=$_POST["work"];
        $data["type"]=1;
        $data["status"]=0;
        $openid=$_POST["openid"];

        $User=M("User");
        $arr['flag']=0;
        if($User->create($data))
        {
            $result=$User->where("openid='$openid'")->save($data);
            if($result)
            {
                $this->ajaxReturn(array("status"=>"success","msg"=>"待审核！"));
            }
            else
            {
                $this->ajaxReturn(array("status"=>"error","msg"=>"请勿重复提交！"));
            }
        }
        else
        {
            $this->ajaxReturn(array("status"=>"error","msg"=>"提交信息错误！"));
        }

    }


}