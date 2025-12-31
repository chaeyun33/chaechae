<?php
include_once("../common.php");

$kakao_id = 'kakao_4659578743';

echo "<h1>회원 정보 확인</h1>";
echo "<hr>";

$sql = "SELECT mb_id, mb_name, mb_nick, mb_email, mb_password, mb_datetime, mb_certify FROM {$g5['member_table']} WHERE mb_id = '$kakao_id'";
$result = sql_query($sql);

if ($row = sql_fetch_array($result)) {
    echo "<h2>✅ 회원 정보 존재</h2>";
    echo "<pre>";
    print_r($row);
    echo "</pre>";
    
    echo "<h2>비밀번호 체크</h2>";
    echo "mb_password 길이: " . strlen($row['mb_password']) . "<br>";
    echo "mb_password 값: " . htmlspecialchars($row['mb_password']) . "<br>";
    echo "mb_password가 비어있는가?: " . (empty($row['mb_password']) ? 'YES' : 'NO') . "<br>";
    
    echo "<h2>자동 로그인 키 계산</h2>";
    $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['SERVER_SOFTWARE'] . $_SERVER['HTTP_USER_AGENT'] . $row['mb_password']);
    echo "계산된 키: " . $key . "<br>";
    
    echo "<h2>쿠키 확인</h2>";
    echo "ck_mb_id 쿠키: " . get_cookie('ck_mb_id') . "<br>";
    echo "ck_auto 쿠키: " . get_cookie('ck_auto') . "<br>";
    
    if (get_cookie('ck_auto') === $key) {
        echo "<br>✅ 쿠키 키가 일치합니다!<br>";
    } else {
        echo "<br>❌ 쿠키 키가 일치하지 않습니다!<br>";
        echo "예상 키: " . $key . "<br>";
        echo "실제 쿠키: " . get_cookie('ck_auto') . "<br>";
    }
    
} else {
    echo "<h2>❌ 회원 정보 없음</h2>";
    echo "SQL: " . $sql;
}
?>
```

### 2단계: 확인

1. 브라우저에서 접속하세요:
```
   https://jeonbukwoorisai.co.kr/gnuboard/bbs/check_member.php