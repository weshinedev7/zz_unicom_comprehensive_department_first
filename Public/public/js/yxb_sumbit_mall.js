vm = new Vue({
    el: '#app',
    data: {
        item: {
            role_name_arr: [],
            kefu: []
        },
        is_send: 0,
        flag: 0,
        type: 1,
        datas: {
            mall_id: '',
            goods_type_id: '',
            role_name: '',
            buyer_mobile: '',
            buyer_qq: '',
            buy_num: 1,
            order_amount: 0,
            smscode: '',
            kf_id: ''
        }
    },
    created: function () {
        this.datas.order_amount = $_GET['price'];
        if ($_GET['price']) {
            this.flag = 1;
        }
        request(config.url.order.getOrder, this.getOrderInfo, $_GET);
    },
    methods: {
        jump: function (url) {
            mui.openWindow(url);
        },
        switchMenu: function (event) {
            $(event.currentTarget).children('.menu_list').toggle();
        },
        switchVal: function (type, index) {
            var vm = this;
            var picker = new mui.PopPicker();
            //选择角色
            if (type == 1) {
                picker.setData(this.item.role_name_arr);
                picker.show(function (items) {
                    vm.datas.role_name = htmlDecodeByRegExp(items[0].text);
                });
            }
            //选择客服
            else if (type == 2) {
                picker.setData(this.item.kf_details);
                picker.show(function (items) {
                    var n = items[0].value;
                    var info = vm.item.kf_details[n];
                    vm.item.kefu.nickname = info.nickname;
                    vm.item.kefu.avatar = info.avatar;
                    vm.item.kefu.slogan = info.slogan;
                    vm.datas.kf_id = info.id;
                });
            }
        },
        getOrderInfo: function (data) {
            if (data.code !== 1) {
                toast(data.msg);
                return;
            }
            var data = data.data;
            this.item = data;
            if (data.role_name_arr.length) {
                this.datas.role_name = htmlDecodeByRegExp(data.role_name_arr[0].text);
            }
            if (data.kf_details.length) {
                var kf_details = data.kf_details[0];
                this.item.kefu = kf_details;
                this.datas.kf_id = kf_details.id;
            }
            this.datas.mall_id = data.id;
            this.datas.goods_type_id = data.goods_type_id;
            this.datas.goods_price = data.price;
            this.datas.buyer_mobile = data.order_mobile;
            this.datas.buyer_qq = data.order_qq;
            this.datas.buy_num = Math.floor(this.datas.order_amount / this.item.price);
            $('.mui-title').html(data.game_name + '/' + data.area_name + '/' + data.server_name);
        },
        formatMoney: function (event) {
            var max = Math.floor(this.item.max_num * this.item.price);
            this.datas.order_amount = formatMoney($(event.currentTarget), max);
            this.datas.buy_num = Math.floor(this.datas.order_amount / this.item.price);
        },
        formatNum: function () {
            this.datas.buy_num = formatMoney($(event.currentTarget), this.item.max_num);
            var money = this.datas.buy_num * this.item.price;
            this.datas.order_amount = money.toFixed(2);
        },
        switchType: function (type) {
            this.type = type;
            $(event.currentTarget).addClass('li_style');
            $(event.currentTarget).siblings().removeClass('li_style');
        },
        //立即购买
        submit: function () {
            if (!(this.datas.order_amount)) {
                toast('请输入购买金额');
                return;
            }
            if (this.datas.buy_num < this.item.min_num) {
                toast('最小购买数量为' + this.item.min_num + this.item.unit_name);
                return;
            }
            if (this.datas.buy_num > this.item.max_num) {
                toast('最大购买数量为' + this.item.max_num + this.item.unit_name);
                return;
            }
            if (!this.datas.role_name) {
                toast('角色名不能为空');
                return;
            }
            if (!checkBlank(this.datas.role_name)) {
                toast('角色名不能包含空格');
                return;
            }
            if (!checkMobile(this.datas.buyer_mobile)) {
                toast('电话号码格式有误');
                return;
            }
            if (this.item.is_bind_mobile == 0 && !this.datas.smscode) {
                toast('短信验证码不能为空');
                return;
            }
            if (!checkQq(this.datas.buyer_qq)) {
                toast('QQ号码格式有误');
                return;
            }
            if (!this.datas.kf_id) {
                toast('请选择专属客服');
                return;
            }
            request(config.url.order.yxb, this.doSubmit, this.datas);
        },
        doSubmit: function (data) {
            if (data.code !== 1) {
                toast(data.msg);
                return;
            }
            mui.openWindow('/trade/cashier.html?order_id=' + data.data.order_id);
        },
        getMobileCode: function (event) {
            if (this.is_send != 0) {
                return;
            }
            var buyer_mobile = this.datas.buyer_mobile;
            if (!buyer_mobile) {
                toast('请输入手机号');
                return;
            }
            if (!checkMobile(buyer_mobile)) {
                toast('手机号码格式有误');
                return;
            }
            var data = {
                mobile: buyer_mobile
            };
            request(config.url.order.bindMobile, this.doGetMobileCode, data);
        },
        doGetMobileCode: function (res) {
            toast(res.msg);
            if (res.code) {
                this.is_send = 1;
                smscodeTimerCount(this);
            }
        }
    }
});
