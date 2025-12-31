<?php
// 커스텀 설정 (common.php 보다 먼저!)
if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}

// 그누보드 연동 - 절대경로 사용
include_once($_SERVER['DOCUMENT_ROOT']."/gnuboard/common.php");
$base_url = '';
$page_title = '공지사항 - 전북우리사이';
$body_class = 'notice-page';

// 헤더 포함 - 절대경로 사용
include_once($_SERVER['DOCUMENT_ROOT']."/includes/header.php");

// 페이징 설정
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// 게시판 테이블명
$bo_table = 'notice';
$write_table = $g5['write_prefix'].$bo_table;

// 관리자 권한 체크 (그누보드 변수 사용)
$is_admin_user = false;
if (isset($is_admin) && $is_admin == 'super') {
    $is_admin_user = true;
} elseif (isset($member['mb_id']) && $member['mb_id'] == 'admin') {
    $is_admin_user = true;
}

// 전체 게시글 수
$total_count = sql_fetch("SELECT COUNT(*) as cnt FROM {$write_table} WHERE wr_is_comment = 0");
$total_count = $total_count['cnt'];

// 전체 페이지 수
$total_pages = ceil($total_count / $per_page);

// 게시글 목록 가져오기
$sql = "SELECT * FROM {$write_table} 
        WHERE wr_is_comment = 0 
        ORDER BY wr_num, wr_reply 
        LIMIT {$offset}, {$per_page}";
$result = sql_query($sql);
?>

<style>
/* 원본 HTML 스타일 그대로 */
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

.notice {
  color: #2559a8; 
  font-weight: 600;
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

table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
  padding: 0 20px;
  box-sizing: border-box;
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

th:nth-child(1), td:nth-child(1) {
  width: 6%;
  text-align: center;
}

th:nth-child(2), td:nth-child(2) {
  width: 55%;
  text-align: left;
}

th:nth-child(3), td:nth-child(3) {
  width: 15%;
  text-align: center;
}

th:nth-child(4), td:nth-child(4) {
  width: 10%;
  text-align: center;
}

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

  article {
    padding: 0;
  }

  table {
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

  #noticeTable tr:first-child th:nth-child(1) {
    width: 12%;
    text-align: center;
  }

  #noticeTable tr:first-child th:nth-child(2) {
    width: 48%;
    text-align: left;
    padding-left: 10px;
  }

  #noticeTable tr:first-child th:nth-child(3) {
    width: 18%;
    text-align: center;
  }

  #noticeTable tr:first-child th:nth-child(4) {
    width: 22%;
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

  #noticeTable tbody tr td:nth-child(1) {
    width: 12%;
    text-align: center;
  }

  #noticeTable tbody tr td:nth-child(2) {
    width: 48%;
    text-align: left;
    padding-left: 10px;
  }

  #noticeTable tbody tr td:nth-child(2) a {
    color: #000;
    font-size: 14px;
  }

  #noticeTable tbody tr td:nth-child(3) {
    width: 18%;
    text-align: center;
    font-size: 13px;
  }

  #noticeTable tbody tr td:nth-child(4) {
    width: 22%;
    text-align: center;
    font-size: 13px;
  }
  
  /* 모바일 글쓰기 버튼 */
  .admin-write-btn {
    padding: 8px 16px;
    font-size: 14px;
    margin-top: 12px;
  }
}
</style>

<section>
  <div class="title">
    <h1>공지사항</h1>
    <p>전북우리사이의 다양한 소식을 알려드립니다.</p>
    
    <?php if ($is_admin_user) { ?>
    <a href="/post/write.php?bo_table=<?php echo $bo_table; ?>" class="admin-write-btn">
      <i class="fa-solid fa-pen"></i> 글쓰기
    </a>
    <?php } ?>
  </div>
</section>

<article>
  <table id="noticeTable">
    <tr>
      <th>번호</th>
      <th>제목</th>
      <th>작성자</th>
      <th>작성일</th>
    </tr>
    <tbody>
      <?php 
      if (sql_num_rows($result) > 0) {
        $num = $total_count - $offset;
        while ($row = sql_fetch_array($result)) { 
      ?>
      <tr>
        <td><?php echo $num--; ?></td>
        <td class="major">
          <a href="/notice/note.php?bo_table=<?php echo $bo_table; ?>&wr_id=<?php echo $row['wr_id']; ?>">
            <?php echo htmlspecialchars($row['wr_subject']); ?>
          </a>
        </td>
        <td class="major"><?php echo htmlspecialchars($row['wr_name']); ?></td>
        <td class="major"><?php echo date("y.m.d", strtotime($row['wr_datetime'])); ?></td>
      </tr>
      <?php 
        }
      } else {
      ?>
      <tr>
        <td colspan="4" style="text-align: center; padding: 50px;">등록된 공지사항이 없습니다.</td>
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
</article>

<?php
// 푸터 포함
include_once($_SERVER['DOCUMENT_ROOT']."/includes/footer.php");
?>