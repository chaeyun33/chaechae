<?php
$sub_menu = "200100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

check_demo();

if ($_POST['act_button'] == "선택삭제") {
    auth_check_menu($auth, $sub_menu, 'd');
    
    $count = count($_POST['chk']);
    if (!$count) {
        alert('선택된 회원이 없습니다.');
    }
    
    for ($i=0; $i<$count; $i++) {
        $k = $_POST['chk'][$i];
        $mb_id = $_POST['mb_id'][$k];
        
        // 자기 자신은 삭제 불가
        if ($mb_id == $member['mb_id']) {
            alert('자기 자신은 삭제할 수 없습니다.');
        }
        
        // 최고관리자가 아니면 자신보다 높은 권한의 회원은 삭제 불가
        if ($is_admin != 'super') {
            $row = sql_fetch("SELECT mb_level FROM {$g5['member_table']} WHERE mb_id = '$mb_id'");
            if ($row['mb_level'] >= $member['mb_level']) {
                alert('자신보다 권한이 높거나 같은 회원은 삭제할 수 없습니다.');
            }
        }
        
        // 회원삭제
        $result = sql_query(" DELETE FROM {$g5['member_table']} WHERE mb_id = '$mb_id' ");
        
        // 그룹접근 삭제
        sql_query(" DELETE FROM {$g5['group_member_table']} WHERE mb_id = '$mb_id' ");
        
        // 포인트 삭제
        sql_query(" DELETE FROM {$g5['point_table']} WHERE mb_id = '$mb_id' ");
        
        // 쪽지 삭제
        sql_query(" DELETE FROM {$g5['memo_table']} WHERE me_recv_mb_id = '$mb_id' ");
        
        // 스크랩 삭제
        sql_query(" DELETE FROM {$g5['scrap_table']} WHERE mb_id = '$mb_id' ");
        
        // 투표 삭제
        sql_query(" DELETE FROM {$g5['poll_etc_table']} WHERE mb_id = '$mb_id' ");
        
        // 자동저장 삭제
        sql_query(" DELETE FROM {$g5['autosave_table']} WHERE mb_id = '$mb_id' ");
        
        // 회원이미지 삭제
        if (file_exists(G5_DATA_PATH.'/member/'.$mb_id)) {
            @unlink(G5_DATA_PATH.'/member/'.$mb_id);
        }
        if (file_exists(G5_DATA_PATH.'/member_image/'.$mb_id)) {
            @unlink(G5_DATA_PATH.'/member_image/'.$mb_id);
        }
    }
    
    alert($count . '건의 자료가 삭제되었습니다.', './member_list.php?'.$qstr);
    
} else if ($_POST['act_button'] == "선택수정") {
    
    $count = count($_POST['chk']);
    if (!$count) {
        alert('선택된 회원이 없습니다.');
    }
    
    for ($i=0; $i<$count; $i++) {
        $k = $_POST['chk'][$i];
        $mb_id = $_POST['mb_id'][$k];
        
        // 본인인증
        $mb_certify = isset($_POST['mb_certify'][$k]) ? $_POST['mb_certify'][$k] : '';
        
        // 정보공개
        $mb_open = isset($_POST['mb_open'][$k]) ? 1 : 0;
        
        // 메일수신
        $mb_mailling = isset($_POST['mb_mailling'][$k]) ? 1 : 0;
        
        // SMS수신
        $mb_sms = isset($_POST['mb_sms'][$k]) ? 1 : 0;
        
        // 성인인증
        $mb_adult = isset($_POST['mb_adult'][$k]) ? 1 : 0;
        
        // 접근차단
        $mb_intercept_date = '';
        if (isset($_POST['mb_intercept_date'][$k])) {
            $mb_intercept_date = $_POST['mb_intercept_date'][$k];
        }
        
        // 권한
        $mb_level = isset($_POST['mb_level'][$k]) ? (int)$_POST['mb_level'][$k] : 0;
        
        // 최고관리자가 아니면 자신보다 높은 권한으로 수정 불가
        if ($is_admin != 'super') {
            if ($mb_level > $member['mb_level']) {
                alert('자신보다 높은 권한으로 수정할 수 없습니다.');
            }
        }
        
        $sql = " UPDATE {$g5['member_table']} 
                 SET mb_certify = '$mb_certify',
                     mb_open = '$mb_open',
                     mb_mailling = '$mb_mailling',
                     mb_sms = '$mb_sms',
                     mb_adult = '$mb_adult',
                     mb_intercept_date = '$mb_intercept_date',
                     mb_level = '$mb_level'
                 WHERE mb_id = '$mb_id' ";
        sql_query($sql);
    }
    
    alert($count . '건의 자료가 수정되었습니다.', './member_list.php?'.$qstr);
    
} else {
    alert('올바른 요청이 아닙니다.');
}
?>