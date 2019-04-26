$(function () {
    var app = new Vue({
        el: '#app',
        data: {
            mobile: '',
            pwd: '',
            smscode: '',
            is_send: 0,
        },
        methods: {
            jump: function (url) {
                mui.openWindow(url);
            },
            register: function (event) {
                if (!this.mobile) {
                    toast('请输入手机号');
                    return;
                }
                if (!checkMobile(this.mobile)) {
                    toast('手机号码格式有误');
                    return;
                }
                if (!this.pwd) {
                    toast('请输入密码');
                    return;
                }
                if (!checkPwd(this.pwd)) {
                    toast('密码格式有误');
                    return;
                }
                if (!this.smscode) {
                    toast('请输入短信验证码');
                    return;
                }
                var data = {
                    mobile: this.mobile,
                    pwd: this.pwd,
                    smscode: this.smscode
                };
                request(config.url.register.register, this.doRegister, data);
            },
            doRegister: function (res) {
                toast(res.msg);
                if (res.code) {
                    loginSuccess();
                }
            },
            getMobileCode: function (event) {
                if (this.is_send != 0) {
                    return;
                }
                if (!this.mobile) {
                    toast('请输入手机号');
                    return;
                }
                if (!checkMobile(this.mobile)) {
                    toast('手机号码格式有误');
                    return;
                }
                var data = {
                    mobile: this.mobile,
                    do: 'regCode',
                };
            },
        }
    });
    //点击查看密码
    $('.mui-icon-eye').click(function () {
        var obj = $(this);
        var type = obj.prev().attr('type');
        if (type == 'text') {
            obj.prev().attr('type', 'password');
            obj.removeClass('mui-active');
        } else {
            obj.prev().attr('type', 'text');
            obj.addClass('mui-active');
        }
    });
});