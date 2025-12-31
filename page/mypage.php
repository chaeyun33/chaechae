<?php
// 그누보드 연동
include_once("../gnuboard/common.php");

// 로그인 체크
if (!$is_member) {
    alert('로그인이 필요합니다.', '/gnuboard/bbs/login.php');
    exit;
}

// 커스텀 설정
if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}

$base_url = '';
$page_title = '마이페이지 - 전북우리사이';
$body_class = 'mypage';

// 헤더 포함
include_once('../includes/header.php');

// 회원 정보 가져오기
$mb_id = $member['mb_id'];
$mb_name = $member['mb_name'];

// 봉사활동 통계 조회
$sql_stats = "
    SELECT 
        COUNT(*) as total_count,
        COALESCE(SUM(volunteer_hours), 0) as total_hours
    FROM volunteer_records 
    WHERE mb_id = '{$mb_id}'
";
$stats = sql_fetch($sql_stats);

// 기본값 설정
if (!$stats) {
    $stats = array('total_count' => 0, 'total_hours' => 0);
}

// 최근 봉사활동 내역 (최근 10개)
$sql_recent = "
    SELECT * 
    FROM volunteer_records 
    WHERE mb_id = '{$mb_id}'
    ORDER BY activity_date DESC 
    LIMIT 10
";
$result_recent = sql_query($sql_recent);

// 월별 봉사시간 통계 (최근 6개월)
$sql_monthly = "
    SELECT 
        DATE_FORMAT(activity_date, '%Y-%m') as month,
        SUM(volunteer_hours) as hours,
        COUNT(*) as count
    FROM volunteer_records 
    WHERE mb_id = '{$mb_id}'
        AND activity_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(activity_date, '%Y-%m')
    ORDER BY month DESC
";
$result_monthly = sql_query($sql_monthly);

// 올해 봉사시간
$sql_year = "
    SELECT 
        COALESCE(SUM(volunteer_hours), 0) as year_hours,
        COUNT(*) as year_count
    FROM volunteer_records 
    WHERE mb_id = '{$mb_id}'
        AND YEAR(activity_date) = YEAR(NOW())
";
$year_stats = sql_fetch($sql_year);

// 기본값 설정
if (!$year_stats) {
    $year_stats = array('year_hours' => 0, 'year_count' => 0);
}

// ⭐ 봉사 레벨 계산 함수
function getVolunteerLevel($total_hours) {
    return floor($total_hours / 10);
}

// 다음 레벨까지 필요한 시간 계산
function getHoursToNextLevel($total_hours) {
    $current_level = floor($total_hours / 10);
    $next_level_hours = ($current_level + 1) * 10;
    return $next_level_hours - $total_hours;
}

// 레벨 타이틀 결정
function getLevelTitle($level) {
    if ($level == 0) return '새싹 봉사자';
    if ($level >= 1 && $level <= 2) return '열정 봉사자';
    if ($level >= 3 && $level <= 5) return '헌신 봉사자';
    if ($level >= 6 && $level <= 9) return '베테랑 봉사자';
    return '명예 봉사자';
}

// 레벨 아이콘 결정
function getLevelIcon($level) {
    if ($level == 0) return '🌱';
    if ($level >= 1 && $level <= 2) return '💪';
    if ($level >= 3 && $level <= 5) return '❤️';
    if ($level >= 6 && $level <= 9) return '⭐';
    return '👑';
}

// 현재 레벨 계산
$volunteer_level = getVolunteerLevel($stats['total_hours']);
$hours_to_next = getHoursToNextLevel($stats['total_hours']);
$level_title = getLevelTitle($volunteer_level);
$level_icon = getLevelIcon($volunteer_level);
$level_progress = ($stats['total_hours'] - ($volunteer_level * 10)) / 10 * 100; // 현재 레벨 내 진행도
?>

