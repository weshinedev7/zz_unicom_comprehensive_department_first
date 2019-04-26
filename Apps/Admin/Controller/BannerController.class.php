<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class BannerController extends Controller {



    public function index()
	{
		if(session('username') != null)
		{
			$Banner=M('banner');
			$count = $Banner->count();// 查询满足要求的总记录数
			$Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数
			$show = $Page->show();// 分页显示输出
			// 进行分页数据查询
			$Model = new Model();
            $list=$Model->query('select * from wx_banner  order by id desc limit '.$Page->firstRow.','.$Page->listRows.'');
			foreach ($list as &$row){
			    $row['time']=date('Y-m-d',time());
			    $row['url']='https://'.$_SERVER['SERVER_NAME'].'/'.$row['url'];
            }

            $this->assign('count',$count);
			$this->assign('list',$list);// 赋值数据集
			$this->assign('page',$show);// 赋值分页输出
			$this->assign('empty','<tr><td colspan=4>暂时没有数据</td></tr>');
			$this->display();
		}
    }
	
	public function add()
	{
		header("Content-Type:text/html; charset=utf-8");
//        $album=M('album')->field('name,id')->select();
//
//
//        $this->assign('album',$album);
		$this->display(); // 输出模板
	}
	
	public function edit()
	{
		header("Content-Type:text/html; charset=utf-8");
		$id=$_GET["i"];
		
		$Banner=M('banner');
		$list = $Banner->where("id=$id")->select();
        $list[0]['url']=$list[0]['url'];
//		$album=M('album')->field('name,id')->select();
//
//        $this->assign('album',$album);
		$this->assign('list',$list);

		$this->display(); // 输出模板
	}
	
	public function insert()
	{
		header("Content-Type:text/html; charset=utf-8");

        $Banner=M("banner");
        $data["url"] = $_POST["url"];
        $data["time"] =time();
        print_r($data);
        exit();

		$arr['flag']=0;
		if($Banner->create())
		{
			$result=$Banner->add($data);
		
			if($result)
			{
				$arr['flag']=1;
                $this->ajaxReturn($arr,json);
                //return json_encode($arr);
			}
			else
			{
				$this->ajaxReturn($arr,json);
			}
		}
		else
		{
			$this->ajaxReturn($arr,json);
		}
	}

	
	public function save()
	{
		header("Content-Type:text/html; charset=utf-8");
        $Banner=M("banner");
        $data["url"] = $_POST["url"];
//        $data["album_id"] = $_POST["album_id"];
        $data["time"] =time();
		$id=$_POST["id"];
		$arr['flag']=0;
		if($Banner->create())
		{
			$result=$Banner->where("id=$id")->save($data);
			if($result!==false)
			{
				$arr['flag']=1;
                $this->ajaxReturn($arr,json);
			}
			else
			{
				$this->ajaxReturn($arr,json);
			}
		}
	}
	
	public function del()
	{
		header("Content-Type:text/html; charset=utf-8");
		$id=$_POST["id"];
        $Banner = M("banner"); // 实例化User对象
		$result=$Banner->delete($id);
		$arr['flag']=0;
		if($result)
		{
			$arr['flag']=1;
			$this->ajaxReturn($arr,json);
		}
		else
		{
			$this->ajaxReturn($arr,json);
		}
	}
	
	
	public function loginout(){
        session('username',null);
		$url="/admin/";
		$this->redirect($url);
    }
	
	
}