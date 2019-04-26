<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Think\Exception;


class IndexController extends Controller{

    public function index()
    {

    }

    /*
     * 获取广告
     * */
    public function Banner()
    {
        $Model = new Model();
        $list=$Model->query('select * from wx_banner order by id asc');
        $this->ajaxReturn($list,json);
    }

    public function GetActiveCate(){
        $Model = new Model();
        $list=$Model->query("select * from wx_activecate order by id desc");
        $arr=array("list"=>$list);
        $this->ajaxReturn($arr,json);
    }

    /*
     * 达人排行榜
     * */
    public function GetTopList()
    {
        $Model = new Model();
        $list=$Model->query('select * from wx_user as a left join wx_level as b on a.level=b.id order by a.point DESC limit 10');
        $this->ajaxReturn($list,json);
    }

    public function GetActiveList(){
        $Model = new Model();
        //$list=$Model->query("select * from wx_active where etime>now() order by id desc");
        $list=$Model->query("select * from wx_active where status=1 and ispublic=0 order by id desc");
        for($i=0;$i<count($list);$i++){
            $aid=$list[$i]["id"];

            $sql_real="select count(*) as rcounts from wx_real where activeid=$aid";
            $real=$Model->query($sql_real);

            $sql_vote="select count(*) as vcounts from wx_vote where activeid=$aid";
            $vote=$Model->query($sql_vote);

            $sql_comment="select count(*) as ccounts from wx_comment where activeid=$aid";
            $comment=$Model->query($sql_comment);

            $list[$i]["counts"]=$real[0]["rcounts"]+$vote[0]["vcounts"]+$comment[0]["ccounts"];

        }

        $users=$Model->query("select count(*) as counts from wx_user order by id desc");

        $pindex=$_POST['acpage'];

        $psize=10;
        $newlist=array_chunk($list,$psize);


        $arr=array("list"=>$newlist[$pindex-1],"counts"=>$users[0]["counts"]);
        $this->ajaxReturn($arr,json);
    }

    public function GetActiveHotList(){

        $Model = new Model();
        $list=$Model->query("select * from wx_active where status=1 and ispublic=0 order by id desc");
        for($i=0;$i<count($list);$i++){
            $aid=$list[$i]["id"];

            $curdata=$_POST["curdata"];
            if($curdata==0){
                $sql_real="select count(*) as rcounts from wx_real where activeid=$aid";
                $sql_vote="select count(*) as vcounts from wx_vote where activeid=$aid";
                $sql_comment="select count(*) as ccounts from wx_comment where activeid=$aid";
            }else if($curdata==1){
                $sql_real="select count(*) as rcounts from wx_real where activeid=$aid and DATE_SUB(CURDATE(),INTERVAL 0 DAY) <= DATE(inputtime)";
                $sql_vote="select count(*) as vcounts from wx_vote where activeid=$aid and DATE_SUB(CURDATE(),INTERVAL 0 DAY) <= DATE(inputtime)";
                $sql_comment="select count(*) as ccounts from wx_comment where activeid=$aid and DATE_SUB(CURDATE(),INTERVAL 0 DAY) <= DATE(inputtime)";

            }else if($curdata==2){
                $sql_real="select count(*) as rcounts from wx_real where activeid=$aid and DATE_SUB(CURDATE(),INTERVAL 7 DAY) <= DATE(inputtime)";
                $sql_vote="select count(*) as vcounts from wx_vote where activeid=$aid and DATE_SUB(CURDATE(),INTERVAL 7 DAY) <= DATE(inputtime)";
                $sql_comment="select count(*) as ccounts from wx_comment where activeid=$aid and DATE_SUB(CURDATE(),INTERVAL 7 DAY) <= DATE(inputtime)";
            }else if($curdata==3){
                $sql_real="select count(*) as rcounts from wx_real where activeid=$aid and DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE(inputtime)";
                $sql_vote="select count(*) as vcounts from wx_vote where activeid=$aid and DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE(inputtime)";
                $sql_comment="select count(*) as ccounts from wx_comment where activeid=$aid and DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE(inputtime)";
            }

            $real=$Model->query($sql_real);
            $vote=$Model->query($sql_vote);
            $comment=$Model->query($sql_comment);

            $list[$i]["counts"]=$real[0]["rcounts"]+$vote[0]["vcounts"]+$comment[0]["ccounts"];
        }

            array_multisort(
            array_column($list,'counts'),SORT_DESC,
            array_column($list,'inputtime'),SORT_DESC,
            $list
        );

        $pindex=$_POST['page'];
        $psize=10;
        $newlist=array_chunk($list,$psize);

     /*   foreach($list as $k =>$v){
            if($k>=(($pindex - 1) * $psize) && $k<($psize*$pindex-1)){

            }else{
                unset($list[$k]);
            }
        }*/

        $arr=array("list"=>$newlist[$pindex-1]);
        $this->ajaxReturn($arr,json);
    }

