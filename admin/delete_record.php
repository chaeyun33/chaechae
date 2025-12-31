<?php
// 그누보드 연동
include_once("../gnuboard/common.php");

// 관리자 권한 체크
if (!$is_admin) {
    echo "<script>
        alert('관리자만 접근 가능합니다.');
        location.href = './volunteer_manage.php';
    </script>";
    exit;
}

// GET 방식으로 record_id 받기
$record_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 디버깅용 - 실제 운영시 삭제
error_log("Delete Record ID: " . $record_id);

if ($record_id <= 0) {
    echo "<script>
        alert('잘못된 요청입니다. (ID: " . $record_id . ")');
        location.href = './volunteer_manage.php';
    </script>";
    exit;
}

// 삭제 전 데이터 확인
$check_sql = "SELECT * FROM volunteer_records WHERE id = {$record_id}";
$check_result = sql_fetch($check_sql);

if (!$check_result) {
    echo "<script>
        alert('해당 기록을 찾을 수 없습니다. (ID: " . $record_id . ")');
        location.href = './volunteer_manage.php';
    </script>";
    exit;
}

// 삭제 실행
$delete_sql = "DELETE FROM volunteer_records WHERE id = {$record_id}";
$delete_result = sql_query($delete_sql, false);

if ($delete_result) {
    echo "<script>
        alert('삭제되었습니다.');
        location.href = './volunteer_manage.php';
    </script>";
} else {
    echo "<script>
        alert('삭제에 실패했습니다.');
        location.href = './volunteer_manage.php';
    </script>";
}
?>