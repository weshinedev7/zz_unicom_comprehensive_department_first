<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class ComplainController extends Controller{
    public $_GPC = array();

    public function _initialize(){
        $this->_GPC = array_merge($_POST, $_GET);
    }
    public function index(){
        $_GPC=$this->_GPC;
        $Model=new Model();
        $sql="select `id`,`title`,`content`,`time` from wx_complain where 1  ";
        $condition='';
        $keyword='';
        if (!empty($_GPC['keyword'])){
            $keyword=trim($_GPC['keyword']);
            $condition.=" and title like '%{$keyword}%' ";
        }
        $count=$Model->query($sql.$condition);
        $Page = new \Think\Page(count($count),10);
        $show = $Page->show();
        $firstRow=$Page->firstRow;
        $listRows=$Page->listRows;

        $sql="select `id`,`title`,`content`,`time` from wx_complain where 1 {$condition}  order by id desc  limit {$firstRow},{$listRows} ";
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
    public function postcomplain(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);

        if (!empty($id)){
            $complain=M('complain')->where(array('id'=>$id))->find();
            $this->assign('id',$id);
            $this->assign('data',$complain);
        }
        $this->display();
    }
    public function savecomplain(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        $data=array(
            'title'=>trim($_GPC['title']),
            'content'=>$_GPC['content'],
            'time'=>time(),
            'isdeleted'=>0
        );
        $flag=1;


        if (empty($id)){
            $res=M('complain')->add($data);
            if (!$res){
                $flag=-1;
            }

        }
        else{
            $res=M('complain')->where(array('id'=>$id))->save($data);
            if (!$res){
                $flag=-1;
            }

        }


        $this->ajaxReturn(array('flag'=>$flag),json);

    }
    public function deletecomplain(){
        $_GPC=$this->_GPC;
        $id=$_GPC['id'];
        if (!empty($id)){
            $res=M('complain')->where(array('id'=>$id))->delete();

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