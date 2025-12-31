<?php
if (!defined('_GNUBOARD_')) exit;

// ========== 테스트 앱 REST API 키 사용 ==========
define('KAKAO_REST_API_KEY', '6a80efdfac260f646b9c8317dd8415b1');  // 테스트 앱 키
define('KAKAO_REDIRECT_URI', 'https://jeonbukwoorisai.co.kr/gnuboard/bbs/kakao_callback.php');

// 카카오 인증 URL 생성
function get_kakao_login_url() {
    $params = array(
        'client_id' => KAKAO_REST_API_KEY,
        'redirect_uri' => KAKAO_REDIRECT_URI,
        'response_type' => 'code'
    );
    
    return 'https://kauth.kakao.com/oauth/authorize?' . http_build_query($params);
}

// 카카오 액세스 토큰 발급
function get_kakao_access_token($code) {
    $url = 'https://kauth.kakao.com/oauth/token';
    
    $params = array(
        'grant_type' => 'authorization_code',
        'client_id' => KAKAO_REST_API_KEY,
        'redirect_uri' => KAKAO_REDIRECT_URI,
        'code' => $code
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded;charset=utf-8'
    ));
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// 카카오 사용자 정보 가져오기
function get_kakao_user_info($access_token) {
    $url = 'https://kapi.kakao.com/v2/user/me';
    
    $headers = array(
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/x-www-form-urlencoded;charset=utf-8'
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}
?>