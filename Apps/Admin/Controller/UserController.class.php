<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class UserController extends Controller {
    public $_GPC=array();

    public function _initialize(){
        $this->_GPC=array_merge($_POST,$_GET);
    }

    public function index()
	{
		if(session('username') != null)
		{

	        $_GPC=array_merge($_POST,$_GET);
            $public=new ApiPublicController();
	        $Model = new Model();
            $countsql="select b.openid  from wx_user as a left join wx_order as b on a.openid = b.openid where 1  ";
            $condition='';
            $keyword='';
            if (!empty($_GPC['keyword'])){
                $keyword=$_GPC['keyword'];
                $condition.=" AND  ( a.nickname like  '%$keyword%' OR b.mobile like '%$keyword%' ) ";
             }


            $count = $Model->query($countsql.$condition.' group by b.openid ');
			$Page = new \Think\Page(count($count),10);
			$show = $Page->show();
            $firstRow=$Page->firstRow;
            $listRows=$Page->listRows;

            $order='';
            if (!empty($_GPC['ordertype'])){
                switch (trim($_GPC['ordertype'])){
                    case 'name':
                        $order.= ' gender desc , CONVERT(a.nickname using gbk) collate gbk_chinese_ci asc ';
                        break;
                    case 'province':
                        $order .='  CONVERT(a.province using gbk) collate gbk_chinese_ci asc ';
                        break;
                    case 'city':
                        $order .='  CONVERT(a.city using gbk) collate gbk_chinese_ci asc ';
                        break;
                    default :
                        $order.=' a.id desc ';
                }
            }else{
                $order.=' a.id desc ';
            }


            $sql=" select a.* from wx_user as a left join wx_order as b on a.openid=b.openid  where 1 ".$condition." group by b.openid  order by {$order} limit {$firstRow},{$listRows} ";
            $list = $Model->query($sql);
            foreach ($list as &$row){
                $ordermobile=M('order')->where(array('openid'=>$row['openid']))->field('mobile')->order('id desc ')->find();
                $row['mobile']=$ordermobile['mobile'];
                $row["nickname"]=rawurldecode( $row["nickname"]);
            }
            unset($row);

            $this->assign('keyword',$keyword);
			$this->assign('count',$count[0]["total"]);
			$this->assign('list',$list);
			$this->assign('page',$show);
            $this->assign('empty','<tr><td colspan=6>暂时没有数据</td></tr>');
            $this->display();

		}
    }

    #ajax 获取用户信息
    public function getinfo(){
        $_GPC=$this->_GPC;
        if (!empty($_GPC['id'])){
            $userinfo=M('user')->where(array('id'=>trim($_GPC['id'])))->find();
            $this->ajaxReturn(array('status'=>-1,'info'=>$userinfo),json);

        }else{
            $this->ajaxReturn(array('status'=>-1),json);
        }

    }
    #AJAX 更新和添加备注
    public function editremark(){
        $_GPC=$this->_GPC;
        $id=trim($_GPC['id']);
        $remark=trim($_GPC['remark']);
        if (!empty($id) && !empty($remark)){
            $res=M('user')->where(array('id'=>$id))->save(array('remark'=>$remark));
            $this->ajaxReturn(array('status'=>$res),json);
        }

    }

    #用户标签
    public function tags(){
        $_GPC=$this->_GPC;
        $list=M('user_tags')->select();
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
            $taginfo=M('user_tags')->where(array('id'=>trim($_GPC['id'])))->find();
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
            $res=M('user_tags')->where(array('id'=>$id))->save($data);
        }else{
            $res=M('user_tags')->add($data);
        }

        $this->ajaxReturn(array('status'=>$res),json);
    }
    #删除标签
    public function deletetag(){
        $_GPC=$this->_GPC;
        $id=$_GPC['id'];
        if (!empty($id)){
            $res=M('user_tags')->where(array('id'=>$id))->delete();
            if ($res){
                $this->ajaxReturn(array('status'=>1),json);

            }else{
                $this->ajaxReturn(array('status'=>-1),json);

            }
        }else{
            $this->ajaxReturn(array('status'=>-1),json);
        }
    }

    #用户消费信息
    public function userconsumption(){
        $_GPC=$this->_GPC;
       
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

        $countsql="select b.openid  from wx_user as a left join wx_order as b on a.openid = b.openid where 1  ";
        $condition='';
        $keyword='';
        $usertags=trim($_GPC['usertags']);
        if (!empty($_GPC['keyword'])){
            $keyword=$_GPC['keyword'];
            $condition.=" AND  ( a.nickname like  '%$keyword%' OR b.mobile like '%$keyword%' ) ";
            $searchwords['keyword']=$keyword;
        }
        if (!empty($_GPC['usertags'])){
            $usertags=trim($_GPC['usertags']);
            $condition .= " AND a.tagid like '%,{$usertags},%' ";
            $searchwords['usertags']=$usertags;
        }
        if (!empty($_GPC['provincename'])){
            $provincename=str_replace('省','',trim($_GPC['provincename']));
            $condition .= " AND a.province like '%{$provincename}%' ";
            $searchwords['provincename']=$provincename;
        }
        if (!empty($_GPC['cityname'])){
            $cityname=str_replace('市','',trim($_GPC['cityname']));
            $condition .= " AND a.city like '%{$cityname}%' ";
            $searchwords['cityname']=$cityname;
        }
        if (!empty($_GPC['areaname'])){
            $areaname=str_replace('区','',trim($_GPC['areaname']));
            $condition .= " AND a.area like '%{$areaname}%' ";
            $searchwords['areaname']=$areaname;
        }



        $count = $Model->query($countsql.$condition.' group by b.openid ');
        $Page = new \Think\Page(count($count),10);
        $show = $Page->show();
        $firstRow=$Page->firstRow;
        $listRows=$Page->listRows;

        $order='';
       
        if (!empty($_GPC['ordertype'])){
            switch (trim($_GPC['ordertype'])){
                case 'authtime':
                    $order.= ' a.authtime desc  ';
                    break;
                case 'province':
                    $order .='  CONVERT(a.province using gbk) collate gbk_chinese_ci asc ';
                    break;
                case 'city':
                    $order .='  CONVERT(a.city using gbk) collate gbk_chinese_ci asc ';
                    break;
                case 'area':
                    $order .='  CONVERT(a.area using gbk) collate gbk_chinese_ci asc ';
                    break;
                default :
                    $order.=' a.id desc ';
            }
        }else{
            $order.=' a.id desc ';
        }

        $sql="select a.* from wx_user as a left join wx_order as b on a.openid=b.openid  where 1 ".$condition."  group by b.openid  order by {$order} limit {$firstRow},{$listRows} ";
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

        foreach ($list as &$row){
            $ordermobile=M('order')->where(array('openid'=>$row['openid']))->field('mobile')->order('id desc ')->find();
            $row['mobile']=$ordermobile['mobile'];
            //2019-02-20昵称
            $row["nickname"]=rawurldecode($row["nickname"]);
        }
        //2019-2-18 手机号不显示 by xd
        unset($row);
        $searcharea=array(
            'procode'=>!empty($_GPC['seachprov']) ? $_GPC['seachprov'] : 0,
            'citycode'=>!empty($_GPC['homecity']) ? $_GPC['homecity'] : 0,
            'areacode'=>!empty($_GPC['seachdistrict']) ? $_GPC['seachdistrict'] : 0,
        );


        $this->assign('searcharea',$searcharea);
        $this->assign('searchwords',$searchwords);
        $this->assign('ordertype',trim($_GPC['ordertype']));

        $this->assign('list',$list);
        $this->assign('pages',$show);
        $this->assign('keyword',$keyword);
        $this->assign('usertags',$usertags);
        $this->assign('tags',$tags);
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


    #todo 2019-1-8 用户评论
    public function comment(){

        $_GPC=$this->_GPC;
        $Model=new Model();
        #todo 联表 评论表:wx_comment,用户表:wx_user,专辑表:ws_album.专辑的分集表:wx_album_episode
        $condition=" ";
        $keyword='';
        if (!empty($_GPC['keyword'])){
            $keyword=$_GPC['keyword'];
            $condition.=" AND  ( u.nickname like  '%$keyword%' ) ";
        }
        $countsql="SELECT count(c.id) as total FROM wx_comment as c LEFT JOIN wx_user as u on c.openid=u.openid LEFT JOIN wx_album as al on c.album_id=al.id LEFT JOIN wx_album_episode as ep on c.album_epsid=ep.id where 1  ";

        $count = $Model->query($countsql.$condition);

        $Page = new \Think\Page($count[0]["total"],10);
        $show = $Page->show();
        $firstRow=$Page->firstRow;
        $listRows=$Page->listRows;


        $sql="SELECT c.*,u.nickname,u.avatar,al.name as albumname,ep.name as episodename FROM wx_comment as c LEFT JOIN wx_user as u on c.openid=u.openid LEFT JOIN wx_album as al on c.album_id=al.id LEFT JOIN wx_album_episode as ep on c.album_epsid=ep.id where 1  ";

        $order='';
        if (!empty($_GPC['ordertype'])){
            switch (trim($_GPC['ordertype'])){
                case 'comefrom':
                    $sql="SELECT c.*,u.nickname,u.avatar,al.name as albumname,ep.name as episodename FROM wx_comment as c LEFT JOIN wx_user as u on c.openid=u.openid LEFT JOIN wx_album as al on c.album_id=al.id LEFT JOIN wx_album_episode as ep on c.album_epsid=ep.id LEFT JOIN (select *,count(*) as count from wx_comment group by album_id ) as b on c.album_epsid=b.album_epsid where 1 ";
                    $order.= ' b.count desc,c.time desc ';
                    break;
                case 'createtime':
                    $order .=' c.time desc ';
                    break;

                default :
                    $order.=' c.time desc ';
            }
        }else{
            $order.=' c.time desc  ';
        }

        $sql.=$condition." order by {$order} limit {$firstRow},{$listRows} ";
        $list=$Model->query($sql);
        foreach ($list as &$item) {
            $item['time']=date('Y-m-d H:i:s',$item['time']);
            $item["nickname"]=rawurldecode($item["nickname"]);
        }
        unset($item);

        $this->assign('ordertype',trim($_GPC['ordertype']));
        $this->assign('keyword',$keyword);
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    #todo 2019-3-4 评论回复
    public function commentreplylist(){
        $_GPC=$this->_GPC;
        $Model=new Model();
        $id=trim($_GPC['id']);
        if (!empty($id)){
            $sql="SELECT a.*,b.avatar,b.nickname FROM wx_comment_reply as a left join wx_user as b on a.openid=b.openid where comment_id={$id} ";
            $res=$Model->query($sql);
            foreach ($res as &$row){
                $row['time']=date('Y-m-d H:i:s',$row['time']);
                if (empty($row['openid'])){
                    $row['nickname']='系统回复';
                }
            }
        }
        $this->assign('list',$res);
        $this->display();
    }
    public function replydeleted(){
        $_GPC=$this->_GPC;
        $id=$_GPC['replyid'];
        if (!empty($id) ){
            $res=M('comment_reply')->where(array('id'=>$id))->delete();

            if ($res){
                $flag=1;
            }else{
                $flag=-1;
            }
        }else{
            $flag=-1;
        }
        $this->ajaxReturn(array('status'=>$flag),json);
    }

    #保存评论回复
    public function savecommentreply(){
        $_GPC=$this->_GPC;
        $id=$_GPC['commentid'];
        $content=$_GPC['content'];

        if (!empty($id) && !empty($content)){

            $data=array(
                'comment_id'=>$id,
                'content'=>$content,
                'time'=>time()
            );

            $res=M('comment_reply')->add($data);
            if ($res){
                $flag=1;
            }else{
                $flag=-1;
            }
        }else{
            $flag=-1;
        }
        $this->ajaxReturn(array('status'=>$flag),json);

    }
    #删除评论
    public function commentdeleted(){
        $_GPC=$this->_GPC;
        $id=$_GPC['commentid'];

        if (!empty($id) ){
            $res=M('comment')->where(array('id'=>$id))->delete();

            if ($res){
                $flag=1;
            }else{
                $flag=-1;
            }
        }else{
            $flag=-1;
        }
        $this->ajaxReturn(array('status'=>$flag),json);

    }

	public function loginout(){
        session('username',null);
		$url="/admin/";
		$this->redirect($url);
    }
	
}