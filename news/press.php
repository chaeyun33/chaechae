<?php
// 에러 표시 활성화 (디버깅용)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 커스텀 설정 (common.php 보다 먼저!)
if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}

// 그누보드 연동 - 절대경로 사용
$gnuboard_path = $_SERVER['DOCUMENT_ROOT']."/gnuboard/common.php";

// 파일 존재 확인
if (!file_exists($gnuboard_path)) {
    die("그누보드 common.php 파일을 찾을 수 없습니다: " . $gnuboard_path);
}

include_once($gnuboard_path);

$base_url = '';
$page_title = '언론보도 - 전북우리사이';
$body_class = 'press-page';

// 헤더 포함 - 절대경로 사용
$header_path = $_SERVER['DOCUMENT_ROOT']."/includes/header.php";
if (!file_exists($header_path)) {
    die("header.php 파일을 찾을 수 없습니다: " . $header_path);
}
include_once($header_path);

// 페이징 설정
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 15; // 한 페이지당 15개
$offset = ($page - 1) * $per_page;

// 게시판 테이블명
$bo_table = 'press'; // 언론보도 게시판 테이블명
$write_table = $g5['write_prefix'].$bo_table;

// 관리자 권한 체크
$is_admin_user = false;
if (isset($is_admin) && $is_admin == 'super') {
    $is_admin_user = true;
} elseif (isset($member['mb_id']) && $member['mb_id'] == 'admin') {
    $is_admin_user = true;
}

// 전체 게시글 수
$total_count_result = sql_fetch("SELECT COUNT(*) as cnt FROM {$write_table} WHERE wr_is_comment = 0");
$total_count = $total_count_result['cnt'];

// 전체 페이지 수
$total_pages = ceil($total_count / $per_page);

// 게시글 목록 가져오기 (최신순)
$sql = "SELECT * FROM {$write_table} 
        WHERE wr_is_comment = 0 
        ORDER BY wr_id DESC
        LIMIT {$offset}, {$per_page}";
$result = sql_query($sql);
?>

<style>
.major{font-family: 'NexonLv2Gothic';}
.notices{font-family: 'NexonLv2Gothic gothic';}

section {
  width: 100%;
  max-width: 1220px;
  margin: 0 auto;
  border-bottom: 2px solid #000;
  overflow: hidden;
  padding: 0 20px;
  box-sizing: border-box;
}

.title {
  text-align: center;
  margin: 35px 0;
}

.title p {
  font-family: 'NexonLv2Gothic gothic'; 
  margin-top: 10px; 
  color: #b3b3b3;
}

h1 {
  font-family: 'NexonLv2Gothic', sans-serif;
  font-size: 2.5rem;
}

/* 관리자 글쓰기 버튼 */
.admin-write-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 10px 20px;
  margin-top: 15px;
  background: #2559a8;
  color: #fff !important;
  text-decoration: none !important;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 500;
  transition: background 0.2s;
}

.admin-write-btn:hover {
  background: #1d4380;
}

.admin-write-btn i {
  font-size: 14px;
}

.notice{
  color: #2559a8; 
  font-family: 'NexonLv2Gothic'; 
}

.notice p {
  font-family: 'NexonLv2Gothic gothic';
  color: #b3b3b3;
  margin-top: 5px;
}

article {
  width: 100%;
  max-width: 1220px;
  margin: 0 auto;
  padding: 0;
  box-sizing: border-box;
  margin-bottom: 300px; 
  font-family: 'NexonLv2Gothic', sans-serif;
}

