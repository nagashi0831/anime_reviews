searchWord = function searchWord() {
  var searchResult,
      searchText = $(this).val(),
      // 検索ボックスに入力された値
  targetText,
      hitNum; // 検索結果を格納するための配列を用意

  searchResult = []; // 検索結果エリアの表示を空にする

  $('.searchResultList').empty();
  $('.searchResultNum').empty(); // 検索ボックスに値が入っている場合

  if (searchText != '') {
    //testクラス、liタグの文字列を取得
    $('.test li').each(function () {
      //ここですべてのliのテキストが一つにつながったものをtargetTextに格納してしまうのではないか？
      targetText = $(this).text(); // 検索対象となるリストに入力された文字列が存在するかどうかを判断

      if (targetText.indexOf(searchText) != -1) {
        // 存在する場合はそのリストのテキストを用意した配列に格納
        searchResult.push(targetText);
      }
    }); 
    
    //検索結果をページに出力
    for (var i = 0; i < searchResult.length; i++) {
      searchResult[i] = searchResult[i] + "\n";
      //$('.searchResultList').html('<option>' + searchResult[i] + '</option>');
      $('<span class="title btn btn-outline-info">'+searchResult[i]+'</span>').appendTo('.searchResultList');
    } 
    // ヒットの件数をページに出力
    hitNum = '<span>検索結果</span> :' + searchResult.length + '件見つかりました';
    $('.searchResultNum').append(hitNum);
    
    //タイトルをクリックしたらフォームに自動補完
    $('.title').on('click', function(){
      $('.searchTitle').val($(this).text());
    });
  }
}; // searchWordを実行


$('.searchTitle').on('input', searchWord);
