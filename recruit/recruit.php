<?php
// 커스텀 설정
if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}

// 그누보드 연동
include_once($_SERVER['DOCUMENT_ROOT']."/gnuboard/common.php");

// 페이지 설정
$page_title = '모집공고 - 전북우리사이';
$body_class = 'recruit-page';

// 헤더 포함
include_once($_SERVER['DOCUMENT_ROOT']."/includes/header.php");

// 페이징 설정
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 12; // 3x4 그리드
$offset = ($page - 1) * $per_page;

// 게시판 테이블명
$bo_table = 'recruit';
$write_table = $g5['write_prefix'].$bo_table;

// 관리자 권한 체크
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

// 게시글 목록 가져오기 (최신순)
$sql = "SELECT * FROM {$write_table} 
        WHERE wr_is_comment = 0 
        ORDER BY wr_id DESC
        LIMIT {$offset}, {$per_page}";
$result = sql_query($sql);
?>

<style>
/* 공통 스타일 */
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

.title h1 {
  font-family: 'NexonLv2Gothic', sans-serif;
  font-size: 2.5rem;
}

.title p {
  font-family: 'NexonLv2Gothic gothic';
  color: #b3b3b3;
  margin-top: 10px;
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

article {
  width: 100%;
  max-width: 1220px;
  margin: 0 auto;
  padding: 0 20px;
  box-sizing: border-box;
}

/* 이미지 갤러리 - 3열 */
.photo {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-top: 50px;
  margin-bottom: 70px;
}

.imgbox {
  min-width: 200px;
  max-width: 300px;
  display: flex;
  flex-direction: column;
  margin: 0 auto;
}

.imgbox a {
  text-decoration: none;
}

.imgbox img {
  width: 100%;
  height: auto;
  border-radius: 10px;
  display: block;
}

.imgtext1 {
  width: 100%;
  font-size: 0.9rem;
  color: #585858;
  line-height: 18px;
  margin-top: 10px;
  padding-right: 5px;
}

.imgbox > a > h3 {
  font-family: 'Pretendard-Bold';
  font-size: 1.3rem;
  color: #000000;
  line-height: 1.2em;
  margin-top: 5px;
  max-width: 300px;
  display: block;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  line-clamp: 2;
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

/* 반응형 */
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
    padding: 0 15px;
  }

  /* 모바일에서 1열로 변경 */
  .photo {
    grid-template-columns: 1fr;
    gap: 30px;
    justify-items: center;
  }

  .imgbox {
    max-width: 100%;
    min-width: auto;
  }

  .imgbox img {
    height: auto;
  }

  .imgbox > a > h3 {
    font-size: 1.2rem;
  }
  
  .admin-write-btn {
    padding: 8px 16px;
    font-size: 14px;
    margin-top: 12px;
  }
}
</style>

<section>
  <div class="title">
    <h1>모집 공고</h1>
    <p>전북우리사이의 다양한 소식을 알려드립니다.</p>
    
    <?php if ($is_admin_user) { ?>
    <a href="/post/write.php?bo_table=<?php echo $bo_table; ?>" class="admin-write-btn">
      <i class="fa-solid fa-pen"></i> 글쓰기
    </a>
    <?php } ?>
  </div>
</section>

<article>
  <?php if (sql_num_rows($result) > 0) { ?>
  <div class="photo">
    <?php 
    while ($row = sql_fetch_array($result)) {
      // 첫 번째 이미지 파일 가져오기
      $img_sql = "SELECT bf_file FROM {$g5['board_file_table']} 
                  WHERE bo_table = '{$bo_table}' 
                  AND wr_id = '{$row['wr_id']}' 
                  AND bf_type IN (1, 2, 3, 18)
                  ORDER BY bf_no 
                  LIMIT 1";
      $img_row = sql_fetch($img_sql);
      
      // 이미지 경로 생성 (원본 사용)
      $img_path = '';
      if ($img_row && $img_row['bf_file']) {
        $img_path = '/gnuboard/data/file/'.$bo_table.'/'.$img_row['bf_file'];
      } else {
        // 기본 이미지
        $img_path = '/img/no-image.jpg';
      }
      
      // 작성일 포맷
      $date_str = date("Y-m-d", strtotime($row['wr_datetime']));
    ?>
    <div class="imgbox">
      <a href="./view.php?bo_table=<?php echo $bo_table; ?>&wr_id=<?php echo $row['wr_id']; ?>">
        <img src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($row['wr_subject']); ?>">
        <h3><?php echo htmlspecialchars($row['wr_subject']); ?></h3>
      </a>
      <div class="imgtext1"><?php echo $date_str; ?></div>
    </div>
    <?php } ?>
  </div>
  
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
    등록된 모집공고가 없습니다.
  </div>
  <?php } ?>
</article>

<?php
// 푸터 포함
include_once($_SERVER['DOCUMENT_ROOT']."/includes/footer.php");
?>