article table {
  padding: 0 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

th, td {
  border-bottom: 1px solid #ddd;
  padding: 15px 10px;
  font-size: 20px;
}

td {
  font-family: 'NexonLv2Gothic gothic';
}

th, td > a {
  color: #000;
  text-decoration: none;
}

/* 관리자 모드일 때 */
.admin-mode th:nth-child(1), 
.admin-mode td:nth-child(1) {
  width: 65%;
  text-align: left;
}

.admin-mode th:nth-child(2), 
.admin-mode td:nth-child(2) {
  width: 20%;
  text-align: center;
}

.admin-mode th:nth-child(3), 
.admin-mode td:nth-child(3) {
  width: 15%;
  text-align: center;
}

/* 일반 모드일 때 */
th:nth-child(1), td:nth-child(1) {
  width: 75%;
  text-align: left;
}

th:nth-child(2), td:nth-child(2) {
  width: 25%;
  text-align: center;
}

td > a:hover {
  color: #2559a8;
  text-decoration: underline;
}

.external-link {
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

.external-link i {
  font-size: 14px;
  color: #888;
}

/* 관리 버튼 */
.admin-actions {
  display: flex;
  gap: 5px;
  justify-content: center;
}

.btn-edit, .btn-delete {
  padding: 5px 10px;
  border-radius: 4px;
  font-size: 13px;
  text-decoration: none;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-edit {
  background: #2559a8;
  color: #fff !important;
}

.btn-edit:hover {
  background: #1d4380;
}

.btn-delete {
  background: #dc3545;
  color: #fff !important;
}

.btn-delete:hover {
  background: #c82333;
}

/* 빈 목록 메시지 */
.empty-list {
  text-align: center;
  padding: 100px 20px;
  color: #888;
  font-size: 18px;
}

/* 페이징 */
.pagination {
  width: 100%;
  margin: 30px 0;
  gap: 10px;
  text-align: center;
}

.pagination a {
  color: #000;
  font-size: 16px;
  padding: 6px 12px;
  font-family: 'NexonLv2Gothic', sans-serif;
  text-decoration: none;
}

.pagination a:hover {
  color: #2559a8;
}

/* 모바일 반응형 */
@media screen and (max-width: 800px) {
  footer { display: none; }
  #wrap { display: block; }
  .mlogo { float: left; display: block; width: 30%; }
  .mlogo img { width: 100%; height: auto; }
  .mmenu { display: block; }
  .menu { display: none; }
  .navi.show { display: flex; }
  .hamburger { display: flex; }

  section {
    padding: 20px 15px;
  }

  article {
    padding: 0;
  }

  article table {
    padding: 0;
  }

  /* 헤더 행 */
  #noticeTable tr:first-child { 
    display: flex;
    width: 100%;
    padding: 10px 15px;
    background: #fff;
    border-bottom: 1px solid #ddd;
  }

  #noticeTable tr:first-child th {
    padding: 5px 0;
    font-size: 14px;
  }

  #noticeTable.admin-mode tr:first-child th:nth-child(1) {
    width: 50%;
    text-align: left;
    padding-left: 10px;
  }

  #noticeTable.admin-mode tr:first-child th:nth-child(2) {
    width: 25%;
    text-align: center;
  }

  #noticeTable.admin-mode tr:first-child th:nth-child(3) {
    width: 25%;
    text-align: center;
  }

  #noticeTable tr:first-child th:nth-child(1) {
    width: 70%;
    text-align: left;
    padding-left: 10px;
  }

  #noticeTable tr:first-child th:nth-child(2) {
    width: 30%;
    text-align: center;
  }
  
  /* 바디 */
  #noticeTable tbody { 
    display: block;
    width: 100%;
  }
  
  #noticeTable tbody tr {
    display: flex;
    width: 100%;
    padding: 15px;
    border-bottom: 1px solid #e5e5e5;
    background: #fff;
    box-sizing: border-box;
    align-items: center;
  }

  #noticeTable tbody tr:last-child {
    border-bottom: none;
  }

  #noticeTable tbody tr td {
    padding: 0;
    border: none;
    font-size: 14px;
  }

  #noticeTable.admin-mode tbody tr td:nth-child(1) {
    width: 50%;
    text-align: left;
    padding-left: 10px;
  }

  #noticeTable.admin-mode tbody tr td:nth-child(2) {
    width: 25%;
    text-align: center;
    font-size: 13px;
  }

  #noticeTable.admin-mode tbody tr td:nth-child(3) {
    width: 25%;
    text-align: center;
  }

  #noticeTable tbody tr td:nth-child(1) {
    width: 70%;
    text-align: left;
    padding-left: 10px;
  }

  #noticeTable tbody tr td:nth-child(1) a {
    color: #000;
    font-size: 14px;
  }

  #noticeTable tbody tr td:nth-child(2) {
    width: 30%;
    text-align: center;
    font-size: 13px;
  }

  .external-link i {
    font-size: 12px;
  }
  
  .admin-write-btn {
    padding: 8px 16px;
    font-size: 14px;
    margin-top: 12px;
  }

  .admin-actions {
    flex-direction: column;
    gap: 3px;
  }

  .btn-edit, .btn-delete {
    padding: 4px 8px;
    font-size: 12px;
  }
}
</style>

