/* eslint-env jquery */
/* eslint camelcase: ["error", {allow: ["parent_id"]}] */

// 主留言的 HTML
function getMainComment(nickname, time, content, id) {
  const maincomment = `<div class='comment-box'>
        <!--主留言-->
        <div class='main-comment-box'>
            <div class='nickname'>${nickname}</div>
            <div class='time'>${time}</div>
            <div class='comment'>${content}</div>
            <div class='edit-comment'>
            <form method='GET' action='./update.php?id=${id}'>
                <input type='hidden' name='id' value='${id}' />
                <input type='submit' value='修改' />
            </form>
        </div>
            <div class='delete-comment'>
                <button class='btn-delete' data-id='${id}'>刪除</button>
            </div>

        </div>
        <!--新增留言-->
        <div class='add-sub-comment-box'>
            <div class='nickname'>${nickname}</div>
            <div class='sub-textarea leave-comment-box'> 
                <form action="handle_add_comments.php" method="post"> 
                    <input type='hidden' value='${id}' name="parent_id" />
                    <textarea name="content" placeholder="回覆留言吧！" rows="10" cols="50"></textarea>
                    <div class='submit-btn'><button class='btn_submit'>送出</button></div>
                </form>
            </div>
        </div>`;
  return maincomment;
}


// 子留言的 HTML
function getSubComment(nickname, time, content, id) {
  return `
    <div class='sub-comment-box'>          
        <div class='nickname'>${nickname}</div>
        <div class='time'>${time}</div>
        <div class='comment'>${content}</div>
        <div class='edit-comment'>
            <form method='GET' action='./update.php?id=${id}'>
                <input type='hidden' name='id' value='${id}' />
                <input type='submit' value='修改' />
            </form>
        </div>
        <div class='delete-comment'>
            <button class='btn-delete' data-id='${id}'>刪除</button>
        </div>
    </div> 
    `;
}


$(document).ready(() => {
  // 刪除 ajax
  $('.comments').on('click', '.delete-comment', (e) => {
    // eslint-disable-next-line no-restricted-globals
    if (!confirm('是否確定要刪除？')) return; // 如果不刪除就跳出，就不會執行到下面的程式碼
    const id = $(e.target).attr('data-id'); // 拿要傳送的值，按鈕的 $id
    $.ajax({
      method: 'POST',
      url: './handle_delete.php',
      data: {
        id, // POST傳id //const id，key 和 value 一樣的話顯示 key 就好
      },
    }).done((response) => { // 完成的話刪掉東西
      // 印出 response 的 msg
      const msg = JSON.parse(response);
      alert(msg.message);
      // 主留言刪除鍵和子留言刪除鍵
      const subComment = $(e.target).parents('.sub-comment-box, .sub-comment-box-color'); // 子留言
      if (subComment.length === 0) { // 選不到sub-comment時，主留言
        $(e.target).parents('.comment-box').hide(300);
      } else { // 選到sub-comment時，子留言
        subComment.hide(300).attr('class');
      }
    }).fail(() => { // 失敗的話 alert 刪除失敗
      alert('刪除失敗！');
    });
  });

  $('.wrapper').on('click', '.btn_submit', (e) => { // 選到送出 btn
    e.preventDefault(); // 按鈕要preventDefault()
    const parent_id = $(e.target).parents('.leave-comment-box').find('input[name="parent_id"]').val(); // 拿要傳送的值 input 的 value 0
    const content = $(e.target).parents('.leave-comment-box').find('textarea[name="content"]').val(); // 拿要傳送的值 content 的值是什麼

    $.ajax({
      method: 'POST',
      url: './handle_add_comments.php', // 資料傳送到 handle_add_comments
      data: {
        parent_id, // POST傳id //const id，key 和 value 一樣的話顯示 key 就好
        content,
      },
    }).done((response) => { // 成功的話 append 資料
      // 印出 response 的 msg
      const msg = JSON.parse(response);// response 用 json 格式印出
      const [nickname, id, time] = [msg.nickname, msg.id, msg.time];
      alert(msg.message); // alert response 的 message
      // ajax 資料到畫面上
      // 新增主留言
      // $(".comments").prepend(getMainComment(nickname, time, content, id, parent_id));
      // 新增子留言
      // $(e.target).parents('.add-sub-comment-box')
      // .before(getSubComment(nickname, time, content, id));
      const addSubComment = $(e.target).parents('.add-sub-comment-box');// 子留言
      if (addSubComment.length === 0) { // 主留言
        // 新增主留言樣式
        $('.comments').prepend(getMainComment(nickname, time, content, id, parent_id));
      } else { // 子留言
        // 新增子留言樣式
        addSubComment.before(getSubComment(nickname, time, content, id));
      }
      // 清空留言
      $(e.target).parents('.leave-comment-box').find('textarea[name="content"]').val('');
    }).fail(() => { // 失敗的話 alert 刪除失敗
      alert('資料傳輸失敗！');
    });
  });
});