    public function GetMyActiveList(){
        $Model = new Model();
        $openid=$_POST["openid"];
        $list=$Model->query("select * from wx_active where openid='".$openid."' order by id desc");
        $arr=array("list"=>$list);
        $this->ajaxReturn($arr,json);
    }

    public function GetMyVoteList(){
        $Model = new Model();
        $openid=$_POST["openid"];
        $list=$Model->query("select a.*,b.title,b.pic,b.vote1 as bvote1,b.vote2 as bvote2,b.vote3 as bvote3 from wx_vote as a left join wx_active as b on a.activeid=b.id where a.openid='".$openid."' order by a.id desc");
        $arr=array("list"=>$list);
        $this->ajaxReturn($arr,json);
    }

    public function GetMyCommentList(){
        $Model = new Model();
        $openid=$_POST["openid"];

        $list=$Model->query("select a.*,b.title,b.pic as bpic from wx_comment as a left join wx_active as b on a.activeid=b.id where a.openid='".$openid."' order by a.id desc");
        for($i=0;$i<count($list);$i++){
            $list[$i]["content"]= base64_decode($list[$i]["content"]);
            $cid=$list[$i]["id"];
            $fab=$Model->query("select count(*) as counts from wx_fab where cid=$cid");
            $list[$i]["counts"]=$fab[0]["counts"];
        }

        $arr=array("list"=>$list);
        $this->ajaxReturn($arr,json);
    }

    public function GetActiveView(){
        $Model = new Model();
        $id=$_POST["id"];
        $openid=$_POST["openid"];
        $list=$Model->query("select * from wx_active where id=$id");
        $list[0]['content']=str_replace("/Public/",__ROOT__."/Public/", $list[0]['content']);
        if($list[0]["etime"]!="0000-00-00"){
            if(strtotime(date("Y-m-d",time()))<strtotime($list[0]["etime"])){
                $list[0]['status']=0;
            }else{
                $list[0]['status']=1;
            }
        }else{
            $list[0]['status']=0;
        }

        $activeid=$list[0]["id"];
        $vote1=$Model->query("select count(*) as v1counts from wx_vote where activeid=$activeid and vote1=1");
        $vote2=$Model->query("select count(*) as v2counts from wx_vote where activeid=$activeid and vote2=1");
        $vote3=$Model->query("select count(*) as v3counts from wx_vote where activeid=$activeid and vote3=1");

        $list[0]['v1counts']=$vote1[0]["v1counts"];
        $list[0]['v2counts']=$vote2[0]["v2counts"];
        $list[0]['v3counts']=$vote3[0]["v3counts"];
        $list[0]['vcounts']=$vote1[0]["v1counts"]+$vote2[0]["v2counts"]+$vote3[0]["v3counts"];

        $vote=$Model->query("select * from wx_vote where activeid=$activeid and openid='".$openid."'");
        if(count($vote)>0){
            if($list[0]['ismulti']==0){
                if($vote[0]["vote1"]==1){
                    $list[0]['vote']=1;
                } else if($vote[0]["vote2"]==1){
                    $list[0]['vote']=2;
                } else{
                    $list[0]['vote']=3;
                }
            }
            else{
                if($vote[0]["vote1"]==1 ){
                    $list[0]['votename1']=1;
                }
                if($vote[0]["vote2"]==1 ){
                    $list[0]['votename2']=1;
                }
                if($vote[0]["vote3"]==1 ){
                    $list[0]['votename3']=1;
                }
                $list[0]['vote']=0;
            }
        }else{
            $list[0]['vote']=0;
        }

        $arr=array("info"=>$list[0]);
        $this->ajaxReturn($arr,json);
    }

