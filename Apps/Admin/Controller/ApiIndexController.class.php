<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
//方法名:驼峰  变量名：_
class ApiIndexController extends Controller{
    //首页轮播图
    public function banners(){
        $Model=new Model();
        $list=$Model->table(array('wx_banner'=>'a','wx_album'=>'b'))->where("a.album_id=b.id")->field('a.id,a.url,b.name,b.id as album_id')->select();
        if($list){
            foreach ($list as &$l){
                $l['url']="https://{$_SERVER['SERVER_NAME']}"."/".$l['url'];
            }
            unset ($l);
            $this->ajaxreturn(array("errno"=>0,'list'=>$list));
        }else{
            $this->ajaxreturn(array('errno'=>-1,'message'=>'暂时还未设置轮播图'));
        }
    }
    //猜你喜欢:视频展示
    public function like(){
        $public=new ApiPublicController();
        $Model=new Model();
        $openid=$_POST['openid'];
        if(empty($openid)){
            $this->ajaxreturn(array('errno'=>-1,'message'=>'openid不能为空'));
        }
        #todo 判断是否是新用户(新老用户推荐算法不同,推荐算法后续处理)
        $type=M('user')->where("openid='$openid'")->getField('type');
        //2019-03-08 从免费音频中选猜你喜欢
        //$album_ids=M('album')->field('id')->select();
        $album_ids=$Model->table(array('wx_album'=>'a','wx_album_episode'=>'b'))->where("b.isfree=1 and a.id=b.album_id ")->field("a.id")->select();

        $science_ids=M('science')->where('price<=0')->field('id')->select();
        $album_ids=$public->arrayTo1($album_ids,"id");
        $science_ids=$public->arrayTo1($science_ids,"id");
        $album_key=array_rand($album_ids,1);
        $science_key=array_rand($science_ids,1);
        $albun_value=$album_ids[$album_key];
        $science_value=$science_ids[$science_key];
       // M('album_episode')->where("album_id=$albun_value")->find('id,') ;
        $list1=$Model->table(array('wx_album'=>'a','wx_album_episode'=>'b'))->where("b.isfree=1 and a.id=b.album_id and a.id=$albun_value")->order("b.id asc")->field("a.id,a.name,b.cover,b.id as episode_id,b.name as episode,b.filepath as audio")->find();

        $list1["type"]=2;//专辑

        $list2=$Model->table(array('wx_science'=>'a','wx_science_audio'=>'b'))->where("b.isfree=1 and a.id=b.science_id and a.id=$science_value")->order("b.id asc")->field("a.id,a.name,a.cover,b.id as episode_id, b.filepath as audio,a.videopath as video")->find();
        $list2["type"]=1;//每日科学

        $list=array($list1,$list2);

        if($list && is_array($list) ){
            foreach ($list as $k=>$v){

                $list[$k]['cover']=!empty($v["cover"])?"https://{$_SERVER['SERVER_NAME']}"."/".$v['cover']:"";
                $list[$k]['audio']=!empty($v["audio"])?"https://{$_SERVER['SERVER_NAME']}"."/".$v['audio']:"";
                $list[$k]['video']=!empty($v["video"])?"https://{$_SERVER['SERVER_NAME']}".$v['video']:"";

            }

            $this->ajaxreturn(array("errno"=>0,'list'=>$list));
        }else{
            $this->ajaxreturn(array('errno'=>-1,'message'=>'获取失败！'));

        }

    }
    //精选专辑(最新上的)
    public function specialCollections(){
        $page=$_POST['page'];//当前页
        $pindex = max(1, intval($page));//页数
        $psize =$_POST["psize"];
        if(empty($psize)){
            $this->ajaxreturn(array('errno'=>-1,'message'=>'psize不能为空'));
        }
        $list=M('album')->field('id,name,cover,introduction')->order("time desc")->limit(($pindex - 1) * $psize ,  $psize)->select();
        if($list){
            $public=new ApiPublicController();
            $list=$public->prefix_img($list);
            $this->ajaxreturn(array("errno"=>0,'list'=>$list));
        }else{
            $this->ajaxreturn(array('errno'=>-1,'message'=>'暂时还没有精选专辑'));
        }
    }
    //搜索展示
    public function searchIndex(){
        $openid=$_POST['openid'];
        $public=new ApiPublicController();
        if(empty($openid)){
            $this->ajaxreturn(array('errno'=>-1,'message'=>'openid不能为空'));
        }
        //最近搜索
        $search_history=M('search_history')->where("openid='$openid'")->getField('keywords');
        $search_history=explode(',',$search_history);
        $search_history=array_slice($public->reverse($search_history),0,10);
        //热门搜索
        $search_hot=M("hottags")->field("name")->select();
        //搜索栏推荐
        $serch_default=M("setting")->getField("recommend");
        $search_hot=$public->arrayTo1($search_hot,"name");
        $this->ajaxreturn(array("errno"=>0,'search_history'=>$search_history,'search_hot'=>$search_hot,"search_default"=>$serch_default));


    }
    //搜索关键字匹配
    public function searchMatch(){
           $keyword=$_POST["keyword"];
           $openid=$_POST["openid"];
           if(empty($keyword)){
               $this->ajaxreturn(array('errno'=>-1,'message'=>'请输入搜索关键词'));
           }
           if(empty($openid)){
            $this->ajaxreturn(array('errno'=>-1,'message'=>'openid不能为空'));
           }

         $list=M("keyword_match")->where("name like '%$keyword%'")->order("priority desc")->field("parent_id,son_id,name as title,isparent,priority")->select();
        if($list && is_array($list)) {
            foreach ($list as $k => $v) {
                $match[] = $v["title"];
            }
            $this->ajaxreturn(array("errno"=>0,"match"=>$match));
        }else{
            $this->ajaxreturn(array("errno"=>-1,"message"=>"暂无匹配结果"));
        }
    }
    public function search(){
        $keyword=$_POST["keyword"];
        //2019-03-04 搜索关键词
        $openid=$_POST["openid"];
        $public=new ApiPublicController();
        $list=M("keyword_match")->where("name like '%$keyword%'")->order("priority desc")->field("parent_id,son_id,name as title,isparent,priority")->select();
        //添加搜索历史记录
        $res=M("search_history")->where("openid='$openid'")->field("keywords")->find();
        if($res){
            $keywords=explode(",",$res["keywords"]);
            $flag=0;
            //去重
            foreach ($keywords as $v){
                if($v==$keyword){
                    $flag=1;
                }
            }
            if($flag==0){
                array_push($keywords,$keyword);
                $keywords=implode(",",$keywords);
                M("search_history")->where("openid='$openid'")->setField("keywords",$keywords);
            }

        }else{
            $data=array("openid"=>$openid,"keywords"=>$keyword);
            M("search_history")->add($data);
        }
        if($list && is_array($list)){
            foreach ($list as $k=>$v){
                //每日科学
                if($v["priority"]=="1"){
                    $list[$k]["name"]="每日科学";
                    $list[$k]["listens"]=$public->listens(1,1,"",$v["parent_id"]);
                    unset($list[$k]["son_id"]);
                }
                $album=M("album")->where("id=".$v["parent_id"])->field("name,cover")->find();
                //专辑故事
                if($v["priority"]=="2" && $v["isparent"]=="1"){
                    $epis=M("album_episode")->where("album_id=".$v["parent_id"])->count();
                    $list[$k]["number"]=$epis;
                    $list[$k]["cover"]="https://{$_SERVER['SERVER_NAME']}" . '/' .$album["cover"];
                    $list[$k]["listens"]=$public->listens(2,1,'',$v["parent_id"]);
                    unset($list[$k]["son_id"]);
                }
                //单集
                if($v["priority"]=="2" && $v["isparent"]=="0"){
                    $list[$k]["name"]=$list[$k]["title"];
                    $list[$k]["title"]=$album["name"];
                    $list[$k]["cover"]="https://{$_SERVER['SERVER_NAME']}" . '/' .$album["cover"];
                        $list[$k]["listens"]=$public->listens(2,0,$v["son_id"],"");
                }
                //收听人数小于100不显示
                //                  if($list[$k]["listens"]<100){
                //                     unset($list[$k]["listens"]);
                //                  }
                unset($list[$k]["isparent"]);

            }
            $this->ajaxreturn(array("errno"=>0,"list"=>$list));
        }else{
            $this->ajaxreturn(array('errno'=>-1,"message"=>"抱歉，暂无搜索结果"));

        }
    }
    //推荐位 推荐6个最新新发布的专辑故事，倒序排列,不足6个，有多少显示多少
    public function recommend(){
        $list=M("album")->field("id,name,cover")->order("time desc")->limit(6)->select();
        $public=new ApiPublicController();
        if(empty($list)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"暂时没有推荐内容"));
        }else{
            $list=$public->prefix_img($list);
            $this->ajaxreturn(array("errno"=>0,"list"=>$list));
        }

    }
    //专辑、每日科学
    public function albumIndex(){
        $type=$_POST["type"]?$_POST["type"]:1;//1每日科学 2 专辑
        $page=$_POST['page'];//当前页
        $pindex = max(1, intval($page));//页数
        $psize =$_POST["psize"];
        $public=new ApiPublicController();
        if($type=="2"){
            $list=M("album")->field("id,name,cover,introduction")->order("time desc")->limit(($pindex - 1) * $psize ,  $psize)->select();

        }elseif($type=="1"){
            $list=M("science")->field("id,name,cover,introduction")->order("time desc")->limit(($pindex - 1) * $psize ,  $psize)->select();
        }
        if($list && is_array($list)){
            $list=$public->prefix_img($list);
            $this->ajaxreturn(array("errno"=>0,'list'=>$list));
        }else{
            $this->ajaxreturn(array('errno'=>-1,"message"=>"抱歉，暂无专辑"));
        }
    }
    //专辑、每日科学详情
    public function albumDetail(){
        $id=$_POST["id"];//专辑、每日科学id
        $type=$_POST["type"]?$_POST["type"]:1;//1每日科学  2专辑
        $flag=$_POST["flag"]?$_POST["flag"]:1;//1目录(默认) 2详情 (非必传)
        $openid=$_POST["openid"];
        $Model=new Model();
        $public=new ApiPublicController();
        $user_id=M("user")->where("openid='$openid'")->getField("id");
        $start_time=strtotime(date("Y-m-d",time()));
        $end_time=$start_time+86399;
        //todo 2019-01-23 生成访问记录
        if(empty($openid)||empty($id)){
            $this->ajaxreturn(array("errno"=>"-1","message"=>"缺少必要参数"));
        }
        $usertype=M("user")->where("openid='$openid'")->getField("type");
        $data=array("openid"=>$openid,"album_id"=>$id,"time"=>time(),"type"=>$usertype,"flag"=>$type);
        $res=M("album_visit_log")->where("openid='$openid' and type=$usertype and album_id=$id and time>='$start_time' and time<=$end_time")->find();
        #todo 2019-3-12 更新新的访客日志.每日一次.去重
        if(!$res){
            M("album_visit_log")->add($data);
        }
        M("album_log")->add($data);
        //2019-03-04 是否显示购买按钮   1 待付款 2已付款(待发货) 3已发货 (待收货)4已完成
        $is_show=1;
        $res=M("order")->where("openid='$openid' and type=$type and status in(2,3,4) and goodsid=$id")->find();
        if($res){
          $is_show=0;
        }
        if($type=="2"){
            $list=M("album")->where("id=$id")->field("id,name,cover,price,content")->find();
            $list["cover"]="https://{$_SERVER['SERVER_NAME']}"."/".$list["cover"];
            if($flag=="1"){
                if($list){
                    $list["catalogs"]=M("album_episode")->where("album_id=$id")->field("id,name,isfree,filepath")->select();
                    # todo 2019-01-10 判断用户是否购买，有权限播放
                    $userids=M("album")->where("id=$id")->getField("userids");
                    if(strstr($userids,$user_id)){
                        foreach ($list["catalogs"] as $k=>$v){
                             $list["catalogs"][$k]["isfree"]=1;
                        }
                    }
                    //获得播放时长
                    foreach ($list["catalogs"] as $k=>$v){
                        //$test=$public->getDuration($v["filepath"]);
                        $minute= floor ( $public->getDuration($v["filepath"])/60 );
                        $seconds=$public->getDuration($v["filepath"])%60;
                        /*2019-3-19 更新 以分和秒的形式来获取*/
                        $minutes=$minute.'分'.$seconds.'秒';
                        //$minutes= sprintf("%.2f",$public->getDuration($v["filepath"])/60)?sprintf("%.2f",$public->getDuration($v["filepath"])/60):0.00;
                        $list["catalogs"][$k]["duration"]=$minutes;
                    }

                    unset($list["content"]);

                    $this->ajaxreturn(array("errno"=>0,'list'=>$list,"is_show"=>$is_show));
                    exit();
                }else{
                    $this->ajaxreturn(array('errno'=>-1,"message"=>"专辑详情获取失败"));
                    exit();
                }
            }
            if($flag=="2"){
                $list["content"]=htmlspecialchars_decode($list["content"]);
                $html = $list['content'];
                preg_match_all('/<img.*?src=[\\\'| \\"](.*?(?:[\\.gif|\\.jpg]?))[\\\'|\\"].*?[\\/]?>/', $html, $imgs);
                preg_match_all('/<embed.*?src=[\\\'| \\"](.*?(?:[\\.flv|\\.mp4|\\.gif]?))[\\\'|\\"].*?[\\/]?>/', $html, $vids);

                if (isset($imgs[1])) {
                    foreach ($imgs[1] as $img) {
                        $im = array("old" => $img, "new" => "https://bidexcx.wshoto.com".$img);
                        $images[] = $im;
                    }
                    if (isset($images)) {
                        foreach ($images as $img) {
                            $html = str_replace($img['old'], $img['new'], $html);
                        }
                    }
                    $list['content'] = $html;

                }

                $this->ajaxreturn(array("errno"=>0,'list'=>$list,"is_show"=>$is_show));
                exit();
            }
        }
        if($type=="1"){
            $list=$Model->table(array("wx_science"=>"a","wx_science_audio"=>"b"))->where("a.id=b.science_id and a.id=$id")->field("a.id,a.name,a.cover,a.content,b.id as episode_id,b.filepath as audio,b.isfree,a.price,a.videopath as video")->find();
            if($list){
                $userids=M("science")->where("id=$id")->getField("userids");
                if(strstr($userids,$user_id)){
                    $list["isfree"]=1;
                }
                $list["cover"]=!empty($list["cover"])?"https://{$_SERVER['SERVER_NAME']}"."/".$list["cover"]:"";
                $list["audio"]=!empty($list["audio"])?"https://{$_SERVER['SERVER_NAME']}"."/".$list["audio"]:"";
                $list["video"]=!empty($list["video"])?"https://{$_SERVER['SERVER_NAME']}"."/".$list["video"]:"";
                /*$list["content"]=substr($list["content"],246);*/
                $list["content"]=htmlspecialchars_decode($list["content"]);
                $html = $list['content'];
                preg_match_all('/<img.*?src=[\\\'| \\"](.*?(?:[\\.gif|\\.jpg]?))[\\\'|\\"].*?[\\/]?>/', $html, $imgs);
                preg_match_all('/<embed.*?src=[\\\'| \\"](.*?(?:[\\.flv|\\.mp4|\\.gif]?))[\\\'|\\"].*?[\\/]?>/', $html, $vids);

                if (isset($imgs[1])) {
                    foreach ($imgs[1] as $img) {
                        $im = array("old" => $img, "new" => "https://bidexcx.wshoto.com".$img);
                        $images[] = $im;
                    }
                    if (isset($images)) {
                        foreach ($images as $img) {
                            $html = str_replace($img['old'], $img['new'], $html);
                        }
                    }
                    $list['content'] = $html;
                }
//                if (isset($vids[1])) {
//                    foreach ($vids[1] as $vid) {
//                        $vi = array("old" => $vid, "new" => "https://bidexcx.wshoto.com".$vid);
//                        $videos[] = $vi;
//                    }
//                    if (isset($videos)) {
//                        foreach ($videos as $vid) {
//                            $html = str_replace($vid['old'], $vid['new'], $html);
//                        }
//                    }
//                    $list['content'] = $html;
//                }

                $this->ajaxreturn(array("errno"=>0,'list'=>$list,"is_show"=>$is_show));
                exit();
            }else{
                $this->ajaxreturn(array('errno'=>-1,"message"=>"每日科学详情获取失败"));
                exit();
            }
        }

    }
    //音频播放(专辑)
    public function playAudio(){
        $openid=$_POST["openid"];
        $id=intval($_POST["id"]);//当前音频id
        $flag=$_POST["flag"];//1 上一集  2：下一集
        $Model=new Model();
        $public=new ApiPublicController();
        if(empty($openid)||empty($id)){
            $this->ajaxreturn(array('errno'=>-1,"message"=>"缺少必要参数"));
        }
        $album_id=M("album_episode")->where("id=$id")->getField("album_id");
        $number=M("album_episode")->where("album_id=$album_id")->field(id)->select();
        $number=$public->arrayTo1($number,"id");//array(4,5,6,7)
        if($flag=="1"){
            if($id==$number[0]){
                $this->ajaxreturn(array('errno'=>-2,"message"=>"当前已经是第一集"));
            }
           $id=$id-1;
           if(!in_array($id,$number)){
               $id=$id+1;
           }

        }
        if($flag=="2"){
            $end=array_pop($number);//7
            if($id==$end){
                $this->ajaxreturn(array('errno'=>-3,"message"=>"当前已经是最后集"));
            }

            $id=$id+1;
            array_push($number,$end);
            if(!in_array($id,$number)){
                $id=$id-1;
            }

        }
        $list=$Model->table(array("wx_album"=>"a","wx_album_episode"=>"b"))->where("a.id=b.album_id and b.id=$id")->field("a.id as album_id,a.name as title,a.price,b.id,b.cover,b.name,b.filepath,b.isfree,b.duration")->find();

        //推荐位：推荐用户未购买的专辑（3）
        $goodsid=M("order")->where("openid='$openid' and type=2")->field("goodsid")->select();
        $goodsid=$public->arrayTo1($goodsid,"goodsid");
        $albumsid=M("album")->field("id")->order("time desc")->select();
        if($albumsid && is_array($albumsid)){
           $albumsid=$public->arrayTo1($albumsid,"id");
           $selectids=array_diff($albumsid,$goodsid);
           $count=count($selectids);
           if($count>=3){
               $selectids=array_slice($selectids,0,3);
           }else{
              $diff=3-$count;
              $science=M('science')->field("id,name,cover")->order("time desc")->limit($diff)->select();
              foreach ($science as $k=>$v){
                  $science[$k]["type"]=1;//每日科学
              }
           }
            foreach ($selectids as $v){
                $recommend[]=M("album")->where("id=$v")->field("id,name,cover")->find();
                foreach ($recommend as $k=>$v){
                    $recommend[$k]["type"]=2;//专辑故事
                }
            }
            if(count($recommend)<3){
               $recommends=array_merge($recommend,$science);
            }else{
               $recommends=$recommend;
            }
        }
        //评论
        $comments=$Model->table(array("wx_comment"=>"a","wx_user"=>"b"))->where("a.openid=b.openid and  a.album_epsid=$id")->field("a.id,a.openid,a.content,a.time,b.avatar,b.nickname")->order("time desc")->select();
        $comments_number=count($comments);
        if($list &&is_array($list)){
            # todo 2019-01-10 判断用户是否购买，有权限播放整个专辑
            $userids=M("album")->where("id=$album_id")->getField("userids");
            $user_id=M("user")->where("openid='$openid'")->getField("id");
            if(strstr($userids,$user_id)){
                $list["isfree"]="1";
            }
            if($list["isfree"]=="0"){
                $this->ajaxreturn(array('errno'=>-4,"message"=>"免费内容已结束，喜欢本专辑的话请购买哦"));
            }
            $list["cover"]="https://{$_SERVER['SERVER_NAME']}"."/".$list["cover"];
            $list["audio"]="https://{$_SERVER['SERVER_NAME']}"."/".$list["filepath"];
            //每一个专辑有多少集
            //$list["catalogs"]=M("album_episode")->where("album_id=".$list["album_id"])->field("id,isfree")->select();

            unset($list["filepath"]);

        }else{
            $list=array();
        }
        if($recommends &&is_array($recommends)){
            foreach ($recommends as $k=>$v){
                $recommends[$k]["cover"]="https://{$_SERVER['SERVER_NAME']}"."/".$v["cover"];
            }
        }else{
            $recommends=array();
        }
        if($comments && is_array($comments)){
            foreach ($comments as $k=>$v){
                $comments[$k]["thumbs"]=M("thumbs")->where("comment_id=".$v["id"]." and deleted=0")->count();
                $comments[$k]["time"]=date("Y-m-d H:i:s",$comments[$k]["time"]);
                $comments[$k]["nickname"]=!empty($v["nickname"])?rawurldecode($v["nickname"]):"";
                //回复
//                $reply=$Model->table(array("wx_comment_reply"=>"a","wx_user"=>"b"))->where("a.openid=b.openid and a.comment_id=".$v["id"])->field("a.id,b.nickname,a.content")->order("a.time desc")->select();
                $reply=M("comment_reply")->where("comment_id=".$v["id"])->field("id,openid,content")->order("time desc")->select();
                if(!empty($reply)){
                    foreach ($reply as $k2=>$v2){
                        if(empty($v2["openid"])){
                            $reply[$k2]["nickname"]="管理员";
                        }else{
                            $openid_reply=$v2["openid"];
                            $nickname=M("user")->where("openid='$openid_reply'")->getField("nickname");
                            $reply[$k2]["nickname"]=!empty($nickname)?rawurldecode($nickname):"";
                        }
                    }
                    $comments[$k]["reply"]=$reply;
                }else{
                    $comments[$k]["reply"]=array();
                }
                //判断当前用户是否对该评论点赞
                $res=M("thumbs")->where("openid='$openid' and comment_id=".$v["id"]." and deleted=0")->find();
                if($res){
                    $comments[$k]["is_thumb"]=1;
                }else{
                    $comments[$k]["is_thumb"]=0;
                }
            }
        }
        $this->ajaxreturn(array("errno"=>0,'list'=>$list,"recommends"=>$recommends,"comments_number"=>$comments_number,"comments"=>$comments));

    }
    //生成播放历史记录
    public function addplaylog(){
       //$id=$_POST["id"];//专辑、每日科学id
       $episode_id=$_POST["episode_id"];//音频id(分集)
       $openid=$_POST["openid"];
       $type=$_POST["type"];//1每日科学  2专辑故事
        # todo 20190115新增收听时长
       $duration=$_POST["duration"];//收听时长
        if($_POST["isfinish"]=="1"){//1已播完 0：没有播完
            $finishtime=time();
        }else{
            $finishtime="";
        }
        if($type=="1"){
            $id=M("science_audio")->where("id=$episode_id")->getField("science_id");
        }
        if($type=="2"){
            $id=M("album_episode")->where("id=$episode_id")->getField("album_id");
        }
        $addData=array("openid"=>$openid,"product_id"=>$episode_id,"type"=>$type,"time"=>time(),"parent_id"=>$id,"finishtime"=>$finishtime,"duration"=>$duration);
        $saveData=array("time"=>time(),"finishtime"=>$finishtime);
//        $id=M("audio_play_log")->where("openid='$openid' and product_id=$episode_id and parent_id=$id")->getField("id");
//        if($id){
//            $res=M("audio_play_log")->where("id=$id")->save($saveData);
//        }else{
//            $res=M("audio_play_log")->add($addData);
//        }
        $res=M("audio_play_log")->add($addData);
        if($res){
            $this->ajaxreturn(array('errno'=>0,"message"=>"音频播放记录更新成功"));
        }else{
            $this->ajaxreturn(array('errno'=>-1,"message"=>"音频播放记录更新失败"));

        }

    }
    //点赞(取消点赞)
    public function thumbsUp(){
        $comment_id=$_POST['id'];//评论id
        $openid=$_POST['openid'];//点赞人openid
        $audio_id=$_POST["audio_id"];//音频id
        $public=new ApiPublicController();
        if(empty($comment_id)||empty($openid)||empty($audio_id)){
            $this->ajaxreturn(array('errno'=>-1,'message'=>'缺少必要参数!'));
        }
        $data=array('comment_id'=>$comment_id,'openid'=>$openid,'deleted'=>0);
        $isthumup=M('thumbs')->where("openid='$openid' and comment_id=$comment_id")->find();
        if($isthumup){
            //如果点过赞再次点赞为取消操作
            if($isthumup['deleted']==0){//已点赞
                $res=M('thumbs')->where("openid='$openid' and comment_id=$comment_id ")->setField('deleted',1);
                $comments=$public->get_comment($audio_id,$openid);
                if($res>=0){
                    $this->ajaxreturn(array('errno'=>0,"list"=>$comments,'message'=>'取消成功！'));
                }else{
                    $this->ajaxreturn(array('errno'=>-1,"list"=>$comments,'message'=>'取消失败！'));
                }
            }elseif($isthumup['deleted']==1){//未点赞
                $res=M('thumbs')->where("openid='$openid' and comment_id=$comment_id ")->setField('deleted',0);
                $comments=$public->get_comment($audio_id,$openid);
                if($res>=0){
                    $this->ajaxreturn(array('errno'=>0,"list"=>$comments,'message'=>'点赞成功！'));
                }else{
                    $this->ajaxreturn(array('errno'=>-1,"list"=>$comments,'message'=>'点赞失败！'));
                }
            }
        }else{
            $res=M('thumbs')->add($data);
            $comments=$public->get_comment($audio_id,$openid);
            if($res){
                $this->ajaxreturn(array('errno'=>0,"list"=>$comments,'message'=>'点赞成功！'));
            }else{
                $this->ajaxreturn(array('errno'=>-1,"list"=>$comments,'message'=>'点赞失败！'));
            }
        }

    }
    //用户发布或回复评论
    public function comment(){
        $type=$_POST["type"]?$_POST["type"]:1;//1发布 2 回复
        $audio=$_POST["id"];//音频id
        $openid=$_POST["openid"];
        $content=$_POST["content"];
        $comment_id=$_POST["commentid"];//评论id(非必传)
        $public=new ApiPublicController();
        if(empty($audio)||empty($openid)||empty($content)){
            $this->ajaxreturn(array('errno'=>-1,'message'=>'缺少必要参数！'));
        }
        if($type=="1"){
            $album_id=M("album_episode")->where("id=$audio")->getField("album_id");
            $data=array("openid"=>$openid,"content"=>$content,"time"=>time(),"album_epsid"=>$audio,"album_id"=>$album_id);
            $res=M('comment')->add($data);
        }elseif($type=="2"){
            $data=array("openid"=>$openid,"content"=>$content,"time"=>time(),"comment_id"=>$comment_id);
            $res=M("comment_reply")->add($data);
        }
        //2019-02-26 获取评论
        $list=$public->get_comment($audio,$openid);
        if($res){
            $this->ajaxreturn(array('errno'=>0,"list"=>$list,'message'=>'提交成功！'));
        }else{
            $this->ajaxreturn(array('errno'=>-1,"list"=>$list,'message'=>'提交失败！'));
        }
    }
    //我的
    public function myIndex(){
        $openid=$_POST["openid"];
        if(empty($openid)){
            $this->ajaxreturn(array('errno'=>-1,'message'=>'openid不能为空'));
        }
        $list=M("user")->where("openid='$openid'")->field("avatar,nickname,wallet")->find();
        //我的徽章个数
        $list["badge_number"]=M("user_badge")->where("openid='$openid'")->count();
        $list["plan_number"]=M("plan")->count();
        if($list){
            if($list["nickname"]){
                $list["nickname"]=rawurldecode($list["nickname"]);
            }
            $this->ajaxreturn(array("errno"=>0,'list'=>$list));
        }else{
            $this->ajaxreturn(array('errno'=>-1,'message'=>'获取个人信息失败！'));
        }
    }
    //投资计划
    public function articles(){
        $list=M("plan")->field("*")->order("time desc")->select();
        if($list && is_array($list)){
            foreach ($list as $k=>$v){
                $list[$k]["time"]=date("Y-m-d H:i:s",$v["time"]);
            }
            $this->ajaxreturn(array("errno"=>0,'list'=>$list));
        }else{
            $this->ajaxreturn(array('errno'=>-1,'message'=>'暂时没有投资计划！'));

        }
    }
    //我的订单
    public function myOrder(){
        $openid=$_POST["openid"];
        $status=$_POST["status"]?$_POST["status"]:0;//0:全部 1待付款 2待发货 3待收货 4已签收
        $Model=new Model();
        $public=new ApiPublicController();
        if(empty($openid)){
            $this->ajaxreturn(array('errno'=>-1,'message'=>'openid不能为空'));
        }
        $condition="openid='$openid' and deleted=0";
        if(!empty($status)){
           $condition.=" and status=$status";
        }
        $orders=M("order")->where($condition)->field("id,type,status")->order("status asc,createtime desc")->select();
        if($orders && is_array($orders)){
            foreach ($orders as $k=>$v){
                $table=$public->selectTable($v["type"]);
                $orderInfo[]=$Model->table(array("wx_order"=>"a","$table"=>"b"))->where("a.goodsid=b.id and a.id=".$v["id"])->field("a.id,a.ordersn,a.status,a.createtime,b.name,b.cover,a.proprice,a.realprice")->find();
            }
            if($orderInfo && is_array($orderInfo)){
                foreach ($orderInfo as $k=>$v){
                    $orderInfo[$k]["cover"]="https://{$_SERVER['SERVER_NAME']}"."/".$v["cover"];
                    $orderInfo[$k]["yh"]=$v["proprice"]-$v["realprice"];
                    //$orderInfo[$k]["createtime"]=date("Y-m-d H:i:s",$v["createtime"]);
                }
            }
            $this->ajaxreturn(array("errno"=>0,"list"=>$orderInfo));
        }else{
            $this->ajaxreturn(array('errno'=>-1,'message'=>'您暂时还没有订单！'));
        }
    }
    //手动取消订单
    //订单详情
    public function orderDetail(){
       $orderid=$_POST["orderid"];
       $Model=new Model();
       $public=new ApiPublicController();
       if(empty($orderid)){
           $this->ajaxreturn(array('errno'=>-1,'message'=>'订单id不能为空!'));
       }
       $type=M("order")->where("id=$orderid")->getField("type");
       $table=$public->selectTable($type);
       $list=$Model->table(array("wx_order"=>"a","$table"=>"b"))->where("a.goodsid=b.id and a.id=$orderid")->field("b.name,b.cover,b.price,a.proprice,a.realprice,a.discountprice,a.status")->find();
       if(!empty($list)){
           $list["yh"]=($list["proprice"]-$list["realprice"]);
           $list["cover"]="https://{$_SERVER['SERVER_NAME']}" ."/".$list["cover"];
           unset($list["discountprice"]);
           $this->ajaxreturn(array('errno'=>0,'list'=>$list));
       }

    }
    public function cancelOrder(){
        $orderid=$_POST["id"];
        if(empty($orderid)){
            $this->ajaxreturn(array('errno'=>-1,'message'=>'订单id不能为空！'));
        }
        $res=M("order")->where("id=$orderid")->setField("deleted",1);
        if($res){
            $this->ajaxreturn(array('errno'=>0,'message'=>'取消成功！'));
        }else{
            $this->ajaxreturn(array('errno'=>-1,'message'=>'取消失败！'));
        }
    }
    //自动取消订单(服务器定时任务)
    public function autoCancel(){
         //下单时间+1小时=当前时间
        $order=M("order")->where("status=1")->field("id,createtime,deleted")->select();
        if($order && is_array($order)){
            foreach ($order as $k=>$v){
//                $public->dump2file(date("ymdhi",($v["createtime"]+3600)));
//                $public->dump2file(date("ymdhi",time()));
                if(date("ymdhi",($v["createtime"]+3600))==date("ymdhi",time())){
                    M("order")->where("id=".$v["id"])->setField("deleted",1);
                }
            }
        }
    }
    //待付款的订单页展示
    public function OrderIndex(){
           $type=$_POST["type"];//订单类型 2音频 3 徽章
           $goodid=$_POST["goodid"];//商品id(用户徽章id)
           $openid=$_POST["openid"];
           $userCoupons_id=$_POST["usercoupons_id"];//(用户优惠券id)非必传,当手动选择优惠券时传
           if(empty($type)||empty($goodid)||empty($openid)){
               $this->ajaxreturn(array('errno'=>-1,'message'=>'缺少必要参数！'));
           }
           $public =new ApiPublicController();
           $Model=new Model();
           if($type=="3"){
               $goodid=$Model->table(array("wx_badge"=>"a","wx_user_badge"=>"b"))->where("b.badgeid=a.id and b.id=$goodid")->getField("a.id");
           }
           $table=substr($public->selectTable($type),3);
           $goodsInfo=M("$table")->where("id=$goodid")->field("id,name,cover,price")->find();
           $goodsInfo["cover"]="https://{$_SERVER['SERVER_NAME']}"."/".$goodsInfo["cover"];
           $goodprice=$goodsInfo["price"];
           if($table=="album"){
               $goodsInfo["episodes"]=M("album_episode")->where("album_id=".$goodsInfo["id"])->count();
           }
           switch ($type){
//               case "1":
//                   $coupons=array("不可使用优惠券");
//                   $wallet=M("user")->where("openid='$openid'")->getField("wallet");
//                   $huabi=array();
//                   if($wallet>=$goodprice){
//                           $huabi["deduct"]=$goodprice;
//                   }else{
//                           $huabi["deduct"]=$wallet;
//                   }
//                   $huabi["rest"]=$wallet- $huabi["deduct"];
//                   $totalprice=$goodprice-$huabi["deduct"];
//                   break;
               case "2":
                   //我的可用优惠券 1先减优惠券再抵扣花币 2 自动为用户选最优的优惠券,用户手动选
                   if(empty($userCoupons_id)){
                       $coupons=$Model->table(array("wx_user_coupons"=>"a","wx_coupons"=>"b"))->where(" a.coupons_id=b.id and a.openid='$openid' and a.state=1 and (b.album_id=0 or b.album_id=$goodid) and (b.discount>0 or b.full<$goodprice)")->field("a.id as usercoupons_id,b.*")->order("b.preferential desc,b.validity desc,b.album_id desc,b.full asc")->limit(1)->select();
                   }elseif($userCoupons_id=="-1"||$userCoupons_id==-1){
                       $coupons=0;
                   }else{
                       $condition=" a.coupons_id=b.id and a.id=$userCoupons_id";
                       $coupons=$public->myCoupons($condition,"");
                   }
                   if(!empty($coupons)){
                       $coupons=$public->arrayTo1($coupons,"");
                       if($coupons["discount"]){
                           $coupons["yh"]=$goodsInfo["price"]*floatval(10-$coupons["discount"])/10;
                       }
                       //2019-02-19 优惠券Bug修复
                       if($coupons["preferential"]){
                           $coupons["yh"]=$coupons["preferential"];
                       }
                       unset($coupons["album_id"]);
                       unset($coupons["number"]);
                       unset($coupons["preferential"]);
                       unset($coupons["discount"]);
                       unset($coupons["validity"]);
                       unset($coupons["full"]);
                       unset($coupons["rest"]);
                       unset($coupons["back_validity"]);
                       $rm=$goodsInfo["price"]-$coupons["yh"];
                   }else{
                       $rm=$goodsInfo["price"];
                   }
                   $wallet=M("user")->where("openid='$openid'")->getField("wallet");
                   $huabi=array();
                   if($rm>0){
                       if($wallet>=$rm){
                           $huabi["deduct"]=$rm;
                       }else{
                           $huabi["deduct"]=$wallet;
                       }
                   }else{
                       $rm=0;
                       $huabi["deduct"]=0;
                   }
                   $huabi["rest"]=$wallet- $huabi["deduct"];
                   $totalprice=$rm-$huabi["deduct"];
                   break;
               case "3":
                   $coupons="0";
                   $huabi=array("不可使用花币");
                   $totalprice=$goodprice;
                   break;
           }

           $this->ajaxreturn(array("errno"=>0,"goodsInfo"=>$goodsInfo,"coupons"=>$coupons,"huabi"=>$huabi,"totalprice"=>$totalprice));

    }
    //我的优惠券列表(所有可用的)
    public function myCoupons(){
        // state 1可用 2已使用 3已过期
        $state=$_POST["state"]?$_POST["state"]:1;//默认显示可使用的
        $openid=$_POST["openid"];
        # todo 2019-1-11 优惠券2种列表  flag  1:所有可用的优惠券 2 某笔订单可选择的优惠券
        //$flag=$_POST["flag"]?$_POST["flag"]:1;
        $goodid=$_POST["goodsid"];//商品id(非必传)
        if(empty($openid)){
            $this->ajaxreturn(array('errno'=>-1,'message'=>'openid不能为空！'));
        }
        $public=new ApiPublicController();
        $time_array=$public->monthRange();
        $condition="1";
        //2019-03-04 查看后不显示今日新增优惠券
        $str_start=date("Y-m-d",time())." 0:0:0";
        $start=strtotime($str_start);
        $str_end=date("Y-m-d",time())." 23:59:59";
        $end=strtotime($str_end);
        M("user_coupons")->where("openid='$openid' and gettime>=$start and gettime<=$end and state=1")->setField("is_show",0);
        switch ($state){
            case "1":
                $orderby="b.validity asc,b.preferential desc,b.album_id asc,b.discount asc";
                break;
            case "2":
                $orderby="a.usetime desc";
                break;
            case "3":
                $condition .=" and b.validity>=".($time_array[0])." and b.validity<=".($time_array[1]);
                $orderby="b.validity desc";
                break;
        }
        if(empty($goodid)){
            $condition .=" and a.coupons_id=b.id and a.openid='$openid' and a.state=$state";
        }else{
            $goodprice=M("album")->where("id=$goodid")->getField("price");
            $condition .=" and a.coupons_id=b.id and a.openid='$openid' and a.state=1 and (b.album_id=0 or b.album_id=$goodid ) and (b.discount>0 or b.full<$goodprice)";
        }
        $coupons=$public->myCoupons($condition,$orderby);
        //优惠券张数
        for($i=1;$i<=3;$i++){
            $cond="  a.coupons_id=b.id and a.openid='$openid' and a.state=$i  ";
            if($i==3){
                $cond .=" and b.validity>=".($time_array[0])." and b.validity<=".($time_array[1]);
            }
            $Model=new Model();
            $number[]=$Model->table(array("wx_user_coupons"=>"a","wx_coupons"=>"b"))->where($cond)->count();
        }
        if($number[1]>20){
            $number[1]=20;
        }
//        if(!empty($goodid)){
//            $number=count($coupons);
//

        if(!empty($coupons)){
            //判断优惠券是否过期
            if($state=="1"){
                foreach ($coupons as $k=>$v){

                    if($v["validity"]!="永久有效"){

                        if(time()>($v["back_validity"])){
                            M("user_coupons")->where("id=".$v["usercoupons_id"])->setField("state",3);
                        }
                        //优惠券快到期3天时置顶，红字提示
                        if(strtotime($v["validity"])<time()+86400*3){
                            $coupons[$k]["notice"]="优惠券即将过期";
                        }
                    }

                    unset($coupons[$k]["back_validity"]);
                }
            }
            if($state=="2"){
                $coupons=array_slice($coupons,0,20);
            }
            foreach ($coupons as $k=>$v){
                 if(!empty($v["album_id"])){
                     $coupons[$k]["name"]=M("album")->where("id=".$v["album_id"])->getField("name");
                 }
                 if(empty($v["discount"])||$v["discount"]=="0.00"){
                     unset($coupons[$k]["discount"]);
                 }
                 if(!empty($v["usetime"])){
                     $coupons[$k]["usetime"]=date("Y-m-d",$v["usetime"]);
                 }else{
                     $coupons[$k]["usetime"]="";
                 }


                unset($coupons[$k]["number"]);

            }


            $this->ajaxreturn(array("errno"=>0,"list"=>$coupons,"number"=>$number));
        }else{
            $this->ajaxreturn(array('errno'=>0,'message'=>'您暂时还没有优惠券！',"list"=>array(),"number"=>$number));

        }
    }
    //领取优惠券(展示可以领取的所有优惠券)
    public function drawCouponsIndex(){
       $time=time();
       $public=new ApiPublicController();
       $coupons=M("coupons")->where("(rest>0 or rest='NAN') and (validity >$time or validity='NAN')")->field("*")->select();
       if(!empty($coupons)&& is_array($coupons)){
           $coupons=$public->strotoDate($coupons);
           foreach ($coupons as $k=>$v){
               if(!empty($v["album_id"])){
                   $coupons[$k]["name"]=M("album")->where("id=".$v["album_id"])->getField("name");
               }
           }
           $this->ajaxreturn(array("errno"=>0,"list"=>$coupons));
       }else{
           $this->ajaxreturn(array('errno'=>-1,'message'=>'暂时还没有优惠券可以领取！'));

       }
    }
    //领取优惠券
    public function drawCoupon(){
        $coupons_id=$_POST["coupons_id"];//优惠券id
        $openid=$_POST["openid"];
        if(empty($coupons_id)||empty($openid)){
            $this->ajaxreturn(array('errno'=>-1,'message'=>'缺少必要参数！'));
        }
        $res=M("user_coupons")->where("coupons_id=$coupons_id and openid='$openid'")->find();
        if($res){
            $this->ajaxreturn(array('errno'=>-1,'message'=>'不能重复领取'));
        }
        $res=M("user_coupons")->add(array("openid"=>$openid,"coupons_id"=>$coupons_id,"state"=>1));
        $rest=M("coupons")->where("id=$coupons_id")->getField("rest");
        M("coupons")->where("id=$coupons_id")->setField("rest",$rest-1);
        if($res){
            $this->ajaxreturn(array('errno'=>0,'message'=>'领取成功！'));
        }else{
            $this->ajaxreturn(array('errno'=>-1,'message'=>'领取失败！'));
        }

    }
    //选择优惠券
    public function selectCoupon(){
        $goodid=$_POST["goodid"];//商品id
        $usercoupons_id=$_POST["usercoupons_id"];//用户优惠券id
        $Model=new Model();
        $couponInfo=$Model->table(array("wx_coupons"=>"a","wx_user_coupons"=>"b"))->where("a.id=b.coupons_id and b.id=$usercoupons_id")->field("a.*")->find();
        $goodInfo=M("album")->where("id=$goodid")->field("*")->find();
        if($couponInfo){
             if($couponInfo["album_id"]!="0" && $couponInfo["album_id"]!=$goodid){
                 $this->ajaxreturn(array("errno"=>-1,"message"=>"该商品不是优惠券指定的商品，不能使用"));
             }
             if($couponInfo["preferential"]){
                 if($couponInfo["full"]>$goodInfo["price"]){
                     $this->ajaxreturn(array("errno"=>-1,"message"=>"订单满".$couponInfo["full"]."元才可以使用"));
                 }
             }
        }
        $this->ajaxreturn(array("errno"=>0,"message"=>"使用成功"));

    }

    //生成订单
    public function addOrder(){
        $public=new ApiPublicController();
        $realname=$_POST["realname"];//收件人姓名
        $mobile=$_POST["mobile"];//手机号
        $goodid=$_POST["goodid"];
        $type=$_POST["type"];//订单类型 1,科学 2.专辑 3 徽章
        $userCoupon_id=$_POST["usercoupons_id"];//用户优惠券id(用户手动选的)
        $openid=$_POST["openid"];
        $ordersn= date("YmdHis", time()) . rand(1111, 9999);
        $address=$_POST["address"];//收件人地址
        $province=$public->divAddress($address)["province"];
        $city=$public->divAddress($address)["city"];
        $area=$public->divAddress($address)["area"];
        $Model=new Model();
        if(empty($realname)||empty($mobile)||empty($address)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"收件人信息不能为空"));
        }
        if(empty($goodid)||empty($type)||empty($openid)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"缺少必要参数"));
        }
        $table=substr($public->selectTable($type),3);
        if($type=="3"){
            $goodid=$Model->table(array("wx_badge"=>"a","wx_user_badge"=>"b"))->where("b.badgeid=a.id and b.id=$goodid")->getField("a.id");
        }
        $proprice=M("$table")->where("id=$goodid")->getField("price");//正常购买的价格（去除优惠券和花币）
        if(!empty($userCoupon_id)){
            $condition="a.coupons_id=b.id and a.id=$userCoupon_id";
            $coupons=$public->myCoupons($condition);
            $coupons=$public->arrayTo1($coupons,"");
            if($coupons["discount"]){
                $yh=$proprice*floatval(10-$coupons["discount"])/10;
            }
            if($coupons["preferential"]&&$coupons["full"]){
                $yh=$coupons["preferential"];
            }
          }else{
              $yh=0;
        }
        if($type=="3"){
            $realprice=$proprice;
        }else{
            $wallet=M("user")->where("openid='$openid'")->getField("wallet");
            $rm=$proprice-$yh;
            //计算用花币抵扣的钱
            if($rm>0){
                if($wallet>=$rm){
                    $discountprice=$rm;
                }else{
                    $discountprice=$wallet;
                }
            }else{
                $rm=0;
                $discountprice=0;
            }
            $realprice=$rm-$discountprice;//最终付款的金额
        }
         //1 待付款 2已付款(待发货) 3已发货 (待收货)4已完成
         $data=array("openid"=>$openid,"type"=>$type,"goodsid"=>$goodid,"realname"=>$realname,"mobile"=>$mobile,"province"=>$province,
             "city"=>$city,"area"=>$area,"address"=>$address,"createtime"=>time(),"status"=>1,"ordersn"=>$ordersn,"proprice"=>$proprice,
             "discountprice"=>$discountprice,"realprice"=>$realprice,"usercoupons_id"=>$userCoupon_id);
         $res=M("order")->add($data);
         if($res){
             $orderid=$res;
             $this->ajaxreturn(array("errno"=>0,"orderid"=>$orderid,"message"=>"订单生成成功"));
         }else{
             $this->ajaxreturn(array("errno"=>-1,"message"=>"订单生成失败"));
         }
    }
    //支付
    public function pay(){
         $orderid=$_POST["orderid"];
         $attach=$_POST["attach"];//1 商品购买 2 花币充值
         $Model=new Model();
         $pay=new PayController();
         if(empty($orderid)||empty($attach)){
             $this->ajaxreturn(array("errno"=>-1,"message"=>"缺少必要参数"));
         }
         if($attach=="1"){
             $orderInfo=M("order")->where("id=$orderid")->field("type,realprice,ordersn,usercoupons_id,openid,goodsid,ordersn")->find();
             if($orderInfo["realprice"]==0){
                 $pay->payResult(1,$orderInfo["ordersn"]);
                 $this->ajaxreturn(array("errno"=>0,"message"=>"支付成功"));
             }else{
                 $pay->pay($orderid,1);
             }
         }
         if($attach=="2"){
             $pay->pay($orderid,2);
         }
    }
    //花币充值
    public function recharge(){
        $openid=$_POST["openid"];
        $ordersn= date("YmdHis", time()) . rand(1111, 9999);
        $realprice=$_POST["realprice"];
        if(empty($openid)||empty($realprice)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"缺少必要参数"));
        }
        $data=array("openid"=>$openid,"ordersn"=>$ordersn,"time"=>time(),"state"=>1,"realprice"=>$realprice);
        $res=M("recharge_order")->add($data);
        if($res){
            $orderid=$res;
            $this->ajaxreturn(array("orderid"=>$orderid,"errno"=>0,"message"=>"订单生成成功"));
        }else{
            $this->ajaxreturn(array("errno"=>-1,"message"=>"订单生成失败"));
        }

    }
    //充值记录
    public function rechargeRecords(){
        $openid=$_POST["openid"];
        if(empty($openid)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"openid不能为空"));
        }
        $list=M("recharge_order")->where("openid='$openid' and state=2")->field("id,ordersn,time,realprice")->order("time desc")->select();
        if(!empty($list)){
            foreach ($list as $k=>$v){
                $list[$k]["time"]=date("Y-m-d",$v["time"]);
            }
            $this->ajaxreturn(array("errno"=>0,"list"=>$list));
        }else{
            $this->ajaxreturn(array("errno"=>-1,"message"=>"暂时没有充值记录"));
        }

    }
    //历史播放记录(每一集历史)
    public function audioHistory(){
        $page=$_POST['page'];//当前页
        $pindex = max(1, intval($page));//页数
        $psize =$_POST["psize"];
        $openid=$_POST["openid"];
        $Model=new Model();
        if(empty($openid)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"openid不能为空"));
        }
        $logs_science=$Model->query("SELECT id,product_id,`type` FROM wx_audio_play_log WHERE  openid='$openid' and `type`=1  group by product_id order by  `time` desc ");
        $logs_album=$Model->query("SELECT  id,product_id,`type`  FROM wx_audio_play_log WHERE  openid='$openid' and `type`=2  group by product_id order by `time` desc");
        $logs=array_merge($logs_science,$logs_album);
        if(!empty($logs) && is_array($logs)){
            foreach ($logs as $v){
                if($v["type"]=="2"){
                    $table="wx_album_episode";
                    $table2="wx_album";
                    $condition="a.product_id=b.id  and b.album_id=c.id and a.openid='$openid' and a.id=".$v["id"];
                    $field=" a.id as log_id,c.id,b.id as episode_id,c.name as title,b.name,b.cover,b.filepath,a.time,a.type";
                }
                if($v["type"]=="1"){
                    $table="wx_science_audio";
                    $table2="wx_science";
                    $condition="a.product_id=b.id  and b.science_id=c.id and a.openid='$openid' and a.id=".$v["id"];
                    $field="a.id as log_id,c.id,b.id as episode_id,c.cover,b.filepath,a.time,c.name,a.type";
                }
                $list[]=$Model->table(array("wx_audio_play_log"=>"a","$table"=>"b","$table2"=>"c"))->where($condition)->limit(($pindex - 1) * $psize ,  $psize)->field($field)->find();
            }
            if($list && is_array($list)){
                 foreach ($list as $k=>$v){
                     $list[$k]["time"]=date("Y-m-d", $v["time"]);
                     $list[$k]["number"]=M("audio_play_log")->where("product_id=".$v["episode_id"]." and type=".$v["type"])->count();
                     if($list[$k]["type"]=="1"){
                        $list[$k]["title"]="每日科学";
                    }
                    $list[$k]["cover"]="https://{$_SERVER['SERVER_NAME']}"."/".$v["cover"];
                    $list[$k]["filepath"]="https://{$_SERVER['SERVER_NAME']}"."/".$v["filepath"];
                 }
                array_multisort(array_column($list,'time'),SORT_DESC,$list);

                $this->ajaxreturn(array("errno"=>0,"list"=>$list));
            }else{
                $this->ajaxreturn(array("errno"=>-1,"message"=>"暂时没有播放记录"));
            }
        }else{
            $this->ajaxreturn(array("errno"=>-1,"message"=>"获取播放记录失败"));

        }



    }

    //我的徽章
    public function myBadge(){
      $openid=$_POST["openid"];
      if(empty($openid)){
          $this->ajaxreturn(array("errno"=>-1,"message"=>"openid不能为空"));
      }
      $Model=new Model();
      $public=new ApiPublicController();
      //头像
      $avatar=M("user")->where("openid='$openid'")->getField("avatar");
      //徽章个数
      $number=M("user_badge")->where("openid='$openid'")->count();
      //分类
      $category=$Model->query(" select a.rule_id,a.rule_content,b.name from wx_badge as a  left join wx_badge_category as b on a.rule_id=b.rule_id where a.rule_id !='' group by  a.rule_id order by a.rule_id  ");
      $sorts=$Model->query("select openid,count(*) as number from wx_user_badge group by openid ");
      if(!empty($sorts) && is_array($sorts)){
//          array_multisort(array_column($sorts,'number'),SORT_DESC,$sorts);
//          foreach ($sorts as $k=>$v){
//              if($v["openid"]==$openid){
//                  $index=$k+1;
//                  break;
//              }
//          }
          //2019-02-19 排名
          $sorts=$public->rank($public->bubblesort($sorts,"number"),"number");
          $count=count($sorts);
          $index_rank=$count;
          foreach ($sorts as $k=>$v){
              if($v["openid"]==$openid){
                  $index_rank=$v["rank"];
              }
          }
          //排名
          $rank=round(($count-$index_rank)/$count*100,0);
          //分类
          if(!empty($category)&& is_array($category)){
              foreach ($category as $k2=>$v2){
                  //该分类下的所有徽章
                  $list=$Model->query("select id, name,cover as cover,grey_icon  from wx_badge where rule_id=".$v2["rule_id"]);
                  $category[$k2]["total"]=count($list);
                  //已经获得该分类下的徽章个数
                  $has=$Model->table(array("wx_user_badge"=>"a","wx_badge"=>"b"))->where("a.badgeid=b.id and a.openid='$openid' and b.rule_id=".$v2["rule_id"])->field("b.id")->select();
                  $has=$public->arrayTo1($has,"id");
                  $has_number=count($has);
                   if($list ){
                       $list=$public->prefix_img($list);
                       $category[$k2]["has_number"]=$has_number;
                       foreach ($list as $k3=>$v3){
                           if(in_array($v3["id"],$has)){
                               $list[$k3]["flag"]=1;//已经获得
                           }else{
                               $list[$k3]["flag"]=0;
                           }
                       }
                       $category[$k2]["list"]=$list;
                   }else{
                       $category[$k2]["has_number"]=0;
                       $category[$k2]["list"]=array();
                   }
              }
          }

      }else{
          $rank="0"."%";
      }
      $this->ajaxreturn(array("errno"=>0,"avatar"=>$avatar,"number"=>$number,"rank"=>$rank,"category"=>$category));


    }
    //徽章系统-详情及兑换
    public function badgeDetail(){
        $badge_id=$_POST["id"];//徽章id
        $openid=$_POST["openid"];
        if(empty($badge_id)||empty($openid)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"缺少必要参数"));
        }
        $Model=new Model();
        $public=new ApiPublicController();
        $res=M("user_badge")->where("openid='$openid' and badgeid=$badge_id")->field("id,status")->find();
        if($res){
            $has_accieved=1;//已经获得
            $list=$Model->table(array("wx_user_badge"=>"a","wx_badge"=>"b"))->where("a.badgeid=b.id and b.id=$badge_id and a.openid='$openid'")->field("a.id as user_badge_id,a.status,a.createtime,b.name,b.cover,b.grey_icon,b.rule,b.rule_id")->find();

        }else{
            $has_accieved=0;//未获得
            $list=M("badge")->where("id=$badge_id")->field("rule,rule_content,name,rule_id,grey_icon,cover")->find();
            $list['status']=0;
        }
        $list["has_accieved"]=$has_accieved;
        $list["rule"]=$list["rule"]?unserialize($list["rule"]):$list["rule"];
        if($list["createtime"]){
            $list["createtime"]=date("Y-m-d",$list["createtime"]);
        }
        //$list=$public->ruleContent($list);
        $list["cover"]=$list["cover"]?"https://{$_SERVER['SERVER_NAME']}".$list["cover"]:"";
        $list["grey_icon"]=$list["grey_icon"]?"https://{$_SERVER['SERVER_NAME']}".$list["grey_icon"]:$list["grey_icon"];
        //已有多少人获得
        $list["number"]=M("user_badge")->where("badgeid=$badge_id")->count();
        unset($list["rule_id"]);
        unset($list["rule"]);
        $this->ajaxreturn(array("errno"=>0,"list"=>$list));

    }
    //分享绑定上下级(可以重复绑定)
    public function Bind()
    {
        $parent_openid=$_POST["parent_openid"];//上级openid
        $son_openid=$_POST["son_openid"];//下级openid
        if(empty($parent_openid)||empty($son_openid)){
            $this->ajaxreturn(array('errno'=>-1,'message'=>'缺少必要参数'));
        }
        $res=M("fxuser")->where("parent_openid='$parent_openid' and son_openid='$son_openid'")->find();
        $res2=M("fxuser")->where("son_openid='$son_openid'")->find();
        if ($parent_openid == $son_openid) {
               $this->ajaxreturn(array('errno'=>-1,'message'=>'自己不能绑定自己'));
            }else {
               if($res){
                   $this->ajaxreturn(array('errno'=>-1,'message'=>'您已绑定过该上级，不能重复绑定哦'));
               }else{
                   if($res2){
                      $res3=M("fxuser")->where("son_openid='$son_openid'")->save(array("parent_openid"=>$parent_openid,"time"=>time()));
                   }else{
                       $res3 =M("fxuser")->add(array("parent_openid" =>$parent_openid, "son_openid" => $son_openid, "time" => time()));

                   }
                   if ($res3) {
                       $this->ajaxreturn(array('errno'=>0,'message'=>'绑定成功'));
                   } else {
                       $this->ajaxreturn(array('errno'=>-1,'message'=>'绑定失败'));
                   }
               }

            }

    }
    //分享
    public function shareIndex(){
        $openid=$_POST["openid"];
        $Model=new Model();
        $public=new ApiPublicController();
        if(empty($openid)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"openid不能为空"));
        }
        $total=$Model->query("select sum(num) as total from wx_share where parent_openid= '$openid' and state=2");
        $total=$public->arrayTo1($total,"total");
        $list=$Model->table(array("wx_share"=>"a","wx_order"=>"b","wx_album"=>"c"))->where("a.orderid=b.id and b.goodsid=c.id and parent_openid='$openid'")->field("a.id,c.cover,c.name,price,a.num,a.time")->order("a.time desc")->select();
        if($list ){
            $list=$public->prefix_img($list);
            foreach ($list as $k=>$v){
                $list[$k]["time"]=date("Y-m-d",$v["time"]);
            }
            $this->ajaxreturn(array('errno'=>0,'total'=>$total,"list"=>$list));
        }else{
            $list=array();
            $this->ajaxreturn(array('errno'=>0,'total'=>0,"list"=>$list,"message"=>"暂时还没有历史记录"));

        }
    }
    // 返回首页展示获取的徽章
    public function badgeShow(){
        //判断当前用户是否满足这6种规则中的一个或多个 规则是固定的
        $_openid=$_POST["openid"];
        if(empty($_openid)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"openid不能为空"));
        }
        $public=new ApiPublicController();
        $Model=new Model();

        #2 订单数量
        $count1=M("order")->where("openid='$_openid' and status in(2,3,4)")->count();
        #3 分享订单数量
        $count2=M("share")->where("parent_openid='$_openid'")->count();
        #4 收听时长
        $duration=$Model->query("select sum(duration) as duration from wx_audio_play_log where openid='$_openid'");
        $duration=$public->arrayTo1($duration,"duration");
        #5 连续收听天数
        $logs=M("audio_play_log")->where("openid='$_openid'")->field("time")->select();
        $rule_id=M("badge")->field("id,rule_id,rule")->select();
        if($rule_id && is_array($rule_id)){
            $flag=0;//0 没有满足徽章获取条件  1 满足获取条件
            $id=array();//用户获取的徽章id
            foreach($rule_id as $k=>$v){
                $rule=unserialize($v["rule"]);
                $return=M("user_badge")->where("openid='$_openid' and badgeid=".$v["id"])->find();
                switch ($v["rule_id"]){
                     case "1":
                          //查播放记录表是否满足该条件

                         foreach ($rule as $k2=>$v2){
                             if($k2=="science"){
                                 $rule_science[]=$v2["sespids"];
                             }elseif($k2=="album"){
                                 $rule_album[]=$v2["sespids"];
                             }
                         }
                         //2019-03-05 修复徽章获取Bug
                         $rule_science=$public->arrayTo1($rule_science,"");
                         $rule_album=$public->arrayTo1($rule_album,"");
                         $rule_length=0;
                         if(!empty($rule_science)&&!empty($rule_album)){
                             $rule_length=count(array_merge($rule_science,$rule_album));
                         }
                         if(!empty($rule_album)&&empty($rule_science)){
                             $rule_length=count($rule_album);
                         }
                         if(!empty($rule_science)&& empty($rule_album)){
                             $rule_length=count($rule_science);
                         }
                         if(!empty($rule_science)){
                             foreach ($rule_science as $k3=>$v3){
                                 $res=M("audio_play_log")->where("openid='$_openid'"." and type=1 and product_id=".$v3)->find();
                                 if(empty($res)){
                                     break;
                                 }else{
                                     $flag++;
                                 }
                             }
                         }
                        if(!empty($rule_album)){
                            foreach ($rule_album as $k4=>$v4){
                                $res=M("audio_play_log")->where("openid='$_openid'"." and type=2 and product_id=".$v4)->find();
                                if(empty($res)){
                                    break;
                                }else{
                                    $flag++;
                                }
                            }
                        }
                         if($rule_length==$flag){
                             array_push($id,$v["id"]); //用户获取的徽章id
                             if(empty($return)){
                                 $public->addBadge_log($v["id"],$_openid);
                             }
                         }
                         break;
                     case "2":
                         //购买订单数
                          if($count1==$rule["ordercounts"]){
                              array_push($id,$v["id"]);

                              if(empty($return)){
                                  $public->addBadge_log($v["id"],$_openid);
                              }

                          }
                         break;
                     case "3":
                         //分享订单数
                         if($count2==$rule["shareorders"]){
                             array_push($id,$v["id"]);
                             if(empty($return)){
                                 $public->addBadge_log($v["id"],$_openid);
                             }

                         }
                         break;
                     case "4":
                         //收听时长
                          if($duration>=$rule["duration"]){
                              array_push($id,$v["id"]);
                              if(empty($return)){
                                  $public->addBadge_log($v["id"],$_openid);
                              }
                          }
                         break;
                     case "5":
                         //连续使用天数
                         $serial=$public->continousDay($logs);
                         if(in_array(intval($rule["days"]),$serial)){
                             array_push($id,$v["id"]);
                             if(empty($return)){
                                 $public->addBadge_log($v["id"],$_openid);
                             }
                         }
                         break;

                     case "6":
                         //其他徽章
                          $user_badges=M("user_badge")->where("openid='$_openid'")->field("badgeid")->select();
                          $user_badges=$public->arrayTo1($user_badges,"badgeid");
                          if(!array_diff($user_badges,$v["badgeids"])){
                              array_push($id,$v["id"]);
                              if(empty($return)){
                                  $public->addBadge_log($v["id"],$_openid);
                              }
                          }
                         break;
                 }
            }
        }
        $list=$Model->table(array("wx_user_badge"=>"a","wx_badge"=>"b"))->where("a.badgeid=b.id and a.openid='$_openid' and a.has_show=0")->field("a.id as user_badge_id,b.id,b.name,b.cover as cover,b.rule_content ")->order("a.createtime desc")->select();
        #2019-3-13 更新 浏览量
        $fnopenid=$_openid;
        $userinfo=M('user')->where("openid='$fnopenid'")->find();
        #浏览量日志
        $logdata=array(
            'openid'=>$fnopenid,
            'time'=>time()
        );
        #todo 游客量：进入但未授权用户，这个不用去重，访问一次算一次
        if (empty($userinfo['authtime'])){
            M('tourist_log')->add($logdata);
        }
        #todo 访客量：浏览量去重，单日每个账号无论访问多少次都只算1次
        $starttime=strtotime(date('Y-m-d'));//strtotime();
        $endtime=strtotime(date('Y-m-d').' 23:59:59');
        $visitlog=M('visitor_perday_log')->where(" time > {$starttime} and time < {$endtime} and openid='{$fnopenid}'")->find();
        if(empty($visitlog)){
            M('visitor_perday_log')->add($logdata);
        }
        #todo 浏览量：访问一次算一次，所有访问都算 visit_log
        M('visit_log')->add($logdata);

        if($list && is_array($list)){
            $list=$public->prefix_img($list);
            $this->ajaxreturn(array("errno"=>"0","list"=>$list));
        }else{
            $this->ajaxreturn(array("errno"=>"0","list"=>array(),"message"=>"您暂时还没有获得徽章"));
        }
    }
    //关闭徽章弹窗
    public function close(){
        $id=$_POST["user_badge_id"];//用户徽章id
        if(!empty($id) ){
             $ids=explode(',',$id);
            foreach ($ids as $v){
                M("user_badge")->where("id=$v")->setField("has_show",1);
            }
            $this->ajaxreturn(array("errno"=>"0","message"=>"关闭成功"));

        }else{
            $this->ajaxreturn(array("errno"=>"-1","message"=>"用户徽章id不能为空"));
        }

    }
    //确认收货
    public function confirmRecive(){
        $id=$_POST["id"];//订单id
        if(empty($id)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"订单id不能为空"));
        }
        $res=M("order")->where("id=$id")->setField("status",4);
        if($res){
            $this->ajaxreturn(array("errno"=>0,"message"=>"收货成功"));
        }else{
            $this->ajaxreturn(array("errno"=>-1,"message"=>"收货失败"));

        }
    }
   //查看物流(及时查询)
    public function logistics(){
        $id=$_POST["id"];
        $eBusinessID="1292967";//测试
        $appKey="32e7cbf2-eb31-498a-a992-2c9092424bab";//测试
        $requstUrl="http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx";
        if(empty($id)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"订单id不能为空"));
        }
        $public=new ApiPublicController();
        $orderInfo=M("order")->where("id=$id")->field("ordersn,shippercode,expressno")->find();
        if(!empty($orderInfo)){
            $logisticResult=$public->getOrderTracesByJson($orderInfo["ordersn"],$orderInfo["shippercode"],$orderInfo["expressno"],$eBusinessID,$appKey,$requstUrl);
            $logisticResult=json_decode($logisticResult,true);

        }else{
            $this->ajaxreturn(array("errno"=>-1,"message"=>"订单信息获取失败"));
        }
        if($logisticResult["Success"]=="true"||$logisticResult["Success"]==true){
            # 数据库倒序
            $logisticResult['Traces'] = array_reverse($logisticResult['Traces']);
            unset($logisticResult["EBusinessID"]);
            unset($logisticResult["Success"]);
            $this->ajaxreturn(array("errno"=>0,"list"=>$logisticResult,));
        }else{
            $this->ajaxreturn(array("errno"=>-1,"message"=>$logisticResult["Reason"]));

        }

    }
    //生成海报
    public function poster(){
        $id=$_POST["id"]=4;//徽章id
        if(empty($id)){
            //$this->ajaxreturn(array("errno"=>-1,"message"=>"徽章id不能为空"));
        }
        $path=M("badge")->where("id=$id")->getField("cover");
        if($path){
            $filepath="."."$path";
        }
        $src="./"."Public/upload/image/imageqrcode.png";

 //第一：设定标头，告诉浏览器你要生成的MIME 类型


 //第二：创建一个画布，以后的操作都将基于此画布区域
         $codew = 240;
         $codeh = 380;
         //$codeimg = imagecreatetruecolor($codew, $codeh);
        $codeimg=imagecreatefrompng("./Public/upload/20190128114340.png");

         //获取画布颜色
         $red = imagecolorallocate($codeimg, 255, 0, 0);
         $white = imagecolorallocate($codeimg, 255, 255, 255);
         $green = imagecolorallocate($codeimg, 75, 222, 26);
         //第三：填充画布背景颜色
         imagefill($codeimg, 0, 0, $white);

         //第四：绘制线条 + 填充文字...
         imageline($codeimg, 0, 00, 30, 60, $white);
         imageline($codeimg, 0, 00, 50, 60, $white);
         imageline($codeimg, 0, 00, 80, 60, $white);

         #徽章和二维码
        $badge = imagecreatefromstring(file_get_contents($filepath));
        list($src_w, $src_h) = getimagesize($filepath);

        imagecopyresized($codeimg, $badge,  70, 100, 0, 0, 160, 190, $src_w, $src_h);

        $qrcode = imagecreatefromstring(file_get_contents($src));
        list($src_w2, $src_h2) = getimagesize($src);
        imagecopyresized($codeimg, $qrcode, 30, 340, 0, 0,80, 80, $src_w2, $src_h2);

        header("Content-type: image/png");
        //imagepng($codeimg);
         //第五：输出创建的画布
        /*
     /            //第六：销毁画布
              imagedestroy($codeimg);*/
        //4 保存
        $time=date('YmdHis');
        $posterreturn="https://{$_SERVER['SERVER_NAME']}"."/"."Public/upload/image/poster/".$time."."."png";
        $poster="/yjdata/www/www/bidexcx/Public/upload/image/poster/".$time."."."png";
        imagepng($codeimg,$poster);
        imagedestroy($codeimg);
        $this->ajaxreturn(array("errno"=>0,"posturl"=>$posterreturn));

    }
    //小程序码
    public function poster2()
    {
            function getaccess_token()
            {
                global $_W, $_GPC;
                $appid = "wx6787b1f09db904ea";
                $secret = "a9a8ff34dc9886d507390aa310b661cd";
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret . '';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $data = curl_exec($ch);
                curl_close($ch);
                $data = json_decode($data, true);
                return $data["access_token"];
            }
            function set_msg()
            {
                $access_token = getaccess_token();
                $data2 = array("scene" => 1, "width" => 400);
                $data2 = json_encode($data2);
                $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $access_token . '';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data2);
                $data = curl_exec($ch);
                curl_close($ch);
                return $data;
            }
            $res = set_msg();
            $result = json_decode($res, 1);
           if (isset($result['errcode']) && $result['errcode'] != 0) {
             $this->ajaxreturn(array("errno"=>-1,"message"=>"二维码获取失败"));
           }
            $path="./Public/upload/image";
            if(!is_dir($path)){
                mkdir($path);
            }
            $filepath=$path."/"."qrcode.png";
            $return=file_put_contents($filepath, $res);

    }
    //优惠券获取提示
    public function coupons_show(){

        $str_start=date("Y-m-d",time())." 0:0:0";
        $start=strtotime($str_start);
        $str_end=date("Y-m-d",time())." 23:59:59";
        $end=strtotime($str_end);
        $openid=$_POST["openid"];
        if(empty($openid)){
            $this->ajaxreturn(array("errno"=>-1,"message"=>"openid不能为空"));
        }
        $number=M("user_coupons")->where("openid='$openid' and state=1 and gettime>=$start and gettime<=$end and is_show=1")->count();
        if($number>0){
            $this->ajaxreturn(array("errno"=>0,"number"=>$number));
        }
        $this->ajaxreturn(array("errno"=>-1,"number"=>0,"message"=>"没有新增优惠券"));

    }






}