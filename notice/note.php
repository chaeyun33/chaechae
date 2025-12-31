<?php
// 커스텀 설정
if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}

// 그누보드 연동
include_once($_SERVER['DOCUMENT_ROOT']."/gnuboard/common.php");

// 게시글 번호 받기
$wr_id = isset($_GET['wr_id']) ? (int)$_GET['wr_id'] : 0;
$bo_table = isset($_GET['bo_table']) ? $_GET['bo_table'] : 'notice';

if (!$wr_id) {
    alert('잘못된 접근입니다.', '/notice/notice.php');
}

// 게시판 정보 가져오기
$board = sql_fetch("SELECT * FROM {$g5['board_table']} WHERE bo_table = '{$bo_table}'");

if (!$board) {
    alert('존재하지 않는 게시판입니다.', '/notice/notice.php');
}

$write_table = $g5['write_prefix'].$bo_table;

// 게시글 데이터 가져오기
$sql = "SELECT * FROM {$write_table} WHERE wr_id = '{$wr_id}'";
$view = sql_fetch($sql);

// 게시글이 없으면 목록으로 이동
if (!$view) {
    alert('존재하지 않는 게시글입니다.', '/notice/notice.php');
}

// 조회수 증가
$sql = "UPDATE {$write_table} SET wr_hit = wr_hit + 1 WHERE wr_id = '{$wr_id}'";
sql_query($sql);

// 첨부파일 목록 가져오기
$file_sql = "SELECT * FROM {$g5['board_file_table']} 
             WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}' 
             ORDER BY bf_no";
$file_result = sql_query($file_sql);
$file_count = sql_num_rows($file_result);

// 이전글/다음글
$prev_sql = "SELECT wr_id, wr_subject FROM {$write_table} 
             WHERE wr_is_comment = 0 
             AND wr_num < {$view['wr_num']}
             ORDER BY wr_num DESC, wr_reply ASC
             LIMIT 1";
$prev = sql_fetch($prev_sql);

$next_sql = "SELECT wr_id, wr_subject FROM {$write_table} 
             WHERE wr_is_comment = 0 
             AND wr_num > {$view['wr_num']}
             ORDER BY wr_num ASC, wr_reply ASC
             LIMIT 1";
$next = sql_fetch($next_sql);

$page_title = $view['wr_subject'].' - 전북우리사이';
$body_class = 'notice-view';

// 헤더 포함
include_once($_SERVER['DOCUMENT_ROOT']."/includes/header.php");

// 관리자 권한 체크
$is_admin_user = false;
if (isset($is_admin) && $is_admin == 'super') {
    $is_admin_user = true;
} elseif (isset($member['mb_id']) && $member['mb_id'] == 'admin') {
    $is_admin_user = true;
}
?>

<style>
.view-container {
  width: 100%;
  max-width: 1220px;
  margin: 50px auto 100px;
  padding: 0 20px;
  box-sizing: border-box;
}

.view-header {
  border-bottom: 2px solid #333;
  padding-bottom: 20px;
  margin-bottom: 30px;
}

.view-title {
  font-size: 2rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 15px;
  line-height: 1.4;
  font-family: 'NexonLv2Gothic', sans-serif;
}

.view-info {
  display: flex;
  gap: 20px;
  color: #888;
  font-size: 0.95rem;
}

.view-info span {
  display: flex;
  align-items: center;
  gap: 5px;
}

.view-content {
  min-height: 300px;
  padding: 30px 0;
  line-height: 1.8;
  color: #333;
  font-size: 1.05rem;
}

.view-content img {
  max-width: 100%;
  height: auto;
  margin: 20px auto;
  display: block;
  border-radius: 8px;
}

.attach-files {
  margin-top: 40px;
  padding: 20px;
  background: #f8f9fa;
  border: 1px solid #e5e5e5;
  border-radius: 8px;
}

.attach-files h3 {
  font-size: 1rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 8px;
  font-family: 'NexonLv2Gothic', sans-serif;
}

.attach-files ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.attach-files li {
  padding: 12px 0;
  border-bottom: 1px solid #e5e5e5;
}

.attach-files li:last-child {
  border-bottom: none;
}

.attach-files a {
  color: #2559a8;
  text-decoration: none;
  font-size: 0.95rem;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: color 0.2s;
}

.attach-files a:hover {
  color: #1d4380;
  text-decoration: underline;
}

.file-size {
  color: #999;
  font-size: 0.85rem;
  margin-left: 10px;
}

.file-download {
  color: #999;
  font-size: 0.85rem;
}

.view-buttons {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 50px;
  padding-top: 30px;
  border-top: 1px solid #ddd;
}

.btn-group {
  display: flex;
  gap: 10px;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 10px 20px;
  border-radius: 6px;
  text-decoration: none;
  font-size: 15px;
  font-weight: 500;
  transition: all 0.2s;
  border: 1px solid #ddd;
  font-family: 'NexonLv2Gothic', sans-serif;
}

.btn-list {
  background: #fff;
  color: #333 !important;
}

.btn-list:hover {
  background: #f8f9fa;
}

.btn-edit {
  background: #2559a8;
  color: #fff !important;
  border-color: #2559a8;
}

.btn-edit:hover {
  background: #1d4380;
}

.btn-delete {
  background: #dc3545;
  color: #fff !important;
  border-color: #dc3545;
}

.btn-delete:hover {
  background: #c82333;
}

.view-navigation {
  margin-top: 30px;
  border-top: 1px solid #ddd;
  padding-top: 20px;
}

.nav-item {
  display: flex;
  padding: 15px 0;
  border-bottom: 1px solid #f0f0f0;
}

