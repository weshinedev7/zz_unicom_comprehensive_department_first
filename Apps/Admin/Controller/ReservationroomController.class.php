<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;


class ReservationroomController extends Controller{

    public $_GPC = array();
    public function _initialize(){
        header("Content-Type:text/html; charset=utf-8");
        $this->_GPC = array_merge($_POST, $_GET);
    }

    // 预约会议室
    public function index(){

        // 判断是否登录
        if(session('username') !=null){

            $_GPC=$this->_GPC;
            var_dump(111111111);
            var_dump($_GPC);die;
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

                    $row['createtime']=date('Y-m-d',$row['createtime']);
                    $row['rebooktime']=date('Y-m-d',$row['rebooktime']);
                }
            }



            $this->assign('keyword',$keyword);
            $this->assign('page',$show);
            $this->assign('list',$list);
            $this->display();
        }
    }
}