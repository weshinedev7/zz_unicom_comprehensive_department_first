<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
define('APP_DEBUG',true);
class CouponsController extends Controller {
    public $_GPC = array();

    public function _initialize(){
        $this->_GPC = array_merge($_POST, $_GET);
    }

    public function index(){
        $_GPC=$this->_GPC;
        $Model=new Model();
        $coupons=M('coupons')->select();

        $Page = new \Think\Page(count($coupons),10);
        $show = $Page->show();
        $firstRow=$Page->firstRow;
        $listRows=$Page->listRows;

        $sql="select * from wx_coupons  order by id desc  limit {$firstRow},{$listRows} ";
        $list=$Model->query($sql);
        foreach ($list as &$row){
            if (!empty($row['album_id'])){
                $temo=M('album')->where(array('id'=>$row['album_id']))->find();
                $row['albuminfo']=$temo['name'];
                unset($temo);
            }else{
                $row['albuminfo']='全专辑通用';
            }
            if ($row['validity']!='NAN'){
                $row['validityinfo']='至 '.date('Y-m-d',$row['validity']);
            }else{
                $row['validityinfo']='永久有效';
            }
            if ($row['number']!='NAN'){
                $row['numberinfo']=$row['number']. '张' ;
                $row['restinfo']=$row['rest']. '张';
            }else{
                $row['numberinfo']='无限量';
                $row['restinfo']='无限量';
            }
            if ($row['full']>0){
                $row['fullinfo']='满 '.$row['full'].' 可用';
            }else{
                $row['fullinfo']='无门槛';
            }


        }

        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display();
    }

