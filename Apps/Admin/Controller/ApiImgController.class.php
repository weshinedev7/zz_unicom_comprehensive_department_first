<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class ApiImgController extends Controller {
    //处理前端传来的临时图片路径start
    public function upload($file){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =  3145728 ;// 设置附件上传大小 3m
        $upload->exts      =  array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =  'Public/upload/image/'; // 设置附件上传根目录
        is_dir($upload->rootPath) OR mkdir($upload->rootPath, 0777, true);
        $upload->savePath  =   ''; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->uploadOne($file);

        if(!$info) {// 上传错误提示错误信息
            return $this->error($upload->getError());
        }else{// 上传成功
            return $info['savepath'].$info['savename'];
        }

    }

    public function img(){
        $file = $_FILES['file'];
        if($file){
            $files = $this->upload($file);
            $img = 'Public/upload/image/'.$files;
        }else{
            $img = $_POST['img'];
        }
        $this->ajaxReturn(array('img'=>$img,'files'=>$file));
    }

}
