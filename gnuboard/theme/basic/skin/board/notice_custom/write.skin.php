<?php
if (!defined('_GNUBOARD_')) exit;
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<style>
.write_wrap {
  width: 100%;
  max-width: 1220px;
  margin: 50px auto;
  padding: 0 20px;
  box-sizing: border-box;
  font-family: 'NexonLv2Gothic', sans-serif;
}

.write_title {
  border-bottom: 2px solid #000;
  padding-bottom: 20px;
  margin-bottom: 30px;
}

.write_title h2 {
  font-size: 2rem;
  color: #000;
}

.write_table {
  width: 100%;
  border-top: 2px solid #000;
  border-collapse: collapse;
}

.write_table th {
  width: 150px;
  padding: 15px;
  background: #f8f8f8;
  border-bottom: 1px solid #ddd;
  text-align: left;
  font-weight: 600;
}

.write_table td {
  padding: 15px;
  border-bottom: 1px solid #ddd;
}

.write_table input[type="text"],
.write_table input[type="password"] {
  width: 100%;
  max-width: 500px;
  padding: 10px;
  border: 1px solid #ddd;
  font-size: 14px;
}

.write_table textarea {
  width: 100%;
  height: 400px;
  padding: 10px;
  border: 1px solid #ddd;
  font-size: 14px;
  line-height: 1.6;
}

.btn_area {
  text-align: center;
  margin: 30px 0;
}

.btn_area button,
.btn_area a {
  display: inline-block;
  padding: 12px 30px;
  margin: 0 5px;
  background: #2559a8;
  color: #fff;
  border: none;
  text-decoration: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
}

.btn_area a {
  background: #666;
}

/* 모바일 반응형 */
@media screen and (max-width: 800px) {
  .write_wrap {
    padding: 0 15px;
    margin: 30px auto;
  }

  .write_title h2 {
    font-size: 1.5rem;
  }

  .write_table th {
    width: 100px;
    padding: 10px;
    font-size: 14px;
  }

  .write_table td {
    padding: 10px;
  }

  .write_table textarea {
    height: 300px;
  }

  .btn_area button,
  .btn_area a {
    padding: 10px 20px;
    font-size: 14px;
  }
}
</style>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<div class="write_wrap">
  <div class="write_title">
    <h2><?php echo $g5['title']; ?></h2>
  </div>

  <form name="fwrite" id="fwrite" action="<?php echo $action_url; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
    <input type="hidden" name="w" value="<?php echo $w; ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table; ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id; ?>">
    <input type="hidden" name="sca" value="<?php echo $sca; ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
    <input type="hidden" name="stx" value="<?php echo $stx; ?>">
    <input type="hidden" name="spt" value="<?php echo $spt; ?>">
    <input type="hidden" name="sst" value="<?php echo $sst; ?>">
    <input type="hidden" name="sod" value="<?php echo $sod; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">

    <table class="write_table">
      <?php if ($is_name) { ?>
      <tr>
        <th><label for="wr_name">이름</label></th>
        <td>
          <input type="text" name="wr_name" id="wr_name" value="<?php echo $name; ?>" required>
        </td>
      </tr>
      <?php } ?>

      <?php if ($is_password) { ?>
      <tr>
        <th><label for="wr_password">비밀번호</label></th>
        <td>
          <input type="password" name="wr_password" id="wr_password" <?php echo $password_required; ?>>
        </td>
      </tr>
      <?php } ?>

      <tr>
        <th><label for="wr_subject">제목<strong class="required">*</strong></label></th>
        <td>
          <input type="text" name="wr_subject" id="wr_subject" value="<?php echo $subject; ?>" required>
        </td>
      </tr>

      <tr>
        <th><label for="wr_content">내용<strong class="required">*</strong></label></th>
        <td>
          <?php echo $editor_html; ?>
        </td>
      </tr>

      <?php for ($i=1; $i<=2; $i++) { ?>
      <tr>
        <th><label for="bf_file_<?php echo $i; ?>">파일 #<?php echo $i; ?></label></th>
        <td>
          <input type="file" name="bf_file[]" id="bf_file_<?php echo $i; ?>">
          <?php if ($w == 'u' && $file[$i]['file']) { ?>
          <input type="checkbox" name="bf_file_del[<?php echo $i; ?>]" value="1" id="bf_file_del_<?php echo $i; ?>">
          <label for="bf_file_del_<?php echo $i; ?>"><?php echo $file[$i]['source'].'('.$file[$i]['size'].')'; ?> 파일 삭제</label>
          <?php } ?>
        </td>
      </tr>
      <?php } ?>
    </table>

    <div class="btn_area">
      <button type="submit">확인</button>
      <a href="<?php echo get_pretty_url($bo_table); ?>">취소</a>
    </div>
  </form>
</div>

<script>
<?php if ($write_min || $write_max) { ?>
// 글자수 제한
var char_min = parseInt(<?php echo $write_min; ?>);
var char_max = parseInt(<?php echo $write_max; ?>);
</script>
<script src="<?php echo G5_JS_URL; ?>/write.js"></script>
<?php } ?>

<script>
function html_auto_br(obj) {
  if (obj.checked) {
    result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
    if (result)
      obj.value = "html2";
    else
      obj.value = "html1";
  }
  else
    obj.value = "";
}

function fwrite_submit(f) {
  if (!f.wr_subject.value) {
    alert("제목을 입력하세요.");
    f.wr_subject.focus();
    return false;
  }

  if (!f.wr_content.value) {
    alert("내용을 입력하세요.");
    f.wr_content.focus();
    return false;
  }

  <?php echo $captcha_js; ?>

  document.getElementById("btn_submit").disabled = "disabled";

  return true;
}
</script>