<style>
    .mypage-container {
        width: 100%;
        max-width: 1200px;
        margin: 120px auto 100px;
        padding: 0 20px;
    }

    .page-header {
        text-align: center;
        margin-bottom: 50px;
        padding-bottom: 30px;
        border-bottom: 1px solid #E0E0E0;
    }

    .page-header h1 {
        font-family: 'NexonLv2Gothic', sans-serif;
        color: #333;
        font-size: 2rem;
        margin-bottom: 15px;
    }

    .page-header .user-info {
        font-family: 'NexonLv2Gothic', sans-serif;
        color: #333;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        line-height: 1;
    }

    .user-level-icon {
        font-size: 1.8rem;
        line-height: 1;
        display: flex;
        align-items: center;
    }

    .admin-menu {
        text-align: center;
        margin-bottom: 40px;
    }

    .btn-admin {
        display: inline-block;
        padding: 12px 30px;
        background: #2559a8;
        color: white;
        text-decoration: none;
        font-family: 'NexonLv2Gothic', sans-serif;
        font-size: 1rem;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .btn-admin:hover {
        background: #1a4278;
        color: white;
    }

    /* 레벨 카드 */
    .level-card {
        background: white;
        padding: 40px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 40px;
        border: 1px solid #E0E0E0;
    }

    .level-card h2 {
        font-family: 'NexonLv2Gothic', sans-serif;
        font-size: 2rem;
        margin-bottom: 10px;
        color: #333;
    }

    .level-card .level-title {
        font-family: 'NexonLv2Gothic light', sans-serif;
        font-size: 1rem;
        margin-bottom: 30px;
        color: #888;
    }

    .level-progress-container {
        background: #F8F9FA;
        border-radius: 8px;
        padding: 20px;
    }

    .level-progress-bar {
        background: #E0E0E0;
        height: 20px;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 12px;
    }

    .level-progress-fill {
        background: #2559a8;
        height: 100%;
        border-radius: 10px;
        transition: width 1s ease-out;
    }

    .level-progress-text {
        font-family: 'NexonLv2Gothic', sans-serif;
        font-size: 0.95rem;
        color: #666;
    }

    /* 통계 그리드 */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        padding: 30px;
        border-radius: 10px;
        border: 1px solid #E0E0E0;
        text-align: center;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        background: #2559a8;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 1.5rem;
        color: white;
    }

    .stat-value {
        font-family: 'NexonLv2Gothic', sans-serif;
        font-size: 2rem;
        color: #333;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .stat-label {
        font-family: 'NexonLv2Gothic light', sans-serif;
        color: #888;
        font-size: 0.95rem;
    }

    /* 콘텐츠 박스 */
    .section-title {
        font-family: 'NexonLv2Gothic', sans-serif;
        color: #333;
        font-size: 1.3rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #2559a8;
    }

    .content-box {
        background: white;
        padding: 35px;
        border-radius: 10px;
        border: 1px solid #E0E0E0;
        margin-bottom: 30px;
    }

    /* 테이블 */
    .activity-table {
        width: 100%;
        border-collapse: collapse;
    }

    .activity-table thead {
        background-color: #F8F9FA;
    }

    .activity-table th {
        font-family: 'NexonLv2Gothic', sans-serif;
        color: #333;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .activity-table td {
        font-family: 'NexonLv2Gothic light', sans-serif;
        padding: 12px;
        border-bottom: 1px solid #F0F0F0;
        color: #333;
        font-size: 0.95rem;
    }

    .activity-table tr:hover {
        background-color: #F8F9FA;
    }

    .no-data {
        text-align: center;
        padding: 50px;
        color: #999;
        font-family: 'NexonLv2Gothic light', sans-serif;
    }

    .level-list {
        font-family: 'NexonLv2Gothic light', sans-serif;
        line-height: 2;
        color: #333;
    }

    .level-list div {
        padding: 8px 0;
        border-bottom: 1px solid #F0F0F0;
        font-size: 0.95rem;
    }

    .level-list div:last-child {
        border-bottom: none;
    }

    /* 💬 정보 말풍선 */
    .info-bubble {
        background: #E8F4FD;
        border-left: 4px solid #2196F3;
        padding: 15px 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        font-family: 'NexonLv2Gothic light', sans-serif;
        font-size: 0.9rem;
        color: #1976D2;
        line-height: 1.6;
    }

    @media screen and (max-width: 800px) {
        .mypage-container {
            margin: 100px auto 50px;
            padding: 0 15px;
            max-width: 100%;
            box-sizing: border-box;
        }

        .page-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }

        .page-header .user-info {
            font-size: 1.1rem;
        }

        .user-level-icon {
            font-size: 1.5rem;
        }

        .admin-menu {
            margin-bottom: 30px;
        }

        .btn-admin {
            width: 100%;
            max-width: 300px;
            padding: 14px 20px;
            margin: 0 auto;
            display: block;
        }

        .level-card {
            padding: 30px 20px;
            margin-left: auto;
            margin-right: auto;
        }

        .level-card h2 {
            font-size: 1.5rem;
        }

        .level-card .level-title {
            font-size: 0.95rem;
        }

        .level-progress-container {
            padding: 15px;
        }

        /* ⭐ 통계 그리드 2열로 변경 */
        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-left: auto;
            margin-right: auto;
        }

        .stat-card {
            padding: 20px 15px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 1.5rem;
            line-height: 1.2;
        }

        .stat-value span {
            font-size: 1rem !important;
        }

        .stat-label {
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .content-box {
            padding: 20px 15px;
            margin-left: auto;
            margin-right: auto;
        }

        .section-title {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .activity-table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .activity-table thead {
            display: none;
        }

        .activity-table tbody {
            display: block;
        }

        .activity-table tr {
            display: block;
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            background: white;
        }

        .activity-table td {
            display: block;
            padding: 5px 0;
            border: none;
            text-align: left;
        }

        .activity-table td:before {
            content: attr(data-label);
            font-weight: 600;
            display: inline-block;
            width: 80px;
            color: #666;
        }

        .level-list div {
            font-size: 0.9rem;
            padding: 10px 0;
        }

        .no-data {
            padding: 40px 20px;
        }

        /* 💬 모바일 말풍선 */
        .info-bubble {
            font-size: 0.85rem;
            padding: 12px 15px;
        }
    }
</style>

<div class="mypage-container">
    <div class="page-header">
        <h1>마이페이지</h1>
        <p class="user-info">
            <span class="user-level-icon"><?php echo $level_icon; ?></span>
            <span><?php echo htmlspecialchars($mb_name); ?>님</span>
        </p>
    </div>

    <!-- 관리자 메뉴 -->
    <?php if ($is_admin): ?>
    <div class="admin-menu">
        <a href="/admin/volunteer_manage.php" class="btn-admin">봉사시간 관리</a>
    </div>
    <?php endif; ?>

    <!-- ⭐ 레벨 카드 -->
    <div class="level-card">
        <h2><?php echo $level_title; ?></h2>
        <div class="level-title">총 <?php echo number_format($stats['total_hours'], 1); ?>시간 봉사</div>
        
        <div class="level-progress-container">
            <div class="level-progress-bar">
                <div class="level-progress-fill" style="width: <?php echo $level_progress; ?>%"></div>
            </div>
            <div class="level-progress-text">
                다음 레벨까지 <strong><?php echo number_format($hours_to_next, 1); ?>시간</strong> 남음
                (<?php echo number_format($level_progress, 1); ?>% 달성)
            </div>
        </div>
    </div>

    <!-- 통계 카드 -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">⏱️</div>
            <div class="stat-value"><?php echo number_format($stats['total_hours'], 1); ?> <span style="font-size: 1.5rem;">시간</span></div>
            <div class="stat-label">총 봉사시간</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-value"><?php echo number_format($stats['total_count']); ?> <span style="font-size: 1.5rem;">회</span></div>
            <div class="stat-label">참여 횟수</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-value"><?php echo number_format($year_stats['year_hours'], 1); ?> <span style="font-size: 1.5rem;">시간</span></div>
            <div class="stat-label">올해 봉사시간</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🎯</div>
            <div class="stat-value">
                <?php 
                if ($stats['total_hours'] >= 100) {
                    echo '달성!';
                } else {
                    echo number_format(100 - $stats['total_hours'], 1).' 시간 남음';
                }
                ?>
            </div>
            <div class="stat-label">100시간 목표</div>
        </div>
    </div>

    <!-- 최근 봉사활동 내역 -->
    <div class="content-box">
        <h2 class="section-title">최근 봉사활동 내역</h2>
        
        <!-- 1365 안내 말풍선 -->
        <div class="info-bubble">
            💡 1365 자원봉사시간 인정은 기관 처리 일정에 따라 시간이 소요될 수 있습니다
        </div>
        
        <?php if($result_recent && sql_num_rows($result_recent) > 0): ?>
        <table class="activity-table">
            <thead>
                <tr>
                    <th>활동명</th>
                    <th>활동일</th>
                    <th>봉사시간</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = sql_fetch_array($result_recent)): ?>
                <tr>
                    <td data-label="활동명"><?php echo htmlspecialchars($row['activity_name']); ?></td>
                    <td data-label="활동일"><?php echo date('Y년 m월 d일', strtotime($row['activity_date'])); ?></td>
                    <td data-label="봉사시간"><strong><?php echo number_format($row['volunteer_hours'], 1); ?>시간</strong></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="no-data">
            <p>아직 참여한 봉사활동이 없습니다.</p>
            <p>첫 봉사활동에 참여해보세요! 🎉</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- 월별 통계 -->
    <div class="content-box">
        <h2 class="section-title">월별 봉사활동 통계 (최근 6개월)</h2>
        
        <?php if($result_monthly && sql_num_rows($result_monthly) > 0): ?>
        <table class="activity-table">
            <thead>
                <tr>
                    <th>월</th>
                    <th>봉사시간</th>
                    <th>참여횟수</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = sql_fetch_array($result_monthly)): ?>
                <tr>
                    <td data-label="월"><?php echo $row['month']; ?></td>
                    <td data-label="봉사시간"><strong><?php echo number_format($row['hours'], 1); ?>시간</strong></td>
                    <td data-label="참여횟수"><?php echo number_format($row['count']); ?>회</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="no-data">
            <p>최근 6개월 간 봉사활동 기록이 없습니다.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
// 푸터 포함
include_once('../includes/footer.php');
?>