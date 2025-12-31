<?php
// 그누보드 연동
$possible_paths = [
    "../gnuboard/common.php",
    "../../gnuboard/common.php",
    "../common.php",
    "../../common.php"
];

$loaded = false;
foreach ($possible_paths as $path) {
    if (file_exists($path)) {
        include_once($path);
        $loaded = true;
        break;
    }
}

if (!$loaded) {
    die('Error: common.php 파일을 찾을 수 없습니다.');
}

// 관리자 권한 체크
if (!isset($is_admin) || empty($is_admin)) {
    die('관리자만 접근 가능합니다. <a href="/gnuboard/adm">관리자 페이지로 이동</a>');
}

// 확인 코드
if (isset($_POST['confirm']) && $_POST['confirm'] == 'DELETE_ALL') {
    // 먼저 개수 세기
    $count_result = sql_fetch("SELECT COUNT(*) as cnt FROM volunteer_records");
    $total_count = $count_result['cnt'];
    
    // 삭제 실행
    $sql = "DELETE FROM volunteer_records";
    $result = sql_query($sql, false);
    
    if ($result) {
        echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>삭제 완료</title>
    <style>
        body {
            font-family: 'NexonLv2Gothic', sans-serif;
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            text-align: center;
            background: #f5f5f5;
        }
        .success {
            background: white;
            border: 3px solid #28a745;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #28a745; 
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .count {
            font-size: 2.5rem;
            color: #2559a8;
            font-weight: bold;
            margin: 20px 0;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 15px 30px;
            background: #2559a8;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.1rem;
        }
        a:hover { 
            background: #1a4278;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 89, 168, 0.3);
        }
    </style>
</head>
<body>
    <div class='success'>
        <h1>✅ 삭제 완료</h1>
        <div class='count'>{$total_count}건</div>
        <p>모든 봉사활동 기록이 삭제되었습니다.</p>
        <a href='./volunteer_manage.php'>봉사시간 관리로 돌아가기</a>
    </div>
</body>
</html>";
    } else {
        echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>삭제 실패</title>
    <style>
        body {
            font-family: 'NexonLv2Gothic', sans-serif;
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            text-align: center;
        }
        .error {
            background: #f8d7da;
            border: 3px solid #dc3545;
            padding: 30px;
            border-radius: 15px;
        }
        h1 { color: #dc3545; }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class='error'>
        <h1>❌ 삭제 실패</h1>
        <p>오류가 발생했습니다.</p>
        <a href='javascript:history.back()'>돌아가기</a>
    </div>
</body>
</html>";
    }
    exit;
}

// 현재 등록된 기록 개수 확인
$count_result = sql_fetch("SELECT COUNT(*) as cnt FROM volunteer_records");
$current_count = $count_result ? $count_result['cnt'] : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>전체 삭제 - 전북우리사이</title>
    <style>
        body {
            font-family: 'NexonLv2Gothic', sans-serif;
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            text-align: center;
            background: #f5f5f5;
        }
        .warning {
            background: white;
            border: 3px solid #dc3545;
            padding: 40px;
            border-radius: 15px;
            margin: 20px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .warning h2 {
            color: #dc3545;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        .warning p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin: 15px 0;
        }
        .count-info {
            background: #fff3cd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 2px solid #ffc107;
        }
        .count-number {
            font-size: 2.5rem;
            color: #dc3545;
            font-weight: bold;
            margin: 10px 0;
        }
        input[type="text"] {
            padding: 15px;
            font-size: 18px;
            width: 250px;
            text-align: center;
            border: 3px solid #dc3545;
            border-radius: 8px;
            margin: 20px 0;
            font-family: monospace;
            font-weight: bold;
        }
        button {
            padding: 15px 30px;
            font-size: 16px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin: 10px;
            font-family: 'NexonLv2Gothic', sans-serif;
            font-weight: bold;
        }
        button:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }
        .cancel {
            background: #6c757d;
        }
        .cancel:hover {
            background: #5a6268;
        }
        .admin-info {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="warning">
        <h2>⚠️ 위험: 모든 봉사활동 기록 삭제</h2>
        
        <div class="admin-info">
            관리자: <strong><?php echo $member['mb_id']; ?></strong> (<?php echo $member['mb_name']; ?>)
        </div>
        
        <div class="count-info">
            <p>현재 등록된 봉사활동 기록</p>
            <div class="count-number"><?php echo number_format($current_count); ?>건</div>
        </div>
        
        <p><strong style="color: #dc3545; font-size: 1.3rem;">이 작업은 되돌릴 수 없습니다!</strong></p>
        <p>모든 회원의 봉사활동 기록이 <strong>영구적으로 삭제</strong>됩니다.</p>
        <p style="color: #666; font-size: 0.95rem;">데이터베이스에서 완전히 제거되며 복구할 수 없습니다.</p>
        
        <form method="POST" onsubmit="return confirm('⚠️ 최종 확인\n\n<?php echo $current_count; ?>건의 봉사활동 기록을 삭제하시겠습니까?\n\n이 작업은 되돌릴 수 없습니다!');">
            <p style="margin-top: 30px;">삭제하려면 아래에 정확히 입력하세요:</p>
            <p style="font-size: 1.2rem; font-weight: bold; color: #dc3545;">DELETE_ALL</p>
            <input type="text" name="confirm" placeholder="여기에 입력" required autocomplete="off">
            <br>
            <button type="submit">🗑️ <?php echo number_format($current_count); ?>건 전체 삭제</button>
            <button type="button" class="cancel" onclick="location.href='./volunteer_manage.php'">❌ 취소하고 돌아가기</button>
        </form>
    </div>
</body>
</html>