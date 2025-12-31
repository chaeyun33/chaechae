<?php
include_once($_SERVER['DOCUMENT_ROOT']."/gnuboard/common.php");

// ✅ 입력값 검증 및 정리
$mb_id       = sql_escape_string(trim($_POST['mb_id']));
$mb_password = trim($_POST['mb_password']);
$mb_name     = sql_escape_string(trim($_POST['mb_name']));
$mb_nick     = sql_escape_string(trim($_POST['mb_nick']));
$mb_email    = sql_escape_string(trim($_POST['mb_email']));
$mb_hp       = sql_escape_string(trim($_POST['mb_hp']));

// ✅ 비밀번호 암호화
$mb_password_encrypt = get_encrypt_string($mb_password);

// ✅ 아이디 중복 체크
$row = sql_fetch("SELECT mb_id FROM {$g5['member_table']} WHERE mb_id = '{$mb_id}'");
if ($row['mb_id']) {
    alert("이미 사용중인 아이디입니다.");
    exit;
}

// ✅ 현재 시간
$now = G5_TIME_YMDHIS;

// ✅ 회원 저장
$sql = "
INSERT INTO {$g5['member_table']}
SET
    mb_id = '{$mb_id}',
    mb_password = '{$mb_password_encrypt}',
    mb_name = '{$mb_name}',
    mb_nick = '{$mb_nick}',
    mb_email = '{$mb_email}',
    mb_hp = '{$mb_hp}',
    mb_level = 2,
    mb_datetime = '{$now}',
    mb_ip = '{$_SERVER['REMOTE_ADDR']}',
    mb_today_login = '{$now}',
    mb_login_ip = '{$_SERVER['REMOTE_ADDR']}'
";
sql_query($sql);

// ✅ ✅ ✅ 핵심: 세션 설정
set_session('ss_mb_id', $mb_id);

// ✅ 세션 토큰 생성 (그누보드 보안 기능)
if(function_exists('update_auth_session_token')) {
    update_auth_session_token($now);
}

// ✅ ✅ ✅ 중요: 세션 저장 강제 실행
session_write_close();

// ✅ 세션 다시 시작 (저장 후)
@session_start();

// ✅ 회원가입 포인트 지급 (선택사항)
if($config['cf_register_point'] > 0) {
    insert_point($mb_id, $config['cf_register_point'], '회원가입 축하', '@member', $mb_id, '회원가입');
}

// ✅ ✅ ✅ JavaScript로 세션 재로드 후 이동
echo "<script>
alert('회원가입이 완료되었습니다.');
window.location.href = 'https://jeonbukwoorisai.co.kr/';
</script>";
exit;
?>