<?php
if (!defined('_GNUBOARD_')) exit;
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<style>
.view_wrap {
  width: 100%;
  max-width: 1220px;
  margin: 50px auto;
  padding: 0 20px;
  box-sizing: border-box;
  font-family: 'NexonLv2Gothic', sans-serif;
}

.view_title {
  border-bottom: 2px solid #000;
  padding-bottom: 20px;
  margin-bottom: 20px;
}

.view_title h2 {
  font-size: 2rem;
  margin-bottom: 15px;
  color: #000;
}

.view_info {
  display: flex;
  gap: 20px;
  color: #666;
  font-size: 14px;
}

.view_content {
  min-height: 300px;
  padding: 30px 0;
  line-height: 1.8;
  font-size: 16px;
  border-bottom: 1px solid #ddd;
  margin-bottom: 30px;
}

.view_content img {
  max-width: 100%;
  height: auto;
}

.btn_area {
  text-align: center;
  margin: 30px 0;
}

.btn_area a {
  display: inline-block;
  padding: 10px 20px;
  margin: 0 5px;
  background: #2559a8;
  color: #fff;
  text-decoration: none;
  border-radius: 4px;
}

.btn_area a.btn_list {
  background: #666;
}

.file_area {
  padding: 15px;
  background: #f8f8f8;
  border: 1px solid #ddd;
  margin-bottom: 20px;
}

.file_area strong {
  display: block;
  margin-bottom: 10px;
  font-weight: 600;
}

.file_area a {
  display: block;
  padding: 5px 0;
  color: #333;
}

/* 모바일 반응형 */
@media screen and (max-width: 800px) {
  .view_wrap {
    padding: 0 15px;
    margin: 30px auto;
  }

  .view_title h2 {
    font-size: 1.5rem;
  }

  .view_info {
    flex-direction: column;
    gap: 5px;
  }

  .view_content {
    font-size: 14px;
    padding: 20px 0;
  }

  .btn_area a {
    padding: 8px 15px;
    font-size: 14px;
  }
}
</style>

<div class="view_wrap">
  <!-- 제목 영역 -->
  <div class="view_title">
    <h2><?php echo get_text($view['wr_subject']); ?></h2>
    <div class="view_info">
      <span>작성자: <?php echo $view['name']; ?></span>
      <span>작성일: <?php echo date('Y-m-d H:i', strtotime($view['wr_datetime'])); ?></span>
      <span>조회: <?php echo number_format($view['wr_hit']); ?></span>
    </div>
  </div>

  <!-- 첨부파일 영역 -->
  <?php if ($view['file']['count']) { ?>
  <div class="file_area">
    <strong>첨부파일</strong>
    <?php
    for ($i=0; $i<count($view['file']); $i++) {
      if (isset($view['file'][$i]['source']) && $view['file'][$i]['source']) {
    ?>
      <a href="<?php echo $view['file'][$i]['href']; ?>" class="view_file_download">
        <i class="fa fa-download"></i> <?php echo $view['file'][$i]['source'].'('.$view['file'][$i]['size'].')'; ?>
      </a>
    <?php
      }
    }
    ?>
  </div>
  <?php } ?>

  <!-- 내용 영역 -->
  <div class="view_content">
    <?php echo get_view_thumbnail($view['content']); ?>
  </div>

  <!-- 버튼 영역 -->
  <div class="btn_area">
    <a href="<?php echo $list_href; ?>" class="btn_list">목록</a>
    
    <?php if ($update_href) { ?>
    <a href="<?php echo $update_href; ?>">수정</a>
    <?php } ?>
    
    <?php if ($delete_href) { ?>
    <a href="<?php echo $delete_href; ?>" onclick="return confirm('정말 삭제하시겠습니까?');">삭제</a>
    <?php } ?>
    
    <?php if ($write_href) { ?>
    <a href="<?php echo $write_href; ?>">글쓰기</a>
    <?php } ?>
  </div>

  <!-- 이전글/다음글 -->
  <div style="border-top: 1px solid #ddd; padding: 15px 0;">
    <?php if ($prev_href) { ?>
    <div style="padding: 10px 0;">
      <strong>이전글:</strong> <a href="<?php echo $prev_href; ?>"><?php echo $prev_wr_subject; ?></a>
    </div>
    <?php } ?>
    
    <?php if ($next_href) { ?>
    <div style="padding: 10px 0;">
      <strong>다음글:</strong> <a href="<?php echo $next_href; ?>"><?php echo $next_wr_subject; ?></a>
    </div>
    <?php } ?>
  </div>
</div>

<?php
// 코멘트(댓글) 출력
if ($board['bo_use_comment']) {
  include_once(G5_BBS_PATH.'/view_comment.php');
}
?>