    public function postcoupon(){
        $_GPC=$this->_GPC;
        $allalbums=M('album')->field('id,name')->select();


        $this->assign('albums',$allalbums);
        $this->display();
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

        if ($_GPC['full']=='NAN'){
            $data['full']=0;
        }else{
            $data['full']=$_GPC['full'];
        }

        $res=M('coupons')->add($data);
        if ($res){
            $this->ajaxReturn(array('status'=>1),json);

        }else{
            $this->ajaxReturn(array('status'=>-1),json);

        }

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

        $couponid=trim($_GPC['couponid']);
        $couponinfo=M('coupons')->where(array('id'=>$couponid))->find();
        if (!empty($couponinfo['album_id'])){
            $temo=M('album')->where(array('id'=>$couponinfo['album_id']))->find();
            $couponinfo['albuminfo']=$couponinfo['name'];
            unset($temo);
        }else{
            $couponinfo['albuminfo']='全专辑通用';
        }
        if ($couponinfo['validity']!='NAN'){
            $couponinfo['validityinfo']='至 '.date('Y-m-d',$couponinfo['validity']);
        }else{
            $couponinfo['validityinfo']='永久有效';
        }
        if ($couponinfo['number']!='NAN'){
            $couponinfo['numberinfo']=$couponinfo['number']. '张' ;
            $couponinfo['restinfo']=$couponinfo['rest']. '张';
        }else{
            $couponinfo['numberinfo']='无限量';
            $couponinfo['restinfo']='无限量';
        }
        if ($couponinfo['full']>0){
            $couponinfo['fullinfo']='满 '.$couponinfo['full'].' 可用';
        }else{
            $couponinfo['fullinfo']='无门槛';
        }

        $Model=new Model();
        #获取标签
        $tagstemp=M('user_tags')->select();
        $tags=array();
        foreach ($tagstemp as $row){
            $tags[$row['id']]=$row;
            unset($row);
        }
        #排序时候需要传入的所有搜索条件
        $searchwords=array();
        $tableb='select openid,count(*) as countorder from wx_order where `status` >1 and deleted=0 GROUP BY openid';
        $countsql="select u.*,b.`countorder` from wx_user as u left join ({$tableb}) as b on u.openid=b.openid COLLATE utf8_unicode_ci";

        //$countsql="select count(id) as total from wx_user where 1 ";
        $condition='';
        $keyword='';
        $usertags=trim($_GPC['usertags']);
        if (!empty($_GPC['keyword'])){
            $keyword=$_GPC['keyword'];
            $condition.=" AND  ( u.nickname like  '%$keyword%' ) ";
            $searchwords['keyword']=$keyword;
        }
        if (!empty($_GPC['usertags'])){
            $usertags=trim($_GPC['usertags']);
            $condition .= " AND u.tagid like '%,{$usertags},%' ";
            $searchwords['usertags']=$usertags;
        }

        $count = $Model->query($countsql.$condition);
        $Page = new \Think\Page(count($count),10);
        $show = $Page->show();
        $firstRow=$Page->firstRow;
        $listRows=$Page->listRows;

        $order='';
        if (!empty($_GPC['ordertype'])){
            switch (trim($_GPC['ordertype'])){
                case 'ordercount':
                    $order.= ' b.countorder desc  ';
                    break;
                case 'system':
                    $order .='  CONVERT(u.systemtype using gbk) collate gbk_chinese_ci desc ,CONVERT(u.nickname using gbk) collate gbk_chinese_ci desc ';
                    break;
                default :
                    $order.=' u.id desc ';
            }
        }else{
            $order.=' u.id desc ';
        }
        $sql="select u.*,b.`countorder` from wx_user as u left join ({$tableb}) as b on u.openid=b.openid COLLATE utf8_unicode_ci  where 1 ".$condition." order by {$order} limit {$firstRow},{$listRows} ";
        //$sql="select * from wx_user  where 1 ".$condition." order by {$order} limit {$firstRow},{$listRows} ";
        $list = $Model->query($sql);

        foreach ($list as &$row){
            $sumprice=$Model->query("SELECT sum(realprice) as sumprice FROM wx_order where openid='{$row['openid']}' and status>1 group by openid ");
            if (empty($sumprice)){
                $row['sumprice']=round(0,2);
            }else{
                $row['sumprice']=$sumprice[0]['sumprice'];
            }
            if (!empty($row['tagid'])){

                $tempids=explode(',',trim($row['tagid'],','));
                foreach ($tags as $v){
                    if (in_array($v['id'],$tempids)){
                        $row['tagname'].=$v['name'].',';
                    }
                }
                $row['tagname']=trim($row['tagname'],',');
                unset($tempids);
            }else{
                $row['tagname']='暂无标签';
            }

            $row['authtime']=date('Y-m-d H:i:s',$row['authtime']);
            $row['countorder']=empty($row['countorder']) ? 0 : $row['countorder'];
            unset($sumprice);
        }


        if ($_GPC['ordertype']=='price'){

            $arrSort = array();
            $sort = array(
                'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                'field'     => 'sumprice',       //排序字段
            );

            foreach($list AS $uniqid => $v){

                foreach($v AS $key=>$value){
                    $arrSort[$key][$uniqid] = $value;
                }
            }

            if($sort['direction']){
                array_multisort($arrSort[$sort['field']], constant($sort['direction']), $list);
            }
        }

        $this->assign('couponid',$couponid);
        $this->assign('couponinfo',$couponinfo);

        $this->assign('searchwords',$searchwords);
        $this->assign('ordertype',trim($_GPC['ordertype']));

        $this->assign('list',$list);
        $this->assign('pages',$show);
        $this->assign('keyword',$keyword);
        $this->assign('usertags',$usertags);
        $this->assign('tags',$tags);
        $this->display("detail");
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
        $couponsinfo=M('coupons')->where('id='.$couponid)->find();

        if ($couponsinfo['number']!='NAN') {
            if (count($_GPC['userids']) > $couponsinfo['number']) {
                $this->ajaxReturn(array( 'status' => -2 ), json);
                exit();
            }
        }

        $i=0;
        foreach ($_GPC['userids'] as $row){

            $tempinfo=M('user')->where(array('id'=>trim($row)))->find();

            if (!empty($tempinfo)){
                $data=array(
                    'openid'=>$tempinfo['openid'],
                    'coupons_id'=>$couponid,
                    'gettime'=>time(),
                    'state'=>1
                );
                $res=M('user_coupons')->add($data);

               if ($res){
                   $i++;
               }

                unset($data);
                unset($tempinfo);
            }
        }

        if ($couponsinfo['number']!='NAN'){
            $res= M('coupons')->where('id='.$couponid)->save(array('number'=>($couponsinfo['number']-$i)));
        }



        if ($res){
            $this->ajaxReturn(array('status'=>1),json);
        }else{
            $this->ajaxReturn(array('status'=>-1),json);
        }

    }
}