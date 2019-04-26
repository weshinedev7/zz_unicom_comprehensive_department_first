<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class TrafficController extends Controller{
    public $_GPC = array();

    public function _initialize(){
        $this->_GPC = array_merge($_POST, $_GET);
    }
    public function index(){
        $_GPC=$this->_GPC;
        $Model=new Model();
        $sql="select `id`,`name`,`time` from wx_article where type=6  ";
        $condition='';
        $keyword='';
        if (!empty($_GPC['keyword'])){
            $keyword=trim($_GPC['keyword']);
            $condition.=" and name like '%{$keyword}%' ";
        }
        $count=$Model->query($sql.$condition);
        $Page = new \Think\Page(count($count),10);
        $show = $Page->show();
        $firstRow=$Page->firstRow;
        $listRows=$Page->listRows;

        $sql="select `id`,`name`,`time` from wx_article where type=6 {$condition}  order by id desc  limit {$firstRow},{$listRows} ";
        $list=$Model->query($sql);
        if (!empty($list)){
            foreach ($list as &$row){
                $row['time']=date('Y-m-d',$row['time']);
            }
        }



        $this->assign('keyword',$keyword);
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display();
    }
    public function postcategory(){
        $_GPC=$this->_GPC;
        $this->display();
    }
    public function savecategory(){
        $_GPC=$this->_GPC;
        $data=array(
            'name'=>$_GPC['name'],
            'type'=>6,
        );
        $flag=1;
        $res=M('category')->add($data);
        if (!$res){
            $flag=-1;
        }
        $this->ajaxReturn(array('flag'=>$flag),json);
    }
    public function posttraffic(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        $cate=M('category')->where('type=6')->select();

        if (!empty($id)){
            $convenience=M('article')->where(array('id'=>$id))->find();
            $this->assign('id',$id);

            $this->assign('data',$convenience);
        }else{
            $this->assign('data',array('lat'=>'39.916527','lng'=>'116.397128'));
        }
        $this->assign('cate',$cate);
        $this->display();
    }
    public function savetraffic(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        $data=array(
            'name'=>trim($_GPC['name']),
            'catid'=>intval($_GPC['catid']),
            'content'=>$_GPC['content'],
            'time'=>time(),
            'type'=>6
        );
        $flag=1;


        if (empty($id)){
            $res=M('article')->add($data);
            if (!$res){
                $flag=-1;
            }

        }
        else{
            $res=M('article')->where(array('id'=>$id))->save($data);
            if (!$res){
                $flag=-1;
            }

        }


        $this->ajaxReturn(array('flag'=>$flag),json);

    }
    public function deletetraffic(){
        $_GPC=$this->_GPC;
        $id=$_GPC['id'];
        if (!empty($id)){
            $res=M('article')->where(array('id'=>$id))->delete();

            if ($res){
                $this->ajaxReturn(array('status'=>1),json);

            }else{
                $this->ajaxReturn(array('status'=>-1),json);

            }
        }else{
            $this->ajaxReturn(array('status'=>-1),json);
        }
    }
}