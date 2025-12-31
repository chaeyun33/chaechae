<?php
// 에러 표시 활성화 (디버깅용)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 커스텀 설정
if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}

// 그누보드 연동
include_once($_SERVER['DOCUMENT_ROOT']."/gnuboard/common.php");

// 파라미터 받기
$bo_table = isset($_GET['bo_table']) ? $_GET['bo_table'] : '';
$wr_id = isset($_GET['wr_id']) ? (int)$_GET['wr_id'] : 0;

if (!$bo_table || !$wr_id) {
    alert('잘못된 접근입니다.', '/gallery/photo.php');
}

$write_table = $g5['write_prefix'].$bo_table;

// 게시글 정보 가져오기
$sql = "SELECT * FROM {$write_table} WHERE wr_id = '{$wr_id}'";
$write = sql_fetch($sql);

if (!$write) {
    alert('존재하지 않는 게시글입니다.', '/gallery/photo.php');
}

// 조회수 증가
sql_query("UPDATE {$write_table} SET wr_hit = wr_hit + 1 WHERE wr_id = '{$wr_id}'");

// 첨부파일 목록 가져오기
$file_sql = "SELECT * FROM {$g5['board_file_table']} 
             WHERE bo_table = '{$bo_table}' 
             AND wr_id = '{$wr_id}' 
             AND bf_type IN (1, 2, 3, 18)
             ORDER BY bf_no";
$file_result = sql_query($file_sql);

// 이전글/다음글
$prev_sql = "SELECT wr_id, wr_subject FROM {$write_table} 
             WHERE wr_is_comment = 0 
             AND wr_num < {$write['wr_num']}
             ORDER BY wr_num DESC, wr_reply ASC
             LIMIT 1";
$prev = sql_fetch($prev_sql);

$next_sql = "SELECT wr_id, wr_subject FROM {$write_table} 
             WHERE wr_is_comment = 0 
             AND wr_num > {$write['wr_num']}
             ORDER BY wr_num ASC, wr_reply ASC
             LIMIT 1";
$next = sql_fetch($next_sql);

$page_title = $write['wr_subject'].' - 포토갤러리';
$body_class = 'view-page';

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

.view-images {
  margin: 30px 0;
}

.view-images img {
  max-width: 100%;
  height: auto;
  display: block;
  margin: 20px auto;
  border-radius: 10px;
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

/* 이전글/다음글 */
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
}

.nav-title {
  flex: 1;
}

.nav-title a {
  color: #333;
  text-decoration: none;
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
  <!-- 게시글 헤더 -->
  <div class="view-header">
    <h1 class="view-title"><?php echo htmlspecialchars($write['wr_subject']); ?></h1>
    <div class="view-info">
      <span>
        <i class="fa-regular fa-calendar"></i>
        <?php echo date("Y.m.d", strtotime($write['wr_datetime'])); ?>
      </span>
      <span>
        <i class="fa-regular fa-eye"></i>
        조회 <?php echo number_format($write['wr_hit']); ?>
      </span>
    </div>
  </div>

  <!-- 게시글 내용 -->
  <div class="view-content">
    <?php 
    // 이미지 파일 표시
    if (sql_num_rows($file_result) > 0) {
      echo '<div class="view-images">';
      while ($file = sql_fetch_array($file_result)) {
        $file_path = '/gnuboard/data/file/'.$bo_table.'/'.$file['bf_file'];
        echo '<img src="'.$file_path.'" alt="'.htmlspecialchars($write['wr_subject']).'">';
      }
      echo '</div>';
    }
    
    // 본문 내용
    if ($write['wr_content']) {
      echo '<div style="margin-top: 30px;">'.nl2br(htmlspecialchars($write['wr_content'])).'</div>';
    }
    ?>
  </div>

  <!-- 버튼 그룹 -->
  <div class="view-buttons">
    <a href="/gallery/photo.php" class="btn btn-list">
      <i class="fa-solid fa-list"></i> 목록
    </a>
    
<?php if ($is_admin_user) { ?>
<div class="admin-buttons">
  <a href="/post/write.php?bo_table=<?php echo $bo_table; ?>&wr_id=<?php echo $wr_id; ?>" class="btn btn-edit">
    <i class="fa-solid fa-pen"></i> 수정
  </a>
  <a href="/post/delete.php?bo_table=<?php echo $bo_table; ?>&wr_id=<?php echo $wr_id; ?>" class="btn btn-delete">
    <i class="fa-solid fa-trash"></i> 삭제
  </a>
</div>
<?php } ?>
  </div>

  <!-- 이전글/다음글 -->
  <div class="view-navigation">
    <div class="nav-item">
      <div class="nav-label">
        <i class="fa-solid fa-chevron-up"></i> 이전글
      </div>
      <div class="nav-title">
        <?php if ($prev) { ?>
          <a href="./view.php?bo_table=<?php echo $bo_table; ?>&wr_id=<?php echo $prev['wr_id']; ?>">
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
          <a href="./view.php?bo_table=<?php echo $bo_table; ?>&wr_id=<?php echo $next['wr_id']; ?>">
            <?php echo htmlspecialchars($next['wr_subject']); ?>
          </a>
        <?php } else { ?>
          <span class="nav-empty">다음글이 없습니다.</span>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<?php
// 푸터 포함
include_once($_SERVER['DOCUMENT_ROOT']."/includes/footer.php");
?>