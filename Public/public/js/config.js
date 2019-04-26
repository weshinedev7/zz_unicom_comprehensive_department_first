/**
 * 配置文件
 * User: happybin
 * Date: 12-6-12
 */
var genUrl = '9891.com';
var infUrl = 'https://inf.9891.com';
var staticUrl = 'https://static.9891.com';
config = {
    'tips': {},
    'url': {
        'goods': {
            'getGame': infUrl + '/home/game/getGame', //获取游戏信息
            'getArea': infUrl + '/home/game/getArea', //获取大区信息
            'getServer': infUrl + '/home/game/getServer', //获取服务器信息
            'getGoodsType': infUrl + '/home/Game/getGoodsType', //获取服务器信息
            'getGameList': infUrl + '/home/game/getGameList', //获取游戏列表
            'getAreaList': infUrl + '/home/game/getAreaList', //获取大区列表
            'getServerList': infUrl + '/home/game/getServerList', //获取服务器列表
            'getGoodsTypeList': infUrl + '/home/game/getGoodsTypeList', //获取商品类型列表
            'getMallList': infUrl + '/home/goods/getMalllist',
            'getList': infUrl + '/home/goods/getList',
            'getDetails': infUrl + '/home/goods/getDetails',
            'getComment': infUrl + '/home/goods/getComment',
            'getRecommend': infUrl + '/home/goods/getRecommend',
            'getGameAreaServer': infUrl + '/home/Game/getGameAreaServer'
        },
        'dl': {
            'getGameAttr': infUrl + '/home/Dl/getGameAttr',
            'getTaocan': infUrl + '/home/Order/getTaocan',
            'submitOrder': infUrl + '/home/Order/submit',
            'getOrder': infUrl + '/home/Order/getOrder',
            'getGameAreaServer': infUrl + '/home/Game/getJsGame',
            'getRoleDetail': infUrl + '/home/Order/getRoleDetail'
        },
        'order': {
            'getOrder': infUrl + '/home/order/getOrder',
            'yxb': infUrl + '/home/order/submit',
            'getPayData': infUrl + '/home/Pay/payment',
            'getPaySuccess': infUrl + '/home/Pay/getPaySuccess',
            'pay': infUrl + '/home/Pay/payLog',
            'sendCode': infUrl + '/home/Pay/sendCode',
            'bindMobile': infUrl + '/home/Order/bindMobileCode',
            //我的订单
            'getOrderList': infUrl + '/user/order/getList',
            'acceptGoods': infUrl + '/user/order/acceptGoods',
            'getOrderDetail': infUrl + '/user/order/getDetail',
            'getNewTradeList': infUrl + '/home/Index/getTradeList'
        },
        'shop': {
            'getShopInfo': infUrl + '/home/Shop/index',
            'getShopGoods': infUrl + '/home/Shop/getShopGoods',
            'collectShop': infUrl + '/home/Shop/collectShop'
        },
        'page': {
            'getAcricle': infUrl + '/home/article/getDetails'
        },
        /*------------------------------------------------------user start----------------------------------------------------------------*/
        'sendSmsCode': infUrl + '/user/send/sendMobileCode',
        'register': {
            'register': infUrl + '/user/publics/register'
        },
        'login': {
            'login': infUrl + '/user/publics/login',
            'fastLogin': infUrl + '/user/publics/fastLogin'
        },
        'findpwd': {
            'findPwd': infUrl + '/user/publics/findPwd',
            'resetPwd': infUrl + '/user/publics/resetPwd'
        },
        'third': {
            'getThirdInfo': infUrl + '/user/publics/getThirdInfo',
            'third': infUrl + '/user/publics/third',
            'thirdLogin': infUrl + '/user/publics/thirdLogin',
            'thirdReg': infUrl + '/user/publics/thirdReg',
        },
        'user_center': {
            'getUserInfo': infUrl + '/user/publics/getUserInfo',
            'logout': infUrl + '/user/publics/logout',
            'isLogin': infUrl + '/user/publics/isLogin',
        },
        'safe': {
            //实名认证
            'getRealInfo': infUrl + '/user/safe/getRealInfo',
            'realname': infUrl + '/user/safe/realname',
            //修改手机
            'getUserMobile': infUrl + '/user/safe/getUserMobile',
            'changeMobile': infUrl + '/user/safe/changeMobile',
            'resetMobile': infUrl + '/user/safe/resetMobile',
            'bindMobile': infUrl + '/user/safe/bindMobile',
            //修改密码
            'setPwd': infUrl + '/user/safe/setPwd',
            'changePwd': infUrl + '/user/safe/changePwd'
        },
        'fund': {
            'getLastPayWay': infUrl + '/user/fund/getLastPayWay',
            'jdRecharge': infUrl + '/user/fund/jdRecharge',
            'getRedpkgList': infUrl + '/user/fund/getRedpkgList'
        },
        'role': {
            'getRoleList': infUrl + '/user/role/getList',
            'getRoleInfo': infUrl + '/user/role/getInfo',
            'add': infUrl + '/user/role/add',
            'edit': infUrl + '/user/role/edit',
            'delete': infUrl + '/user/role/delete'
        }
        /*------------------------------------------------------user end------------------------------------------------------------------*/
    }
};