<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Think\Exception;


class SettingController extends Controller{

    public function index()
    {
        $Model = new Model();
        $list=$Model->query('select * from jz_setting where id=1 order by id asc');
        $this->ajaxReturn($list[0],json);
    }

    public function Card()
    {
        $Model = new Model();
        $list=$Model->query('select * from jz_setting where id=2 order by id asc');
        $this->ajaxReturn($list[0],json);
    }

    /*
     * 1表示申请提交成功
     * 2表示此商户名重复，不允许重复申请
     * 0表示申请失败
     * */

    public function Order(){
        $Apply=M("apply");

        $data['username']=$_GET['username'];
        $data['useraddress']=$_GET['useraddress'];
        $data['usertel']=$_GET['usertel'];
        $data['date']=$_GET['date']." : ".$_GET['time'];
        $data['req']=$_GET['req'];
        $data["inputtime"] = date('Y-m-d H:i:s',time());

        $username = $_GET['username'];
        $cid = $Apply->where("username='$username'")->getField('id');

        $arr['flag']=0;

        if($cid=="")
        {
            if($Apply->create($data))
            {
                $result=$Apply->add($data);

                if($result)
                {
                    $arr['flag']=1;
                    $this->ajaxReturn($arr,json);
                }
                else
                {
                    $this->ajaxReturn($arr,json);
                }
            }
            else
            {
                $this->ajaxReturn($arr,json);
            }
        }else{
            $arr['flag']=2;
            $this->ajaxReturn($arr,json);
        }
    }


    /*
     * 1表示申请提交成功
     * 2表示此身份证重复，不允许重复申请
     * 0表示申请失败
     * */

    public function CardOrder(){
        $Card=M("card");

        $data['username']=$_GET['username'];
        $data['usercard']=$_GET['usercard'];
        $data['usertel']=$_GET['usertel'];
        $data['useraddress']=$_GET['useraddress'];
        $data['hyval']=$_GET['hyval'];
        $data['jycdval']=$_GET['jycdval'];
        $data['zzval']=$_GET['zzval'];
        $data['company']=$_GET['company'];
        $data['companyaddress']=$_GET['companyaddress'];
        $data['hangyeval']=$_GET['hangyeval'];
        $data['zyval']=$_GET['zyval'];
        $data['zwval']=$_GET['zwval'];
        $data['money']=$_GET['money'];
        $data['email']=$_GET['email'];
        $data["inputtime"] = date('Y-m-d H:i:s',time());

        $usercard = $_GET['usercard'];
        $cid = $Card->where("usercard='$usercard'")->getField('id');

        $arr['flag']=0;

        if($cid=="")
        {
            if($Card->create($data))
            {
                $result=$Card->add($data);

                if($result)
                {
                    $arr['flag']=1;
                    $this->ajaxReturn($arr,json);
                }
                else
                {
                    $this->ajaxReturn($arr,json);
                }
            }
            else
            {
                $this->ajaxReturn($arr,json);
            }
        }else{
            $arr['flag']=2;
            $this->ajaxReturn($arr,json);
        }
    }


}