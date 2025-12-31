<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}

include_once($_SERVER['DOCUMENT_ROOT']."/gnuboard/common.php");

// 로그인 체크
if (!$is_member) {
    die("<script>alert('로그인이 필요합니다.'); location.href='/notice/notice.php';</script>");
}

// 관리자 권한 체크
$is_admin_user = ($is_admin == 'super' || $member['mb_id'] == 'admin');

if (!$is_admin_user) {
    die("<script>alert('관리자만 글을 작성할 수 있습니다.'); location.href='/notice/notice.php';</script>");
}

// POST 데이터
$bo_table = isset($_POST['bo_table']) ? $_POST['bo_table'] : 'notice';
$wr_id = isset($_POST['wr_id']) ? (int)$_POST['wr_id'] : 0;
$w = isset($_POST['w']) ? $_POST['w'] : '';
$wr_subject = isset($_POST['wr_subject']) ? trim($_POST['wr_subject']) : '';
$wr_content = isset($_POST['wr_content']) ? $_POST['wr_content'] : '';
$wr_link1 = isset($_POST['wr_link1']) ? trim($_POST['wr_link1']) : '';

// 허용된 게시판 체크
$allowed_boards = array('notice', 'gallery', 'press', 'recruit');
if (!in_array($bo_table, $allowed_boards)) {
    die("<script>alert('잘못된 접근입니다.'); location.href='/notice/notice.php';</script>");
}

// 게시판별 목록 페이지 URL - 절대 경로로 수정
$list_urls = array(
    'notice' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/notice/notice.php',
    'gallery' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/gallery/gallery.php',
    'press' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/press/press.php',
    'recruit' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/recruit/recruit.php'
);
$list_url = isset($list_urls[$bo_table]) ? $list_urls[$bo_table] : $list_urls['notice'];

// 제목 필수 체크
if (empty($wr_subject)) {
    die("<script>alert('제목을 입력해주세요.'); history.back();</script>");
}

// 게시판별 필수 항목 체크
if ($bo_table == 'press') {
    if (empty($wr_link1)) {
        die("<script>alert('외부 링크 URL을 입력해주세요.'); history.back();</script>");
    }
} else {
    if (empty($wr_content)) {
        die("<script>alert('내용을 입력해주세요.'); history.back();</script>");
    }
}

$write_table = $g5['write_prefix'].$bo_table;

// XSS 방지
$allowed_tags = '<p><br><strong><b><em><i><u><h1><h2><h3><h4><h5><h6><a><img><ul><ol><li><blockquote><pre><code><table><tr><td><th><thead><tbody>';
$wr_content = strip_tags($wr_content, $allowed_tags);

