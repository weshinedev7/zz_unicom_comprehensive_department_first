<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

/**
 * Class ApiUserController
 * @package Admin\Controller
 * 请求方式  post
 * 方法命名 驼峰
 * 双引号
 */
class ApiUserController extends Controller {

    /*获取用户基本信息*/
    public function getuserInfo(){
        $code = $_POST['code'];
        $appid = "wx94233df88122d237";
        $secret = "9d56a920706a92808f3db7a9f8790e26";
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$code."&grant_type=authorization_code";
        $public=new ApiPublicController();
        $res = $public->getcurl($url);
        //返回的数据为json格式
        $resInfo=json_decode($res,true);
        #todo 获取openid时存储到数据库

        if(!empty($resInfo["openid"])){
            $openid=$resInfo['openid'];
            $useropenid = M('user')->where("openid='$openid'")->getField('openid');
            if(!$useropenid){
                $fnopenid=$openid;
                $data=array('openid'=>$openid);
                M('user')->add($data);
            }else{
                $fnopenid=$useropenid;
            }


        }
        $this->ajaxReturn($res);
    }
    /*存储个人信息*/
    public function saveInfo(){
        $Model = new Model();
        $public=new ApiPublicController();
        $openid = $_POST['openid'];
        $nickname=$public->emoji_encode( $_POST['nickname']);
        $data['nickname'] = $nickname;
        $data['avatar'] = $_POST['avatar'];
        $data['gender']=$_POST['gender'];
        $data['country']=$_POST['country'];
        $data['city']=$_POST['city'];
        $data['province']=$_POST['province'];
        $data['openid']=$openid;
        $data['time']=time();
        $data["systemtype"]=$_POST['system'];
        $userinfo = $Model->table('wx_user')->where("openid='$openid'")->find();
        if(empty($openid)){
            $this->ajaxReturn(array('errno'=>-1,'message'=>'openid不能为空'));
        }
        if($userinfo){
            if(!empty($_POST['nickname'])||!empty($_POST['avatar'])||!empty(!$_POST['country'])||!empty($_POST['city'])||!empty($_POST['province'])){
                $data['type']=1;
                $data["authtime"]=time();

            }
            $result = M('user')->where("openid='$openid'")->save($data);
            if($result>=0){
                $this->ajaxReturn(array('errno'=>0,'message'=>'用户信息存储成功'));
            }else{
                $this->ajaxReturn(array('errno'=>-1,'message'=>'用户信息存储失败'));
            }
        }else{
            if(!empty($_POST['nickname'])||!empty($_POST['avatar'])||!empty(!$_POST['country'])||!empty($_POST['city'])||!empty($_POST['province'])){
                $data['type']=1;
                $data["authtime"]=time();
            }
            $result = M("user")->add($data);
            if($result){
                $this->ajaxReturn(array('errno'=>0,'message'=>'用户信息存储成功'));
            }else{
                $this->ajaxReturn(array('errno'=>-1,'message'=>'用户信息存储失败'));
            }
        }

    }




}