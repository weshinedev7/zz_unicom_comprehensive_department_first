<?php
namespace Common\Controller;
use Think\Controller;
class WxpayBaseController extends Controller{
    public function _initialize(){
        ini_set('date.timezone','Asia/Shanghai');
        Vendor('Weixin.WxPayApi');
        Vendor('Weixin.JsApiPay');
        Vendor('Weixin.WxPayNotify');
        Vendor("Weixin.NativePay");
    }
}