/**
 * web端
 */
Client = (function() {
    var Return = {
        // 打开弹幕
        openTan : function() {
            //AndroidWrapper.openTanCallback(code, message);
        },

        // 关闭弹幕
        closeTan : function() {
            //AndroidWrapper.closeTanCallback(code, message);
        },

        // 绑定点击头像事件
        bindClickAvatar : function() {
            $('.avatar').live('click', function() {
                var userId = $(this).parent().attr('data-user-id');
                AndroidWrapper.clickAvatar(userId);
            });
        },

        // 绑定点击赞事件
        bindClickUp : function() {
            var api = '/joke/up';
            var imgObj = {
                0 : '/Public/img/joke_up.png',
                1 : '/Public/img/joke_up_press.png',
            };

            $('#joke-up-btn img').click(function() {
                bindJokeAction($(this), api, imgObj)
            });
        },

        // 绑定点击喜欢事件
        bindClickFavorate : function() {
            var api = '/joke/favorate';
            var imgObj = {
                0 : '/Public/img/joke_favorite.png',
                1 : '/Public/img/joke_favorite_press.png',
            };

            $('#joke-favorite-btn img').click(function() {
                result = bindJokeAction($(this), api, imgObj)
                if (result) {
                    AndroidWrapper.clickFavorate();
                }
            });
        },

        // 绑定点击分享事件
        bindClickShare : function() {
            var $ele = $('#joke-share-btn img');
            $ele.on('touchstart', function() {
                this.src = '/Public/img/joke_share_press.png';
            });
            $ele.on('touchend', function() {
                this.src = '/Public/img/joke_share.png';
            });
            $ele.click(function() {
                AndroidWrapper.clickShare();
            });
        }, 
       
        // 绑定加载更多评论
        bindLoadMoreComments : function() {
            var $j = $('#joke-header');
            var jokeId = $j.attr('data-id');
            var jokeUid = $j.attr('data-user-id');

            var startNum = parseInt($('#comment-lastest').attr('data-start'));
            var startNums = new Array();
            startNums.push(0, startNum);

            $(window).scroll(function(){
                if (startNums[0] === startNums[1]) {
                    return false;
                }

                if ($(document).height() - $(window).scrollTop() - $(window).height() < 100) {
                    var result = sendAjax('/comment/lastest', 'GET', {
                        joke_id:jokeId,
                        user_id:jokeUid,
                        start:startNum
                    });
                    if (result) {
                        startNum = result.start;
                        startNums.shift();
                        startNums.push(startNum)

                        var html = '';
                        $.each(result.list, function(i, v) {
                            html += getCommentCellHtml(v);
                        })
                        $('#comment-lastest').append(html);
                    }
                }
            })
        }
    } 

    var getCommentCellHtml = function(d) {
        if ('' == d.user_avatar) {
            d.user_avatar = '/Public/img/avatar_default_small.png';
        }

        var code = '';
        code += '<div class="comment-body">';
        code += '   <div class="comment-header" data-user-id="'+d.user_id+'">';
        code += '       <img class="comment-avatar avatar" src="'+d.user_avatar+'"/>';
        code += '       <div class="comment-author">';
        code += '           <span>'+d.user_nickname+'</span>';
        code += '           <span>'+d.create_time+'</span>';
        code += '       </div>';
        code += '       <img class="comment-up-btn" src="/Public/img/comment_up.png"/>';
        code += '       <span class="comment-up-count">'+d.up_count+'</span>';
        code += '   </div>';
        code += '   <span class="comment-content">'+d.content+'</span>';
        code += "</div>";
        return code;
    }

    var bindJokeAction = function($image, api, imgObj) {
        var opUtid = $('#wrapper').attr('data-op-user-tid');
        var jokeId = $('#joke-header').attr('data-id');

        var $m = $image.parent();
        var $count = $m.children('span');

        var oldNum = parseInt($count.text());
        var oldIsAct = $m.attr('data-isact');
        var newNum, newIsAct, newImg;
        if ('1' == oldIsAct) {
            newNum = oldNum - 1;
            newIsAct = 0;
            newImg = imgObj[newIsAct];
        } else {
            newNum = oldNum + 1;
            newIsAct = 1;
            newImg = imgObj[newIsAct];
        }

        var result = sendAjax(api, 'POST', {
            joke_id:jokeId,
            user_tid:opUtid,
            is_act:newIsAct
        });
        if (result) {
            $image.attr('src', newImg);
            $count.text(newNum);
            $m.attr('data-isact', newIsAct)
        }

        return result;
    }

    var sendAjax = function(url, methodType, params) {
        var result;
        $.ajax({
            url: url,
            type: methodType,
            async: false,
            data: params,
            dataType: 'json',
            success: function(data){
                if (data.status) {
                    result = data.data;
                } else {
                    AndroidWrapper.alert(data.data);
                }
            },
            error: function(xhr, type){
                AndroidWrapper.alert('请求时遇到错误，请尝试重新操作。');
            }
        })
        return result;
    }

    return Return;
})();

/**
 * Android端接口封装
 */
AndroidWrapper = (function() {
    var Return = {
        showTan : function(isShow) {
            if (isExistAndroidObj()) {
                Android.showTan(isShow);
            }
        },
        openTanCallback : function(code, message) {
            if (isExistAndroidObj()) {
                Android.openTanCallback(code, message);
            }
        },
        closeTanCallback : function(code, message) {
            if (isExistAndroidObj()) {
                Android.closeTanCallback(code, message);
            }
        },
        clickAvatar : function(userId) {
            if (isExistAndroidObj()) {
                Android.clickAvatar(userId);
            }
        },
        clickFavorate : function() {
            if (isExistAndroidObj()) {
                Android.clickFavorate();
            }
        },
        clickShare : function() {
            if (isExistAndroidObj()) {
                Android.clickShare();
            }
        },
        alert : function(message) {
            if (isExistAndroidObj()) {
                Android.alert(message);
            } else {
                console.log(message);
            }
        },
    };

    var isExistAndroidObj = function() {
        return typeof(Android) != 'undefined';
    };

    return Return;
})();


Zepto(function($){
    // 关闭弹幕
    AndroidWrapper.showTan(0);

    // 绑定加载更多评论
    Client.bindLoadMoreComments();
    // 绑定点击头像事件
    Client.bindClickAvatar();
    // 绑定点击赞事件
    Client.bindClickUp();
    // 绑定点击喜欢事件
    Client.bindClickFavorate();
    // 绑定点击分享事件
    Client.bindClickShare();
})
