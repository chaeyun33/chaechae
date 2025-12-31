<?php
if (!defined('_GNUBOARD_')) exit;
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
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
  margin-bottom: 100px; 
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

.btn_write {
  text-align: right;
  margin: 20px 0;
}

.btn_write a {
  display: inline-block;
  padding: 10px 20px;
  background: #2559a8;
  color: #fff;
  text-decoration: none;
  border-radius: 4px;
}

/* 모바일 반응형 */
@media screen and (max-width: 800px) {
  table {
    padding: 0;
  }

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
}
</style>

<section>
  <div class="title">
    <h1>공지사항</h1>
    <p>전북우리사이의 다양한 소식을 알려드립니다.</p>
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
      for ($i=0; $i<count($list); $i++) {
        $is_notice = $list[$i]['is_notice'];
      ?>
      <tr>
        <td class="<?php echo $is_notice ? 'notice' : 'notice1'; ?>">
          <?php echo $is_notice ? '공지' : $list[$i]['num']; ?>
        </td>
        <td class="major">
          <a href="<?php echo $list[$i]['href']; ?>">
            <?php echo $list[$i]['subject']; ?>
            <?php if ($list[$i]['comment_cnt']) { ?>
              <span style="color:#2559a8;">[<?php echo $list[$i]['comment_cnt']; ?>]</span>
            <?php } ?>
          </a>
        </td>
        <td class="major"><?php echo $list[$i]['name']; ?></td>
        <td class="major"><?php echo date('y.m.d', strtotime($list[$i]['wr_datetime'])); ?></td>
      </tr>
      <?php } ?>
      
      <?php if (count($list) == 0) { ?>
      <tr>
        <td colspan="4" style="text-align:center; padding:50px 0;">게시물이 없습니다.</td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

  <?php if ($is_admin || $is_auth) { ?>
  <div class="btn_write">
    <a href="<?php echo $write_href; ?>">글쓰기</a>
  </div>
  <?php } ?>

  <!-- 페이징 -->
  <?php echo $write_pages; ?>
</article>