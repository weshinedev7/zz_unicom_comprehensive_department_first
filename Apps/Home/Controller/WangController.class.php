<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Think\Exception;


class WangController extends Controller{

    public function index()
    {
        $Model = new Model();
        $list=$Model->query('select * from jz_wang order by id asc');
        $this->ajaxReturn($list,json);
    }


}