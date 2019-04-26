<?php
namespace Admin\Controller;
use function PHPSTORM_META\type;
use Think\Controller;
use Think\Model;
//方法名:驼峰  变量名：_
class ApiHomeController extends Controller{
    public $_GPC = array();

    public function _initialize(){
        $this->_GPC = array_merge($_POST, $_GET);
    }
    //首页轮播图
    public function banners(){
        $Model=new Model();
        $list=$Model->table('wx_banner')->select();
        $newlist=array();
        if($list){
            foreach ($list as &$l){
                $l['url']="https://{$_SERVER['SERVER_NAME']}"."/".$l['url'];
                $newlist[]=$l['url'];
            }
            unset ($l);
            $this->ajaxreturn(array("errno"=>0,'list'=>array('banner'=>$newlist)));
        }else{
            $this->ajaxreturn(array('errno'=>-1,'message'=>'暂时还未设置轮播图'));
        }
    }
    //首页第二个接口
    public function second(){
        $Model=new Model();
        //旅行攻略
        $cate1=$Model->table('wx_category')->where('type=3')->field('id')->select();
        //活动盛事
//        $cate2=$Model->table('wx_category')->where('type=4')->field('id')->select();
        //出游推荐
        $cate3=$Model->table('wx_category')->where('type=5')->field('id')->select();
        $strategy=array();
        $activity=array();
        $recommend=array();
        foreach ($cate1 as &$l){
            $strategy[]=$Model->table(array('wx_article'=>'a','wx_category'=>'b'))->where("a.catid=b.id and a.type=3 and a.catid={$l['id']}")->field('b.id,b.name,a.cover')->find();
        }
//        foreach ($cate2 as &$l){
//            $activity[]=$Model->table(array('wx_article'=>'a','wx_category'=>'b'))->where("a.catid=b.id and  a.type=4 and a.catid={$l['id']}")->field('b.id,b.name,a.cover')->select();
//        }
        $activity=$Model->table('wx_article')->where("type=4 ")->field('id,name,cover')->select();

        foreach ($cate3 as &$l){
            $recommend[]=$Model->table(array('wx_article'=>'a','wx_category'=>'b'))->where("a.catid=b.id and  a.type=5 and a.catid={$l['id']}")->field('b.id,b.name,a.cover')->find();
        }
            if ($strategy){
                foreach ($strategy as &$l){
                    $l['cover']="https://{$_SERVER['SERVER_NAME']}"."/".$l['cover'];
                }
            }
            if ($activity){
            foreach ($activity as &$l){
                $l['cover']="https://{$_SERVER['SERVER_NAME']}"."/".$l['cover'];
                }
             }
            if ($recommend){
            foreach ($recommend as &$l){
                $l['cover']="https://{$_SERVER['SERVER_NAME']}"."/".$l['cover'];
                }
            }

        if($strategy||$activity||$recommend){
            $this->ajaxreturn(array("errno"=>0,'data'=>array('strategy'=>$strategy,'activity'=>$activity,'recommend'=>$recommend)));
        }
        else{
            $this->ajaxreturn(array('errno'=>-1,'message'=>'获取数据为空！'));
        }
    }
    //获取文章内容
    public function getcontent(){
        $id = $_POST['id'];
        $Model=new Model();
        $content=$Model->table('wx_article')->where("id='$id'")->field('content,name,lat,lng,isbooked')->find();
        $content['content']=htmlspecialchars($content['content']);
        if ($content){
            $this->ajaxreturn(array("errno"=>0,'data'=>$content));
        }
        else{
            $this->ajaxreturn(array('errno'=>-1,'message'=>'获取数据为空！'));
        }
    }
    //获取印象利通、交通、便民查询文章列表
    public function getexpression(){
        $Model=new Model();
        $type=$this->_GPC['type'];
        $catid=$this->_GPC['catid'];
        if (empty($type)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"type不能为空"));

        }
        if (empty($catid)){
            $cate=$Model->table('wx_category')->where("type={$type}")->field('id')->find();
            $expression=$Model->table('wx_article')->where("type={$type} and catid={$cate['id']}")->field('id,name,cover')->select();

        }
        else{

            $expression=$Model->table('wx_article')->where("type={$type} and catid={$catid}")->field('id,name,cover')->select();

        }
        foreach ($expression as &$l){
            $l['cover']="https://{$_SERVER['SERVER_NAME']}"."/".$l['cover'];
        }
        if($expression){
            $this->ajaxreturn(array("errno"=>0,'title'=>$expression));
        }
        else{
            $this->ajaxreturn(array("errno"=>-1,'title'=>'获取数据失败！'));
        }
    }
    //获取预约列表
    public function getorder(){
        $openid = $_POST['openid'];
        if(empty($openid)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"openid不能为空"));
        }
        $Model=new Model();
        $order=$Model->table('wx_rebook')->where("openid='{$openid}'")->field('id,realname,mobile,rebooktime')->select();
        foreach ($order as &$l){
            $l['rebooktime']=date('Y-m-d',$l['rebooktime']);
        }
        if($order){
            $this->ajaxreturn(array("errno"=>0,'data'=>$order));
        }
        else{
            $this->ajaxreturn(array("errno"=>-1,"message"=>"预约列表为空"));
        }
    }
    //加入预约列表
    public function sendorder(){
        $name=$_POST['name'];
        $tel=$_POST['tel'];
        $openid=$_POST['openid'];
        $id=$_POST['id'];
        $time=strtotime($_POST['time']);
        if (empty($openid)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"openid获取失败!"));
        }
        $data=array(
            'artid'=>$id,
            'openid'=>$openid,
            'realname'=>$name,
            'mobile'=>$tel,
            'rebooktime'=>$time,
            'createtime'=>time(),
            'isdeleted'=>0);
        $Model=new Model();
        if ($Model->table('wx_rebook')->data($data)->add()){
            $this->ajaxreturn(array("errno"=>0,"message"=>"预约成功！"));
        }
        else{
            $this->ajaxreturn(array("errno"=>-1,"message"=>"预约失败！"));
        }
    }
    //获取视频列表
    public function getvideo(){
        $Model=new Model();
        $order=$Model->table('wx_video')->field('videopath')->find();
        $order['videopath']="https://{$_SERVER['SERVER_NAME']}"."/".$order['videopath'];
        if ($order){
            $this->ajaxreturn(array("errno"=>0,'data'=>$order));
        }
        else{
            $this->ajaxreturn(array("errno"=>-1,'message'=>'获取视频路径失败！'));
        }
    }
    //获取其他文章列表
    public function getlist(){
        $Model=new Model();
        $catid=$this->_GPC['catid'];
        $page=$this->_GPC['page'];
        if (empty($page)){
            $page=1;
        }
        if (empty($catid)){
            $this->ajaxreturn(array("errno"=>-1,'title'=>'catid为空！'));
        }
        $expression=$Model->table('wx_article')->where("catid={$catid}")->field('id,name,cover,isbooked,lat,lng')->limit(($page-1)*3,3)->select();
        foreach ($expression as &$l){
            $l['cover']="https://{$_SERVER['SERVER_NAME']}"."/".$l['cover'];
        }
        if($expression){
            $this->ajaxreturn(array("errno"=>0,'title'=>$expression));
        }
        else{
            $this->ajaxreturn(array("errno"=>-1,'title'=>'获取数据失败！'));
        }
    }
    //旅游投诉
    public function sendcomplain(){
        $title=$_POST['title'];
        $content=$_POST['content'];
        $openid=$_POST['openid'];
        if (empty($openid)){
            $this->ajaxreturn(array("errno"=>0,"message"=>"openid为空"));
        }
        $data=array(
            'openid'=>$openid,
            'title'=>$title,
            'content'=>$content,
            'time'=>time(),
            'isdeleted'=>0
        );
        $Model=new Model();
        if ($Model->table('wx_complain')->data($data)->add()){
            $this->ajaxreturn(array("errno"=>0,"message"=>"提交成功！"));
        }
        else{
            $this->ajaxreturn(array("errno"=>-1,"message"=>"提交失败！"));

        }
    }
    //图片走廊
    public function  getimglist(){
        $list=M('pic')->select();
        $listreturn=array();
        foreach ($list as &$row){
            $row['img']="https://{$_SERVER['SERVER_NAME']}"."/".$row['img'];
            $listreturn[]=$row['img'];
        }
        if($list){
            $this->ajaxreturn(array("errno"=>0,'data'=>$listreturn));
        }
        else{
            $this->ajaxreturn(array("errno"=>-1,'data'=>'暂无图片！'));
        }
    }
}