    public function GetSign(){
        $Model = new Model();
        $activeid=$_POST["activeid"];
        $openid=$_POST["openid"];
        $list=$Model->query("select * from wx_sign where activeid=$activeid and openid='".$openid."'");
        $arr=array("sign"=>$list[0]);
        $this->ajaxReturn($arr,json);
    }

    /*
     * 每日签到
     * */
    public function DaySign(){
        $Model = new Model();
        $openid=$_POST["openid"];
        $inputtime = date('Y-m-d',time());
        $check=$Model->query("select * from wx_pointlog where openid='".$openid."' and  inputtime='".$inputtime."'");
        $userinfo=$Model->query("select * from wx_user as a left join wx_level as b on a.level=b.id where a.openid='".$openid."'");

        if(count($check)==0){
            $data["openid"]=$openid;
            $data["point"]=$userinfo[0]["spoint"];
            $data["type"]=0;
            $data["inputtime"] = date('Y-m-d H:i:s',time());
            $pointlog=M("pointlog");
            $pointlog->create($data);
            $pointlog->add($data);

            $d["point"]=$userinfo[0]["point"]+$userinfo[0]["spoint"];
            $level=$Model->query("select * from wx_level where uppoint<=".$d["point"]." order by id desc");
            $d["level"]=$level[0]['id'];

            $user=M("user");
            $user->create($d);
            $result=$user->where("openid='$openid'")->save($d);

            $this->ajaxReturn("0",json);
        }else{
            $this->ajaxReturn("1",json);
        }
    }

    /*
     * 会员中心获取会员相关信息
     * */
    public function GetMyUserInfo(){
        $Model = new Model();
        $openid=$_POST["openid"];
        $inputtime = date('Y-m-d',time());
        $check=$Model->query("select * from wx_pointlog where openid='".$openid."' and  inputtime='".$inputtime."'");
        $userinfo=$Model->query("select * from wx_user as a left join wx_level as b on a.level=b.id where a.openid='".$openid."'");
        $userinfo=$userinfo[0];
        if(count($check)==0){
            $check=0;
        }else{
            $check=1;
        }
        $arr=array("userinfo"=>$userinfo,"check"=>$check);
        $this->ajaxReturn($arr,json);
    }

    /*
     * 我的积分记录
     * */
    public function GetMyPointList(){
        $Model = new Model();
        $openid=$_POST["openid"];
        $list=$Model->query("select * from wx_pointlog where openid='$openid' order by id desc");
        $arr=array("list"=>$list);
        $this->ajaxReturn($arr,json);
    }


    public function GetCommentList(){
        $Model = new Model();
        $activeid=$_POST["id"];
        $list=$Model->query("select * from wx_comment where activeid=$activeid and status=1 order by id desc");
        for($i=0;$i<count($list);$i++){
            $list[$i]["content"]= base64_decode($list[$i]["content"]);
            $cid=$list[$i]["id"];
            $fab=$Model->query("select count(*) as counts from wx_fab where cid=$cid");
            $list[$i]["counts"]=$fab[0]["counts"];
        }

        array_multisort(
            array_column($list,'counts'),SORT_DESC,
            array_column($list,'inputtime'),SORT_DESC,
            $list
        );

        $arr=array("list"=>$list);
        $this->ajaxReturn($arr,json);
    }

