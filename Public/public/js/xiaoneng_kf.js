/**
 * Created by Administrator on 2017/9/30 0030.
 */

var NTKF_PARAM = {
    siteid: "kf_10019",                    //企业ID，为固定值，必填
    settingid: "kf_10019_1506481895071",    //接待组ID，为固定值，必填
    uid: "",                              //用户ID，未登录可以为空，但不能给null，uid赋予的值在显示到小能客户端上
    uname: "",                            //未登录可以为空，但不能给null，uname赋予的值显示到小能客户端上
    isvip: "0",                           //是否为vip用户，0代表非会员，1代表会员，取值显示到小能客户端上
    userlevel: "0",                       //网站自定义会员级别，0-N，可根据选择判断，取值显示到小能客户端上
    erpparam: "erp"                        //erpparam为erp功能的扩展字段，可选，购买erp功能后用于erp功能集成
};
$.ajax({
    url: "https://dl.ntalker.com/js/b2b/ntkfstat.js?siteid=kf_10019",
    dataType: 'jsonp',
    timeout: 10000,          // 设置超时时间
    method: 'get',
    cache: true, // 必须
    success: function () {
    },
    complete: function (XMLHttpRequest, status) {
        if (status == 'timeout') {
            xhr.abort();    // 超时后中断请求
        }
    }
});
