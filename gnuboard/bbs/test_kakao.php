<?php
include_once("../common.php");

echo "<h1>카카오 로그인 테스트</h1>";
echo "<p>그누보드 연결: " . (defined('G5_VERSION') ? 'OK' : 'FAIL') . "</p>";
echo "<p>G5_DATA_PATH: " . G5_DATA_PATH . "</p>";
echo "<p>현재 경로: " . __FILE__ . "</p>";

// 로그 디렉토리 생성 테스트
$log_dir = G5_DATA_PATH . '/log';
if (!is_dir($log_dir)) {
    $result = @mkdir($log_dir, 0755, true);
    echo "<p>로그 디렉토리 생성: " . ($result ? 'SUCCESS' : 'FAIL') . "</p>";
} else {
    echo "<p>로그 디렉토리 존재: OK</p>";
}

// 파일 쓰기 테스트
$test_file = $log_dir . '/test.txt';
$write_result = @file_put_contents($test_file, 'test');
echo "<p>파일 쓰기 테스트: " . ($write_result !== false ? 'SUCCESS' : 'FAIL') . "</p>";

echo "<p>GET 파라미터: <pre>" . print_r($_GET, true) . "</pre></p>";
echo "<p>SESSION: <pre>" . print_r($_SESSION, true) . "</pre></p>";
?>