    public function Fab(){
        date_default_timezone_set("Asia/Shanghai");
        $Model = new Model();
        $cid=$_POST["cid"];
        $openid=$_POST["openid"];
        $fabs=$Model->query("select id from wx_fab where cid=$cid and openid='".$openid."'");
        $comment=$Model->query("select openid from wx_comment where id=$cid");

        if(count($fabs)==0){
            $this->CheckLog($_POST["openid"],3);
            if(count($fabs)<3){
                $this->CheckLog($comment[0]["openid"],3);
            }
            $data["openid"]=$_POST["openid"];
            $data["cid"]=$_POST["cid"];
            $data["inputtime"] = date('Y-m-d H:i:s',time());
            $fab=M("fab");
            $fab->create($data);
            $fab->add($data);
            $this->ajaxReturn("0",json);
        }else{
            $this->ajaxReturn("1",json);
        }
    }

    /*
     * 删除会员自己的活动列表
     * */
    public function DelActive(){
        $activeid=$_POST["activeid"];
        $Active = M("active");
        $Comment = M("comment");
        $Vote = M("vote");
        $Active->where('id='.$activeid)->delete();
        $Comment->where('activeid='.$activeid)->delete();
        $Vote->where('activeid='.$activeid)->delete();
    }

    public function Vote(){
        date_default_timezone_set("Asia/Shanghai");
        $Model = new Model();
        $openid=$_POST["openid"];
        $activeid=$_POST["activeid"];

        $check=$Model->query("select id from wx_real where activeid=$activeid and openid='".$openid."'");

        $Real=M("real");
        if(count($check)==0){
            $d["openid"]=$openid;
            $d["avatar"]=$_POST["avatar"];
            $d["nickname"]=$_POST["nickname"];
            $d["activeid"]=$activeid;
            $d["inputtime"] = date('Y-m-d H:i:s',time());
            $Real->create($d);
            $Real->add($d);
        }

        $votes=$Model->query("select * from wx_vote where activeid=$activeid and openid='".$openid."'");
        if(count($votes)==0){
            $data["openid"]=$_POST["openid"];
            $data["activeid"]=$_POST["activeid"];
            $data["avatar"]=$_POST["avatar"];
            $data["nickname"]=$_POST["nickname"];
            if($_POST["vote"]==1){
                $data["vote1"]=1;
                $data["vote2"]=0;
                $data["vote3"]=0;
            } else if($_POST["vote"]==2){
                $data["vote1"]=0;
                $data["vote2"]=1;
                $data["vote3"]=0;
            }else{
                $data["vote1"]=0;
                $data["vote2"]=0;
                $data["vote3"]=1;
            }
            $data["inputtime"] = date('Y-m-d H:i:s',time());
            $vote=M("vote");
            $vote->create($data);
            $vote->add($data);
            $this->ajaxReturn("0",json);
        }else{
            $id=$votes[0]["id"];
            if($_POST["vote"]==1){
                if($votes[0]["vote1"]==0){
                    $data["vote1"]=1;
                    $vote=M("vote");
                    $vote->create($data);
                    $result=$vote->where("id=$id")->save($data);
                    $this->ajaxReturn("1",json);
                }else{
                    $this->ajaxReturn("11",json);
                }
            }else if($_POST["vote"]==2){
                if($votes[0]["vote2"]==0){
                    $data["vote2"]=1;
                    $vote=M("vote");
                    $vote->create($data);
                    $result=$vote->where("id=$id")->save($data);
                    $this->ajaxReturn("2",json);
                }else{
                    $this->ajaxReturn("22",json);
                }
            }else if($_POST["vote"]==3){
                if($votes[0]["vote3"]==0){
                    $data["vote3"]=1;
                    $vote=M("vote");
                    $vote->create($data);
                    $result=$vote->where("id=$id")->save($data);
                    $this->ajaxReturn("3",json);
                }else{
                    $this->ajaxReturn("33",json);
                }
            }
        }
    }

