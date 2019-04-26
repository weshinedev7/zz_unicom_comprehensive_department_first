<?php
// +----------------------------------------------------------------------
// | WSHOTO [ 技术主导，服务至上，提供微信端解决方案 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2020 http://www.wshoto.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: FeoniX <janksomn@qq.com>
// +----------------------------------------------------------------------
/**
 * 图片处理服务类
 * 使用php扩展服务Imagick实现
 * ImageMagick
 */
namespace Think;
class Imagick
{
    private $image = null;
    private $type = null;
    private $width = null;
    private $height = null;

    // 构造函数
    public function __construct()
    {
    }
    // 析构函数
    public function __destruct()
    {
        if ($this->image !== null) {
            $this->image->destroy();
        }
    }
    public function __get($name)
    {
        $method = 'get_' . $name;
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        return null;
    }
    public function __set($name, $param)
    {
        $method = 'set_' . $name;
        if (method_exists($this, $method)) {
            $this->$method($param);
        }
    }
    /**
     * 设置图像对象
     * @param $im    [Imagick object] 源图像对象
     */
    public function set_image($im)
    {
        $this->image = $im;
        if ($this->image) {
            $this->type = strtolower($this->image->getImageFormat());
        }
    }
    /**
     * 获取图像对象
     * @return $image [Imagick object] 图像对象
     */
    public function get_image()
    {
        return $this->image;
    }
    /**
     * 从文件路径载入图像
     * @param $path string 图片文件路径
     */
    public function open($path)
    {
        $this->image = new Imagick($path);
        if ($this->image) {
            $this->type = strtolower($this->image->getImageFormat());
        }
        return $this->image;
    }
    /**
     * 创建一个 Imagick 图片对象，同时可以附带设置一个 ImagickPixel 值作为其背景色
     * @param $width     int     图像宽度
     * @param $height    int     图像高度
     * @param $bg_color  string  图像填充背景色(支持"red|green|blue|..."、"#FFFFFF"、"rgb(255,255,255)")
     * @param $format    string  图像格式('jpeg'|'png'|...)
     */
    public function newimage($width, $height, $bg_color, $format = 'jpeg')
    {
        $this->image = new Imagick();
        if ($this->image) {
            $this->image->newImage($width, $height, new ImagickPixel($bg_color), $format);
        }
    }
    /**
     * 从二进制字符串中创建图片
     * @param $blob  [binary string] 图像二进制字符串
     */
    public function readImageFromBlob($blob)
    {
        $this->image = new Imagick();
        if ($this->image) {
            $this->image->readImageBlob($blob);
        }
    }
    /**
     * 图片裁剪
     * 裁剪规则：
     *   1. 高度为空或为零   按宽度缩放 高度自适应
     *   2. 宽度为空或为零  按高度缩放 宽度自适应
     *   3. 宽度，高度到不为空或为零  按宽高比例等比例缩放裁剪  默认从头部居中裁剪
     * @param number $width
     * @param number $height
     */
    public function resize($width = 0, $height = 0)
    {
        if ($width == 0 && $height == 0) {
            return;
        }

        $color = ''; // 'rgba(255,255,255,1)';
        $size = $this->image->getImagePage();
        //原始宽高
        $src_width = $size['width'];
        $src_height = $size['height'];

        //按宽度缩放 高度自适应
        if ($width != 0 && $height == 0) {
            if ($src_width > $width) {
                $height = intval($width * $src_height / $src_width);

                if ($this->type == 'gif') {
                    $this->_resizeGif($width, $height);
                } else {
                    $this->image->thumbnailImage($width, $height, true);
                }
            }
            return;
        }
        //按高度缩放 宽度自适应
        if ($width == 0 && $height != 0) {
            if ($src_height > $height) {
                $width = intval($src_width * $height / $src_height);

                if ($this->type == 'gif') {
                    $this->_resizeGif($width, $height);
                } else {
                    $this->image->thumbnailImage($width, $height, true);
                }
            }
            return;
        }
        //缩放的后的尺寸
        $crop_w = $width;
        $crop_h = $height;

        //缩放后裁剪的位置
        $crop_x = 0;
        $crop_y = 0;

        if (($src_width / $src_height) < ($width / $height)) {
            //宽高比例小于目标宽高比例  宽度等比例放大      按目标高度从头部截取
            $crop_h = intval($src_height * $width / $src_width);
            //从顶部裁剪  不用计算 $crop_y
        } else {
            //宽高比例大于目标宽高比例   高度等比例放大      按目标宽度居中裁剪
            $crop_w = intval($src_width * $height / $src_height);
            $crop_x = intval(($crop_w - $width) / 2);
        }

        if ($this->type == 'gif') {
            $this->_resizeGif($crop_w, $crop_h, true, $width, $height, $crop_x, $crop_y);
        } else {
            $this->image->thumbnailImage($crop_w, $crop_h, true);
            $this->image->cropImage($width, $height, $crop_x, $crop_y);
        }
    }
    /**
     * 处理gif图片 需要对每一帧图片处理
     * @param unknown $t_w  缩放宽
     * @param unknown $t_h  缩放高
     * @param string $isCrop  是否裁剪
     * @param number $c_w  裁剪宽
     * @param number $c_h  裁剪高
     * @param number $c_x  裁剪坐标 x
     * @param number $c_y  裁剪坐标 y
     */
    private function _resizeGif($t_w, $t_h, $isCrop = false, $c_w = 0, $c_h = 0, $c_x = 0, $c_y = 0)
    {
        $dest = new Imagick();
        $color_transparent = new ImagickPixel("transparent"); //透明色
        foreach ($this->image as $img) {
            $page = $img->getImagePage();
            $tmp = new Imagick();
            $tmp->newImage($page['width'], $page['height'], $color_transparent, 'gif');
            $tmp->compositeImage($img, Imagick::COMPOSITE_OVER, $page['x'], $page['y']);

            $tmp->thumbnailImage($t_w, $t_h, true);
            if ($isCrop) {
                $tmp->cropImage($c_w, $c_h, $c_x, $c_y);
            }

            $dest->addImage($tmp);
            $dest->setImagePage($tmp->getImageWidth(), $tmp->getImageHeight(), 0, 0);
            $dest->setImageDelay($img->getImageDelay());
            $dest->setImageDispose($img->getImageDispose());

        }
        $this->image->destroy();
        $this->image = $dest;
    }
    /**
     * 更改图像大小
     * @param $width    目标图片宽度
     * @param $height   目标图片高度
     * @param $fit      适应大小方式
     * 'force': 把图片强制变形成 $width * $height 大小
     * 'scale': 按比例在安全框 $width * $height 内缩放图片, 输出缩放后图像大小 不完全等于 $width * $height
     * 'scale_fill': 按比例在安全框 $width * $height 内缩放图片，安全框内没有像素的地方填充色,
     * 使用此参数时可设置背景填充色 $bg_color = array(255,255,255)(红,绿,蓝, 透明度)
     * 透明度(0不透明-127完全透明)) 其它: 智能模能 缩放图像并载取图像的中间部分 $width * $height 像素大小
     * $fit = 'force','scale','scale_fill' 时： 输出完整图像
     * $fit = 图像方位值 时, 输出指定位置部分图像 字母与图像的对应关系如下:
     * north_west   左上角
     * north        上边
     * north_east   右上角
     * west         左边
     * center       中间
     * east         右边
     * south_west   左下角
     * south        下边
     * south_east   右下角
     * @param $fill_color   填充颜色和透明度
     */
    public function resize_to($width = 100, $height = 100, $fit = 'center', $fill_color = array(255, 255, 255, 0))
    {
        switch ($fit) {
            case 'force':
                if ($this->type == 'gif') {
                    $image = $this->image;
                    $canvas = new Imagick();

                    $images = $image->coalesceImages();
                    foreach ($images as $frame) {
                        $img = new Imagick();
                        $img->readImageBlob($frame);
                        $img->thumbnailImage($width, $height, false);

                        $canvas->addImage($img);
                        $canvas->setImageDelay($img->getImageDelay());
                    }
                    $image->destroy();
                    $this->image = $canvas;
                } else {
                    $this->image->thumbnailImage($width, $height, false);
                }
                break;
            case 'scale':
                if ($this->type == 'gif') {
                    $image = $this->image;
                    $images = $image->coalesceImages();
                    $canvas = new Imagick();
                    foreach ($images as $frame) {
                        $img = new Imagick();
                        $img->readImageBlob($frame);
                        $img->thumbnailImage($width, $height, true);

                        $canvas->addImage($img);
                        $canvas->setImageDelay($img->getImageDelay());
                    }
                    $image->destroy();
                    $this->image = $canvas;
                } else {
                    $this->image->thumbnailImage($width, $height, true);
                }
                break;
            case 'scale_fill':
                $size = $this->image->getImagePage();
                $src_width = $size['width'];
                $src_height = $size['height'];

                $x = 0;
                $y = 0;

                $dst_width = $width;
                $dst_height = $height;

                if ($src_width * $height > $src_height * $width) {
                    $dst_height = intval($width * $src_height / $src_width);
                    $y = intval(($height - $dst_height) / 2);
                } else {
                    $dst_width = intval($height * $src_width / $src_height);
                    $x = intval(($width - $dst_width) / 2);
                }

                $image = $this->image;
                $canvas = new Imagick();

                $color = 'rgba(' . $fill_color[0] . ',' . $fill_color[1] . ',' . $fill_color[2] . ',' . $fill_color[3] . ')';
                if ($this->type == 'gif') {
                    $images = $image->coalesceImages();
                    foreach ($images as $frame) {
                        $frame->thumbnailImage($width, $height, true);

                        $draw = new ImagickDraw();
                        $draw->composite($frame->getImageCompose(), $x, $y, $dst_width, $dst_height, $frame);

                        $img = new Imagick();
                        $img->newImage($width, $height, $color, 'gif');
                        $img->drawImage($draw);

                        $canvas->addImage($img);
                        $canvas->setImageDelay($img->getImageDelay());
                        $canvas->setImagePage($width, $height, 0, 0);
                    }
                } else {
                    $image->thumbnailImage($width, $height, true);

                    $draw = new ImagickDraw();
                    $draw->composite($image->getImageCompose(), $x, $y, $dst_width, $dst_height, $image);

                    $canvas->newImage($width, $height, $color, $this->get_type());
                    $canvas->drawImage($draw);
                    $canvas->setImagePage($width, $height, 0, 0);
                }
                $image->destroy();
                $this->image = $canvas;
                break;
            default:
                $size = $this->image->getImagePage();
                $src_width = $size['width'];
                $src_height = $size['height'];

                $crop_x = 0;
                $crop_y = 0;

                $crop_w = $src_width;
                $crop_h = $src_height;

                if ($src_width * $height > $src_height * $width) {
                    $crop_w = intval($src_height * $width / $height);
                } else {
                    $crop_h = intval($src_width * $height / $width);
                }

                switch ($fit) {
                    case 'north_west':
                        $crop_x = 0;
                        $crop_y = 0;
                        break;
                    case 'north':
                        $crop_x = intval(($src_width - $crop_w) / 2);
                        $crop_y = 0;
                        break;
                    case 'north_east':
                        $crop_x = $src_width - $crop_w;
                        $crop_y = 0;
                        break;
                    case 'west':
                        $crop_x = 0;
                        $crop_y = intval(($src_height - $crop_h) / 2);
                        break;
                    case 'center':
                        $crop_x = intval(($src_width - $crop_w) / 2);
                        $crop_y = intval(($src_height - $crop_h) / 2);
                        break;
                    case 'east':
                        $crop_x = $src_width - $crop_w;
                        $crop_y = intval(($src_height - $crop_h) / 2);
                        break;
                    case 'south_west':
                        $crop_x = 0;
                        $crop_y = $src_height - $crop_h;
                        break;
                    case 'south':
                        $crop_x = intval(($src_width - $crop_w) / 2);
                        $crop_y = $src_height - $crop_h;
                        break;
                    case 'south_east':
                        $crop_x = $src_width - $crop_w;
                        $crop_y = $src_height - $crop_h;
                        break;
                    default:
                        $crop_x = intval(($src_width - $crop_w) / 2);
                        $crop_y = intval(($src_height - $crop_h) / 2);
                        break;
                }
                $image = $this->image;
                $canvas = new Imagick();
                if ($this->type == 'gif') {
                    $images = $image->coalesceImages();
                    foreach ($images as $frame) {
                        $img = new Imagick();
                        $img->readImageBlob($frame);
                        $img->cropImage($crop_w, $crop_h, $crop_x, $crop_y);
                        $img->thumbnailImage($width, $height, true);

                        $canvas->addImage($img);
                        $canvas->setImageDelay($img->getImageDelay());
                        $canvas->setImagePage($width, $height, 0, 0);
                    }
                } else {
                    $image->cropImage($crop_w, $crop_h, $crop_x, $crop_y);
                    $image->thumbnailImage($width, $height, true);
                    $canvas->addImage($image);
                    $canvas->setImagePage($width, $height, 0, 0);
                }
                $image->destroy();
                $this->image = $canvas;
                break;
        }
    }
    /**
     * 添加水印文字
     * @param $text     string      水印文字
     * @param $x        int         水印位置坐标x轴
     * @param $y        int         水印位置坐标y轴
     * @param $angle    int         水印文字旋转角度
     * @param $style    array       水印文字样式 font：字体，font_size：文字大小，font_color：文字颜色，under_color：文字背景色
     */
    public function add_text($text, $x = 0, $y = 0, $angle = 0, $style = array())
    {
        $draw = new ImagickDraw();
        if (isset($style['font'])) {
            $draw->setFont($style['font']);
        }

        if (isset($style['font_size'])) {
            $draw->setFontSize($style['font_size']);
        }

        if (isset($style['font_color'])) {
            $draw->setFillColor($style['font_color']);
        }

        if (isset($style['under_color'])) {
            $draw->setTextUnderColor($style['under_color']);
        }

        if ($this->type == 'gif') {
            foreach ($this->image as $frame) {
                $frame->annotateImage($draw, $x, $y, $angle, $text);
            }
        } else {
            $this->image->annotateImage($draw, $x, $y, $angle, $text);
        }
    }
    /**
     * 添加水印图片
     * @param $text     string|object    图片路径|图片对象
     * @param $type     string           path参数传入的类型（path：文件路径，obj：文件对象）
     * @param $x        int              水印位置坐标x轴
     * @param $y        int              水印位置坐标y轴
     */
    public function add_pic($path, $type = 'path', $x = 0, $y = 0)
    {
        if ($type == 'path') {
            $watermark = new Imagick($path);
        } else {
            $watermark = $path;
        }

        if ($this->type == 'gif') {
            $image = $this->image;
            $canvas = new Imagick();
            $images = $image->coalesceImages();
            foreach ($image as $frame) {
                $img = new Imagick();
                $img->readImageBlob($frame);
                $img->compositeImage($watermark, Imagick::COMPOSITE_OVER, $x, $y);
                $canvas->addImage($img);
                $canvas->setImageDelay($img->getImageDelay());
            }
            $image->destroy();
            $this->image = $canvas;
        } else {
            $this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, $x, $y);
        }
    }
    /**
     * 保存到指定路径
     * @param $path   string   图片保存路径
     * @param $q      float    图片压缩率，(0,1)之间的两位数小数，默认 0.60
     */
    public function save_to($path, $q = 0.60)
    {
        //压缩图片质量
        $this->image->setImageFormat('JPEG');
        $this->image->setImageCompression(Imagick::COMPRESSION_JPEG);

        $a = $this->image->getImageCompressionQuality() * sprintf('%.2f', $q);
        if ($a == 0) {
            $a = 60;
        }
        $this->image->setImageCompressionQuality($a);
        $this->image->stripImage();

        if ($this->type == 'gif') {
            $this->image->writeImages($path, true);
        } else {
            $this->image->writeImage($path);
        }
    }
    /**
     * 输出图像
     * @param $header bool  是否设置请求头，默认 true
     * true：设置请求头，直接在浏览器显示图像
     * false：不设置，输出二进制字符串
     */
    public function output($header = true)
    {
        if ($header) {
            header('Content-type: ' . $this->type);
        }

        echo $this->image->getImagesBlob();
    }
    // 读取图片宽度
    public function get_width()
    {
        $size = $this->image->getImagePage();
        return $size['width'];
    }
    // 读取图片高度
    public function get_height()
    {
        $size = $this->image->getImagePage();
        return $size['height'];
    }
    /**
     * 设置图像类型
     * @param $type  string  图像类型，默认 'png'
     */
    public function set_type($type = 'png')
    {
        $this->type = $type;
        $this->image->setImageFormat($type);
    }

    // 获取源图像类型
    public function get_type()
    {
        return $this->type;
    }
    // 获取图片文件类型
    public function get_file_type()
    {
        if ($this->image) {
            return $this->image->getImageMimeType();
        } else {
            return 0;
        }
    }
    // 计算图片sha1哈希值
    public function get_sha1()
    {
        if ($this->image) {
            return sha1($this->image->__tostring());
        } else {
            return '';
        }
    }
    // 当前对象是否为图片
    public function is_image()
    {
        if ($this->image) {
            return true;
        } else {
            return false;
        }
    }
    /*
     * 添加一个边框
     * $width: 左右边框宽度
     * $height: 上下边框宽度
     * $color: 颜色: RGB 颜色 'rgb(255,0,0)' 或 16进制颜色 '#FF0000' 或颜色单词 'white'/'red'...
     */
    public function border($width, $height, $color = 'rgb(220, 220, 220)')
    {
        $color = new ImagickPixel();
        $color->setColor($color);
        $this->image->borderImage($color, $width, $height);
    }
    // 模糊
    public function blur($radius, $sigma)
    {
        $this->image->blurImage($radius, $sigma);
    }
    // 高斯模糊
    public function gaussian_blur($radius, $sigma)
    {
        $this->image->gaussianBlurImage($radius, $sigma);
    }
    // 运动模糊
    public function motion_blur($radius, $sigma, $angle)
    {
        $this->image->motionBlurImage($radius, $sigma, $angle);
    }
    // 径向模糊
    public function radial_blur($radius)
    {
        $this->image->radialBlurImage($radius);
    }
    // 添加噪点
    public function add_noise($type = null)
    {
        $this->image->addNoiseImage($type == null ? imagick::NOISE_IMPULSE : $type);
    }
    // 调整色阶
    public function level($black_point, $gamma, $white_point)
    {
        $this->image->levelImage($black_point, $gamma, $white_point);
    }
    // 调整亮度、饱和度、色调
    public function modulate($brightness, $saturation, $hue)
    {
        $this->image->modulateImage($brightness, $saturation, $hue);
    }
    // 素描
    public function charcoal($radius, $sigma)
    {
        $this->image->charcoalImage($radius, $sigma);
    }
    // 油画效果
    public function oil_paint($radius)
    {
        $this->image->oilPaintImage($radius);
    }
    // 水平翻转
    public function flop()
    {
        $this->image->flopImage();
    }
    // 垂直翻转
    public function flip()
    {
        $this->image->flipImage();
    }
    /**
     * 设置圆角图片
     * @param $x_rounding       float   X轴圆度，必填
     * @param $y_rounding       float   Y轴圆度，必填
     * @param $stroke_width     float   笔画宽度，可选，默认 10
     * @param $displace         float   图像置换，可选，默认 5
     * @param $size_correction  float   尺寸校正，可选，默认 -6
     */
    public function roundCorners($x_rounding, $y_rounding, $stroke_width = 10, $displace = 5, $size_correction = -6)
    {
        if ($this->image) {
            $this->image->roundCorners($x_rounding, $y_rounding, $stroke_width, $displace, $size_correction);
        }
    }

    /**
     * 计算一个字符串在图片中所占的宽度
     * @param $fontsize  字体大小
     * @param $angle     角度
     * @param $ttfpath   字体文件
     * @param $charArr   字符数组
     * @return $width
     */
    public function stringWidth($fontsize, $angle, $ttfpath, $charArr)
    {
        $width = 0;
        if (!empty($charArr) && is_array($charArr)) {
            foreach ($charArr as $char) {
                $box = imagettfbbox($fontsize, $angle, $ttfpath, $char);
                $width += (max($box[2], $box[4]) - min($box[0], $box[6]));
            }
            return intval($width);
        } else {
            return 0;
        }
    }
    /**
     * 返回一个字符在图片中所占的宽度
     * @param $fontsize  字体大小
     * @param $angle 角度
     * @param $ttfpath   字体文件
     * @param $char      字符
     * @return $width
     */
    public function charWidth($fontsize, $angle, $ttfpath, $char)
    {
        $box = imagettfbbox($fontsize, $angle, $ttfpath, $char);
        $width = max($box[2], $box[4]) - min($box[0], $box[6]);

        return $width;
    }
    /**
     * 返回一个字符的数组
     *
     * @param $str      文字
     * @param $charset  字符编码
     * @return $match   返回一个字符的数组
     */
    public function charArray($str, $charset = "utf-8")
    {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);

        return $match;
    }
}
