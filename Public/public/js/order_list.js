
mui.ready(function () {
    mui('.mui-bar-tab').on('tap', 'a', function () {
        mui.openWindow({
            url: this.href
        });
    });
    //禁止左右滑动
    mui('.mui-slider').slider().setStopped(true);
    //滚动条
    mui('.mui-scroll-wrapper').scroll({
        bounce: true,
        indicators: true,
        deceleration: mui.os.ios ? 0.003 : 0.0009
    });
    //初始化数据
    mui('.mui-scroll').on('tap', '.mui-control-item:not(.mui-active)', function () {
        var mask = mui.createMask();
        mask.show();
        var index = this.getAttribute('data-index');
        mui('.mui-slider').slider().gotoItem(index);
        mask.close();
    });
    var arr = ['#scroll_0', '#scroll_1', '#scroll_2', '#scroll_3'];
    for (var i in arr) {
        $(arr[i]).on('scroll', function (e) {
            if (e.detail.y == (e.detail.maxScrollY) && e.detail.y != 0) {
                
            }
        });
    }
});


