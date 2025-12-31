<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); // 사용자에게는 오류를 보여주지 않음

// 삭제 처리
define('_CUSTOM_', true); // 그누보드 common.php보다 먼저 정의

include_once($_SERVER['DOCUMENT_ROOT']."/gnuboard/common.php");

// 로그인 체크
if (!$is_member) {
    die("<script>alert('로그인이 필요합니다.'); location.href='/';</script>");
}

// 관리자 권한 체크
$is_admin_user = ($is_admin == 'super' || $member['mb_id'] == 'admin');

if (!$is_admin_user) {
    die("<script>alert('관리자만 삭제할 수 있습니다.'); history.back();</script>");
}

// 파라미터
$bo_table = isset($_REQUEST['bo_table']) ? $_REQUEST['bo_table'] : '';
$wr_id = isset($_REQUEST['wr_id']) ? (int)$_REQUEST['wr_id'] : 0;

// 게시판별 목록 URL
$list_urls = array(
    'notice' => '/notice/notice.php',
    'gallery' => '/gallery/gallery.php',
    'press' => '/press/press.php',
    'recruit' => '/recruit/recruit.php'
);

$list_url = isset($list_urls[$bo_table]) ? $list_urls[$bo_table] : '/';

if (!$wr_id || !$bo_table) {
    die("<script>alert('잘못된 접근입니다.'); location.href='{$list_url}';</script>");
}

$write_table = $g5['write_prefix'].$bo_table;

// 게시글 존재 확인
$write = sql_fetch("SELECT * FROM {$write_table} WHERE wr_id = '{$wr_id}'");
if (!$write) {
    die("<script>alert('존재하지 않는 게시글입니다.'); location.href='{$list_url}';</script>");
}

// 첨부파일 삭제
$file_result = sql_query("SELECT * FROM {$g5['board_file_table']} WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}'");
if ($file_result) {
    while ($file = sql_fetch_array($file_result)) {
        $file_path = G5_DATA_PATH.'/file/'.$bo_table.'/'.$file['bf_file'];
        if (file_exists($file_path)) {
            @unlink($file_path);
        }
        
        $thumb_path = G5_DATA_PATH.'/file/'.$bo_table.'/thumb-'.$file['bf_file'];
        if (file_exists($thumb_path)) {
            @unlink($thumb_path);
        }
    }
}

// 파일 테이블에서 삭제
sql_query("DELETE FROM {$g5['board_file_table']} WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}'");

// 게시글 삭제
$result = sql_query("DELETE FROM {$write_table} WHERE wr_id = '{$wr_id}'");

if (!$result) {
    die("<script>alert('삭제에 실패했습니다.'); location.href='{$list_url}';</script>");
}

// 게시판 카운트 감소
sql_query("UPDATE {$g5['board_table']} SET bo_count_write = bo_count_write - 1 WHERE bo_table = '{$bo_table}'");

// 성공 메시지와 리다이렉트
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>삭제 처리</title>
</head>
<body>
<script>
alert('삭제되었습니다.');
location.href = '<?php echo $list_url; ?>';
</script>
</body>
</html>