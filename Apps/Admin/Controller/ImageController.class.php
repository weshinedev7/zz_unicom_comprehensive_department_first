<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class ImageController extends Controller{
    public $_GPC = array();

    public function _initialize(){
        $this->_GPC = array_merge($_POST, $_GET);
    }
    public function index(){
        $_GPC=$this->_GPC;
        $Model=new Model();
        $sql="select * from wx_pic where 1  ";
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

        $sql="select * from wx_pic where 1 {$condition}  order by id desc  limit {$firstRow},{$listRows} ";
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
    public function postimage(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        if (!empty($id)){
            $video=M('pic')->where(array('id'=>$id))->find();
            $this->assign('id',$id);
            $this->assign('data',$video);
        }
        $this->display();
    }
    public function saveimage(){
        $_GPC=$this->_GPC;

        if (!empty($_GPC['imglist'])){
            foreach ($_GPC['imglist'] as $row){
                $data=array(
                    'img'=>$row,
                    'time'=>time()
                );
                M('pic')->create($data);
                $res=M('pic')->add();
                unset($data);
            }
        }
        $flag=1;
        if (!$res){
            $flag=0;
        }

        $this->ajaxReturn(array('flag'=>$flag),json);

    }

    public function deleteimage(){
        $_GPC=$this->_GPC;
        $id=$_GPC['id'];
        if (!empty($id)){
            $res=M('pic')->where(array('id'=>$id))->delete();
//            M('science_audio')->where(array('science_id'=>$id))->delete();
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