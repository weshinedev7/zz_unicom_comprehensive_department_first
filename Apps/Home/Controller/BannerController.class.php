<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Think\Exception;


class BannerController extends Controller{

    public function index()
    {
        $Model = new Model();
        $list=$Model->query('select * from wx_banner order by id asc');
        $this->ajaxReturn($list,json);
    }


}