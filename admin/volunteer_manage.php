<?php
// 그누보드 연동
include_once("../gnuboard/common.php");

// 관리자 권한 체크
if (!$is_admin) {
    alert('관리자만 접근 가능합니다.', '/');
    exit;
}

$base_url = '';
$page_title = '봉사시간 관리 - 전북우리사이';
$body_class = 'admin-volunteer';

// volunteer_records 테이블 자동 생성
$table_check = sql_query("SHOW TABLES LIKE 'volunteer_records'", false);
if (!$table_check || sql_num_rows($table_check) == 0) {
    $create_table = "CREATE TABLE `volunteer_records` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `mb_id` varchar(20) NOT NULL,
        `activity_name` varchar(255) NOT NULL,
        `activity_date` date NOT NULL,
        `volunteer_hours` decimal(5,1) NOT NULL DEFAULT 0.0,
        `description` text DEFAULT NULL,
        `status` varchar(20) NOT NULL DEFAULT 'complete',
        `created_at` datetime NOT NULL,
        PRIMARY KEY (`id`),
        KEY `mb_id` (`mb_id`),
        KEY `activity_date` (`activity_date`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    sql_query($create_table, true);
}

// 처리 로직
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    // 회원+활동명으로 삭제
    if ($action == 'delete_by_activity') {
        $target_mb_id = isset($_POST['target_mb_id']) ? sql_real_escape_string(trim($_POST['target_mb_id'])) : '';
        $target_activity = isset($_POST['target_activity']) ? sql_real_escape_string(trim($_POST['target_activity'])) : '';
        
        if (empty($target_mb_id)) {
            echo "<script>alert('회원을 선택해주세요.');</script>";
        } elseif (empty($target_activity)) {
            echo "<script>alert('활동명을 선택해주세요.');</script>";
        } else {
            // 삭제할 기록 조회
            $check_sql = "SELECT * FROM volunteer_records WHERE mb_id = '{$target_mb_id}' AND activity_name = '{$target_activity}'";
            $check_result = sql_query($check_sql);
            $delete_count = sql_num_rows($check_result);
            
            if ($delete_count > 0) {
                $delete_sql = "DELETE FROM volunteer_records WHERE mb_id = '{$target_mb_id}' AND activity_name = '{$target_activity}'";
                $result = sql_query($delete_sql, true);
                
                if ($result) {
                    echo "<script>alert('{$delete_count}건의 기록이 삭제되었습니다.'); location.href='./volunteer_manage.php';</script>";
                } else {
                    echo "<script>alert('삭제 실패: " . sql_error() . "');</script>";
                }
            } else {
                echo "<script>alert('삭제할 기록이 없습니다.');</script>";
            }
        }
        exit;
    }
    
    // 봉사시간 등록
    if ($action == 'add') {
        $mb_id = sql_real_escape_string(trim($_POST['mb_id']));
        $activity_name = sql_real_escape_string(trim($_POST['activity_name']));
        $activity_date = sql_real_escape_string(trim($_POST['activity_date']));
        $volunteer_hours = floatval($_POST['volunteer_hours']);
        $description = sql_real_escape_string(trim($_POST['description']));
        
        if (empty($mb_id)) {
            echo "<script>alert('회원을 선택해주세요.');</script>";
        } elseif (empty($activity_name)) {
            echo "<script>alert('활동명을 입력해주세요.');</script>";
        } elseif ($volunteer_hours <= 0) {
            echo "<script>alert('봉사시간은 0보다 커야 합니다.');</script>";
        } else {
            $description_value = empty($description) ? "NULL" : "'{$description}'";
            
            $sql = "INSERT INTO volunteer_records 
                    (mb_id, activity_name, activity_date, volunteer_hours, description, status, created_at) 
                    VALUES ('{$mb_id}', '{$activity_name}', '{$activity_date}', {$volunteer_hours}, {$description_value}, 'complete', NOW())";
            
            if (sql_query($sql, true)) {
                echo "<script>alert('봉사시간이 등록되었습니다.'); location.href='./volunteer_manage.php';</script>";
                exit;
            } else {
                echo "<script>alert('등록 실패: " . sql_error() . "');</script>";
            }
        }
    }
}

// 레벨 함수
function getVolunteerLevel($total_hours) { return floor($total_hours / 10); }
function getLevelTitle($level) {
    if ($level == 0) return '새싹 봉사자';
    if ($level >= 1 && $level <= 2) return '열정 봉사자';
    if ($level >= 3 && $level <= 5) return '헌신 봉사자';
    if ($level >= 6 && $level <= 9) return '베테랑 봉사자';
    return '명예 봉사자';
}

