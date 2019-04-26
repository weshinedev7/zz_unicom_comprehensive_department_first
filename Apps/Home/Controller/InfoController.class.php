<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Think\Exception;


class InfoController extends Controller{

    public function index()
    {

    }

    public function GetMySend(){
        $Model = new Model();
        $openid=$_POST["openid"];
        $list=$Model->query("select * from wx_info where openid='$openid'");
        for($i=0;$i<count($list);$i++){
            $list[$i]["img"]=explode(",",$list[$i]["img"]);
            $list[$i]["img"]=$list[$i]["img"][0];
        }

        $this->ajaxReturn($list,json);
    }

    public function GetInfo()
    {
        $Model = new Model();
        $id=$_POST["id"];
        $list=$Model->query("select a.*,b.name,b.nickname,b.avatar from wx_info as a left join wx_user as b on a.openid=b.openid where a.id='$id'");
        for($i=0;$i<count($list);$i++){
            $list[$i]["img"]=str_replace("https://weixiu.wshoto.com.","https://weixiu.wshoto.com",$list[$i]["img"]);
            $list[$i]["img"]=explode(",",$list[$i]["img"]);
        }

        $arr=array("list"=>$list);

        $this->ajaxReturn($arr,json);
    }


    public function Insert(){

        date_default_timezone_set("Asia/Shanghai");
        $Model = new Model();
        $data["img"]=$_POST["img"];
        $data["username"]=$_POST["username"];
        $data["address"]=$_POST["address"];
        $data["price"]=$_POST["price"];
        $data["title"]=$_POST["title"];
        $data["content"]=$_POST["content"];
        $data["lat"]=$_POST["lat"];
        $data["lon"]=$_POST["lon"];
        $data["phone"]=$_POST["phone"];
        $data["openid"]=$_POST["openid"];
        $data["status"]=0;
        $data["inputtime"] = date('Y-m-d H:i:s',time());

        $Info=M("info");
        if($Info->create($data))
        {
            $result=$Info->add($data);

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
            $this->ajaxReturn(array("status"=>"error","msg"=>"发布失败！"));
        }
    }

    public function UpdateInfo(){

        date_default_timezone_set("Asia/Shanghai");
        $Model = new Model();
        $id=$_POST["id"];
        $data["img"]=$_POST["img"];
        $data["username"]=$_POST["username"];
        $data["address"]=$_POST["address"];
        $data["price"]=$_POST["price"];
        $data["title"]=$_POST["title"];
        $data["content"]=$_POST["content"];
        $data["lat"]=$_POST["lat"];
        $data["lon"]=$_POST["lon"];
        $data["phone"]=$_POST["phone"];
        $data["openid"]=$_POST["openid"];
        $data["status"]=0;

        $Info=M("info");
        if($Info->create($data))
        {
            $result=$Info->where("id=$id")->save($data);

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

    public function DelInfo()
    {
        header("Content-Type:text/html; charset=utf-8");
        $id=$_POST["id"];
        $Info = M("info"); // 实例化User对象
        $Info->delete($id);
    }


}