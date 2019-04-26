<?php
// +----------------------------------------------------------------------
// | WSHOTO [ 技术主导，服务至上，提供微信端解决方案 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2020 http://www.wshoto.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: yc <yc@yuanxu.top>
// +----------------------------------------------------------------------
function Check(){
    if (!session("?userOpenid")) {
        $data = getOpenid();
        session('userOpenid', $data);
    } else {
        $data = session('userOpenid');
    }
    $model = M();
    if ($rst = $model->table('home_member')->where("openid='{$data}'")->select()) {
        //设置SESSION
        setSession($rst[0]);
        return true;
    } else {
        //设置临时变量
        if (!session("?status")) {
            unset($_GET['code']);
            session("status", "1");
        }
        //获取用户所以信息
        getUserInfo();
    }
}

//获取用户openid
function getOpenid(){
    if (!$_GET['code']) {
        //获取当前的url地址
        $rUrl = _URL_ . __ACTION__ . '.html';
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . _APPID_ . "&redirect_uri=" . $rUrl . "&response_type=code&scope=snsapi_base&state=123456#wechat_redirect";
        //跳转页面
        redirect($url, 0);
    } else {
        $aUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . _APPID_ . "&secret=" . _APPSECRET_ . "&code=" . $_GET['code'] . "&grant_type=authorization_code";
        //获取网页授权access_token和openid等
        $data = getHttp($aUrl);
        return $data['openid'];
    }
}

//获取用户详细信息
function getUserInfo(){
    if (!$_GET['code']) {
        //获取当前的url地址
        $rUrl = _URL_ . __ACTION__ . '.html';
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . _APPID_ . "&redirect_uri=" . $rUrl . "&response_type=code&scope=snsapi_userinfo&state=123456#wechat_redirect";
        //跳转页面
        redirect($url, 0);
    } else {
        $getOpenidUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . _APPID_ . "&secret=" . _APPSECRET_ . "&code=" . $_GET['code'] . "&grant_type=authorization_code";
        //获取网页授权access_token和openid等
        $data = getHttp($getOpenidUrl);
        $getUserInfoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $data['access_token'] . "&openid=" . $data['openid'] . "&lang=zh_CN";
        //获取用户数据
        $userInfo = getHttp($getUserInfoUrl);
        //默认设置头像是132*132的
        $userInfo['headimgurl'] = substr($userInfo['headimgurl'], 0, strlen($userInfo['headimgurl']) - 1);
        $userInfo['headimgurl'] = $userInfo['headimgurl'] . '132';
        // 将信息插入数据库
        $userInfo['addtime'] = date("Y-m-d H:i:s");
        //删除language元素
        //        unset($userInfo['language']);
        $model = M();
        if ($model->table('home_member')->data($userInfo)->add()) {
            setSession($userInfo);
            session("status", null);
        } else {
            echo "验证错误";
        }
    }
}

//设置SESSION
function setSession($data){
    session('userOpenid', $data['openid']);
    session('userNickname', $data['nickname']);
    session('userSex', $data['sex']);
    session('userHeadimgurl', $data['headimgurl']);
    session('userID', $data['stuID']);
}

//获取access_token
function getAccess_token(){
    //URL
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . _APPID_ . "&secret=" . _APPSECRET_;
    //get请求
    $data = getHttp($url);
    //缓存access_token
    S('access_token', $data['access_token'], 7200);
    return $data['access_token'];
}

//get请求
function getHttp($url){
    $ch = curl_init();
    //设置传输地址
    curl_setopt($ch, CURLOPT_URL, $url);
    //设置以文件流形式输出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //接收返回数据
    $data = curl_exec($ch);
    curl_close($ch);
    $jsonInfo = json_decode($data, true);
    return $jsonInfo;
}

//post请求
function postHttp($url, $json){
    $ch = curl_init();
    //设置传输地址
    curl_setopt($ch, CURLOPT_URL, $url);
    //设置以文件流形式输出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //设置已post方式请求
    curl_setopt($ch, CURLOPT_POST, 1);
    //设置post文件
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    $data = curl_exec($ch);
    curl_close($ch);
    $jsonInfo = json_decode($data, true);
    return $jsonInfo;
}

function returnfilepath1($filepath){
    $split = explode(',', $filepath);
    $path1 = "";
    $sign1 = "<img src=\"";
    $sign2 = "\"/>";
    foreach ($split as $item => $value) {
        if ($split[$item] != "") {
            if ($path1 == "") {
                $path1 = $sign1 . $split[$item] . $sign2;
            } else {

                $path1 = $path1 . $sign1 . $split[$item] . $sign2;
            }
        }
    }
    function returnjointdata1($sign = '', $data){
        if ($sign == 'planttype') {
            $split = explode(',', $data);
            $model = M('info_plant_usetype');
            $planttype_name = "";
            foreach ($split as $item => $value) {
                if ($split[$item] != "") {
                    $name = $model->where("id='$split[$item]'")->find();
                    if ($planttype_name == "") {
                        $planttype_name = $name['name'];
                    } else {
                        $planttype_name = $planttype_name . " " . $name['name'];
                    }
                }
            }
            return $planttype_name;
        }
    }

    //     $res_path1 =$path1 ;
    //     $str=substr_replace($res_path1,"",-1);
    //    $res_path=$path1;

    return $path1;
}


//该公共方法获取和全局缓存js-sdk需要使用的access_token
function getAccessToken_f(){
    //我们将access_token全局缓存在文件中,每次获取的时候,先判断是否过期,如果过期重新获取再全局缓存
    //我们缓存的在文件中的数据，包括access_token和该access_token的过期时间戳.
    //获取缓存的access_token
    $access_token_data = json_decode(S('access_token'), true);

    //判断缓存的access_token是否存在和过期，如果不存在和过期则重新获取.
    if ($access_token_data !== null
        && $access_token_data['access_token']
        && $access_token_data['expires_in'] > time()) {
        return $access_token_data['access_token'];
    } else {
        //重新获取access_token,并全局缓存
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxd2b01e88159423b8&secret=f183e49e380dd6b7ee6b15daebcf736b");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //获取access_token
        $data = json_decode(curl_exec($curl), true);

        if ($data != null && $data['access_token']) {
            //设置access_token的过期时间,有效期是7200s
            //将access_token全局缓存，快速缓存到文件中.
           S('access_token', $data['access_token'], 7200);
            //返回access_token
            return $data['access_token'];
        } else {
            exit('微信获取access_token失败');
        }
    }
}


