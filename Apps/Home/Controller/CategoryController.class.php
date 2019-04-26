<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Think\Exception;


class CategoryController extends Controller{

    public function index()
    {
        $Model = new Model();
        $list=$Model->query('select * from wx_servicecategory order by sortid,id asc');
        foreach ($list as $value){
            $name[]=$value["name"];
        }
        $arr[]=array('list'=>$list,'name'=>$name);
        $this->ajaxReturn($arr,json);
    }


    public function IndexCategory()
    {
        $Model = new Model();
        $list=$Model->query('select * from wx_servicecategory order by sortid,id asc');
        for($i=0;$i<count($list);$i++){
            $ordernum=$Model->query("select count(id) as ordernum from wx_order where servicename='".$list[$i]["name"]."' and status=1");
            $list[$i]["ordernum"]=$ordernum[0]["ordernum"];
            $name[]=$list[$i]["name"];
            $ordernum="";
        }

        $arr[]=array('list'=>$list,'name'=>$name);
        $this->ajaxReturn($arr,json);
    }

}