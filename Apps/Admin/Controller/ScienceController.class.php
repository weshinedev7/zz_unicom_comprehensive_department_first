<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class ScienceController extends Controller {
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

    public function postscience(){
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

    public function savescience(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        $audioid=trim($_GPC['audioid']);
        $data=array(
            'name'=>trim($_GPC['name']),
            'cover'=>trim($_GPC['cover']),
            'price'=>trim($_GPC['price']),
            'content'=>$_GPC['content'],
            'time'=>time(),
            'introduction'=>$_GPC['introduction'],
            'videopath'=>$_GPC['video']
        );
        $flag=1;
        #2019-2-14 更新 添加wx_keyword_match表的信息
        $keyworddata=array(
            'parent_id'=>'',
            'son_id'=>0,
            'name'=>trim($_GPC['name']),
            'isparent'=>1,
            'priority'=>1
        );

        if (empty($id)){
            $res=M('science')->add($data);
            if ($res){
                $id=$res;
            }else{
                $flag=-1;
            }
            $keyworddata['parent_id']=$res;
            $keywordres=M('keyword_match')->add($keyworddata);
        }elseif (!empty($id)){
            $res=M('science')->where(array('id'=>$id))->save($data);
            if (!$res){
                $flag=-1;
            }
            $keyworddata['parent_id']=$id;
            $keywordres=M('keyword_match')->where(array('parent_id'=>$id,'priority'=>1,'isparent'=>1))->save(array('name'=>trim($_GPC['name'])));

        }



        $audiodata=array(
            'filepath'=>trim($_GPC['audio'],'/'),
            'updatetime'=>time(),
            'science_id'=>$id,
            'duration'=>audiolength($_GPC['audio'])
        );
        if ($data['price']>0){
            $audiodata['isfree']=0;
        }else{
            $audiodata['isfree']=1;
        }
        
        if (empty($audioid)){
            $res2=M('science_audio')->add($audiodata);
        }else{
            $res2=M('science_audio')->where(array('id'=>$audioid))->save($audiodata);
        }
        if (!$res2){
            $flag=-1;
        }

        $this->ajaxReturn(array('flag'=>$flag),json);

    }

    public function deletescience(){
        $_GPC=$this->_GPC;
        $id=$_GPC['id'];
        if (!empty($id)){
            $res=M('science')->where(array('id'=>$id))->delete();
            M('science_audio')->where(array('science_id'=>$id))->delete();
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