    public function InsertComment(){
        date_default_timezone_set("Asia/Shanghai");
        $Model = new Model();
        $data["openid"]=$_POST["openid"];
        $data["avatar"]=$_POST["avatar"];
        $data["nickname"]=$_POST["nickname"];
        $data["content"]=base64_encode($_POST["content"]);
        $data["pic"]=$_POST["pic"];
        $data["activeid"]=$_POST["activeid"];
        $data["status"]=1;
        $data["inputtime"] = date('Y-m-d H:i:s',time());

        $activeid=$_POST["activeid"];
        $openid=$_POST["openid"];
        $coms=$Model->query("select id from wx_comment where activeid=$activeid and openid='".$openid."'");
        $active=$Model->query("select openid from wx_active where id=$activeid");
        if(count($coms)>0){
            $this->ajaxReturn("1",json);
        }else{

            $this->CheckLog($_POST["openid"],2);
            $this->CheckLog($active[0]["openid"],2);

            $Comment=M("comment");
            $Comment->create($data);
            $Comment->add($data);
            $this->ajaxReturn("0",json);
        }
    }


    public function InsertMessage(){
        date_default_timezone_set("Asia/Shanghai");
        $Model = new Model();
        $data["openid"]=$_POST["openid"];
        $data["avatar"]=$_POST["avatar"];
        $data["nickname"]=$_POST["nickname"];
        $data["content"]=base64_encode($_POST["content"]);
        $data["inputtime"] = date('Y-m-d H:i:s',time());

        $Message=M("message");
        $Message->create($data);
        $Message->add($data);
    }


    public function InsertActive(){
        date_default_timezone_set("Asia/Shanghai");
        $Model = new Model();

        $openid=$_POST["openid"];
        $check=$Model->query("select count(id) as counts from wx_active where openid='$openid' and to_days(inputtime)=to_days(now())");

        if($check[0]["counts"]>4){
            $this->ajaxReturn("0",json);
        }else{
            $data["title"]=$_POST["title"];
            $data["ftitle"]=$_POST["ftitle"];
            $data["description"]=$_POST["description"];
            $data["cateid"]=$_POST["cateid"];
            $data["vote1"]=$_POST["vote1"];
            $data["vote2"]=$_POST["vote2"];
            if(trim($_POST["vote3"])!=""){
                $data["vote3"]=$_POST["vote3"];
            }
            $data["vote3"]=$_POST["vote3"];
            $data["ismulti"]=$_POST["ismulti"];
            $data["ispublic"]=$_POST["ispublic"];
            $data["stime"]=$_POST["stime"];
            $data["title"]=$_POST["title"];
            $data["etime"]=$_POST["etime"];
            $data["openid"]=$_POST["openid"];
            $data["avatar"]=$_POST["avatar"];
            $data["nickname"]=$_POST["nickname"];
            $data["content"]=$_POST["content"];
            $data["pic"]=$_POST["pic"];
            $data["readnumber"]=0;

            if($_POST["check"]==1){
                $data["status"]=1;
                $this->CheckLog($_POST["openid"],1);
            }else{
                $data["status"]=0;
            }

            $data["inputtime"] = date('Y-m-d H:i:s',time());
            $Active=M("active");
            $Active->create($data);
            $Active->add($data);

            $this->ajaxReturn("1",json);
        }

    }

    /*
     * 完善手机号
     * */
    public function UpdatePhone(){
        $User=M("user");
        $data['phone']=$_POST['phone'];
        $data['nickname']=$_POST['nickname'];
        $openid = $_POST['openid'];
        $User->create($data);
        $User->where("openid='$openid'")->save($data);
    }

