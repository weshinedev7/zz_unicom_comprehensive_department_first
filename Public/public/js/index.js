$(function () {
    vm = new Vue({
        el: '#app',
        data: {
            newTradeList: {}
        },
        mounted: function () {
            request(config.url.order.getNewTradeList, this.getNewTradeList);
        },
        methods: {
            jump: function (url) {
                mui.openWindow(url);
            },
            getNewTradeList: function (res) {
                this.newTradeList = res.data;
            }
        }
    });
    $(document).ready(function () {
        setInterval('autoScroll(".over")', 3000);
    })
    mui('.mui-bar-tab').on('tap', 'a', function () {
        var url = $(this).attr('data-url');
        if (url) {
            goLogin(url);
        } else {
            mui.openWindow({
                url: this.href
            })
        }
    });
    //获得slider插件对象
    var gallery = mui('.mui-slider');
    gallery.slider({
        interval: 3000 //自动轮播周期，若为0则不自动播放，默认为0；
    });
});
function autoScroll(obj) {
    $(obj).find(".ul").animate({
        marginTop: "-1.96rem" //li的高度
    }, 500, function () {
        $(this).css({
            marginTop: "0px"
        }).find(".li").eq(0).appendTo(this);
    })
}
$(function(){
	 $(window).bind("scroll", function() {

        if($(window).scrollTop() > 30) {
            $('.top_bar').addClass('top_bar_bg');
        }else{
            $('.top_bar').removeClass('top_bar_bg');
        }
    })
})

 