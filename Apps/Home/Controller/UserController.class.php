<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Think\Exception;


class UserController extends Controller{

    public function index()
    {

    }

    public function GetUser(){
        $Model = new Model();
        $openid=$_POST["openid"];
        $list=$Model->query("select * from wx_user where openid='$openid'");
        if($list[0]["type"]==1) {
            $pic = $list[0]["pic"];
            $pic = explode(",", $pic);

//            array_walk(
//                $pic,
//                function (&$s, $k, $prefix = __ROOT__) {
//                    $s = str_pad($s, strlen($prefix) + strlen($s), $prefix, STR_PAD_LEFT);
//                }
//            );


        }else{
            $pic=array();
        }
        $userinfo=array("list"=>$list,"pic"=>$pic);

        $this->ajaxReturn($userinfo,json);
    }

    public function Cash(){

        date_default_timezone_set("Asia/Shanghai");
        $Model = new Model();
        $money=$_POST["money"];
        $data["openid"]=$_POST["openid"];

        $openid=$_POST["openid"];
        $list=$Model->query("select * from wx_user where openid='$openid'");

        $userData["money"]=0;

        $log["openid"]=$_POST["openid"];
        $log["orderid"]=0;
        $log["money"]=$money;
        $log["oldmoney"]=$money;
        $log["type"]=1;
        $log["status"]=0;
        $log["inputtime"]=date('Y-m-d H:i:s',time());

        $User=M("user");
        $Log=M("log");
        $User->where("openid='$openid'")->save($userData);
        $Log->add($log);

        $this->ajaxReturn(array("flag"=>1));

    }

    public function GetMyLog(){
        $Model = new Model();
        $openid=$_POST["openid"];
        $list=$Model->query("select * from wx_log where openid='$openid' order by id desc");

        $this->ajaxReturn($list,json);
    }

}