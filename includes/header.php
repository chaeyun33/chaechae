<?php
// includes/header.php
if (!defined('_CUSTOM_')) exit;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    
    <?php
    // ============================================
    // 🎯 페이지별 SEO 메타태그 자동 설정
    // ============================================
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
    
    // 페이지별 설명 (description)
    $page_descriptions = [
        'index' => '전북우리사이는 청년들이 자발적으로 모여 지역사회에 긍정적인 변화를 만들어가는 자원봉사단체입니다.',
        'intro' => '전북우리사이 소개 - 우리는 단순한 봉사활동이 아닌, 청년들의 성장과 서로 연결되며 지역사회의 긍정적 변화를 만들어갑니다.',
        'recruit' => '전북우리사이 청년 봉사자 모집 - 만 19세~34세 전북 지역 청년들의 참여를 기다립니다.',
        'recruit_view' => '전북우리사이 모집공고 - 청년 봉사자 모집 상세 내용을 확인하세요.',
        'notice' => '전북우리사이 공지사항 - 봉사활동 일정과 중요 공지를 확인하세요.',
        'note' => '전북우리사이 공지사항 상세 - 봉사활동 관련 상세 공지를 확인하세요.',
        'news' => '전북우리사이 언론보도 - 우리 활동이 언론에 소개된 내용을 확인하세요.',
        'photo' => '전북우리사이 포토갤러리 - 봉사활동 현장의 생생한 사진을 만나보세요.'
    ];
    
    // 페이지별 제목 (title)
    $page_titles = [
        'index' => '전북우리사이 - 전북 청년 자원봉사단',
        'intro' => '단체 소개 - 전북우리사이',
        'recruit' => '모집공고 - 전북우리사이',
        'recruit_view' => '모집공고 상세 - 전북우리사이',
        'notice' => '공지사항 - 전북우리사이',
        'note' => '공지사항 상세 - 전북우리사이',
        'news' => '언론보도 - 전북우리사이',
        'photo' => '포토갤러리 - 전북우리사이'
    ];
    
    // 현재 페이지의 메타 정보 가져오기
    $meta_description = isset($page_descriptions[$current_page]) 
        ? $page_descriptions[$current_page] 
        : $page_descriptions['index'];
    
    $meta_title = isset($page_titles[$current_page]) 
        ? $page_titles[$current_page] 
        : (isset($page_title) ? $page_title : $page_titles['index']);
    
    // 현재 URL
    $current_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    
    // OG 이미지 (페이지별 다르게 설정 가능)
    $og_image = 'https://jeonbukwoorisai.co.kr/img/슬라이드1.png';
    ?>
    
    <title><?php echo $meta_title; ?></title>
    
    <!-- ============================================ -->
    <!-- SEO 메타태그 -->
    <!-- ============================================ -->
    <meta name="description" content="<?php echo $meta_description; ?>">
    <meta name="keywords" content="전북우리사이, 전북 봉사, 청년 봉사활동, 자원봉사, 전북 자원봉사, 전북특별자치도, 청년단체, 전북 청년, 봉사단체">
    <meta name="author" content="전북우리사이">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="google" content="notranslate">
    
    <!-- ============================================ -->
    <!-- Open Graph (카카오톡, 페이스북 공유) -->
    <!-- ============================================ -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $meta_title; ?>">
    <meta property="og:description" content="<?php echo $meta_description; ?>">
    <meta property="og:url" content="<?php echo $current_url; ?>">
    <meta property="og:site_name" content="전북우리사이">
    <meta property="og:image" content="<?php echo $og_image; ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="ko_KR">
    
    <!-- ============================================ -->
    <!-- Twitter Card (트위터 공유) -->
    <!-- ============================================ -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $meta_title; ?>">
    <meta name="twitter:description" content="<?php echo $meta_description; ?>">
    <meta name="twitter:image" content="<?php echo $og_image; ?>">
    
    <!-- ============================================ -->
    <!-- 웹마스터 도구 인증 (필요시 추가) -->
    <!-- ============================================ -->
    <!-- <meta name="naver-site-verification" content="여기에_네이버_인증코드"> -->
    <!-- <meta name="google-site-verification" content="여기에_구글_인증코드"> -->
    
    <!-- ============================================ -->
    <!-- Canonical URL (중복 콘텐츠 방지) -->
    <!-- ============================================ -->
    <link rel="canonical" href="<?php echo $current_url; ?>">
    
    <!-- ✅ CSS 파일 로드 순서 중요! -->
    <link rel="stylesheet" href="/style/common.css">
    <link rel="stylesheet" href="/style/mobile.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    <!-- JavaScript 파일 -->
    <script src="/javasrcipt/jquery-1.12.3.js"></script>
    <script src="/javasrcipt/script.js"></script>
    
    <style>
    /* ✅ 로고 보이게 하기 */
    .logo img {
        display: block !important;
        height: 50px;
        width: auto;
    }
    
    .mlogo img {
        display: block !important;
        height: 40px !important;
        width: 150px !important;
        object-fit: contain !important;
    }
    
    /* ✅ 메뉴 링크 색상 검정으로 고정 */
    .navi > li > a {
        color: #333 !important;
        text-decoration: none !important;
    }
    
    .navi > li > a:visited {
        color: #333 !important;
    }
    
    .navi > li > a:hover {
        color: #4285f4 !important;
    }
    
    /* ✅ 모바일 메뉴 링크도 검정 */
    .mnav a {
        color: #333 !important;
        text-decoration: none !important;
    }
    
    .mnav a:visited {
        color: #333 !important;
    }
    
    /* ✅ 로그인 영역 스타일 */
    .login-area {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .login-user {
        color: #333 !important;
        font-family: 'NexonLv2Gothic', sans-serif;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .user-level {
        font-size: 1.2rem;
        line-height: 1;
    }
    
    /* ✅ 메인 페이지 맨 위 흰색 */
    .main-page .login-user {
        color: white !important;
    }
    
    .main-page .navi > li > a {
        color: white !important;
    }
    
    /* ✅ 메인 페이지 스크롤 시 검은색으로 */
    .main-page header.scrolled .login-user {
        color: #333 !important;
    }
    
    .main-page header.scrolled .navi > li > a {
        color: #333 !important;
    }
    
    .main-page header.scrolled .navi > li > a:hover {
        color: #2559a8 !important;
    }
    
    /* ✅ 로그인 버튼 스타일 */
    .login-btn {
        color: #fff !important;
        background: #2559a8;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none !important;
        font-family: 'NexonLv2Gothic', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    
    .login-btn:hover {
        background: #1a4278;
    }
    
    .logout-btn {
        background: #666;
    }
    
    .logout-btn:hover {
        background: #444;
    }
    
    /* ✅ 모바일 로그인 영역 스타일 */
    .mobile-login-area {
        padding: 20px;
        background: #f8f9fa;
    }
    
    .mobile-login-top {
        border-bottom: 2px solid #eee;
    }
    
    .mobile-user-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-bottom: 15px;
        font-family: 'NexonLv2Gothic', sans-serif;
        font-size: 1rem;
        color: #333;
    }
    
    .mobile-level {
        font-size: 1.5rem;
        line-height: 1;
    }
    
    .mobile-login-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    
    /* 버튼 스타일 - 최고 우선순위 */
    a.mobile-btn,
    a.mobile-btn:link,
    a.mobile-btn:visited,
    a.mobile-btn:hover,
    a.mobile-btn:active {
        display: block;
        padding: 14px 12px;
        text-align: center;
        text-decoration: none !important;
        border-radius: 8px;
        font-family: 'NexonLv2Gothic', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.2s;
        border: none;
    }
    
    a.mobile-btn-primary,
    a.mobile-btn-primary:link,
    a.mobile-btn-primary:visited,
    a.mobile-btn-primary:active {
        background: #2559a8 !important;
        color: #fff !important;
    }
    
    a.mobile-btn-primary:hover {
        background: #1a4278 !important;
        color: #fff !important;
    }
    
    a.mobile-btn-logout,
    a.mobile-btn-logout:link,
    a.mobile-btn-logout:visited,
    a.mobile-btn-logout:active {
        background: #666 !important;
        color: #fff !important;
    }
    
    a.mobile-btn-logout:hover {
        background: #444 !important;
        color: #fff !important;
    }
    </style>
</head>

<body<?php echo isset($body_class) ? ' class="'.$body_class.'"' : ''; ?>>
    <div id="wrap">
        <!-- 헤더 -->
        <header>
            <!-- 모바일 메뉴 -->
            <div class="mmenu">
                <div class="mlogo">
                    <a href="/"><img src="/img/logo.png" alt="전북우리사이 로고"></a>
                </div>
                <div class="hamburger" onclick="toggleMenu()">☰</div>
            </div>
            
            <!-- 모바일 네비게이션 -->
            <nav class="mnav" id="mnav">
                <?php if (isset($is_member) && $is_member) { 
                    // 봉사시간 조회
                    $volunteer_sql = "SELECT COALESCE(SUM(volunteer_hours), 0) as total_hours 
                                     FROM volunteer_records 
                                     WHERE mb_id = '{$member['mb_id']}'";
                    $volunteer_result = sql_fetch($volunteer_sql);
                    $total_hours = $volunteer_result ? $volunteer_result['total_hours'] : 0;
                    
                    // 레벨 계산
                    $level = floor($total_hours / 10);
                    
                    // 레벨별 아이콘
                    if ($level == 0) $level_icon = '🌱';
                    else if ($level >= 1 && $level <= 2) $level_icon = '💪';
                    else if ($level >= 3 && $level <= 5) $level_icon = '❤️';
                    else if ($level >= 6 && $level <= 9) $level_icon = '⭐';
                    else $level_icon = '👑';
                ?>
                    <!-- 로그인 상태 - 닉네임 맨 위 -->
                    <div class="mobile-login-area mobile-login-top">
                        <div class="mobile-user-info">
                            <span class="mobile-level"><?php echo $level_icon; ?></span>
                            <span><?php echo isset($member['mb_nick']) ? $member['mb_nick'] : '회원'; ?>님</span>
                        </div>
                        <div class="mobile-login-buttons">
                            <a href="/page/mypage.php" class="mobile-btn mobile-btn-primary">마이페이지</a>
                            <a href="/gnuboard/bbs/logout.php" class="mobile-btn mobile-btn-logout">로그아웃</a>
                        </div>
                    </div>
                <?php } else { ?>
                    <!-- 비로그인 상태 - 버튼 맨 위 -->
                    <div class="mobile-login-area mobile-login-top">
                        <div class="mobile-login-buttons">
                            <a href="/page/register_agree.php" class="mobile-btn mobile-btn-primary">회원가입</a>
                            <a href="/page/login.php?url=<?php echo urlencode('/'); ?>" class="mobile-btn mobile-btn-primary">로그인</a>
                        </div>
                    </div>
                <?php } ?>
                
                <ul>
                    <li><a href="/intro.php">전북우리사이봉사단</a></li>
                    <li><a href="https://www.instagram.com/jeonbuk_woorisai/" target="_blank">활동사업</a></li>
                    <li><a href="/recruit/recruit.php">모집공고</a></li>
                    <li><a href="/notice/notice.php">공지사항</a></li>
                    <li><a href="/gallery/photo.php">포토갤러리</a></li>
                </ul>
            </nav>
            
            <!-- 데스크탑 메뉴 -->
            <nav class="menu">
                <div class="logo">
                    <?php 
                    // 메인 페이지는 btlogo, 나머지는 logo
                    $logo_img = (isset($body_class) && $body_class == 'main-page') ? 'btlogo.png' : 'logo.png';
                    ?>
                    <a href="/"><img src="/img/<?php echo $logo_img; ?>" alt="전북우리사이 로고"></a>
                </div>
                <ul class="navi">
                    <li><a href="/intro.php">전북우리사이봉사단</a></li>
                    <li><a href="https://www.instagram.com/jeonbuk_woorisai/" target="_blank">활동사업</a></li>
                    <li><a href="/recruit/recruit.php">모집공고</a></li>
                    <li><a href="/notice/notice.php">공지사항</a></li>
                    <li><a href="/gallery/photo.php">포토갤러리</a></li>
                </ul>
                
                <div class="login-area">
                    <?php if (isset($is_member) && $is_member) { 
                        // 봉사시간 조회
                        $volunteer_sql = "SELECT COALESCE(SUM(volunteer_hours), 0) as total_hours 
                                         FROM volunteer_records 
                                         WHERE mb_id = '{$member['mb_id']}'";
                        $volunteer_result = sql_fetch($volunteer_sql);
                        $total_hours = $volunteer_result ? $volunteer_result['total_hours'] : 0;
                        
                        // 레벨 계산
                        $level = floor($total_hours / 10);
                        
                        // 레벨별 아이콘
                        if ($level == 0) $level_icon = '🌱';
                        else if ($level >= 1 && $level <= 2) $level_icon = '💪';
                        else if ($level >= 3 && $level <= 5) $level_icon = '❤️';
                        else if ($level >= 6 && $level <= 9) $level_icon = '⭐';
                        else $level_icon = '👑';
                    ?>
                        <!-- 로그인 상태 -->
                        <span class="login-user">
                            <span class="user-level"><?php echo $level_icon; ?></span>
                            <?php echo isset($member['mb_nick']) ? $member['mb_nick'] : '회원'; ?>님
                        </span>
                        <a href="/page/mypage.php" class="login-btn">마이페이지</a>
                        <a href="/gnuboard/bbs/logout.php" class="login-btn logout-btn">로그아웃</a>
                    <?php } else { ?>
                        <!-- 비로그인 상태 -->
                        <a href="/page/register_agree.php" class="login-btn">회원가입</a>
                        <a href="/page/login.php?url=<?php echo urlencode('/'); ?>" class="login-btn">로그인</a>
                    <?php } ?>
                </div>
            </nav>
        </header>

        <script>
        // 햄버거 메뉴 토글
        function toggleMenu() {
            const mnav = document.getElementById('mnav');
            const hamburger = document.querySelector('.hamburger');
            
            if (mnav.style.display === 'block') {
                mnav.style.display = 'none';
                hamburger.innerHTML = '☰';
            } else {
                mnav.style.display = 'block';
                hamburger.innerHTML = '✕';
            }
        }

        // 메인페이지 스크롤 효과
        window.addEventListener('DOMContentLoaded', function() {
            const isMainPage = document.body.classList.contains('main-page');
            
            if (isMainPage && window.innerWidth > 800) {
                const header = document.querySelector('header');
                const logo = document.querySelector('.logo img');
                
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        header.classList.add('scrolled');
                        if (logo) logo.src = '/img/logo.png';
                    } else {
                        header.classList.remove('scrolled');
                        if (logo) logo.src = '/img/btlogo.png';
                    }
                });
            }
        });
        </script>