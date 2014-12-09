Client = (function() {
    var Return = {
        bindLoadMore : function(jokeId, userId) {
            var startNum = parseInt($('#comment-lastest').attr('data-start'));
            var startNums = new Array();
            startNums.push(0, startNum);

            $(window).scroll(function(){
                if (startNums[0] === startNums[1]) {
                    return false;
                }

                if ($(document).height() - $(window).scrollTop() - $(window).height() < 100) {
                    $.ajax({
                        type: 'GET',
                        url: '/comment/lastest',
                        async: false,
                        data: {joke_id:jokeId, user_id:userId, start:startNum},
                        dataType: 'json',
                        context: $('#comment-lastest'),
                        success: function(data){
                            if (data.status) {
                                startNum = data.data.start;
                                startNums.shift();
                                startNums.push(startNum)

                                var html = '';
                                $.each(data.data.list, function(i, v) {
                                    html += getCommentCellHtml(v);
                                })
                                $(this).append(html);
                            }
                        },
                        error: function(xhr, type){
                        }
                    })
                }
            })
        },
        
    }

    var getCommentCellHtml = function(d) {
        if ('' == d.user_avatar) {
            d.user_avatar = '/Public/img/avatar_default_small.png';
        }

        var code = '';
        code += '<div class="comment-body">';
        code += '   <div class="comment-header">';
        code += '       <img class="comment-avatar" src="'+d.user_avatar+'"/>';
        code += '       <div class="comment-name">';
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

    return Return;
})();

Zepto(function($){
    var jokeId = $('#joke').attr('data-joke-id');
    var userId = $('#joke').attr('data-joke-user-id');

    //$(window).bind('touchmove', function(){
    //})
    Client.bindLoadMore(jokeId, userId);
})