// 수정 모드
if ($w == 'u' && $wr_id) {
    $write = sql_fetch("SELECT * FROM {$write_table} WHERE wr_id = '{$wr_id}'");
    
    if (!$write) {
        die("<script>alert('존재하지 않는 게시글입니다.'); location.href='{$list_url}';</script>");
    }
    
    $wr_subject_escaped = sql_real_escape_string($wr_subject);
    $wr_content_escaped = sql_real_escape_string($wr_content);
    $wr_link1_escaped = sql_real_escape_string($wr_link1);
    
    $sql = "UPDATE {$write_table} SET ";
    $sql .= "wr_subject = '{$wr_subject_escaped}', ";
    $sql .= "wr_content = '{$wr_content_escaped}', ";
    $sql .= "wr_link1 = '{$wr_link1_escaped}', ";
    $sql .= "wr_last = '".G5_TIME_YMDHIS."' ";
    $sql .= "WHERE wr_id = '{$wr_id}'";
    
    $result = sql_query($sql);
    
    if (!$result) {
        die("<script>alert('게시글 수정 중 오류가 발생했습니다.'); history.back();</script>");
    }
    
    // 파일 삭제 처리
    if (isset($_POST['bf_file_del']) && is_array($_POST['bf_file_del'])) {
        foreach ($_POST['bf_file_del'] as $bf_no) {
            $bf_no = (int)$bf_no;
            
            $file = sql_fetch("SELECT * FROM {$g5['board_file_table']} WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}' AND bf_no = '{$bf_no}'");
            
            if ($file) {
                $file_path = G5_DATA_PATH.'/file/'.$bo_table.'/'.$file['bf_file'];
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }
                
                $thumb_path = G5_DATA_PATH.'/file/'.$bo_table.'/thumb-'.$file['bf_file'];
                if (file_exists($thumb_path)) {
                    @unlink($thumb_path);
                }
                
                sql_query("DELETE FROM {$g5['board_file_table']} WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}' AND bf_no = '{$bf_no}'");
            }
        }
    }
}
// 새 글 작성
else {
    $tmp_row = sql_fetch("SELECT MIN(wr_num) as min_wr_num FROM {$write_table}");
    $wr_num = $tmp_row['min_wr_num'] ? $tmp_row['min_wr_num'] - 1 : -1;
    
    $wr_subject_escaped = sql_real_escape_string($wr_subject);
    $wr_content_escaped = sql_real_escape_string($wr_content);
    $wr_link1_escaped = sql_real_escape_string($wr_link1);
    $mb_id_escaped = sql_real_escape_string($member['mb_id']);
    $mb_name_escaped = sql_real_escape_string($member['mb_name']);
    $mb_email_escaped = sql_real_escape_string($member['mb_email']);
    $mb_homepage_escaped = sql_real_escape_string($member['mb_homepage']);
    
    $sql = "INSERT INTO {$write_table} SET ";
    $sql .= "wr_num = '{$wr_num}', ";
    $sql .= "wr_reply = '', ";
    $sql .= "wr_parent = 0, ";
    $sql .= "wr_is_comment = 0, ";
    $sql .= "wr_comment = 0, ";
    $sql .= "wr_comment_reply = '', ";
    $sql .= "ca_name = '', ";
    $sql .= "wr_option = 'html1', ";
    $sql .= "wr_subject = '{$wr_subject_escaped}', ";
    $sql .= "wr_content = '{$wr_content_escaped}', ";
    $sql .= "wr_seo_title = '', ";
    $sql .= "wr_link1 = '{$wr_link1_escaped}', ";
    $sql .= "wr_link2 = '', ";
    $sql .= "wr_link1_hit = 0, ";
    $sql .= "wr_link2_hit = 0, ";
    $sql .= "wr_hit = 0, ";
    $sql .= "wr_good = 0, ";
    $sql .= "wr_nogood = 0, ";
    $sql .= "mb_id = '{$mb_id_escaped}', ";
    $sql .= "wr_password = '', ";
    $sql .= "wr_name = '{$mb_name_escaped}', ";
    $sql .= "wr_email = '{$mb_email_escaped}', ";
    $sql .= "wr_homepage = '{$mb_homepage_escaped}', ";
    $sql .= "wr_datetime = '".G5_TIME_YMDHIS."', ";
    $sql .= "wr_file = 0, ";
    $sql .= "wr_last = '".G5_TIME_YMDHIS."', ";
    $sql .= "wr_ip = '{$_SERVER['REMOTE_ADDR']}', ";
    $sql .= "wr_facebook_user = '', ";
    $sql .= "wr_twitter_user = '', ";
    $sql .= "wr_1 = '', ";
    $sql .= "wr_2 = '', ";
    $sql .= "wr_3 = '', ";
    $sql .= "wr_4 = '', ";
    $sql .= "wr_5 = '', ";
    $sql .= "wr_6 = '', ";
    $sql .= "wr_7 = '', ";
    $sql .= "wr_8 = '', ";
    $sql .= "wr_9 = '', ";
    $sql .= "wr_10 = ''";
    
    $result = sql_query($sql);
    
    if (!$result) {
        die("<script>alert('게시글 등록 중 오류가 발생했습니다.'); history.back();</script>");
    }
    
    $wr_id = sql_insert_id();
    
    // wr_id 가져오기 재시도
    if (!$wr_id || $wr_id == 0) {
        $new_write = sql_fetch("SELECT wr_id FROM {$write_table} WHERE mb_id = '{$mb_id_escaped}' AND wr_datetime = '".G5_TIME_YMDHIS."' ORDER BY wr_id DESC LIMIT 1");
        if ($new_write) {
            $wr_id = $new_write['wr_id'];
        }
    }
    
    if (!$wr_id || $wr_id == 0) {
        die("<script>alert('글은 등록되었으나 글 번호를 가져올 수 없습니다.'); location.href='{$list_url}';</script>");
    }
    
    // 게시판 글 수 증가
    sql_query("UPDATE {$g5['board_table']} SET bo_count_write = bo_count_write + 1 WHERE bo_table = '{$bo_table}'");
}

// 파일 업로드 처리 ($wr_id가 확정된 후 실행)
$uploaded_files = 0;

