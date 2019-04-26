<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class HottagsController extends Controller {
    public $_GPC=array();

    public function _initialize(){
        $this->_GPC=array_merge($_POST,$_GET);
    }

    #用户标签
    public function index(){
        $_GPC=$this->_GPC;
        $list=M('hottags')->select();
        foreach ($list as &$row){
            $row['updatetime']=date('Y-m-d H:i:s',$row['updatetime']);
        }

        $this->assign('list',$list);
        $this->display();
    }
    #获取标签信息
    public function gettaginfo(){
        $_GPC=$this->_GPC;
        if (!empty($_GPC['id'])){
            $taginfo=M('hottags')->where(array('id'=>trim($_GPC['id'])))->find();
            $this->ajaxReturn(array('status'=>-1,'info'=>$taginfo),json);

        }else{
            $this->ajaxReturn(array('status'=>-1),json);
        }
    }
    #更新标签
    public function edittag(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        $data=array(
            'name'=>trim($_GPC['tagname']),
            'updatetime'=>time()
        );
        if (!empty($id)){
            $res=M('hottags')->where(array('id'=>$id))->save($data);
        }else{
            $res=M('hottags')->add($data);
        }

        $this->ajaxReturn(array('status'=>$res),json);
    }
    #删除标签
    public function deletetag(){
        $_GPC=$this->_GPC;
        $id=$_GPC['id'];
        if (!empty($id)){
            $res=M('hottags')->where(array('id'=>$id))->delete();
            if ($res){
                $this->ajaxReturn(array('status'=>1),json);

            }else{
                $this->ajaxReturn(array('status'=>-1),json);

            }
        }else{
            $this->ajaxReturn(array('status'=>-1),json);
        }
    }




    public function loginout(){
        session('username',null);
        $url="/admin/";
        $this->redirect($url);
    }

}