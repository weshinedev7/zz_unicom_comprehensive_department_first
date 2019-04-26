<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class ApiVideoController extends Controller {
    //处理前端传来的临时视屏路径start
    public function upload($file){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =  20971520 ;// 设置附件上传大小 100m
        $upload->exts      =  array('mp4', 'avi', 'wmv', 'mov','mpeg','mkv','flv','f4v','vob','cd','wave','aiff','mpeg','mp3','mpge-4','midi','wma','vqf','amr','ape','flac');// 设置附件上传类型
        $upload->rootPath  =  'Public/upload/video/'; // 设置附件上传根目录
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

    public function video(){
        $file = $_FILES['file'];
        if($file){
            $files = $this->upload($file);
            $img = 'Public/upload/video/'.$files;
        }else{
            $img = $_POST['video'];
        }
        $this->ajaxReturn(array('video'=>$img,'files'=>$file));
    }

}
