/**
 * Web端封装类
 */
var Web = (function() {
    var Return = {
        // 绑定点击头像事件
        bindClickAvatar : function() {
            $('.avatar').live('click', function() {
                var userId = $(this).parent().parent().attr('data-user-id');
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
                var result = bindAction($(this), api, imgObj, {})
                if (result) {
                    $(this).next().css('color', 'white');
                }
            });
        },

        // 绑定点击喜欢事件
        bindClickFavorate : function() {
            var api = '/joke/favorate';
            var imgObj = {
                0 : '/Public/img/joke_favorite.png',
                1 : '/Public/img/joke_favorite_press.png',
            };

            $('#joke-favorite-btn').click(function() {
                var result = bindAction($(this), api, imgObj, {})
                if (result) {
                    AndroidWrapper.clickFavorate();
                }
            });
        },

        // 绑定点击分享事件
        bindClickShare : function() {
            var $ele = $('#joke-share-btn');
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

        // 绑定评论点击赞事件
        bindCommentClickUp : function() {
            var api = '/comment/up';
            var imgObj = {
                0 : '/Public/img/comment_up.png',
                1 : '/Public/img/comment_up_press.png',
            };

            $('.comment-up-btn').live('click', function(e) {
                var extraParamsObj = {'comment_id' : $(this).parent().parent().parent().attr('data-id')};
                bindAction($(this), api, imgObj, extraParamsObj)
            });
        },

        // 绑定评论点击事件
        bindCommentClick : function() {
            $('.comment-body').live('click', function(e) {
                if ($(e.target).hasClass('comment-up-btn')) {
                    return false;
                }
                if ($(e.target).hasClass('comment-avatar')) {
                    return false;
                }

                var comment_id = $(this).attr('data-id');
                var comment_user_nickname = $(this).attr('data-user-name');
                AndroidWrapper.sendReply(comment_user_nickname, comment_id);
            });
        },
       
        // 绑定加载更多评论
        bindLoadMoreComments : function() {
            var $j = $('#joke');
            var jokeId = $j.attr('data-id');
            var jokeUid = $j.attr('data-user-id');

            var startNum = parseInt($('#comment-lastest').attr('data-start'));
            var startNums = new Array();
            startNums.push(0, startNum);

            $(window).scroll(function(){
                if (startNums[0] === startNums[1]) {
                    return false;
                }

                if ($(document).height() - $(window).scrollTop() - $(window).height() == 0) {
                    // display loading image
                    //$('#comment-loading').css('display', 'block');
                    // request
                    var result = $.global.sendAjax('/comment/lastest', 'GET', {
                        joke_id:jokeId,
                        user_id:jokeUid,
                        start:startNum
                    });
                    if (result) {
                        // fill comments
                        startNum = result.start;
                        startNums.shift();
                        startNums.push(startNum)

                        var html = '';
                        $.each(result.list, function(i, v) {
                            html += getCommentCellHtml(v);
                        })
                        $('#comment-lastest').append(html);
                        // remove loading image
                        //$('#comment-loading').hide();
                    } else {
                        AndroidWrapper.alert('已经最后一页咯');
                    }
                }
            })
        },

        // 获取评论模块html
        showCommentHtml : function(idStr, d) {
            return getCommentHtml(idStr, d);
        },

        // 获取评论单元html
        showCommentCellHtml : function(d) {
            return getCommentCellHtml(d);
        },
    }

    var getCommentHtml = function(idStr, d) {
        if (idStr == 'comment-mine') {
            title = '我的评论';
        } else if (idStr == 'comment-lastest') {
            title = '最新';
        } else {
            return '';
        }

        var code = '';
        code += '<div id="'+idStr+'">';
        code += '   <span class="comment-title">'+title+'</span>';
        code += getCommentCellHtml(d);
        code += '</div>';
        return code;
    }

    var getCommentCellHtml = function(d) {
        if ('' == d.user_avatar) {
            d.user_avatar = '/Public/img/avatar_default_small.png';
        }
        if (1 == d.is_up) {
            var img = '<img class="comment-up-btn" src="/Public/img/comment_up_press.png"/>';
        } else {
            var img = '<img class="comment-up-btn" src="/Public/img/comment_up.png"/>';
        }
        var code = '';
        code += '<div class="comment-body" data-id="'+d.id+'" data-user-id="'+d.user_id+'" data-user-name="'+d.user_nickname+'">';
        code += '   <div class="comment-header">';
        code += '       <img class="comment-avatar avatar" src="'+d.user_avatar+'"/>';
        code += '       <div class="comment-author">';
        code += '           <span>'+d.user_nickname+'</span>';
        code += '           <span>'+d.create_time+'</span>';
        code += '       </div>';
        code += '       <div class="comment-up-btn" data-isact="'+d.is_up+'">';
        code += '           '+img;
        code += '           <span class="comment-up-count">'+d.up_count+'</span>';
        code += '       </div>';
        code += '   </div>';
        code += '   <span class="comment-content">'+d.content+'</span>';
        code += "</div>";
        return code;
    }

    var bindAction = function($image, api, imgObj, extraParamsObj) {
        var opUtid = $('#wrapper').attr('data-op-user-tid');
        var jokeId = $('#joke').attr('data-id');

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

        var params = {
            joke_id:jokeId,
            user_tid:opUtid,
            is_act:newIsAct
        }
        $.extend(params, extraParamsObj);
        var result = $.global.sendAjax(api, 'POST', params);
        if (result) {
            $image.attr('src', newImg);
            $count.text(newNum);
            $m.attr('data-isact', newIsAct)
        }

        return result;
    }

    return Return;
})();

/**
 * Client调用类 
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

        // 发送评论
        sendComment : function(opUtid, comment) {
            var jokeId = $('#joke').attr('data-id');
            var params = {
                'joke_id' : jokeId,
                'user_tid': opUtid,
                'comment' : comment
            };
            var id = $.global.sendAjax('/comment/add', 'POST', params, true);
            if (id) {
                increaseJokeCmtNum();
                insertCommentHtml(id, comment);
                AndroidWrapper.sendCommentCallback(0, '评论成功')
            } else {
                AndroidWrapper.sendCommentCallback(1, '评论失败')
            }
        },

        // 发送回复
        sendReply : function(opUtid, comment, commentId) {
            var jokeId = $('#joke').attr('data-id');
            var params = {
                'joke_id' : jokeId,
                'user_tid': opUtid,
                'comment' : comment,
                'comment_id' : commentId,
            };
            var id = $.global.sendAjax('/comment/reply', 'POST', params, true);
            if (id) {
                increaseJokeCmtNum();
                insertCommentHtml(id, comment);
                AndroidWrapper.sendReplyCallback(0, '回复成功')
            } else {
                AndroidWrapper.sendReplyCallback(1, '回复失败')
            }
        }
    };

    // 笑话评论数+1
    var increaseJokeCmtNum = function() {
        var $btn = $('#joke-comment-btn').children('span');
        $btn.html(parseInt($btn.text()) + 1);
    };

    // 向评论头部插入最新评论或回复
    var insertCommentHtml = function(id, comment) {
        var d = new Date();
        var create_time = d.getMonth() + 1 + '-' + d.getDate() + ' ' + d.getHours() + ':' + d.getMinutes();
        var $user = $('#wrapper');
        var data = {
            'id' : id,
            'user_id' : $user.attr('data-op-user-id'), 
            'user_nickname' :  $user.attr('data-op-user-name'),
            'user_avatar' : $user.attr('data-op-user-avatar'),
            'create_time' : create_time,
            'is_up' : 0,
            'up_count' : 0,
            'content' : comment
        };

        var cmtIdStr = 'comment-mine';
        if ($('#' + cmtIdStr).length > 0) {
            var html = Web.showCommentCellHtml(data);
            $('#' + cmtIdStr).children('span').after(html);
        } else {
            var html = Web.showCommentHtml(cmtIdStr, data);
            $('#comment').prepend(html);
        }

        var cmtIdStr = 'comment-lastest';
        if ($('#' + cmtIdStr).length > 0) {
            var html = Web.showCommentCellHtml(data);
            $('#' + cmtIdStr).children('span').after(html);
        } else {
            var html = Web.showCommentHtml(cmtIdStr, data);
            $('#comment').append(html);
        }
    };

    return Return;
})();

/**
 * Android端接口封装
 */
AndroidWrapper = (function() {
    var Return = {
        getJokeData : function() {
            if (isExistAndroidObj()) {
                var jokeType = $('#joke').attr('data-type');
                var jokeContent = $('#joke-content span').text();
                var jokeImg = '';
                if (2 == jokeType) {
                    jokeImg = $('#joke-content img').attr('src');
                }

                Android.getJokeData(jokeType, jokeContent, jokeImg);
            }
        },
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
        sendCommentCallback : function(code, message) {
            if (isExistAndroidObj()) {
                Android.sendCommentCallback(code, message);
            }
        },
        sendReply : function(comment_user_nickname, comment_id) {
            if (isExistAndroidObj()) {
                Android.sendReply(comment_user_nickname, comment_id);
            }
        },
        sendReplyCallback : function(code, message) {
            if (isExistAndroidObj()) {
                Android.sendReplyCallback(code, message);
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

/**
 * 全局类
 */
$.global = (function() {
    var Return = {
        sendAjax : function(url, methodType, params, isQuiet) {
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
                        ! isQuiet && AndroidWrapper.alert(data.data);
                    }
                },
                error: function(xhr, type){
                    ! isQuiet && AndroidWrapper.alert('请求时遇到错误，请尝试重新操作。');
                }
            })
            return result;
        }
    };

    return Return;
})();


Zepto(function($){
    // 设置笑话数据(还是set通畅。。。)
    AndroidWrapper.getJokeData();
    // 关闭弹幕
    AndroidWrapper.showTan(0);

    // 绑定加载更多评论
    Web.bindLoadMoreComments();
    // 绑定点击头像事件
    Web.bindClickAvatar();
    // 绑定点击赞事件
    Web.bindClickUp();
    // 绑定点击喜欢事件
    Web.bindClickFavorate();
    // 绑定点击分享事件
    Web.bindClickShare();
    // 绑定评论点击赞事件
    Web.bindCommentClickUp();
    // 绑定评论点击事件
    Web.bindCommentClick();
})
