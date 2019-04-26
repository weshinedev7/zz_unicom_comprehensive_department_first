<?php

function dumps($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function get_stay_time($timestamp, $is_hour = 1, $is_minutes = 1)
{
	if(empty($timestamp) || $timestamp <= 60) {
		return false;
	}
	$remain_time =$timestamp;

	$day = floor($remain_time / (3600*24));
	$day = $day > 0 ? $day.'天' : '';
	$hour = floor(($remain_time % (3600*24)) / 3600);
	$hour = $hour > 0 ? $hour.'小时' : '';
	if($is_hour && $is_minutes) {
		$minutes = floor((($remain_time % (3600*24)) % 3600) / 60);
		$minutes = $minutes > 0 ? $minutes.'分' : '';
		return $day.$hour.$minutes;
	}

	if($hour) {
		return $day.$hour;
	}
	return $day;
}


function GetPartStr($str,$len)//$str字符串   $len 控制长度
{
	$one=0;
	$partstr='';
	for($i=0;$i<$len;$i++)
	{ 
		$sstr=substr($str,$one,1);
		if(ord($sstr)>224){
			$partstr.=substr($str,$one,3);
			$one+=3;
		}elseif(ord($sstr)>192){
			$partstr.=substr($str,$one,2);
			$one+=2;
		}elseif(ord($sstr)<192){
			$partstr.=substr($str,$one,1);
			$one+=1;
		}
	}
	if(strlen($str)<$one)
	{
		return $partstr;
	}
	else
	{
		return $partstr.'....';
	}
}

function http_request($url,$data=array()){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    // POST数据
    curl_setopt($ch, CURLOPT_POST, 1);
    // 把post的变量加上
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function audiolength($fileUrl){
    vendor('getid3.getid3');
    $getID3 = new \getID3();    //实例化类
    $ThisFileInfo = $getID3->analyze(trim($fileUrl,'/'));   //分析文件
    $time = $ThisFileInfo['playtime_seconds'];      //获取mp3的长度信息
    return $time;
}

?>