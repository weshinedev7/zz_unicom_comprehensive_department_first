<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Think\Exception;


class QrcodeController extends Controller{

    public function index()
    {

    }


    public function GetShareQrcode(){
        $Model = new Model();

        $id=$_POST["id"];
        $openid=$_POST["openid"];
        $title=$_POST["title"];
        $description=$_POST["description"];

        $Model = new Model();
        $user=$Model->query("select * from wx_user where openid='".$openid."'");
        $avatar=$user[0]["avatar"];
        $nickname=$user[0]["nickname"];

        $access_token = $this->AccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token='.$access_token;
        $path="pages/active/active?id=".$id;
        $width=430;
        $data='{"path":"'.$path.'","width":'.$width.'}';
        $return = $this->request_post($url,$data);
        //将生成的小程序码存入相应文件夹下
        $time=time();
        $file_url='./Public/wxappqrcode/'.$openid.'-active'.$id.'.png';
        file_put_contents($file_url,$return);

        $config = array(
            'text'=>array(
                array(
                    'text'=>$nickname,
                    'left'=>174,
                    'top'=>305,
                    'fontPath'=>'./Public/font/FZY4JW.TTF',
                    'fontSize'=>14,
                    'fontColor'=>'34,34,34',
                    'angle'=>0,
                ),
                array(
                    'text'=>$title,
                    'left'=>55,
                    'top'=>410,
                    'fontPath'=>'./Public/font/FZY4JW.TTF',
                    'fontSize'=>20,
                    'fontColor'=>'34,34,34',
                    'angle'=>0,
                ),
                array(
                    'text'=>$description,
                    'left'=>55,
                    'top'=>470,
                    'fontPath'=>'./Public/font/FZY4JW.TTF',
                    'fontSize'=>18,
                    'fontColor'=>'34,34,34',
                    'angle'=>0,
                )
            ),
            'image'=>array(
                array(
                    'url'=>$avatar,
                    'left'=>216,
                    'top'=>189,
                    'stream'=>0,
                    'right'=>0,
                    'bottom'=>0,
                    'width'=>74,
                    'height'=>74,
                    'opacity'=>100
                ),
                array(
                    'url'=>$file_url,
                    'left'=>190,
                    'top'=>625,
                    'stream'=>0,
                    'right'=>0,
                    'bottom'=>0,
                    'width'=>120,
                    'height'=>137,
                    'opacity'=>100
                ),
            ),
            'background'=>'./Public/public/images/bg.png',
        );

        $filename = './Public/poster/'.$openid.'-active'.$id.'.png';
        $this->createPoster($config,$filename);

        //'url'=>$this->round($avatar),

        $arr=array("qrcode"=>__ROOT__.'/Public/poster/'.$openid.'-active'.$id.'.png');
        $this->ajaxReturn($arr,json);

    }


