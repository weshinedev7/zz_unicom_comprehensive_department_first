<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class AlbumController extends Controller {
    public $_GPC = array();

    public function _initialize(){
        $this->_GPC = array_merge($_POST, $_GET);
    }

    public function index(){
        $_GPC=$this->_GPC;
        $Model=new Model();
        $sql="select `id`,`name`,`cover`,`time` from wx_album where 1  ";
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

        $sql="select `id`,`name`,`cover`,`time` from wx_album where 1 {$condition}  order by id desc  limit {$firstRow},{$listRows} ";
        $list=$Model->query($sql);
        if (!empty($list)){
            foreach ($list as &$row){
                $row['cover']='https://'.$_SERVER['SERVER_NAME'].'/'.$row['cover'];
                $row['time']=date('Y-m-d',$row['time']);
            }
        }



        $this->assign('keyword',$keyword);
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display();
    }

    public function postalbum(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        if (!empty($id)){
            $science=M('album')->where(array('id'=>$id))->find();
          /*  $audio=M('science_audio')->where(array('science_id'=>$id))->find();

            $this->assign('audioid',$audio['id']);
            $this->assign('audio',$audio);*/
            $this->assign('id',$id);
            $this->assign('data',$science);
        }
        $this->display();
    }

    public function savealbum(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        $data=array(
            'name'=>trim($_GPC['name']),
            'cover'=>trim($_GPC['cover']),
            'price'=>trim($_GPC['price']),
            'content'=>$_GPC['content'],
            'time'=>time(),
            'introduction'=>$_GPC['introduction']
        );
        $flag=1;
        #2019-2-14 更新 添加wx_keyword_match表的信息
        $keyworddata=array(
            'parent_id'=>'',
            'son_id'=>0,
            'name'=>trim($_GPC['name']),
            'isparent'=>1,
            'priority'=>2
        );


        if (empty($id)){
            $res=M('album')->add($data);
            if (!$res){
                $flag=-1;
            }
            $keyworddata['parent_id']=$res;
            $keywordres=M('keyword_match')->add($keyworddata);

        }elseif (!empty($id)){
            $res=M('album')->where(array('id'=>$id))->save($data);
            if (!$res){
                $flag=-1;
            }

            $keyworddata['parent_id']=$id;
            $keywordres=M('keyword_match')->where(array('parent_id'=>$id,'priority'=>2,'isparent'=>1))->save(array('name'=>trim($_GPC['name'])));

        }

        $this->ajaxReturn(array('flag'=>$flag),json);

    }
    public function deleteablum(){
        $_GPC=$this->_GPC;
        $id=$_GPC['id'];
        if (!empty($id)){
            $res=M('album')->where(array('id'=>$id))->delete();
            M('album_episode')->where(array('album_id'=>$id))->delete();

            if ($res){
                $this->ajaxReturn(array('status'=>1),json);

            }else{
                $this->ajaxReturn(array('status'=>-1),json);
            }
        }else{
            $this->ajaxReturn(array('status'=>-1),json);
        }


    }
    public function deleteesp(){
        $_GPC=$this->_GPC;
        $id=$_GPC['id'];
        if (!empty($id)){
            $res=M('album_episode')->where(array('id'=>$id))->delete();
            if ($res){
                $this->ajaxReturn(array('status'=>1),json);

            }else{
                $this->ajaxReturn(array('status'=>-1),json);
            }
        }else{
            $this->ajaxReturn(array('status'=>-1),json);
        }
    }

    public function audiolist(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        $album=M('album')->where(array('id'=>$id))->field('id,name,price')->find();
        $list=M('album_episode')->where(array('album_id'=>$id))->select();

        foreach ($list as &$row){
            $row['updatetime']=date('Y-m-d',$row['updatetime']);
            $row['cover']='https://'.$_SERVER['SERVER_NAME'].'/'.$row['cover'];
        }
        if ($album['price']>0){
            $isfree=0;
        }else{
            $isfree=1;
        }
        $this->assign('isfree',$isfree);
        $this->assign('albumid',$id);
        $this->assign('list',$list);
        $this->assign('album',$album);
        $this->display();
    }

    public function getespinfo(){
        $_GPC=$this->_GPC;
        $espinfo=M('album_episode')->where(array('id'=>$_GPC['id']))->find();
        $this->ajaxReturn(array('espinfo'=>$espinfo),json);
    }

    public function editesp(){
        $_GPC=$this->_GPC;

        $id=trim($_GPC['id']);
        $flag=1;
        $espdata=array(
            'name'=>$_GPC['name'],
            'cover'=>trim($_GPC['cover'],'/'),
            'filepath'=>trim($_GPC['audio'],'/'),
            'updatetime'=>time(),
            'album_id'=>$_GPC['albumid'],
            'duration'=>audiolength($_GPC['audio']),
            'isfree'=>$_GPC['esisfree']
        );

        #2019-2-14 更新 添加wx_keyword_match表的信息
        $keyworddata=array(
            'parent_id'=>$_GPC['albumid'],
            'son_id'=>'',
            'name'=>trim($_GPC['name']),
            'isparent'=>0,
            'priority'=>2
        );

        if (empty($id)){
            $res=M('album_episode')->add($espdata);
            $keyworddata['son_id']=$res;
            $keywordres=M('keyword_match')->add($keyworddata);

        }else{
            $res=M('album_episode')->where(array('id'=>$id))->save($espdata);
            $keyworddata['son_id']=$id;
            $keywordres=M('keyword_match')->where(array('son_id'=>$id,'priority'=>2,'isparent'=>0))->save(array('name'=>trim($_GPC['name'])));


        }

       
        if (!$res){
            $flag=-1;
        }
        $this->ajaxReturn(array('status'=>$flag),json);
    }
}