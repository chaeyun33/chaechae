<?php
// 관리자 전용 - 게시물 날짜/조회수 수정
if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}

include_once($_SERVER['DOCUMENT_ROOT']."/gnuboard/common.php");

// 관리자 체크
$is_admin_user = ($is_admin == 'super' || $member['mb_id'] == 'admin');

if (!$is_admin_user) {
    die("<script>alert('관리자만 접근 가능합니다.'); history.back();</script>");
}

$bo_table = 'notice';
$write_table = $g5['write_prefix'].$bo_table;

// POST 처리
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $wr_id = (int)$_POST['wr_id'];
    
    if ($action == 'update') {
        $wr_datetime = $_POST['wr_datetime'];
        $wr_hit = (int)$_POST['wr_hit'];
        
        // 날짜 형식 검증
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $wr_datetime)) {
            $sql = "UPDATE {$write_table} SET 
                    wr_datetime = '{$wr_datetime}',
                    wr_last = '{$wr_datetime}',
                    wr_hit = {$wr_hit}
                    WHERE wr_id = {$wr_id}";
            
            if (sql_query($sql)) {
                echo "<script>alert('수정되었습니다.'); location.reload();</script>";
            } else {
                echo "<script>alert('수정 실패');</script>";
            }
        }
    } elseif ($action == 'delete') {
        // 첨부파일 삭제
        $file_result = sql_query("SELECT * FROM {$g5['board_file_table']} 
                                  WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}'");
        
        while ($file = sql_fetch_array($file_result)) {
            $file_path = G5_DATA_PATH.'/file/'.$bo_table.'/'.$file['bf_file'];
            if (file_exists($file_path)) {
                @unlink($file_path);
            }
        }
        
        // DB에서 파일 정보 삭제
        sql_query("DELETE FROM {$g5['board_file_table']} WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}'");
        
        // 게시글 삭제
        sql_query("DELETE FROM {$write_table} WHERE wr_id = '{$wr_id}'");
        
        // 게시판 카운트 감소
        sql_query("UPDATE {$g5['board_table']} SET bo_count_write = bo_count_write - 1 WHERE bo_table = '{$bo_table}'");
        
        echo "<script>alert('삭제되었습니다.'); location.reload();</script>";
    }
}

// 게시물 목록
$result = sql_query("SELECT wr_id, wr_subject, wr_datetime, wr_hit FROM {$write_table} ORDER BY wr_id DESC LIMIT 20");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>게시물 날짜/조회수 수정</title>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Malgun Gothic', sans-serif;
    padding: 20px;
    background: #f5f5f5;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

h1 {
    color: #333;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid #2559a8;
}

.warning {
    background: #fff3cd;
    border: 1px solid #ffc107;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    color: #856404;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

tr:hover {
    background: #f8f9fa;
}

.btn {
    padding: 6px 12px;
    background: #2559a8;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.btn:hover {
    background: #1d4380;
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 30px;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
}

.modal-header {
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #555;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-group input:focus {
    outline: none;
    border-color: #2559a8;
}

.modal-buttons {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 25px;
}

.btn-cancel {
    background: #6c757d;
}

.btn-cancel:hover {
    background: #5a6268;
}

.text-center {
    text-align: center;
}
</style>
</head>
<body>
<div class="container">
    <h1>🔧 게시물 날짜/조회수 수정</h1>
    
    <div class="warning">
        <strong>⚠️ 주의:</strong> 이 페이지는 관리자 전용입니다. 게시물의 작성일시와 조회수를 직접 수정할 수 있습니다.
    </div>

    <table>
        <thead>
            <tr>
                <th width="60">번호</th>
                <th>제목</th>
                <th width="160">작성일시</th>
                <th width="80">조회수</th>
                <th width="120">수정/삭제</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = sql_fetch_array($result)) { ?>
            <tr>
                <td><?php echo $row['wr_id']; ?></td>
                <td><?php echo htmlspecialchars($row['wr_subject']); ?></td>
                <td><?php echo $row['wr_datetime']; ?></td>
                <td><?php echo number_format($row['wr_hit']); ?></td>
                <td>
                    <button class="btn" onclick="openModal(<?php echo $row['wr_id']; ?>, '<?php echo $row['wr_subject']; ?>', '<?php echo $row['wr_datetime']; ?>', <?php echo $row['wr_hit']; ?>)">
                        수정
                    </button>
                    <button class="btn" style="background: #dc3545; margin-left: 5px;" onclick="deletePost(<?php echo $row['wr_id']; ?>, '<?php echo addslashes($row['wr_subject']); ?>')">
                        삭제
                    </button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- 모달 -->
<div id="modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">게시물 정보 수정</div>
        <form method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="wr_id" id="wr_id">
            
            <div class="form-group">
                <label>게시물 제목</label>
                <input type="text" id="wr_subject" readonly style="background: #f5f5f5;">
            </div>
            
            <div class="form-group">
                <label>작성일시 (YYYY-MM-DD HH:MM:SS)</label>
                <input type="text" name="wr_datetime" id="wr_datetime" placeholder="2025-12-24 17:00:00" required>
                <small style="color: #888; font-size: 12px;">예: 2025-12-24 17:00:00</small>
            </div>
            
            <div class="form-group">
                <label>조회수</label>
                <input type="number" name="wr_hit" id="wr_hit" min="0" required>
            </div>
            
            <div class="modal-buttons">
                <button type="button" class="btn btn-cancel" onclick="closeModal()">취소</button>
                <button type="submit" class="btn">수정하기</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id, subject, datetime, hit) {
    document.getElementById('wr_id').value = id;
    document.getElementById('wr_subject').value = subject;
    document.getElementById('wr_datetime').value = datetime;
    document.getElementById('wr_hit').value = hit;
    document.getElementById('modal').style.display = 'block';
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}

function deletePost(id, subject) {
    if (confirm('정말 삭제하시겠습니까?\n\n게시물: ' + subject)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = '<input type="hidden" name="action" value="delete">' +
                        '<input type="hidden" name="wr_id" value="' + id + '">';
        document.body.appendChild(form);
        form.submit();
    }
}

// 모달 외부 클릭시 닫기
window.onclick = function(event) {
    const modal = document.getElementById('modal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>
</body>
</html>