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
$page_title = '포토갤러리 - 전북우리사이';
$body_class = 'photo-page';

// 헤더 포함 - 절대경로 사용
$header_path = $_SERVER['DOCUMENT_ROOT']."/includes/header.php";
if (!file_exists($header_path)) {
    die("header.php 파일을 찾을 수 없습니다: " . $header_path);
}
include_once($header_path);

// 페이징 설정
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 9; // 3x3 그리드
$offset = ($page - 1) * $per_page;

// 게시판 테이블명
$bo_table = 'gallery';
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

article {
  width: 100%;
  max-width: 1220px;
  margin: 0 auto;
  padding: 0 20px;
  box-sizing: border-box;
  margin-bottom: 200px;
}

.photo {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 30px;
  margin-top: 50px;
  margin-bottom: 50px;
}

.imgbox {
  width: 100%;
  max-width: 350px;
  text-align: left;
}

.imgbox a {
  text-decoration: none;
  display: block;
}

.imgbox a > img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  display: block;
  border-radius: 10px;
}

.imgbox > a > h2 {
  font-size: 1.4rem;
  color: #000000;
  line-height: 1.4;
  margin-top: 10px;
  margin-bottom: 5px;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 1;
  line-clamp: 1;
  word-break: break-word;
  text-align: left;
}

.imgtext1 {
  width: 100%;
  font-size: 0.85rem; 
  color: #888;
  text-align: left;
}

/* 빈 갤러리 메시지 */
.empty-gallery {
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
    text-align: left;
  }

  .photo {
    grid-template-columns: 1fr;
    gap: 25px;
    justify-items: center; /* 모바일에서 그리드 아이템 가운데 정렬 */
  }

  .imgbox {
    max-width: 100%; /* 모바일에서 최대 너비 제한 해제 */
  }

  .imgbox a > img {
    height: 300px;
  }

  .imgbox > a > h2 {
    font-size: 1.3rem;
    text-align: left;
  }

  .imgtext1 {
    font-size: 1rem;
    text-align: left;
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
    <h1>포토갤러리</h1>
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
      
      // 이미지 경로 생성
      $thumb_path = '';
      if ($img_row && $img_row['bf_file']) {
        // 썸네일 파일명 생성
        $thumb_file = 'thumb-'.$img_row['bf_file'];
        $thumb_path = '/gnuboard/data/file/'.$bo_table.'/'.$thumb_file;
        
        // 썸네일이 없으면 원본 이미지 사용
        if (!file_exists($_SERVER['DOCUMENT_ROOT'].$thumb_path)) {
          $thumb_path = '/gnuboard/data/file/'.$bo_table.'/'.$img_row['bf_file'];
        }
      } else {
        // 기본 이미지
        $thumb_path = '/img/no-image.jpg';
      }
      
      // 작성일 포맷
      $date_str = date("Y.m.d", strtotime($row['wr_datetime']));
    ?>
    <div class="imgbox">
      <a href="./view.php?bo_table=<?php echo $bo_table; ?>&wr_id=<?php echo $row['wr_id']; ?>">
        <img src="<?php echo $thumb_path; ?>" alt="<?php echo htmlspecialchars($row['wr_subject']); ?>">
        <h2><?php echo htmlspecialchars($row['wr_subject']); ?></h2>
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
  <div class="empty-gallery">
    등록된 갤러리가 없습니다.
  </div>
  <?php } ?>
</article>

<?php
// 푸터 포함
include_once($_SERVER['DOCUMENT_ROOT']."/includes/footer.php");
?>