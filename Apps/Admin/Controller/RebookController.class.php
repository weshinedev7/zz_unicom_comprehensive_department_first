<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class RebookController extends Controller{
    public $_GPC = array();

    public function _initialize(){
        $this->_GPC = array_merge($_POST, $_GET);
    }
    public function index(){
        $_GPC=$this->_GPC;
        $Model=new Model();
        $sql="select r.*,a.name from wx_rebook r left join wx_article a on r.artid=a.id where 1  ";
        $condition='';
        $keyword='';
        if (!empty($_GPC['keyword'])){
            $keyword=trim($_GPC['keyword']);
            $condition.=" and a.name like '%{$keyword}%' ";
        }
        $count=$Model->query($sql.$condition);
        $Page = new \Think\Page(count($count),10);
        $show = $Page->show();
        $firstRow=$Page->firstRow;
        $listRows=$Page->listRows;

        $sql="select r.*,a.name from wx_rebook r left join wx_article a on r.artid=a.id where 1 {$condition}  order by r.id desc  limit {$firstRow},{$listRows} ";
        $list=$Model->query($sql);
        if (!empty($list)){
            foreach ($list as &$row){
//                $row['cover']='https://'.$_SERVER['SERVER_NAME'].'/'.$row['cover'];
                $row['createtime']=date('Y-m-d',$row['createtime']);
                $row['rebooktime']=date('Y-m-d',$row['rebooktime']);
            }
        }



        $this->assign('keyword',$keyword);
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display();
    }
    public function deleterebook(){
        $_GPC=$this->_GPC;
        $id=$_GPC['id'];
        if (!empty($id)){
            $data=array(
                'isdeleted'=>1
            );
            $res=M('rebook')->where(array('id'=>$id))->save($data);
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