include_once('../includes/header.php');

// 데이터 조회
$members_result = sql_query("SELECT mb_id, mb_name, mb_level FROM {$g5['member_table']} ORDER BY mb_name ASC");
$stats = sql_fetch("SELECT COUNT(DISTINCT mb_id) as total_members, COUNT(*) as total_records, COALESCE(SUM(volunteer_hours), 0) as total_hours FROM volunteer_records");
if (!$stats) $stats = array('total_members' => 0, 'total_records' => 0, 'total_hours' => 0);

$recent_records_result = sql_query("SELECT v.*, m.mb_name FROM volunteer_records v LEFT JOIN {$g5['member_table']} m ON v.mb_id = m.mb_id ORDER BY v.created_at DESC LIMIT 100");
$member_stats_result = sql_query("SELECT m.mb_id, m.mb_name, m.mb_level, 
    (SELECT COUNT(*) FROM volunteer_records v WHERE v.mb_id = m.mb_id) as activity_count,
    (SELECT COALESCE(SUM(volunteer_hours), 0) FROM volunteer_records v WHERE v.mb_id = m.mb_id) as total_hours
    FROM {$g5['member_table']} m ORDER BY total_hours DESC");
?>

<style>
.admin-container { width: 1200px; max-width: 1200px; margin: 100px auto; padding: 20px; }
.admin-header { background: white; padding: 35px; border-radius: 10px; margin-bottom: 30px; border: 1px solid #E0E0E0; border-left: 4px solid #2559a8; }
.admin-header h1 { font-family: 'NexonLv2Gothic', sans-serif; font-size: 1.8rem; margin-bottom: 8px; color: #333; }
.admin-header p { font-family: 'NexonLv2Gothic light', sans-serif; color: #888; font-size: 0.95rem; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
.stat-card { background: white; padding: 25px; border-radius: 10px; border: 1px solid #E0E0E0; text-align: center; }
.stat-value { font-family: 'NexonLv2Gothic', sans-serif; font-size: 1.8rem; color: #333; font-weight: bold; margin-bottom: 5px; }
.stat-label { font-family: 'NexonLv2Gothic light', sans-serif; color: #888; font-size: 0.9rem; }
.section-box { background: white; padding: 30px; border-radius: 10px; border: 1px solid #E0E0E0; margin-bottom: 30px; }
.section-title { font-family: 'NexonLv2Gothic', sans-serif; font-size: 1.3rem; color: #333; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #2559a8; }
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-bottom: 20px; }
.form-group { display: flex; flex-direction: column; }
.form-group label { font-family: 'NexonLv2Gothic', sans-serif; color: #333; margin-bottom: 6px; font-weight: 600; font-size: 0.95rem; }
.form-group label .required { color: #2559a8; }
.form-group select, .form-group input, .form-group textarea { padding: 10px; border: 1px solid #E0E0E0; border-radius: 6px; font-family: 'NexonLv2Gothic light', sans-serif; font-size: 0.95rem; }
.form-group select:focus, .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #2559a8; }
.form-group textarea { resize: vertical; min-height: 70px; }
.btn { padding: 10px 20px; font-family: 'NexonLv2Gothic', sans-serif; font-size: 0.95rem; border: none; border-radius: 6px; cursor: pointer; transition: all 0.2s; }
.btn-primary { background: #2559a8; color: white; }
.btn-primary:hover { background: #1a4278; }
.btn-warning { background: #ff9800; color: white; }
.btn-warning:hover { background: #e68900; }
.data-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
.data-table th { background: #F8F9FA; padding: 12px; text-align: center; font-family: 'NexonLv2Gothic', sans-serif; color: #333; border-bottom: 2px solid #2559a8; font-size: 0.9rem; }
.data-table td { padding: 12px; border-bottom: 1px solid #F0F0F0; font-family: 'NexonLv2Gothic light', sans-serif; text-align: center; font-size: 0.9rem; }
.data-table tr:hover { background: #F8F9FA; }
.member-level-badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; font-weight: 600; }
.level-1 { background: #FFE0B2; color: #E65100; }
.level-2 { background: #C8E6C9; color: #2E7D32; }
.level-10 { background: #2559a8; color: white; }
.volunteer-level-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 12px; font-size: 0.8rem; font-weight: 600; color: white; background: #2559a8; }
.text-truncate { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin: 0 auto; }
.no-data { text-align: center; padding: 50px; color: #999; font-size: 0.95rem; }
.warning-box { background: #FFF3CD; border-left: 4px solid #ff9800; padding: 15px 20px; margin-bottom: 20px; border-radius: 6px; }
.warning-box p { font-family: 'NexonLv2Gothic light', sans-serif; color: #856404; font-size: 0.9rem; margin: 5px 0; }
.info-box { background: #E8F4FD; border-left: 4px solid #2196F3; padding: 15px 20px; margin-bottom: 20px; border-radius: 6px; }
.info-box p { font-family: 'NexonLv2Gothic light', sans-serif; color: #1976D2; font-size: 0.9rem; margin: 5px 0; }
@media screen and (max-width: 800px) {
    .admin-container { width: auto; max-width: 100%; margin: 80px auto; padding: 15px; }
    .form-grid { grid-template-columns: 1fr; }
    .data-table th, .data-table td { padding: 8px; font-size: 0.85rem; }
    .text-truncate { max-width: 100px; }
}
</style>

<div class="admin-container">
    <div class="admin-header">
        <h1>🛠️ 봉사시간 관리</h1>
        <p>회원들의 봉사활동 시간을 등록하고 관리합니다</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?php echo number_format($stats['total_members']); ?>명</div>
            <div class="stat-label">참여 회원</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo number_format($stats['total_records']); ?>건</div>
            <div class="stat-label">총 활동 건수</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo number_format($stats['total_hours'], 1); ?>시간</div>
            <div class="stat-label">총 봉사시간</div>
        </div>
    </div>

    <div class="section-box">
        <h2 class="section-title">📝 봉사시간 등록</h2>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-grid">
                <div class="form-group">
                    <label>회원 선택 <span class="required">*</span></label>
                    <select name="mb_id" required>
                        <option value="">회원을 선택하세요</option>
                        <?php 
                        $members_for_add = sql_query("SELECT mb_id, mb_name, mb_level FROM {$g5['member_table']} ORDER BY mb_name ASC");
                        while($m = sql_fetch_array($members_for_add)): 
                        ?>
                        <option value="<?=$m['mb_id']?>"><?=$m['mb_name']?> (<?=$m['mb_id']?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>활동명 <span class="required">*</span></label>
                    <input type="text" name="activity_name" required>
                </div>
                <div class="form-group">
                    <label>활동일 <span class="required">*</span></label>
                    <input type="date" name="activity_date" id="activity_date" required>
                </div>
                <div class="form-group">
                    <label>봉사시간 <span class="required">*</span></label>
                    <input type="number" name="volunteer_hours" step="0.5" min="0.5" required>
                </div>
            </div>
            <div class="form-group">
                <label>활동 내용</label>
                <textarea name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">등록하기</button>
        </form>
    </div>

    <!-- ⭐ 활동별 삭제 섹션 -->
    <div class="section-box">
        <h2 class="section-title">🗑️ 활동별 봉사시간 삭제</h2>
        
        <div class="warning-box">
            <p><strong>⚠️ 주의사항</strong></p>
            <p>• 선택한 회원의 특정 활동명에 해당하는 모든 기록이 삭제됩니다</p>
            <p>• 삭제된 데이터는 복구할 수 없습니다</p>
        </div>

        <form method="POST" onsubmit="return confirm('정말 삭제하시겠습니까?\n삭제된 데이터는 복구할 수 없습니다.');">
            <input type="hidden" name="action" value="delete_by_activity">
            <div class="form-grid">
                <div class="form-group">
                    <label>회원 선택 <span class="required">*</span></label>
                    <select name="target_mb_id" id="delete_member" required onchange="loadActivities()">
                        <option value="">회원을 선택하세요</option>
                        <?php 
                        $members_for_delete = sql_query("SELECT DISTINCT m.mb_id, m.mb_name 
                                                         FROM {$g5['member_table']} m 
                                                         INNER JOIN volunteer_records v ON m.mb_id = v.mb_id 
                                                         ORDER BY m.mb_name ASC");
                        while($m = sql_fetch_array($members_for_delete)): 
                        ?>
                        <option value="<?=$m['mb_id']?>"><?=$m['mb_name']?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>활동명 선택 <span class="required">*</span></label>
                    <select name="target_activity" id="activity_select" required>
                        <option value="">먼저 회원을 선택하세요</option>
                    </select>
                </div>
            </div>
            <div id="delete_info" style="display: none;">
                <div class="info-box">
                    <p id="delete_info_text">선택한 활동의 정보가 여기에 표시됩니다</p>
                </div>
            </div>
            <button type="submit" class="btn btn-warning">활동 삭제하기</button>
        </form>
    </div>

    <div class="section-box">
        <h2 class="section-title">🗂️ 최근 봉사활동 내역</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>번호</th><th>회원명</th><th>활동명</th><th>활동일</th><th>봉사시간</th><th>활동내용</th><th>등록일시</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while($r = sql_fetch_array($recent_records_result)): ?>
                <tr>
                    <td><?=$no++?></td>
                    <td><?=htmlspecialchars($r['mb_name'])?></td>
                    <td><?=htmlspecialchars($r['activity_name'])?></td>
                    <td><?=date('Y-m-d', strtotime($r['activity_date']))?></td>
                    <td><strong><?=number_format($r['volunteer_hours'], 1)?>시간</strong></td>
                    <td><div class="text-truncate"><?=$r['description'] ? htmlspecialchars($r['description']) : '-'?></div></td>
                    <td><?=date('Y-m-d H:i', strtotime($r['created_at']))?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="section-box">
        <h2 class="section-title">👥 회원별 봉사시간 통계</h2>
        <table class="data-table">
            <thead>
                <tr><th>순위</th><th>회원명</th><th>회원등급</th><th>봉사레벨</th><th>참여횟수</th><th>총 봉사시간</th></tr>
            </thead>
            <tbody>
                <?php 
                $rank = 1;
                while($m = sql_fetch_array($member_stats_result)): 
                    $level_class = $m['mb_level'] == 1 ? 'level-1' : ($m['mb_level'] >= 10 ? 'level-10' : 'level-2');
                    $level_text = $m['mb_level'] == 1 ? '준회원' : ($m['mb_level'] >= 10 ? '관리자' : '정회원');
                    $v_level = getLevelTitle(getVolunteerLevel($m['total_hours']));
                ?>
                <tr>
                    <td><?=$rank++?></td>
                    <td><?=htmlspecialchars($m['mb_name'])?></td>
                    <td><span class="member-level-badge <?=$level_class?>"><?=$level_text?></span></td>
                    <td><span class="volunteer-level-badge">🏆 <?=$v_level?></span></td>
                    <td><?=number_format($m['activity_count'])?>회</td>
                    <td><strong><?=number_format($m['total_hours'], 1)?>시간</strong></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// 활동일 기본값 설정
document.getElementById('activity_date').value = new Date().toISOString().split('T')[0];

// AJAX로 활동명 불러오기
function loadActivities() {
    const mbId = document.getElementById('delete_member').value;
    const activitySelect = document.getElementById('activity_select');
    const deleteInfo = document.getElementById('delete_info');
    
    // 초기화
    activitySelect.innerHTML = '<option value="">활동을 선택하세요</option>';
    deleteInfo.style.display = 'none';
    
    if (!mbId) {
        activitySelect.innerHTML = '<option value="">먼저 회원을 선택하세요</option>';
        return;
    }
    
    // AJAX 요청
    fetch('./get_activities.php?mb_id=' + encodeURIComponent(mbId))
        .then(response => response.json())
        .then(data => {
            if (data.success && data.activities.length > 0) {
                data.activities.forEach(activity => {
                    const option = document.createElement('option');
                    option.value = activity.activity_name;
                    option.textContent = activity.activity_name + ' (' + activity.count + '건, ' + activity.total_hours + '시간)';
                    option.dataset.count = activity.count;
                    option.dataset.hours = activity.total_hours;
                    activitySelect.appendChild(option);
                });
            } else {
                activitySelect.innerHTML = '<option value="">등록된 활동이 없습니다</option>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            activitySelect.innerHTML = '<option value="">불러오기 실패</option>';
        });
}

// 활동 선택 시 정보 표시
document.getElementById('activity_select').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const deleteInfo = document.getElementById('delete_info');
    const infoText = document.getElementById('delete_info_text');
    
    if (this.value && selected.dataset.count) {
        const count = selected.dataset.count;
        const hours = selected.dataset.hours;
        infoText.textContent = `선택한 활동: ${this.value} | 삭제될 기록: ${count}건 (총 ${hours}시간)`;
        deleteInfo.style.display = 'block';
    } else {
        deleteInfo.style.display = 'none';
    }
});
</script>

<?php include_once('../includes/footer.php'); ?>