<section>
  <div class="title">
    <h1>언론보도</h1>
    <p>전북우리사이의 다양한 소식을 알려드립니다.</p>
    
    <?php if ($is_admin_user) { ?>
    <a href="/post/write.php?bo_table=<?php echo $bo_table; ?>" class="admin-write-btn">
      <i class="fa-solid fa-pen"></i> 글쓰기
    </a>
    <?php } ?>
  </div>
</section>

<article>
  <?php if ($result && sql_num_rows($result) > 0) { ?>
  <table id="noticeTable" class="<?php echo $is_admin_user ? 'admin-mode' : ''; ?>">
    <tr>
      <th>제목</th>
      <th>작성일</th>
      <?php if ($is_admin_user) { ?>
      <th>관리</th>
      <?php } ?>
    </tr>
    <tbody>
      <?php 
      while ($row = sql_fetch_array($result)) {
        // 외부 링크 URL (wr_link1 필드 사용)
        $external_url = $row['wr_link1'];
        
        // 작성일 포맷
        $date_str = date("y.m.d", strtotime($row['wr_datetime']));
      ?>
      <tr>
        <td class="major">
          <?php if ($external_url) { ?>
            <a href="<?php echo htmlspecialchars($external_url); ?>" target="_blank" class="external-link">
              <?php echo htmlspecialchars($row['wr_subject']); ?>
              <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>
          <?php } else { ?>
            <?php echo htmlspecialchars($row['wr_subject']); ?>
          <?php } ?>
        </td>
        <td class="major"><?php echo $date_str; ?></td>
        <?php if ($is_admin_user) { ?>
        <td>
          <div class="admin-actions">
            <a href="/post/write.php?bo_table=<?php echo $bo_table; ?>&wr_id=<?php echo $row['wr_id']; ?>" class="btn-edit">
              <i class="fa-solid fa-pen"></i> 수정
            </a>
            <a href="/post/delete.php?bo_table=<?php echo $bo_table; ?>&wr_id=<?php echo $row['wr_id']; ?>" class="btn-delete">
              <i class="fa-solid fa-trash"></i> 삭제
            </a>
          </div>
        </td>
        <?php } ?>
      </tr>
      <?php } ?>
    </tbody>
  </table>

  <?php if ($total_pages > 1) { ?>
  <div class="pagination">
    <?php if ($page > 1) { ?>
      <a href="?page=1">«</a>
      <a href="?page=<?php echo $page-1; ?>">‹</a>
    <?php } ?>
    
    <?php
    $start_page = max(1, $page - 2);
    $end_page = min($total_pages, $page + 2);
    
    for ($i = $start_page; $i <= $end_page; $i++) {
      if ($i == $page) {
        echo '<a href="?page='.$i.'" style="font-weight: bold; color: #2559a8;">'.$i.'</a>';
      } else {
        echo '<a href="?page='.$i.'">'.$i.'</a>';
      }
    }
    ?>
    
    <?php if ($page < $total_pages) { ?>
      <a href="?page=<?php echo $page+1; ?>">›</a>
      <a href="?page=<?php echo $total_pages; ?>">»</a>
    <?php } ?>
  </div>
  <?php } ?>
  
  <?php } else { ?>
  <div class="empty-list">
    등록된 언론보도가 없습니다.
  </div>
  <?php } ?>
</article>

<?php
// 푸터 포함
include_once($_SERVER['DOCUMENT_ROOT']."/includes/footer.php");
?>