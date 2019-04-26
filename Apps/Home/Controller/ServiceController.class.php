<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Think\Exception;


class ServiceController extends Controller{

    public function index()
    {

    }

    public function GetService(){
        $Model = new Model();
        $name=$_POST["name"];
        $list=$Model->query("select a.*,b.name from wx_service as a left join wx_servicecategory as b on a.cateId=b.id where b.name='$name' order by a.id desc ");
        $this->ajaxReturn($list,json);
    }

    public function GetFprice(){
        $Model = new Model();
        $list=$Model->query("select fprice from wx_setting where id=1");
        $this->ajaxReturn($list[0],json);
    }

    public function UploadImg(){

        date_default_timezone_set("Asia/Shanghai");

        $url = 'active_join_pic';
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      './Public/upload/'.$url.'/'; // 设置附件上传根目录
        $info   =   $upload->uploadOne($_FILES['file']);


        if(!$info && $info!=null) {  // 上传错误提示错误信息
            $this->ajaxReturn(array("status"=>"error","data"=>'上传失败'));
            return ;
        }

        $filename = __ROOT__.$upload->rootPath.date("Y-m-d").'/'.$info['savename'];
        $this->ajaxReturn(array("status"=>"success","data"=>$filename));
        return ;
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

    public function UpdateUser(){
        $data["account"]=$_POST["account"];
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