.nav-item:last-child {
  border-bottom: none;
}

.nav-label {
  min-width: 80px;
  color: #888;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 6px;
}

.nav-title {
  flex: 1;
}

.nav-title a {
  color: #333;
  text-decoration: none;
  transition: color 0.2s;
}

.nav-title a:hover {
  color: #2559a8;
  text-decoration: underline;
}

.nav-empty {
  color: #aaa;
}

@media screen and (max-width: 800px) {
  .view-container {
    margin: 30px auto 80px;
    padding: 0 15px;
  }

  .view-title {
    font-size: 1.5rem;
  }

  .view-info {
    flex-direction: column;
    gap: 8px;
    font-size: 0.9rem;
  }

  .view-content {
    font-size: 1rem;
    padding: 20px 0;
  }

  .attach-files {
    padding: 15px;
    margin-top: 30px;
  }

  .attach-files h3 {
    font-size: 0.9rem;
  }

  .attach-files a {
    font-size: 0.9rem;
  }

  .view-buttons {
    flex-direction: column;
    gap: 15px;
  }

  .btn-group {
    width: 100%;
    justify-content: center;
  }

  .btn {
    padding: 8px 16px;
    font-size: 14px;
  }

  .nav-item {
    flex-direction: column;
    gap: 8px;
  }

  .nav-label {
    min-width: auto;
  }
}
</style>

<div class="view-container">
  <div class="view-header">
    <h1 class="view-title"><?php echo htmlspecialchars($view['wr_subject']); ?></h1>
    <div class="view-info">
      <span>
        <i class="fa-regular fa-calendar"></i>
        <?php echo date("Y.m.d", strtotime($view['wr_datetime'])); ?>
      </span>
      <span>
        <i class="fa-regular fa-eye"></i>
        조회 <?php echo number_format($view['wr_hit']); ?>
      </span>
    </div>
  </div>

  <div class="view-content">
    <?php echo $view['wr_content']; ?>
  </div>

  <?php if ($file_count > 0) { ?>
  <div class="attach-files">
    <h3>
      <i class="fa-solid fa-paperclip"></i> 첨부파일
    </h3>
    <ul>
      <?php 
      while ($file = sql_fetch_array($file_result)) {
        if ($file['bf_file']) {
          $download_url = '/notice/download_custom.php?bo_table='.$bo_table.'&wr_id='.$wr_id.'&no='.$file['bf_no'];
          
          $file_size = $file['bf_filesize'];
          if ($file_size < 1024) {
            $file_size_str = $file_size . 'B';
          } elseif ($file_size < 1024 * 1024) {
            $file_size_str = number_format($file_size / 1024, 1) . 'KB';
          } else {
            $file_size_str = number_format($file_size / (1024 * 1024), 1) . 'MB';
          }
          
          $download_count = isset($file['bf_download']) ? $file['bf_download'] : 0;
          
          echo '<li>';
          echo '<a href="'.$download_url.'" class="file-link">';
          echo '<i class="fa-solid fa-download"></i> ';
          echo htmlspecialchars($file['bf_source']);
          echo '<span class="file-size">('.$file_size_str.')</span>';
          if ($download_count > 0) {
            echo '<span class="file-download"> | 다운로드: '.$download_count.'회</span>';
          }
          echo '</a>';
          echo '</li>';
        }
      }
      ?>
    </ul>
  </div>
  <?php } ?>

  <div class="view-buttons">
    <a href="/notice/notice.php" class="btn btn-list">
      <i class="fa-solid fa-list"></i> 목록
    </a>
    
    <?php if ($is_admin_user) { ?>
    <div class="btn-group">
      <a href="/post/write.php?bo_table=<?php echo $bo_table; ?>&wr_id=<?php echo $wr_id; ?>" class="btn btn-edit">
        <i class="fa-solid fa-pen"></i> 수정
      </a>
      <a href="/post/delete.php?bo_table=<?php echo $bo_table; ?>&wr_id=<?php echo $wr_id; ?>" 
         class="btn btn-delete" 
         onclick="return confirm('정말 삭제하시겠습니까?');">
        <i class="fa-solid fa-trash"></i> 삭제
      </a>
    </div>
    <?php } ?>
  </div>

  <div class="view-navigation">
    <div class="nav-item">
      <div class="nav-label">
        <i class="fa-solid fa-chevron-up"></i> 이전글
      </div>
      <div class="nav-title">
        <?php if ($prev) { ?>
          <a href="./note.php?bo_table=<?php echo $bo_table; ?>&wr_id=<?php echo $prev['wr_id']; ?>">
            <?php echo htmlspecialchars($prev['wr_subject']); ?>
          </a>
        <?php } else { ?>
          <span class="nav-empty">이전글이 없습니다.</span>
        <?php } ?>
      </div>
    </div>
    
    <div class="nav-item">
      <div class="nav-label">
        <i class="fa-solid fa-chevron-down"></i> 다음글
      </div>
      <div class="nav-title">
        <?php if ($next) { ?>
          <a href="./note.php?bo_table=<?php echo $bo_table; ?>&wr_id=<?php echo $next['wr_id']; ?>">
            <?php echo htmlspecialchars($next['wr_subject']); ?>
          </a>
        <?php } else { ?>
          <span class="nav-empty">다음글이 없습니다.</span>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileLinks = document.querySelectorAll('.file-link');
    
    fileLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = this.getAttribute('href');
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/notice/download_custom.php';
            
            const urlParams = new URLSearchParams(url.split('?')[1]);
            
            urlParams.forEach((value, key) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        });
    });
});
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT']."/includes/footer.php");
?>