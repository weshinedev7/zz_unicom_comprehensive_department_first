$(function () {
    var htmlFont = function () {
        var docEl = document.documentElement,
            l = docEl.clientWidth,
            f;
        f = l / 15;
        l > 750 ? docEl.style.fontSize = 50 + "px" : docEl.style.fontSize = f + "px";
    };
    htmlFont();
    window.addEventListener("resize", htmlFont, false);
});

//百度统计
var _hmt = _hmt || [];
(function () {
    var hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?054cd01fb3dd622680c9c800ab605c3c";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();

/**
 * 获取地址栏url参数
 * @type {{}}
 */
var $_GET = function () {
    var url = window.document.location.href.toString();
    var u = url.split("?");
    if (typeof(u[1]) === "string") {
        u = u[1].split("&");
        var get = {};
        for (var i in u) {
            var j = u[i].split("=");
            get[j[0]] = j[1];
        }
        return get;
    } else {
        return {};
    }
}();


//下拉
$(function () {
    $('.menu li').click(function () {
        $(this).children('.menu_list').toggle();
    })
    $('.menu_list').on('click', 'li', function () {
        $(this).parent().siblings('span').text($(this).text());
        $(this).parent().siblings('input').val($(this).text());
        $(this).addClass('li_style');
        $(this).siblings().removeClass('li_style');
    })

    $('.wjb_input').keyup(function () {
        $('.dollor').show();
        $('.tishi_show').hide();
    });
    $('.menu input').keyup(function () {
        $(this).siblings('.menu_list').hide();
    });

});

/*------------------------------------------------------------user start------------------------------------------------------------------*/

//MUI初始化
$(function () {
    mui.init();
});

//检测真实姓名
function checkRealname(realname) {
    var pattern = /^[\u4e00-\u9fa5]{2,4}$/;
    if (!pattern.test(realname)) {
        return false;
    } else {
        return true;
    }
}
//检测身份证号
function checkIdCard(idcard) {
    if (!isIdCardNo(idcard)) {
        return false;
    } else {
        return true;
    }
}

//检测手机号
function checkMobile(mobile) {
    var pattern = /^1[34578][0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/;
    if (!pattern.test(mobile)) {
        return false;
    } else {
        return true;
    }
}

//检测密码
function checkPwd(pwd) {
    var pattern = /^[0-9a-zA-Z_!\.@#\$%\^&\*\(\)\[\]\\?\\\/\|\-~`\+\=\,\r\n\:\'\"]{6,18}$/;
    if (!pattern.test(pwd)) {
        return false;
    } else {
        return true;
    }
}
function checkGamePwd(pwd) {
    var pattern = /^[0-9a-zA-Z_!\.@#\$%\^&\*\(\)\[\]\\?\\\/\|\-~`\+\=\,\r\n\:\'\"]{6,30}$/;
    if (!pattern.test(pwd)) {
        return false;
    } else {
        return true;
    }
}
//检测QQ
function checkQq(qq) {
    var pattern = /^[1-9][\d]{4,11}$/;
    if (!pattern.test(qq)) {
        return false;
    } else {
        return true;
    }
}
//检测QQ
function checkBlank(name) {
    var pattern = /(\s+)/g;
    if (pattern.test(name)) {
        return false;
    } else {
        return true;
    }
}
//错误提示框
function toast(msg, time, el) {
    if (!time) {
        time = 1200;
    }
    mui.toast(msg, {duration: time, type: el});
}

//延时跳转
function delayJump(url, time) {
    if (!url || typeof(url) == 'undefined') {
        return;
    }
    if (typeof(time) == 'undefined') {
        time = 1200;
    }
    setTimeout(function () {
        mui.openWindow(url);
    }, time);
}
//返回上一页
function muiBack(time) {
    if (typeof(time) == 'undefined') {
        time = 1200;
    }
    setTimeout(function () {
        mui.back();
    }, time);
}
//通用请求
function request(url, method, data, dataType, LoadingStyle) {
    if (!dataType) {
        dataType = 'jsonp';
    }
    if (!LoadingStyle) {
        LoadingStyle = 1;
    }
    //透明遮罩层 防止连续点击
    var mask = mui.createMask();
    mask.show();
    $.ajax({
        type: 'get',
        url: url,
        data: data,
        dataType: dataType,
        beforeSend: function () {
            if (LoadingStyle === 1) {
                $('body').append("<div class='loading1'></div>");
            } else if (LoadingStyle === 2) {
                $('body').append("<div class='loading2'><p>拼命加载中...</p></div>");
            }
        },
        success: function (data) {
            mask.close();
            if (data.code == 2) {
                setCookie('is_login', 0);
                toast('请先登录！');
                delayJump('/user/login.html');
                return;
            } else {
                method(data);
            }
            $('.mui-content').show();
        },
        complete: function () {
            mask.close();
            if (LoadingStyle) {
                $(".loading" + LoadingStyle).remove();
            }
        },
        error: function (data) {
            mask.close();
            toast('请求失败');
        }
    });
}
function scrollPage(vm, method) {
    $(window).scroll(function () {
        var elHeight = $(document).height();
        var winHeight = $(window).height();
        var scrollHeight = $(this).scrollTop();
        if (scrollHeight + winHeight >= elHeight - 30) {
            if (vm.flag) {
                vm.flag = 0;
                method();
            }
        }
    });
}

//格式化金额
function formatMoney(obj, max) {
    var regStrs = [
        ['^0(\\d+)$', '$1'],           //禁止录入整数部分两位以上，但首位为0
        ['^\\.$', ''],                 //禁止录入任何非数字和点
        ['[^\\d\\.]+$', ''],           //禁止录入任何非数字和点
        ['\\.(\\d?)\\.+', '.$1'],      //禁止录入两个以上的点
        ['^(\\d+\\.\\d{2}).+', '$1'],  //禁止录入小数点后两位以上
    ];
    for (i = 0; i < regStrs.length; i++) {
        var reg = new RegExp(regStrs[i][0]);
        obj.val(obj.val().replace(reg, regStrs[i][1]));
    }
    if (parseFloat(obj.val()) > parseFloat(max)) {
        obj.val(max)
    }
    return obj.val();
}

function inputInt(obj, max) {
    var regStrs = [
        ['^0(\\d+)$', '$1'],
        ['[^0-9]+', ''],           //禁止录入任何非数字和点
        ['[\\.]+', ''],           //禁止录入任何非数字和点
    ];
    for (i = 0; i < regStrs.length; i++) {
        var reg = new RegExp(regStrs[i][0]);
        obj.val(obj.val().replace(reg, regStrs[i][1]));
    }
    if (parseFloat(obj.val()) > parseFloat(max)) {
        obj.val(max)
    }
    return obj.val();
}

//短信验证码倒计时
function smscodeTimerCount(app) {
    var second = 60;
    var obj = $('#smscodeBtn');
    obj.addClass('sms_hui');
    var inter = window.setInterval(function () {
        //时间默认值
        if (second > 0) {
            var cont = second + 's后重新获取';
        } else {
            var cont = '获取验证码';
            app.is_send = 0;
            obj.removeClass('sms_hui');
            window.clearInterval(inter);
        }
        $('#smscodeBtn').html(cont);
        second--;
    }, 1000);
}
//倒计时
function timerCount(intDiff) {
    window.setInterval(function () {
        var day = 0,
            hour = 0,
            minute = 0,
            second = 0;//时间默认值
        if (intDiff > 0) {
            day = Math.floor(intDiff / (60 * 60 * 24));
            hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
            minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
            second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
        }
        if (minute <= 9) minute = '0' + minute;
        if (second <= 9) second = '0' + second;
        $('#day_show').html(day);
        $('#hour_show').html(hour);
        $('#minute_show').html(minute);
        $('#second_show').html(second);
        intDiff--;
    }, 1000);
}
function setCookie(name, value, time) {
    $.fn.cookie(name, value, {path: '/', domain: genUrl});
}
function getCookie(name) {
    return $.fn.cookie(name);
}
//登录成功操作
function loginSuccess() {
    var callback = getCookie('m_callback');
    var url = callback ? callback : '/user/index.html';
    setCookie('m_callback', '');
    delayJump(url);
}
function is_login() {
    if (getCookie('is_login') != 1) {
        return false;
    } else {
        return true;
    }
}
//如果未登录,跳转到登录
function goLogin(url) {
    if (getCookie('is_login') != 1) {
        setCookie('m_callback', url);
        var url = '/user/login.html';
    }
    mui.openWindow({
        url: url
    })
}
function goLoginFast(url) {
    if (getCookie('is_login') != 1) {
        setCookie('m_callback', url);
        var url = '/user/login_fast.html';
    }
    mui.openWindow({
        url: url
    })
}
/*用正则表达式实现html解码*/
function htmlDecodeByRegExp(str) {
    var s = "";
    if (str.length == 0) return "";
    s = str.replace(/&amp;/g, "&");
    s = s.replace(/&lt;/g, "<");
    s = s.replace(/&gt;/g, ">");
    s = s.replace(/&nbsp;/g, " ");
    s = s.replace(/&#39;/g, "\'");
    s = s.replace(/&quot;/g, "\"");
    return s;
}
/*------------------------------------------------------------user end--------------------------------------------------------------------*/
/**
 * 生成url请求参数字符串
 * @param obj
 * @returns {string}
 */
function http_build_query(obj) {
    var str = '?';
    $.each(obj, function (key, val) {
        if (typeof(val) !== undefined) {
            str += key + '=' + val + '&';
        }
    });
    return str.substring(0, str.length - 1);
}

/**
 * 保存浏览历史
 * @param $params
 */
function history_log($params) {
    if (!window.localStorage) return;
    var data = localStorage.getItem('history_log');
    data = data ? JSON.parse(data) : [];
    if (data.length > 20) {
        data.pop();
    }
    var flag = true;
    //变量数组检查商品是否已经存在
    $.each(data, function (key, val) {
        if (val.goods_id === $params.goods_id) {
            flag = false;
            return flag;
        }
    });
    if (flag) {
        data.unshift($params);
        var $str = JSON.stringify(data);
        localStorage.setItem('history_log', $str);
    }
}