    /*
     * 阅读量增加
     * */
    public function AddReading(){
        $Active=M("active");
        $Model = new Model();
        $id=$_POST['id'];
        $openid=$_POST['openid'];
        $avatar=$_POST['avatar'];
        $nickname=$_POST['nickname'];
        $activeinfo=$Model->query("select * from wx_active where id=$id");

        $data['readnumber']=$activeinfo[0]["readnumber"]+1;
        $Active->create($data);
        $Active->where("id='$id'")->save($data);

        $check=$Model->query("select id from wx_real where activeid=$id and openid='".$openid."'");

        $Real=M("real");
        if(count($check)==0){
            $data["openid"]=$openid;
            $data["avatar"]=$avatar;
            $data["nickname"]=$nickname;
            $data["activeid"]=$id;
            $data["inputtime"] = date('Y-m-d H:i:s',time());
            $Real->create($data);
            $Real->add($data);
        }
    }

    /*
     * 插入积分记录以及判断是否升级
     * */
    public function CheckLog($openid,$type){
        $Model = new Model();
        $userinfo=$Model->query("select * from wx_user as a left join wx_level as b on a.level=b.id where a.openid='".$openid."'");

        if($type==0){
            $point=$userinfo[0]["spoint"];
        }else if($type==1){
            $point=$userinfo[0]["apoint"];
        }else if($type==2){
            $point=$userinfo[0]["cpoint"];
        }else if($type==3){
            $point=$userinfo[0]["zpoint"];
        }
        $data["openid"]=$openid;
        $data["point"]=$point;
        $data["type"]=$type;
        $data["inputtime"] = date('Y-m-d H:i:s',time());
        $pointlog=M("pointlog");
        $pointlog->create($data);
        $pointlog->add($data);

        $d["point"]=$userinfo[0]["point"]+$point;
        $level=$Model->query("select * from wx_level where uppoint<=".$d["point"]." order by id desc");
        $d["level"]=$level[0]['id'];

        $user=M("user");
        $user->create($d);
        $user->where("openid='$openid'")->save($d);
    }

    public function InsertSign(){
        date_default_timezone_set("Asia/Shanghai");
        $Model = new Model();
        $data["openid"]=$_POST["openid"];
        $data["avatar"]=$_POST["avatar"];
        $data["nickname"]=$_POST["nickname"];
        $data["name"]=$_POST["name"];
        $data["telephone"]=$_POST["telephone"];
        $data["activeid"]=$_POST["activeid"];
        $data["inputtime"] = date('Y-m-d H:i:s',time());

        $activeid=$_POST["activeid"];
        $openid=$_POST["openid"];
        $signs=$Model->query("select id from wx_sign where activeid=$activeid and openid='".$openid."'");

        $Sign=M("sign");
        if(count($signs)>0){
            $id=$signs[0]["id"];
            $Sign->where("id=$id")->save($data);
            $this->ajaxReturn("1",json);
        }else{
            $Sign->create($data);
            $Sign->add($data);
            $this->ajaxReturn("0",json);
        }
    }


    public function UploadImages()
    {
        if($_FILES["file"]["error"])
        {
            $message=$_FILES["file"]["error"];
            $arr=array("message"=>$message);
            $this->ajaxReturn($arr,json);
        }
        else
        {
            if($_FILES["file"]["size"]<1024000000)
            {
                $picname=time().$_FILES["file"]["name"];
                $filename ="/yjdata/www/www/huodong.wshoto.com/Public/upload/pic/".$picname;
                $filename =iconv("UTF-8","gb2312",$filename);
                move_uploaded_file($_FILES["file"]["tmp_name"],$filename);
                $url="https://huodong.wshoto.com/Public/upload/pic/".$picname;
                $arr=array("url"=>$url);
//                $this->ajaxReturn($arr,json);
                echo $url;
            }else{
                $this->ajaxReturn("error",json);
            }
        }

    }


