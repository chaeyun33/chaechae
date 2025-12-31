<?php
// 그누보드 연동
$gnuboard_path = $_SERVER['DOCUMENT_ROOT']."/gnuboard/common.php";

if (file_exists($gnuboard_path)) {
    include_once($gnuboard_path);
} else {
    die("그누보드를 찾을 수 없습니다.");
}

// 관리자 체크
if (!$is_admin) {
    die("관리자만 접근 가능합니다.");
}

// 테이블 생성 SQL
$sql = "CREATE TABLE IF NOT EXISTS `volunteer_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mb_id` varchar(20) NOT NULL COMMENT '회원 아이디',
  `activity_name` varchar(255) NOT NULL COMMENT '활동명',
  `activity_date` date NOT NULL COMMENT '활동일',
  `volunteer_hours` decimal(5,1) NOT NULL DEFAULT 0.0 COMMENT '봉사시간',
  `description` text COMMENT '활동 내용',
  `status` varchar(20) DEFAULT 'complete' COMMENT '상태',
  `created_at` datetime NOT NULL COMMENT '등록일시',
  PRIMARY KEY (`id`),
  KEY `mb_id` (`mb_id`),
  KEY `activity_date` (`activity_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

// SQL 실행
if (sql_query($sql)) {
    echo "<h1 style='color: green;'>✅ 테이블 생성 완료!</h1>";
    echo "<p>volunteer_records 테이블이 성공적으로 생성되었습니다.</p>";
    echo "<p><a href='/admin/volunteer_manage.php'>봉사시간 관리 페이지로 이동</a></p>";
} else {
    echo "<h1 style='color: red;'>❌ 오류 발생</h1>";
    echo "<p>오류 내용: " . sql_error() . "</p>";
}
?>
```

3. **파일 → 다른 이름으로 저장**
   - 파일명: `create_table.php`
   - 저장 위치: 바탕화면
   - **인코딩: UTF-8** (중요!)

### 2단계: FileZilla 접속

1. **FileZilla 실행**
2. 상단에 접속 정보 입력:
   - **호스트(H)**: `112.175.185.144`
   - **사용자명(U)**: `jeonbukwoorisai`
   - **비밀번호(W)**: (FTP 비밀번호 입력)
   - **포트(P)**: `21`
3. **빠른연결** 버튼 클릭

### 3단계: 파일 업로드

1. FileZilla 연결되면:
   - **왼쪽**: 내 컴퓨터 (바탕화면으로 이동)
   - **오른쪽**: 서버 (`/html/` 폴더로 이동)

2. 바탕화면의 `create_table.php` 파일을 **오른쪽 /html/ 폴더**로 드래그

### 4단계: 브라우저에서 실행

브라우저 주소창에 입력:
```
https://jeonbukwoorisai.co.kr/create_table.php