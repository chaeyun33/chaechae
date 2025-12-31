<?php
include_once("../common.php");

// 카카오 API 설정 - 상수 정의
if (!defined('KAKAO_REST_API_KEY')) {
    define('KAKAO_REST_API_KEY', 'a7d604d5ea37690cd056095003adf0ff');
}
if (!defined('KAKAO_REDIRECT_URI')) {
    define('KAKAO_REDIRECT_URI', 'https://jeonbukwoorisai.co.kr/gnuboard/bbs/kakao_callback.php');
}

// 인증 코드 받기
$code = isset($_GET['code']) ? $_GET['code'] : '';

if (empty($code)) {
    alert('카카오 로그인에 실패했습니다.', '/page/login.php');
    exit;
}

// 액세스 토큰 요청
$token_url = 'https://kauth.kakao.com/oauth/token';
$token_data = array(
    'grant_type' => 'authorization_code',
    'client_id' => KAKAO_REST_API_KEY,
    'redirect_uri' => KAKAO_REDIRECT_URI,
    'code' => $code
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $token_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$token_info = json_decode($response, true);

if (isset($token_info['access_token'])) {
    $access_token = $token_info['access_token'];
    
    // 사용자 정보 요청
    $user_url = 'https://kapi.kakao.com/v2/user/me';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $user_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $access_token
    ));
    $user_response = curl_exec($ch);
    curl_close($ch);
    
    $user_info = json_decode($user_response, true);
    
    if (isset($user_info['id'])) {
        // 카카오 ID로 회원 정보 조회
        $kakao_id = 'kakao_' . $user_info['id'];
        $sql = "SELECT * FROM {$g5['member_table']} WHERE mb_id = '{$kakao_id}'";
        $member = sql_fetch($sql);
        
        if ($member) {
            // 기존 회원 로그인 처리
            set_session('ss_mb_id', $member['mb_id']);
            
            // 최근 로그인 시간 업데이트
            sql_query("UPDATE {$g5['member_table']} SET mb_today_login = NOW(), mb_login_ip = '{$_SERVER['REMOTE_ADDR']}' WHERE mb_id = '{$member['mb_id']}'");
            
            goto_url('/');
        } else {
            // 신규 회원 가입 처리
            $mb_name = isset($user_info['properties']['nickname']) ? $user_info['properties']['nickname'] : 'kakao_user';
            $mb_email = isset($user_info['kakao_account']['email']) ? $user_info['kakao_account']['email'] : '';
            
            $sql = "INSERT INTO {$g5['member_table']} SET
                    mb_id = '{$kakao_id}',
                    mb_name = '{$mb_name}',
                    mb_nick = '{$mb_name}',
                    mb_email = '{$mb_email}',
                    mb_password = '',
                    mb_level = 2,
                    mb_today_login = NOW(),
                    mb_datetime = NOW(),
                    mb_login_ip = '{$_SERVER['REMOTE_ADDR']}'";
            
            sql_query($sql);
            
            set_session('ss_mb_id', $kakao_id);
            goto_url('/');
        }
    } else {
        alert('사용자 정보를 가져오는데 실패했습니다.', '/page/login.php');
    }
} else {
    alert('카카오 로그인에 실패했습니다.', '/page/login.php');
}
?>