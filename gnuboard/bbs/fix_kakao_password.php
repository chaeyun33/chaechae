<?php
include_once("../common.php");

$kakao_id = 'kakao_4659578743';
$dummy_password = 'KAKAO_DUMMY_' . md5($kakao_id);

echo "<h1>카카오 계정 비밀번호 수정</h1>";
echo "<hr>";

// 업데이트
$sql = "UPDATE {$g5['member_table']} 
        SET mb_password = '" . sql_real_escape_string($dummy_password) . "'
        WHERE mb_id = '" . sql_real_escape_string($kakao_id) . "'";

if (sql_query($sql)) {
    echo "✅ 비밀번호 업데이트 성공!<br><br>";
    
    // 확인
    $check_sql = "SELECT mb_id, mb_password FROM {$g5['member_table']} WHERE mb_id = '$kakao_id'";
    $row = sql_fetch($check_sql);
    
    echo "업데이트된 비밀번호: " . htmlspecialchars($row['mb_password']) . "<br>";
    echo "비밀번호 길이: " . strlen($row['mb_password']) . "<br>";
    
    echo "<br><h2>이제 다음을 하세요:</h2>";
    echo "1. 브라우저의 모든 쿠키를 삭제하세요<br>";
    echo "2. 브라우저를 닫았다가 다시 여세요<br>";
    echo "3. <a href='/page/login.php'>일반 로그인 페이지</a>에서 기존 계정으로 로그인 시도<br>";
    echo "4. 또는 <a href='/page/login.php'>카카오 로그인</a> 다시 시도<br>";
    
} else {
    echo "❌ 업데이트 실패: " . sql_error();
}
?>
```

### 2단계: 실행

1. 브라우저에서 접속:
```
   https://jeonbukwoorisai.co.kr/gnuboard/bbs/fix_kakao_password.php