Zepto(function($){
    // 检查是否下载客户端
    $('#download-banner').show();
    $('#comment').css('margin-bottom', '70px');
    $('#download-btn').click(function() {
        $(this).attr('src', '/Public/img/download_btn_press.png');
        if (isWechat()) {
            $('#download-tips').show();
            $('#joke').css('margin-top', '68px');
        } else {
            window.location.href = 'http://www.jgxhb.com/download/laughter.apk';
        }
    });
})

/**
 * 检查是否是微信浏览器
 *
 * @return bool
 */
function isWechat() {
    var ua = navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger/i) == 'micromessenger') {
        return true;
    } else {
        return false;
    }
}