    public function createPoster($config=array(),$filename=""){

        $background = $config['background'];//海报最底层得背景
        //背景方法
        $backgroundInfo = getimagesize($background);
        $backgroundFun = 'imagecreatefrom'.image_type_to_extension($backgroundInfo[2], false);
        $background = $backgroundFun($background);
        $backgroundWidth = imagesx($background);  //背景宽度
        $backgroundHeight = imagesy($background);  //背景高度
        $imageRes = imageCreatetruecolor($backgroundWidth,$backgroundHeight);
        $color = imagecolorallocate($imageRes, 255, 255, 255);
        imageColorTransparent($imageRes, $color);  //颜色透明
        imagefill($imageRes, 0, 0, $color);
        imagecopyresampled($imageRes,$background,0,0,0,0,imagesx($background),imagesy($background),imagesx($background),imagesy($background));
        //处理了图片
        if(!empty($config['image'])){
            foreach ($config['image'] as $key => $val) {
                $info = getimagesize($val['url']);
                $function = 'imagecreatefrom'.image_type_to_extension($info[2], false);
                if($val['stream']){   //如果传的是字符串图像流
                    $info = getimagesizefromstring($val['url']);
                    $function = 'imagecreatefromstring';
                }
                $res = $function($val['url']);
                $resWidth = $info[0];
                $resHeight = $info[1];
                //建立画板 ，缩放图片至指定尺寸
                $canvas=imagecreatetruecolor($val['width'], $val['height']);
                imagefill($canvas, 0, 0, $color);
                //关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
                imagecopyresampled($canvas, $res, 0, 0, 0, 0, $val['width'], $val['height'],$resWidth,$resHeight);
                $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']) - $val['width']:$val['left'];
                $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']) - $val['height']:$val['top'];
                //放置图像
                imagecopymerge($imageRes,$canvas, $val['left'],$val['top'],$val['right'],$val['bottom'],$val['width'],$val['height'],$val['opacity']);//左，上，右，下，宽度，高度，透明度
            }
        }
        //处理文字
        if(!empty($config['text'])){
            foreach ($config['text'] as $key => $val) {

                list($R,$G,$B) = explode(',', $val['fontColor']);
                $fontColor = imagecolorallocate($imageRes, $R,$G,$B);
                $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']):$val['left'];
                $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']):$val['top'];
                $content = "";
                $letter="";

                for ($i=0;$i<mb_strlen($val['text']);$i++) {
                    $letter[] = mb_substr($val['text'], $i, 1);
                }
                foreach ($letter as $l) {
                    $teststr = $content." ".$l;
                    $fontBox = imagettfbbox($val['fontSize'], 0, $val['fontPath'], $teststr);
                    if (($fontBox[2] > 400) && ($content !== "")) {
                        $content .= "\n";
                    }
                    $content .= $l;
                }

                $content=mb_convert_encoding($content, "html-entities", "utf-8");
                imagettftext($imageRes,$val['fontSize'],$val['angle'], ceil((400 - $fontBox[2]) / 2)+50,$val['top'],$fontColor,$val['fontPath'],$content);
                $config['text']="";
            }
        }
        //生成图片
        if(!empty($filename)){
            $res = imagejpeg ($imageRes,$filename,90); //保存到本地
            imagedestroy($imageRes);
            if(!$res) return false;
            return $filename;
        }else{
            imagejpeg ($imageRes);     //在浏览器上显示
            imagedestroy($imageRes);
        }
    }


    public function AccessToken(){
        $setting=M('setting')->find();
        $appid=$setting['appid'];
        $appsecret=$setting['appsecret'];
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
        $AccessToken = $this->request_post($url);
        $AccessToken = json_decode($AccessToken , true);
        $AccessToken = $AccessToken['access_token'];
        return $AccessToken;
    }
    public function request_post($url, $data){
        $ch = curl_init();
        $header = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }else{
            return $tmpInfo;
        }
    }


    public function round($url,$path='./Public/avatar/'){
        $w = 110;  $h=110; // original size
        $original_path= $url;
        $dest_path = $path.uniqid().'.png';
        $src = imagecreatefromstring(file_get_contents($original_path));
        $newpic = imagecreatetruecolor($w,$h);
        imagealphablending($newpic,false);
        $transparent = imagecolorallocatealpha($newpic, 0, 0, 0, 127);
        $r=$w/2;
        for($x=0;$x<$w;$x++)
            for($y=0;$y<$h;$y++){
                $c = imagecolorat($src,$x,$y);
                $_x = $x - $w/2;
                $_y = $y - $h/2;
                if((($_x*$_x) + ($_y*$_y)) < ($r*$r)){
                    imagesetpixel($newpic,$x,$y,$c);
                }else{
                    imagesetpixel($newpic,$x,$y,$transparent);
                }
            }
        imagesavealpha($newpic, true);
        imagepng($newpic, $dest_path);
        imagedestroy($newpic);
        imagedestroy($src);
        // unlink($url);
        return $dest_path;
    }


}