    public function GetInfo(){
        $Model = new Model();
        $id=$_POST["id"];
        $list=$Model->query("select * from wx_info where status=1 order by id desc");
        for($i=0;$i<count($list);$i++){
            $list[$i]["img"]=str_replace("https://weixiu.wshoto.com.","https://weixiu.wshoto.com",$list[$i]["img"]);
            $list[$i]["img"]=explode(",",$list[$i]["img"]);
            $list[$i]["thumbOne"]=$list[$i]["img"][0];
        }
        $this->ajaxReturn($list,json);
    }


    //发送短信验证码
    public function SendCode(){
        $appid = '1400094128';
        $appkey = '9bae903822fc502321d0d0015d43ce8e';
        $a = rand(1,9);
        $b = rand(1,9);
        $c = rand(1,9);
        $d = rand(1,9);
        $mycode = $a.$b.$c.$d;
        $tel = $_POST['tel'];
        $openid = $_POST['openid'];
        $muban = "【速易维】{$mycode}为您的登录验证码，请尽快填写。如非本人操作，请忽略本短信。";
        vendor('Sender.SmsSingleSender');
        $sender = new  \SmsSingleSender($appid,$appkey);
        $result = $sender->send("0", "86",$tel,$muban, "", "");
        if($result['result'] == 0){
            $myres =M('code')->where(array('openid'=>$openid))->find();
            if(!$myres){
                $user_data = array(
                    'openid'=>$openid,
                    'tel'=>$tel,
                    'code'=>$mycode,
                );
                M('code')->add($user_data);
                $data = array(
                    'code'=>1,
                );
            }else{
                $user_data = array(
                    'openid'=>$openid,
                    'tel'=>$tel,
                    'code'=>$mycode,
                );
                M('code')->where(array('openid'=>$openid))->save($user_data);
                $data = array(
                    'code'=>2,
                );
            }
        }else{
            $data = array(
                'code'=>3,
            );
        }
        echo json_encode($data);
    }

    /*
     * 1表示申请提交成功
     * 2表示此手机重复，不允许重复注册
     * 0表示申请失败
     * 3表示短信验证码不正确
     * */
    public function UpdateInfo(){
        $User=M("user");

        $data['phone']=$_POST['phone'];
        $data['name']=$_POST['name'];
        $data['openid']=$_POST['openid'];

        $openid = $_POST['openid'];

        $code=$_POST['code'];
        $code_z = M("code")->where("openid='$openid'")->getField('code');

        $arr['flag']=0;

        if($code==$code_z){
            $User->where("openid='$openid'")->save($data);
            $arr['flag']=1;
            $this->ajaxReturn($arr,json);
        }else{
            $arr['flag']=2;
            $this->ajaxReturn($arr,json);
        }
    }

    /*
     * 1表示申请提交成功
     * 2表示此手机重复，不允许重复注册
     * 0表示申请失败
     * 3表示短信验证码不正确
     * */
    public function Perfect(){
        $User=M("user");

        $data['phone']=$_POST['phone'];
        $data['name']=$_POST['name'];
        $data['openid']=$_POST['openid'];

        $phone = $_POST['phone'];
        $openid = $_POST['openid'];
        $cid = $User->where("phone='$phone'")->getField('id');

        $code=$_POST['code'];
        $code_z = M("code")->where("openid='$openid'")->getField('code');

        $arr['flag']=0;

        if($code==$code_z){
            if($cid=="")
            {
                $User->where("openid='$openid'")->save($data);
                $arr['flag']=1;
                $this->ajaxReturn($arr,json);

            }else{
                $arr['flag']=2;
                $this->ajaxReturn($arr,json);
            }
        }else{
            $arr['flag']=3;
            $this->ajaxReturn($arr,json);
        }
    }

