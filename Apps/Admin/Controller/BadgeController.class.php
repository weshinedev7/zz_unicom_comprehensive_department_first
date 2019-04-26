<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class BadgeController extends Controller {
    public $_GPC = array();

    public function _initialize(){
        $this->_GPC = array_merge($_POST, $_GET);
    }

    public function index(){
        $_GPC=$this->_GPC;
        $Model=new Model();

        $sql="select * from wx_badge where 1  ";
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
        $sql="select * from wx_badge where 1 {$condition}  order by id desc  limit {$firstRow},{$listRows} ";
        $list=$Model->query($sql);

        foreach ($list as &$row){
            $tempcount=$Model->query('select count(*) as `count` from wx_user_badge where badgeid='.$row['id'].' ');
            $row['count']=$tempcount[0]['count'];
            $row['icon']='https://'.$_SERVER['SERVER_NAME'].'/'.$row['icon'];
            unset($tempcount);
        }
        $this->assign('keyword',$keyword);
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display();
    }

    public function postbadge(){

        $_GPC=$this->_GPC;
        $Model=new Model();
        $id=trim($_GPC['id']);
        if (!empty($id)){
            $badge=M('badge')->where("id=$id")->find();
            $badge['rule']=unserialize( $badge['rule']);

            if ($badge['rule_id']==1 && !empty($badge['rule']['album'])){

                $badge_album=$badge['rule']['album'];
                $sesps=M('album_episode')->where('id in ( '.implode(',',$badge_album['sespids']).')')->select();

                $albuminfo=M('album')->where('id='.$sesps[0]['album_id'])->field('id,name')->find();

                $esps=M('album_episode')->where('album_id ='.$albuminfo['id'])->select();
                $content='';
                foreach ($esps as $row){
                    if (in_array($row['id'],$badge_album['sespids'])){
                        $content.='<label class="middle"><input class="ace"  name="espids" value="'.$row['id'].'" type="checkbox" id="'.$row['id'].'" checked="checked"><span class="lbl">'.$row['name'].'</span></label><br>';

                    }else{
                        $content.='<label class="middle"><input class="ace"  name="espids" value="'.$row['id'].'" type="checkbox" id="'.$row['id'].'"><span class="lbl">'.$row['name'].'</span></label><br>';

                    }
                }
                #专辑信息
                $this->assign('content',$content);
                $this->assign('albuminfo',$albuminfo);

            }

        }
        #获取条件需要的前置
        $allbadge=M('badge')->select();
        #获取每日科学
        $science=$Model->query('SELECT a.id,s.name FROM wx_science_audio as a left join wx_science as s on a.science_id=s.id ' );
        $this->assign('science',$science);
        #获取所有专辑
        $album=M('album')->field('id,name')->select();

        $category=M('badge_category')->select();
        $this->assign('category',$category);
        $this->assign('album',$album);
        $this->assign('allbadge',$allbadge);
        $this->assign('id',$id);
        $this->assign('data',$badge);
        $this->display();
    }
    #todo 2019-01-16 新增徽章 by xd
    public function addbadge(){
        $_GPC=$this->_GPC;
        $data=array(
            'name'=>$_GPC['name'],
            'icon'=>$_GPC['icon'],
            'grey_icon'=>$_GPC['icon_grey'],
            'price'=>$_GPC['price'],
            'rule_content'=>$_GPC['rule_content'],
            'rule_id'=>$_GPC['type'],
            'rule'=>serialize($_GPC['rule']),
            'cate_id'=>$_GPC['cate_id']
        );
        $res=M('badge')->add($data);
        if ($res){
            $flag=1;
        }else{
            $flag=-1;
        }
        $this->ajaxReturn(array('status'=>$flag),json);
    }
    public function getesps(){
        $_GPC=$this->_GPC;
        $id=$_GPC['albumid'];
        $content='';
        if (!empty($id)){
            $esps=M('album_episode')->where(array('album_id'=>$id))->select();

            if ($esps){
                foreach ($esps as $row){
                    $content.='<label class="middle"><input class="ace"  name="espids" value="'.$row['id'].'" type="checkbox" id="'.$row['id'].'"><span class="lbl">'.$row['name'].'</span></label><br>';
                }

                $flag=1;
            }else{
                $flag=-1;
            }
        }else{
            $flag=-1;
        }
        $this->ajaxReturn(array('status'=>$flag,'esps'=>$content),json);

    }

    public function savecoupon(){
        $_GPC=$this->_GPC;
        $data=array(
            'album_id'=>$_GPC['album_id'],
            'preferential'=>$_GPC['preferential'],
            'discount'=>$_GPC['discount'],
            'validity'=>strtotime($_GPC['validity'].' 23:59:59'),
            'number'=>$_GPC['number'],
            'full'=>$_GPC['full'],
            'rest'=>$_GPC['number'],
        );
        if ($_GPC['validity']=='NAN'){
            $data['validity']=$_GPC['validity'];
        }else{
            $data['validity']=strtotime($_GPC['validity'].' 23:59:59');
        }

        $res=M('coupons')->add($data);
        if ($res){
            $this->ajaxReturn(array('status'=>1),json);

        }else{
            $this->ajaxReturn(array('status'=>-1),json);

        }

    }

    public function savebadge(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        $data=array(
            'name'=>$_GPC['name'],
            'cover'=>$_GPC['icon'],
            'grey_icon'=>$_GPC['icon_grey'],
            'price'=>$_GPC['price'],
            'rule_content'=>$_GPC['rule_content'],
            'rule_id'=>$_GPC['type'],
            'rule'=>serialize($_GPC['rule']),
            'cate_id'=>$_GPC['cate_id']
        );
        if (empty($id)){
            $res=M('badge')->add($data);
        }else{
            $res=M('badge')->where("id=$id")->save($data);
        }
        if ($res){
            $flag=1;
        }else{
            $flag=-1;
        }
        $this->ajaxReturn(array('status'=>$flag),json);
    }

    public function deletecoupon(){
        $_GPC=$this->_GPC;
        $id=$_GPC['id'];
        if (!empty($id)){
            $res=M('coupons')->where(array('id'=>$id))->delete();
            if ($res){
                $this->ajaxReturn(array('status'=>1),json);

            }else{
                $this->ajaxReturn(array('status'=>-1),json);

            }
        }else{
            $this->ajaxReturn(array('status'=>-1),json);
        }
    }

    public function detail(){
        $_GPC=$this->_GPC;
        $badgeinfo=M('badge')->where('id='.trim($_GPC['id']))->find();

        #todo 可以一次性提取的信息 头像 昵称 手机号 获得时间 是否兑换
        $Model=new Model();
        $keyword='';
        $condition=" and a.badgeid={$badgeinfo['id']} ";
        if (!empty($_GPC['keyword'])){
            $keyword=trim($_GPC['keyword']);
            $condition.=" AND  ( u.nickname like  '%$keyword%' OR o.mobile like '%$keyword%') ";

        }
        $countsql="select a.*,u.avatar,u.nickname,o.mobile,o.paytime from wx_user_badge as a left join wx_user as u on a.openid=u.openid left join wx_order as o on o.openid=u.openid where 1 {$condition} group by o.openid  ";

        $count = $Model->query($countsql);

        $Page = new \Think\Page(count($count),10);
        $show = $Page->show();
        $firstRow=$Page->firstRow;
        $listRows=$Page->listRows;



        $order='';

        if (!empty($_GPC['ordertype'])){
            switch (trim($_GPC['ordertype'])){
                case 'gettime':
                    $order.= ' a.createtime desc  ';
                    break;
                case 'exchange':
                    $order .=' a.status desc ';
                    break;
                case 'paytime':
                    $order .='  o.paytime desc ';
                    break;
                default :
                    $order.=' a.id desc ';
            }
        }else{
            $order.=' a.id desc ';
        }
        $sql="select a.*,u.avatar,u.nickname,o.mobile,o.paytime,a.openid from wx_user_badge as a left join wx_user as u on a.openid=u.openid left join wx_order as o on o.openid=u.openid where 1 {$condition} group by o.openid  order by {$order}  limit {$firstRow},{$listRows} ";


        //$sql="select * from wx_user  where 1 ".$condition." order by {$order} limit {$firstRow},{$listRows} ";
        $list = $Model->query($sql);
        foreach ($list as &$row){
            $temp=M('order')->where(' openid="'.$row['openid'].'" and status>1 and type=3 and goodsid='.$badgeinfo['id'] .' ')->find();

            $row['createtime']=!empty($row['createtime']) ? date('Y-m-d H:i:s',$row['createtime']) : '';
            if (!empty($temp)){
                $row['paytime']=date('Y-m-d H:i:s',$temp['paytime']);
            }else{
                $row['paytime']='';
            }
        }

        $this->assign('badgeinfo',$badgeinfo);
        $this->assign('ordertype',trim($_GPC['ordertype']));
        $this->assign('list',$list);
        $this->assign('pages',$show);
        $this->assign('keyword',$keyword);

        $this->display();
    }
    #保存修改的标签
    public function saveusertags(){
        $_GPC=$this->_GPC;
        if(!empty($_GPC['tagids']) && !empty($_GPC['userids'])){
            foreach ($_GPC['userids'] as $row ){
                $tags=','.trim(implode($_GPC['tagids'],','),',').',';
                $res=M('user')->where(array('id'=>$row))->save(array('tagid'=>$tags));
            }
            if ($res){
                $flag=1;
            }else{
                $flag=1;
            }
        }else{
            $flag=-1;
        }
        $this->ajaxReturn(array('status'=>$flag),json);

    }

    public function sendcoupons(){
        $_GPC=$this->_GPC;
        $couponid=trim($_GPC['couponid']);
        foreach ($_GPC['userids'] as $row){
            $tempinfo=M('user')->where(array('id'=>trim($row)))->find();
            if (!empty($tempinfo)){
                $data=array(
                    'openid'=>$tempinfo['openid'],
                    'coupons_id'=>$couponid,
                    'state'=>1
                );
                $res= M('user_coupons')->add($data);
                unset($data);
                unset($tempinfo);
            }
        }
        if ($res){
            $this->ajaxReturn(array('status'=>1),json);
        }else{
            $this->ajaxReturn(array('status'=>-1),json);

        }

    }
    //删除徽章
    public function delete(){
        $_GPC=$this->_GPC;
        $id=$_GPC['id'];
        if (!empty($id)){
            $res=M('badge')->where(array('id'=>$id))->delete();
            if ($res){
                $this->ajaxReturn(array('status'=>1),json);

            }else{
                $this->ajaxReturn(array('status'=>-1),json);

            }
        }else{
            $this->ajaxReturn(array('status'=>-1),json);
        }
    }

    #todo 2019-3-4新增徽章类别
    public function category(){
        $_GPC=$this->_GPC;
        $list=M('badge_category')->select();
        foreach ($list as &$row){
            $row['updatetime']=date('Y-m-d H:i:s',$row['updatetime']);
            switch ($row['rule_id']){
                case 1:
                    $row['rulename']='收听完成';
                    break;
                case 2:
                    $row['rulename']='购买成功';
                    break;
                case 3:
                    $row['rulename']='推广类订单数量';
                    break;
                case 4:
                    $row['rulename']='收听时长';
                    break;
                case 5:
                    $row['rulename']='使用天数';
                    break;
                case 6:
                    $row['rulename']='获取其他徽章';
                    break;

            }
        }

        $this->assign('list',$list);
        $this->display();
    }
    public function editcate(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        $data=array(
            'name'=>trim($_GPC['tagname']),
            'updatetime'=>time()
        );
        if (!empty($id)){
            $res=M('badge_category')->where(array('id'=>$id))->save($data);
        }else{
            $res=M('badge_category')->add($data);
        }
        $this->ajaxReturn(array('status'=>$res),json);
    }
    public function deletecate(){

            $_GPC=$this->_GPC;
            $id=$_GPC['id'];
            if (!empty($id)){
                $res=M('badge_category')->where(array('id'=>$id))->delete();
                if ($res){
                    $this->ajaxReturn(array('status'=>1),json);

                }else{
                    $this->ajaxReturn(array('status'=>-1),json);

                }
            }else{
                $this->ajaxReturn(array('status'=>-1),json);
            }

    }
    public function getcateinfo(){
        $_GPC=$this->_GPC;
        if (!empty($_GPC['id'])){
            $taginfo=M('badge_category')->where(array('id'=>trim($_GPC['id'])))->find();
            switch ($taginfo['rule_id']){
                case 1:
                    $taginfo['rulename']='收听完成';
                    break;
                case 2:
                    $taginfo['rulename']='购买成功';
                    break;
                case 3:
                    $taginfo['rulename']='推广类订单数量';
                    break;
                case 4:
                    $taginfo['rulename']='收听时长';
                    break;
                case 5:
                    $taginfo['rulename']='使用天数';
                    break;
                case 6:
                    $taginfo['rulename']='获取其他徽章';
                    break;

            }

            $this->ajaxReturn(array('status'=>-1,'info'=>$taginfo),json);

        }else{
            $this->ajaxReturn(array('status'=>-1),json);
        }
    }
}