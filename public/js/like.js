/*global $ */
$(function () {
    var like = $('.js-like-toggle');
    var likePostId;
    console.log('like.jsが読み込まれました');
    like.on('click', function () {
        var $this = $(this);
        console.log('クリックされました');
        likePostId = $this.data('postid');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: 'anime/ajaxlike', //routeの記述
            type: 'POST', //受け取り方法の記述（GETもある)
            data: {
                'anime_id': likePostId //コントローラーに渡すパラメーター
            },
        })
        
        //Ajaxリクエストが成功した場合
        .done(function (data) {
            //lovedクラスを追加
            $this.toggleClass('loved');
            
            //.likesCountの次の要素のhtmlを「data.postLikesCount」の値に書き換える
            $this.next('.likesCount').html(data.postLikesCount);
        })
        //AJAXリクエストが失敗した場合
        .fail(function (data, xhr, err) {
            //ここの処理はエラーが出たときにエラー内容をわかるようにしておく。
            console.log('エラー');
            console.log(err);
            console.log(xhr);
        });
        
        return false;
    });
});