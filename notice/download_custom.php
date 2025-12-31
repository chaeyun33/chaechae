<?php
// download_custom.php - /notice/ 폴더에 저장하세요

// 커스텀 설정
if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}

include_once($_SERVER['DOCUMENT_ROOT']."/gnuboard/common.php");

$bo_table = isset($_REQUEST['bo_table']) ? clean_xss_tags($_REQUEST['bo_table']) : '';
$wr_id = isset($_REQUEST['wr_id']) ? (int)$_REQUEST['wr_id'] : 0;
$no = isset($_REQUEST['no']) ? (int)$_REQUEST['no'] : 0;

if (!$bo_table || !$wr_id) {
    alert('잘못된 접근입니다.');
}

// 파일 정보 가져오기
$sql = "SELECT * FROM {$g5['board_file_table']} 
        WHERE bo_table = '{$bo_table}' 
        AND wr_id = '{$wr_id}' 
        AND bf_no = '{$no}'";
$file = sql_fetch($sql);

if (!$file || !$file['bf_file']) {
    alert('파일이 존재하지 않습니다.');
}

// 파일 경로
$file_path = G5_DATA_PATH.'/file/'.$bo_table.'/'.$file['bf_file'];

if (!file_exists($file_path)) {
    alert('파일이 존재하지 않습니다.');
}

// 다운로드 횟수 증가
sql_query("UPDATE {$g5['board_file_table']} SET bf_download = bf_download + 1 WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}' AND bf_no = '{$no}'");

// 파일 다운로드
$filename = $file['bf_source'];
$filesize = $file['bf_filesize'];

// 브라우저 캐시 방지
header("Pragma: public");
header("Expires: 0");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$filename."\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".$filesize);

// 파일 출력
readfile($file_path);
exit;
?>