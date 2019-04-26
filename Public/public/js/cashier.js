$(function () {
    vm = new Vue({
        el: '#app',
        data: {
            allowType: [103, 301],
            item: {},
            is_send: 0,
            redpkg: [],
            redpkg_title: '',
            diyong_text: '抵用',
            green_icon_01: 'block',
            gray_icon: 'none',
            datas: {
                order_id: '',
                pay_way: 103,
                pay_amount: '',
                jindou_amount: '',
                redpkg_amount: 0,
                redpkg: 0,
                code: ''
            }
        },
        mounted: function () {
            request(config.url.order.getPayData, this.getPayData, $_GET);
        },
        methods: {
            chosePayWay: function (pay_way, e) {
                this.datas.pay_way = pay_way;
                $(e.currentTarget).children().children('.red_icon').css('display', 'block');
                $(e.currentTarget).children().children('.green_icon').css('display', 'none');
                $(e.currentTarget).siblings().children().children('.red_icon').hide();
                $(e.currentTarget).siblings().children().children('.green_icon').show();

            },
            getPayData: function ($res) {
                if ($res.code !== 1) {
                    toast($res.msg);
                    mui.back();
                    return;
                }
                //订单付款倒计时
                timerCount($res.data.remain_time);

                this.item = $res.data;
                this.datas.order_id = this.item.id;
                if ($.inArray(parseInt(this.item.last_pay_way), this.allowType) != -1) {
                    this.datas.pay_way = parseInt(this.item.last_pay_way);
                }
                //判断金豆是否已经支付过
                var payed_jindou_amount = $res.data.pay_jindou_amount;
                if (payed_jindou_amount > 0) {
                    this.diyong_text = '已抵用';
                    this.green_icon_01 = 'none';
                    this.gray_icon = 'block';
                }
                //判断红包是否已经支付过
                if ($res.data.have_redpkg.money > 0) {
                    var hava_redpkg = $res.data.have_redpkg;
                    this.redpkg_title = '已抵用' + hava_redpkg.money + '元';
                    this.redpkg = [];
                } else {
                    if (!$res.data.redpkg.length) {
                        this.redpkg_title = '暂无可用';
                    } else {
                        var redpkg = $res.data.redpkg[0];
                        this.datas.redpkg = redpkg.id;
                        this.redpkg_title = '已抵用' + redpkg.money + '元';
                        this.datas.redpkg_amount = redpkg.money;
                        this.getPayAmount();
                    }
                    this.redpkg = $res.data.redpkg;
                }
                this.getPayAmount();
            },
            switchRedpkg: function (index, event) {
                if (this.redpkg.length == 0) {
                    return;
                }
                if (index == -1) {
                    this.datas.redpkg = 0;
                    this.redpkg_title = this.redpkg.length ? this.redpkg.length + '张可用' : '暂无可用';
                    this.datas.redpkg_amount = 0;
                } else {
                    var data = this.redpkg[index];
                    this.datas.redpkg = data.id;
                    this.redpkg_title = '已抵用' + data.money + '元';
                    this.datas.redpkg_amount = data.money;
                    $('.user_tishi').show();
                }
                $(event.currentTarget).siblings('.box_01').children('.user_tishi').hide();
                this.getPayAmount();
                setTimeout(function () {
                    hideRedpkg();
                    $('body').css('overflow', 'initial');
                }, 300);
            },
            checkJindou1: function (e) {
                $('.hiden_div').toggle();
                this.datas.jindou_amount = '0.00';
                this.datas.pay_amount = this.item.total_amount - this.datas.redpkg_amount - this.item.have_redpkg.money;
                $(e.currentTarget).css('display', 'none');
                $(e.currentTarget).next().css('display', 'block');
            },
            getPayAmount: function () {
                var redpkg_amount = parseFloat(this.datas.redpkg_amount);
                var total_amount = parseFloat(this.item.total_amount);
                var jindou_amount = parseFloat(this.item.jindou_amount);
                var pay_jindou_amount = parseFloat(this.item.pay_jindou_amount);
                var pay_redpkg_amount = parseFloat(this.item.have_redpkg.money);
                if (pay_jindou_amount > 0) {
                    var jindou_amount = pay_jindou_amount;
                    if (pay_redpkg_amount > 0) {
                        var pay_amount = total_amount - jindou_amount - pay_redpkg_amount;
                    } else {
                        var pay_amount = total_amount - jindou_amount - redpkg_amount;
                    }
                }
                else if (pay_redpkg_amount > 0) {
                    if (pay_jindou_amount > 0) {
                        var jindou_amount = pay_jindou_amount;
                        var pay_amount = total_amount - jindou_amount - pay_redpkg_amount;
                    } else {
                        var total_amount = total_amount - pay_redpkg_amount;
                        if (parseFloat(total_amount) > parseFloat(jindou_amount)) {
                            var jindou_amount = jindou_amount;
                            var pay_amount = total_amount - jindou_amount;
                        } else {
                            var jindou_amount = total_amount;
                            var pay_amount = 0;
                        }
                    }
                }
                else if (parseFloat(total_amount) > parseFloat(jindou_amount)) {
                    if (redpkg_amount > (total_amount - jindou_amount)) {
                        var jindou_amount = total_amount - redpkg_amount;
                        var pay_amount = 0;
                    } else {
                        var jindou_amount = jindou_amount;
                        var pay_amount = total_amount - jindou_amount - redpkg_amount;
                    }
                } else {
                    var jindou_amount = total_amount - redpkg_amount;
                    var pay_amount = 0;
                }
                this.datas.jindou_amount = jindou_amount.toFixed(2);
                this.datas.pay_amount = pay_amount.toFixed(2);
            },
            checkJindou2: function (e) {
                this.getPayAmount();
                $('.hiden_div').toggle();
                $(e.currentTarget).css('display', 'none');
                $(e.currentTarget).prev().css('display', 'block');
            },
            getMobileCode: function (event) {
                if (this.is_send !== 0) {
                    return;
                }
                request(config.url.order.sendCode, this.doGetMobileCode);
            },
            doGetMobileCode: function (res) {
                toast(res.msg);
                if (res.code) {
                    this.is_send = 1;
                    smscodeTimerCount(this);
                }
            },
            submit: function () {
                var obj = $('body');
                if (obj.css('overflow') == 'hidden') {
                    obj.css('overflow', 'initial');
                    return;
                }
                if (this.datas.pay_amount > 0 && !this.datas.pay_way) {
                    mui.toast('请选择支付方式');
                    return false;
                }
                //如果金豆已支付过提交时候则清0
                if (this.item.pay_jindou_amount > 0) {
                    this.datas.jindou_amount = 0;
                }
                $.ajax({
                    type: 'get',
                    url: config.url.order.pay,
                    data: this.datas,
                    dataType: 'jsonp',
                    success: function (data) {
                        if (data.code !== 1) {
                            mui.toast(data.msg);
                            return false;
                        }
                        if (data.data.pay_url) {
                            window.location.href = data.data.pay_url;
                        } else {
                            window.location.href = '/order/success.html?order_id=' + data.data.order_id;
                        }
                    },
                    error: function () {
                        mui.toast('数据获取失败');
                    }
                });
            }
        }
    });
    //	弹窗
    $('.hongbao').on('click', function () {
        if (vm.redpkg.length == 0) {
            return;
        }
        var obj = $('body');
        if (obj.css('overflow') == 'hidden') {
            obj.css('overflow', 'initial');
            hideRedpkg();
            return;
        }
        $('.diarlog-box_02').show().addClass('fadeIn').removeClass('fadeOut');
        $('.diarlog_top').hide();
        $('.diarlog-box_02 .main_dialog_02').addClass('translateX');
        $('body').css('overflow', 'hidden');
    });
    $(".shadow").tap(function () {
        hideRedpkg();
    });
});
function hideRedpkg() {
    $(".diarlog-box_02").hide().addClass('fadeOut').removeClass('fadeIn');
}
function isBack() {
    var flag = mui.confirm('您真的要放弃支付吗？', '提示', ['去意已决', '再想想'], function (e) {
        if (e.index == 0 && flag) {
            flag = false;
            mui.back();
        }
    });
}