if (isset($_FILES['bf_file']) && is_array($_FILES['bf_file']['name'])) {
    $upload_dir = G5_DATA_PATH.'/file/'.$bo_table;
    
    if (!is_dir($upload_dir)) {
        @mkdir($upload_dir, 0707, true);
        @chmod($upload_dir, 0707);
    }
    
    // 기존 파일 개수 확인
    $file_count = sql_fetch("SELECT COUNT(*) as cnt FROM {$g5['board_file_table']} WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}'");
    $bf_no = $file_count['cnt'];
    
    foreach ($_FILES['bf_file']['name'] as $key => $filename) {
        if (empty($filename)) continue;
        
        $tmp_name = $_FILES['bf_file']['tmp_name'][$key];
        $filesize = $_FILES['bf_file']['size'][$key];
        $error = $_FILES['bf_file']['error'][$key];
        
        if ($error != UPLOAD_ERR_OK) continue;
        if ($filesize > 10 * 1024 * 1024) continue; // 10MB 제한
        
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed_exts = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        
        if (!in_array($ext, $allowed_exts)) continue;
        
        $bf_file = date('YmdHis').'_'.mt_rand(1000, 9999).'.'.$ext;
        $bf_source = $filename;
        $bf_filepath = $upload_dir.'/'.$bf_file;
        
        if (move_uploaded_file($tmp_name, $bf_filepath)) {
            @chmod($bf_filepath, 0606);
            
            $is_image = 0;
            $bf_width = 0;
            $bf_height = 0;
            
            $size = @getimagesize($bf_filepath);
            if ($size) {
                $is_image = 1;
                $bf_width = $size[0];
                $bf_height = $size[1];
                
                // 갤러리와 모집공고는 썸네일 생성
                if ($bo_table == 'gallery' || $bo_table == 'recruit') {
                    create_thumbnail($bf_filepath, $upload_dir.'/thumb-'.$bf_file, 400, 300);
                }
            }
            
            $bf_source_escaped = sql_real_escape_string($bf_source);
            
            $sql = "INSERT INTO {$g5['board_file_table']} SET ";
            $sql .= "bo_table = '{$bo_table}', ";
            $sql .= "wr_id = '{$wr_id}', ";
            $sql .= "bf_no = '{$bf_no}', ";
            $sql .= "bf_source = '{$bf_source_escaped}', ";
            $sql .= "bf_file = '{$bf_file}', ";
            $sql .= "bf_download = 0, ";
            $sql .= "bf_content = '', ";
            $sql .= "bf_filesize = '{$filesize}', ";
            $sql .= "bf_width = '{$bf_width}', ";
            $sql .= "bf_height = '{$bf_height}', ";
            $sql .= "bf_type = '{$is_image}', ";
            $sql .= "bf_datetime = '".G5_TIME_YMDHIS."'";
            
            if (sql_query($sql)) {
                $uploaded_files++;
                $bf_no++;
            }
        }
    }
}

// 파일 개수 업데이트
if ($uploaded_files > 0 || ($w == 'u' && isset($_POST['bf_file_del']))) {
    $file_count = sql_fetch("SELECT COUNT(*) as cnt FROM {$g5['board_file_table']} WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}'");
    sql_query("UPDATE {$write_table} SET wr_file = '{$file_count['cnt']}' WHERE wr_id = '{$wr_id}'");
}

// 썸네일 생성 함수
function create_thumbnail($source, $dest, $max_width, $max_height) {
    $size = @getimagesize($source);
    if (!$size) return false;
    
    list($width, $height, $type) = $size;
    
    if ($width <= $max_width && $height <= $max_height) {
        @copy($source, $dest);
        return true;
    }
    
    $ratio = min($max_width / $width, $max_height / $height);
    $new_width = (int)($width * $ratio);
    $new_height = (int)($height * $ratio);
    
    switch ($type) {
        case IMAGETYPE_JPEG:
            $src_img = @imagecreatefromjpeg($source);
            break;
        case IMAGETYPE_PNG:
            $src_img = @imagecreatefrompng($source);
            break;
        case IMAGETYPE_GIF:
            $src_img = @imagecreatefromgif($source);
            break;
        default:
            return false;
    }
    
    if (!$src_img) return false;
    
    $dst_img = imagecreatetruecolor($new_width, $new_height);
    
    if ($type == IMAGETYPE_PNG) {
        imagealphablending($dst_img, false);
        imagesavealpha($dst_img, true);
    }
    
    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($dst_img, $dest, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($dst_img, $dest, 9);
            break;
        case IMAGETYPE_GIF:
            imagegif($dst_img, $dest);
            break;
    }
    
    imagedestroy($src_img);
    imagedestroy($dst_img);
    
    @chmod($dest, 0606);
    return true;
}

// 리다이렉트 URL - 목록 페이지로 이동
$list_pages = array(
    'notice' => '/notice/notice.php',
    'gallery' => '/gallery/photo.php',
    'press' => '/news/press.php',
    'recruit' => '/recruit/recruit.php'
);

$redirect_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
$redirect_url .= isset($list_pages[$bo_table]) ? $list_pages[$bo_table] : '/notice/notice.php';

$msg = ($w == 'u') ? '게시글이 수정되었습니다.' : '게시글이 등록되었습니다.';

echo "<script>";
echo "alert('{$msg}');";
echo "location.href='{$redirect_url}';";
echo "</script>";
?>