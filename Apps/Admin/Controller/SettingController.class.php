<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class SettingController extends Controller {

    public function index()
	{
		if(session('username') != null)
		{
			$Setting=M('setting');
			$count = $Setting->count();// 查询满足要求的总记录数
			$Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数
			$show = $Page->show();// 分页显示输出
			// 进行分页数据查询
			$Model = new Model();
            $list=$Model->query('select * from wx_setting order by id desc limit '.$Page->firstRow.','.$Page->listRows.'');
			$this->assign('count',$count);
			$this->assign('list',$list);// 赋值数据集
			$this->assign('page',$show);// 赋值分页输出
			$this->assign('empty','<tr><td colspan=3>暂时没有数据</td></tr>');
			$this->display();
		}
    }
	
	public function edit()
	{
		header("Content-Type:text/html; charset=utf-8");
		$id=$_GET["i"];
		
		$Setting=M('setting');
		$list = $Setting->where("id=$id")->select();
		$this->assign('list',$list);

		$this->display(); // 输出模板
	}

	public function save()
	{
		header("Content-Type:text/html; charset=utf-8");
        $Setting=M("setting");
	/*	$data["appid"] = $_POST["appid"];
		$data["appsecret"] = $_POST["appsecret"];
        $data["apoint"] = $_POST["apoint"];
        $data["cpoint"] = $_POST["cpoint"];
        $data["zpoint"] = $_POST["zpoint"];*/
        $data["recommend"] = $_POST["recommend"];

		$id=$_POST["id"];
		$arr['flag']=0;
		if($Setting->create())
		{
			$result=$Setting->where("id=$id")->save($data);
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

	
	
	public function loginout(){
        session('username',null);
		$url="/admin/";
		$this->redirect($url);
    }
	
	
}