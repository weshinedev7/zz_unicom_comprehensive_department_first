<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class ConvenienceController extends Controller{
    public $_GPC = array();

    public function _initialize(){
        $this->_GPC = array_merge($_POST, $_GET);
    }
    public function index(){
        $_GPC=$this->_GPC;
        $Model=new Model();
        $sql="select `id`,`name`,`cover`,`time` from wx_article where type=7  ";
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

        $sql="select `id`,`name`,`cover`,`time` from wx_article where type=7 {$condition}  order by id desc  limit {$firstRow},{$listRows} ";
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
    public function postconvenience(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        $cate=M('category')->where('type=7')->select();

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
    public function saveconvenience(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        $data=array(
            'name'=>trim($_GPC['name']),
            'tel'=>intval($_GPC['tel']),
            'catid'=>intval($_GPC['catid']),
            'content'=>$_GPC['content'],
            'mapword'=>trim($_GPC['mapword']),
            'lat'=>$_GPC['lat'],
            'lng'=>$_GPC['lng'],
            'time'=>time(),
            'type'=>7
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
    public function deleteconvenience(){
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