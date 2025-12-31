<?php
// 세션 설정을 common.php include 전에 먼저 실행
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '.jeonbukwoorisai.co.kr');
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // HTTPS 사용 시

include_once('./common.php');

// 커뮤니티 사용여부
if(defined('G5_COMMUNITY_USE') && G5_COMMUNITY_USE === false) {
    if (!defined('G5_USE_SHOP') || !G5_USE_SHOP)
        die('<p>쇼핑몰 설치 후 이용해 주십시오.</p>');

    define('_SHOP_', true);
}