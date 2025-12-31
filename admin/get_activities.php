<?php
// 에러 표시 (디버깅용)
error_reporting(E_ALL);
ini_set('display_errors', 0); // JSON 응답이므로 에러는 숨김

// 그누보드 연동
include_once("../gnuboard/common.php");

// JSON 헤더 먼저 설정
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
    // 활동명별 통계 조회
    $sql = "SELECT 
                activity_name,
                COUNT(*) as count,
                SUM(volunteer_hours) as total_hours
            FROM volunteer_records 
            WHERE mb_id = '{$mb_id}'
            GROUP BY activity_name
            ORDER BY activity_name ASC";

    $result = sql_query($sql);
    
    if (!$result) {
        throw new Exception('데이터베이스 쿼리 실패');
    }
    
    $activities = array();

    while ($row = sql_fetch_array($result)) {
        $activities[] = array(
            'activity_name' => $row['activity_name'],
            'count' => $row['count'],
            'total_hours' => number_format($row['total_hours'], 1)
        );
    }

    echo json_encode([
        'success' => true,
        'activities' => $activities
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>