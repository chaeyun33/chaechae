<?php
// 그누보드 연동
include_once("../gnuboard/common.php");

// JSON 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 관리자 권한 체크
if (!$is_admin) {
    echo json_encode(['success' => false, 'message' => '권한이 없습니다'], JSON_UNESCAPED_UNICODE);
    exit;
}

$mb_id = isset($_GET['mb_id']) ? sql_real_escape_string(trim($_GET['mb_id'])) : '';

if (empty($mb_id)) {
    echo json_encode(['success' => false, 'message' => '회원 ID가 필요합니다'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // 회원의 모든 봉사활동 기록 조회
    $sql = "SELECT 
                id,
                activity_name,
                DATE_FORMAT(activity_date, '%Y-%m-%d') as activity_date,
                volunteer_hours,
                DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') as created_at
            FROM volunteer_records 
            WHERE mb_id = '{$mb_id}'
            ORDER BY activity_date DESC, created_at DESC";

    $result = sql_query($sql);
    
    if (!$result) {
        throw new Exception('데이터베이스 쿼리 실패');
    }
    
    $records = array();

    while ($row = sql_fetch_array($result)) {
        $records[] = array(
            'id' => $row['id'],
            'activity_name' => $row['activity_name'],
            'activity_date' => $row['activity_date'],
            'volunteer_hours' => number_format($row['volunteer_hours'], 1),
            'created_at' => $row['created_at']
        );
    }

    echo json_encode([
        'success' => true,
        'records' => $records
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>