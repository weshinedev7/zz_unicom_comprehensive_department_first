<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class ShopController extends Controller{
    public $_GPC = array();

    public function _initialize(){
        $this->_GPC = array_merge($_POST, $_GET);
    }
    public function index(){
        $_GPC=$this->_GPC;
        $Model=new Model();

        $sql="select `id`,`name`,`cover`,`time` from wx_science where 1  ";
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
        $sql="select `id`,`name`,`cover`,`time` from wx_science where 1 {$condition}  order by id desc  limit {$firstRow},{$listRows} ";
        $list=$Model->query($sql);
        foreach ($list as &$row){
        $row['time'] = date('Y-m-d H:i:s',$row['time']);
        }
        $this->assign('keyword',$keyword);
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display();
}
    public function postshop(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        if (!empty($id)){
            $science=M('science')->where(array('id'=>$id))->find();
            $audio=M('science_audio')->where(array('science_id'=>$id))->find();

            $this->assign('audioid',$audio['id']);
            $this->assign('audio',$audio);
            $this->assign('id',$id);
            $this->assign('data',$science);
        }
        $this->display();
    }
}