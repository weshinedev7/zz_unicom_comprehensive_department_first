<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/8/21
 * Time: 20:25
 */

namespace Admin\Controller;
use Think\Controller;
use Think\Model;
/*
 * 微信支付接口
 */

class PayController extends Controller
{
    protected $jsApiParameters;

    public function pay($orderid,$attach)
    {   // 下单
        // 商户基本信息，可以写死在WxPay.Config.php里面， 其他详细参考WxPayConfig.php
        vendor('Wxpay.WxPayJsApiPay');
        $tools = new \JsApiPay();
        //2019-1-4  1商品订单  2花币充值
        if($attach=="1"){
            $table="order";
            $title="商品订单";
        }
        if($attach=="2"){
            $table="recharge_order";
            $title="花币充值";
        }
        $orderInfo=M("$table")->where("id=$orderid")->find();
        $openid = $orderInfo['openid'];
        $out_trade_no = $orderInfo['ordersn'];
        $total_fee=$orderInfo['realprice'];
        $total_fee = intval(floatval($total_fee) * 100); // 价格
        $input = new \WxPayUnifiedOrder();
        $input->SetAppid(__APPID__);
        $input->SetBody($title);
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($total_fee); // 价格
        $input->SetNotify_url($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . U('Pay/notify'));
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openid);
        $input->SetAttach($attach);
        $order = \WxPayApi::unifiedOrder($input);
        $this->jsApiParameters = $tools->GetJsApiParameters($order);
        $arr['jsApiParameters']=$this->jsApiParameters;
        $this->ajaxReturn(array('payinfo'=>json_decode($arr['jsApiParameters'],true)));

    }
    /*
     * 支付回调
     */
    public function notify()
    {
        // 接收微信支付结果
        $xml = file_get_contents('php://input');
        $arr = json_decode(
            json_encode(simplexml_load_string($xml,
                'SimpleXMLElement',
                LIBXML_NOCDATA)),
            true);

        $f = fopen('./notify.txt', 'w+');
        fwrite($f, $xml);
        fclose($f);
        // 验证签名。默认支付MD5
        if ($arr['result_code'] == 'SUCCESS' && $arr['return_code'] == 'SUCCESS') {
            $ordersn=$arr['out_trade_no'];
            $this->payResult($arr["attach"],$ordersn);
        }
        // 回应服务器
        $return = array('return_code' => 'SUCCESS', 'return_msg' => 'OK');
        $xml = '<xml>';
        foreach ($return as $key => $value) {
            $xml .= '<' . $key . '><![CDATA[' . $value . ']]></' . $key . '>';
        }
        $xml .= '</xml>';
        echo $xml;
    }

    //支付回调处理
    public function payResult($attach,$ordersn){
        $Model = new Model();
        if($attach=="1"){
            $orderInfo=M("order")->where("ordersn=$ordersn")->field("id,usercoupons_id,type,openid,goodsid,discountprice")->find();
            $openid=$orderInfo["openid"];
            //更新订单状态
            $Model->execute("update wx_order SET status=2,paytime=".time()." where ordersn=$ordersn");
            //改变优惠券状态
            if(!empty($orderInfo["usercoupons_id"])){
                $Model->execute(" update wx_user_coupons set state=2,usetime=".time()." where id=".$orderInfo["usercoupons_id"]);
            }
            //更新用户身份  0 关注用户(访客) 1授权用户 2付费用户
            $Model->execute("update wx_user set type=2,paytime=".time()." where openid='$openid'");
            //更新花币余额
            $wallet=M("user")->where("openid='$openid'")->getField("wallet");
            $wallet-=$orderInfo["discountprice"];
            $Model->execute("update wx_user set wallet=$wallet where openid='$openid'");
            if($orderInfo["type"]=="2"){//专辑
                //如果是购买专辑，则查找是否有上级
                $parent=M("fxuser")->where("son_openid='$openid'")->getField("parent_openid");
                //如果存在上级,奖励上级花币
                if($parent){
                    $wallet=M("user")->where("openid='$parent'")->getField("wallet");
                    $money=$wallet+10;
                    $Model->execute("update wx_user set wallet=$money where openid='$parent'");
                    M("share")->add(array("parent_openid"=>$parent,"son_openid"=>$openid,"num"=>10,"state"=>2,"time"=>time(),"orderid"=>$orderInfo["id"]));
                    //解除绑定关系
                    M("fxuser")->where("son_openid='$openid' and parent_openid='$parent'")->delete();
                }
                $tale="album";
            }
            if($orderInfo["type"]=="3"){//徽章
                //2019-03-05 更新徽章是否兑换 BUG修复
                $Model->execute("update wx_user_badge set status=1 where openid='$openid' and badgeid=".$orderInfo["goodsid"]);
            }
               //购买成功后增加播放权限
                $userids=M("$tale")->where("id=".$orderInfo["goodsid"])->getField("userids");
                $user_id=M("user")->where("openid='$openid'")->getField("id");
               if(!empty($userids)){
                    $userids=explode(",",$userids);
                    array_push($userids,$user_id);
                    $userids=array_unique($userids);
                    $userids=implode(",",$userids);
                }else{
                    $userids=$user_id;
                }
               M("$tale")->where("id=".$orderInfo["goodsid"])->setField("userids",$userids);
        }
        if($attach=="2"){
            $public=new ApiPublicController();
            $orderInfo=M("recharge_order")->where("ordersn=$ordersn")->field("realprice,openid")->find();
            $openid=$orderInfo["openid"];
            $wallet=M("user")->where("openid='$openid'")->getField("wallet");
            //$public->dump2file($wallet);
            $money=$orderInfo["realprice"]+$wallet;
            //$public->dump2file($money);
            $Model->execute(" update wx_user set wallet=$money where openid='$openid'");
            $Model->execute("update wx_recharge_order set state=2 where ordersn=$ordersn");
        }
    }
}