    public function GetOpenid()
    {
        $code=$_POST["code"];
        $nickname=$_POST["nickname"];
        $avatar=$_POST["avatar"];

        $get_openid_url = 'https://api.weixin.qq.com/sns/jscode2session';
        $Model = new Model();
        $setting=$Model->query("select * from wx_setting where id = 1");
        $setting=$setting[0];
        $params = array(
            'appid' => $setting['appid'],
            'secret' => $setting['appsecret'],
            'js_code' => $code,
            'grant_type' => 'authorization_code'
        );

        $response = $this->http($get_openid_url, $params, true);

        $arr = json_decode($response);
        $myarr = $this->object_array($arr);
        $info = array(
            'openid' => $myarr['openid'],
        );

        if($myarr['openid']!=""){
            $userinfo=$Model->query("select * from wx_user where openid = '".$myarr['openid']."'");
            $userinfo=$userinfo[0];
            if($userinfo["id"]==""){
                $data['openid']=$myarr['openid'];
                $data['level']=1;
                $data['point']=0;
                $data['nickname']=$nickname;
                $data['avatar']=$avatar;
                $data["inputtime"] = date('Y-m-d H:i:s',time());

                $User=M("user");
                if($User->create($data)) {
                    $User->add($data);
                }
            }
        }

        $userinfo=$Model->query("select * from wx_user where openid = '".$myarr['openid']."'");
        $userinfo=$userinfo[0];

        $this->ajaxReturn($userinfo,json);
    }

    public function object_array($array) {
        if(is_object($array)) {
            $array = (array)$array;
        } if(is_array($array)) {
            foreach($array as $key=>$value) {
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }

    public function getHTTPS($urls) {
        //初始化curl
        $ch = curl_init($urls);
        //3.设置参数
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//跳过证书验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        //4.调用接口
        $res = curl_exec($ch);
        $access_token_data = json_decode($res,1);

        //5.关闭curl
        curl_close($ch);
        return $access_token_data;
    }

    public function http($url, $params = false, $ispost = 0, $header = array(), $verify = false)
    {
        $httpInfo = array();
        $ch = curl_init();
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        //忽略ssl证书
        if ($verify === true) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if (is_array($params)) {
                $params = http_build_query($params);
            }
            if ($params) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === FALSE) {
            trace("cURL Error: " . curl_errno($ch) . ',' . curl_error($ch), 'error');
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
            trace($httpInfo, 'error');
            return false;
        }
        curl_close($ch);
        return $response;
    }

    public function syncpoint(){
        $returnarray=array();
        $openid=trim($_POST['openid']);
        $userinfo=M('user')->where(array('openid'=>$openid))->find();
        if (empty($userinfo)){
            $returnarray['status']=-1;
        }else{
            $url='https://jmt.wshoto.com/app/index.php?i=3&c=entry&do=test&m=zzed_card&mobile='.$userinfo['phone'].'&point='.$userinfo['point'];
            $params=array(
                'mobile'=>$userinfo['phone'],
                'point'=>$userinfo['point']
            );
            $res=$this->getHTTPS($url);
            $returnarray['status']=1;
        }
        $this->ajaxReturn($returnarray,json);
    }

    public function receivepoint(){

        $point=trim($_GET['point']);//积分
        $mobile=trim($_GET['mobile']);//手机号(唯一标识)
        $logdata=array(
            'mobile'=>$mobile,
            'time'=>date('Y-m-d H:i:s',time()),
            'point'=>$point,
        );

        if (empty($mobile)){
            $logdata['issuccess']=0;
            $logdata['desc']='未有手机号,未查询到用户.更新失败';
        }else{
            $memberinfo=M('user')->where(array('phone'=>$mobile))->find();
            if (!empty($memberinfo)){
                $logdata['target']=$memberinfo['openid'];
                $res=M('user')->where(array('id'=>$memberinfo['id']))->save(array('point'=>(intval($memberinfo['point'])-intval($point))));

                if ($res){
                    $logdata['issuccess']=1;
                    $logdata['desc']='查询到用户,更新成功';
                }else{
                    $logdata['issuccess']=0;
                    $logdata['desc']='查询到用户.更新失败';
                }
            }else{
                $logdata['issuccess']=0;
                $logdata['desc']='未查询到用户.更新失败';
            }
        }

        M('sync_log')->add($logdata);

    }
}