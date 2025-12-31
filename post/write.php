<?php
// 커스텀 설정 (common.php 보다 먼저!)
if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}

// 그누보드 연동 - 절대경로 사용
include_once($_SERVER['DOCUMENT_ROOT']."/gnuboard/common.php");

// 로그인 체크
if (!$is_member) {
    alert('글쓰기 권한이 없습니다.', '/notice/notice.php');
}

// 관리자 권한 체크
$is_admin_user = false;
if (isset($is_admin) && $is_admin == 'super') {
    $is_admin_user = true;
} elseif (isset($member['mb_id']) && $member['mb_id'] == 'admin') {
    $is_admin_user = true;
}

if (!$is_admin_user) {
    alert('관리자만 글을 작성할 수 있습니다.', '/notice/notice.php');
}

// 게시판 테이블명
$bo_table = isset($_GET['bo_table']) ? $_GET['bo_table'] : 'notice';
$wr_id = isset($_GET['wr_id']) ? (int)$_GET['wr_id'] : 0;

// 허용된 게시판 체크
$allowed_boards = array('notice', 'gallery', 'press', 'recruit');
if (!in_array($bo_table, $allowed_boards)) {
    alert('잘못된 접근입니다.', '/notice/notice.php');
}

// 게시판 정보 가져오기
$board = sql_fetch("SELECT * FROM {$g5['board_table']} WHERE bo_table = '{$bo_table}'");

if (!$board) {
    alert('존재하지 않는 게시판입니다.', '/notice/notice.php');
}

$write_table = $g5['write_prefix'].$bo_table;

// 수정 모드인지 확인
$is_edit = false;
$write = array();
$files = array();

if ($wr_id) {
    $is_edit = true;
    
    // 게시글 데이터 가져오기
    $write = sql_fetch("SELECT * FROM {$write_table} WHERE wr_id = '{$wr_id}'");
    
    if (!$write) {
        alert('존재하지 않는 게시글입니다.', '/notice/notice.php');
    }
    
    // 첨부파일 가져오기
    $file_sql = "SELECT * FROM {$g5['board_file_table']} 
                 WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}' 
                 ORDER BY bf_no";
    $file_result = sql_query($file_sql);
    while ($row = sql_fetch_array($file_result)) {
        $files[] = $row;
    }
}

// 게시판별 제목 및 설명
$board_info = array(
    'notice' => array('title' => '공지사항', 'desc' => '전북우리사이의 공지사항을 작성합니다.'),
    'gallery' => array('title' => '포토갤러리', 'desc' => '사진과 함께 활동 내용을 공유합니다.'),
    'press' => array('title' => '언론보도', 'desc' => '언론 보도 자료 링크를 등록합니다.'),
    'recruit' => array('title' => '모집공고', 'desc' => '채용 및 모집 공고를 작성합니다.')
);

$page_title = ($is_edit ? '글 수정' : '글쓰기').' - '.$board_info[$bo_table]['title'];
$body_class = 'board-write';

// 헤더 포함
include_once($_SERVER['DOCUMENT_ROOT']."/includes/header.php");
?>

<style>
.write-container {
  width: 100%;
  max-width: 1220px;
  margin: 50px auto 100px;
  padding: 0 20px;
  box-sizing: border-box;
}

.write-header {
  border-bottom: 2px solid #333;
  padding-bottom: 20px;
  margin-bottom: 40px;
  text-align: center;
}

.write-header h1 {
  font-size: 2rem;
  font-weight: bold;
  color: #333;
  font-family: 'NexonLv2Gothic', sans-serif;
}

.write-header p {
  margin-top: 10px;
  color: #888;
  font-size: 1rem;
}

.board-badge {
  display: inline-block;
  padding: 5px 15px;
  background: #2559a8;
  color: #fff;
  border-radius: 20px;
  font-size: 0.9rem;
  margin-bottom: 10px;
}

.write-form {
  background: #fff;
}

.form-group {
  margin-bottom: 30px;
}

.form-label {
  display: block;
  font-size: 1rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 10px;
  font-family: 'NexonLv2Gothic', sans-serif;
}

.form-label .required {
  color: #dc3545;
  margin-left: 4px;
}

.form-input {
  width: 100%;
  padding: 12px 15px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
  font-family: 'NexonLv2Gothic', sans-serif;
  box-sizing: border-box;
  transition: border-color 0.2s;
}

.form-input:focus {
  outline: none;
  border-color: #2559a8;
}

.form-textarea {
  width: 100%;
  min-height: 400px;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
  font-family: 'NexonLv2Gothic', sans-serif;
  box-sizing: border-box;
  resize: vertical;
  line-height: 1.6;
  transition: border-color 0.2s;
}

.form-textarea:focus {
  outline: none;
  border-color: #2559a8;
}

/* 파일 업로드 */
.file-upload-area {
  border: 2px dashed #ddd;
  border-radius: 8px;
  padding: 20px;
  background: #f8f9fa;
}

.file-input-wrapper {
  margin-bottom: 15px;
}

.file-input-wrapper:last-child {
  margin-bottom: 0;
}

.file-input {
  display: block;
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background: #fff;
  font-size: 0.95rem;
  font-family: 'NexonLv2Gothic', sans-serif;
  cursor: pointer;
}

.file-input::-webkit-file-upload-button {
  padding: 8px 16px;
  background: #2559a8;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-family: 'NexonLv2Gothic', sans-serif;
  margin-right: 10px;
}

.file-input::-webkit-file-upload-button:hover {
  background: #1d4380;
}

/* 기존 첨부파일 */
.existing-files {
  margin-top: 15px;
  padding-top: 15px;
  border-top: 1px solid #e5e5e5;
}

.existing-file-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 0;
}

.existing-file-item input[type="checkbox"] {
  width: 18px;
  height: 18px;
  cursor: pointer;
}

.existing-file-name {
  flex: 1;
  color: #333;
  font-size: 0.95rem;
}

.file-size {
  color: #999;
  font-size: 0.85rem;
}

/* 에디터 도구 모음 */
.editor-toolbar {
  display: flex;
  gap: 5px;
  margin-bottom: 10px;
  padding: 10px;
  background: #f8f9fa;
  border: 1px solid #ddd;
  border-radius: 6px 6px 0 0;
  flex-wrap: wrap;
}

.editor-btn {
  padding: 6px 12px;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  color: #333;
  transition: all 0.2s;
  font-family: 'NexonLv2Gothic', sans-serif;
}

.editor-btn:hover {
  background: #e9ecef;
  border-color: #adb5bd;
}

.editor-btn i {
  font-size: 14px;
}

/* 버튼 그룹 */
.form-buttons {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 50px;
  padding-top: 30px;
  border-top: 1px solid #ddd;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 30px;
  border-radius: 6px;
  text-decoration: none;
  font-size: 16px;
  font-weight: 600;
  transition: all 0.2s;
  border: none;
  cursor: pointer;
  font-family: 'NexonLv2Gothic', sans-serif;
}

.btn-submit {
  background: #2559a8;
  color: #fff;
}

.btn-submit:hover {
  background: #1d4380;
}

.btn-cancel {
  background: #6c757d;
  color: #fff;
}

.btn-cancel:hover {
  background: #5a6268;
}

.form-help {
  font-size: 0.85rem;
  color: #888;
  margin-top: 8px;
  line-height: 1.5;
}

.form-info-box {
  background: #f0f7ff;
  border-left: 4px solid #2559a8;
  padding: 15px;
  margin-bottom: 20px;
  border-radius: 4px;
}

.form-info-box p {
  margin: 5px 0;
  color: #555;
  font-size: 0.95rem;
}

/* 모바일 반응형 */
@media screen and (max-width: 800px) {
  .write-container {
    margin: 30px auto 80px;
    padding: 0 15px;
  }

  .write-header h1 {
    font-size: 1.5rem;
  }

  .form-textarea {
    min-height: 300px;
    font-size: 0.95rem;
  }

  .editor-toolbar {
    padding: 8px;
    gap: 3px;
  }

  .editor-btn {
    padding: 5px 10px;
    font-size: 13px;
  }

  .form-buttons {
    flex-direction: column;
    gap: 10px;
  }

  .btn {
    width: 100%;
    justify-content: center;
    padding: 10px 20px;
  }

  .file-input-wrapper {
    margin-bottom: 10px;
  }
}
</style>

<div class="write-container">
  <!-- 헤더 -->
  <div class="write-header">
    <span class="board-badge"><?php echo $board_info[$bo_table]['title']; ?></span>
    <h1><?php echo $is_edit ? '글 수정' : '글쓰기'; ?></h1>
    <p><?php echo $board_info[$bo_table]['desc']; ?></p>
  </div>

  <!-- 게시판별 안내 메시지 -->
  <?php if ($bo_table == 'gallery') { ?>
  <div class="form-info-box">
    <p><i class="fa-solid fa-circle-info"></i> <strong>포토갤러리 작성 안내</strong></p>
    <p>• 썸네일로 사용될 대표 이미지를 첫 번째 파일로 업로드해주세요.</p>
    <p>• 이미지는 가로 16:9 비율을 권장합니다.</p>
  </div>
  <?php } elseif ($bo_table == 'press') { ?>
  <div class="form-info-box">
    <p><i class="fa-solid fa-circle-info"></i> <strong>언론보도 작성 안내</strong></p>
    <p>• 외부 링크 URL을 반드시 입력해주세요.</p>
    <p>• 원문 기사의 제목과 링크를 정확히 입력해주세요.</p>
  </div>
  <?php } elseif ($bo_table == 'recruit') { ?>
  <div class="form-info-box">
    <p><i class="fa-solid fa-circle-info"></i> <strong>모집공고 작성 안내</strong></p>
    <p>• 대표 이미지를 첫 번째 파일로 업로드해주세요.</p>
    <p>• 모집 기간, 지원 방법 등을 명확히 작성해주세요.</p>
  </div>
  <?php } ?>

  <!-- 글쓰기 폼 -->
  <form name="fwrite" id="fwrite" method="post" action="/post/write_update.php" enctype="multipart/form-data" class="write-form">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table; ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id; ?>">
    <input type="hidden" name="w" value="<?php echo $is_edit ? 'u' : ''; ?>">
    
    <!-- 제목 -->
    <div class="form-group">
      <label class="form-label">
        제목<span class="required">*</span>
      </label>
      <input type="text" name="wr_subject" class="form-input" 
             value="<?php echo $is_edit ? htmlspecialchars($write['wr_subject']) : ''; ?>" 
             placeholder="제목을 입력하세요" required>
    </div>

    <?php if ($bo_table == 'press') { ?>
    <!-- 언론보도: 외부 링크 -->
    <div class="form-group">
      <label class="form-label">
        <i class="fa-solid fa-link"></i> 외부 링크 URL<span class="required">*</span>
      </label>
      <input type="url" name="wr_link1" class="form-input" 
             value="<?php echo $is_edit ? htmlspecialchars($write['wr_link1']) : ''; ?>" 
             placeholder="https://example.com/article" required>
      <p class="form-help">
        <i class="fa-solid fa-circle-info"></i> 
        원문 기사 링크를 입력하세요. (예: https://www.jjan.kr/news/articleView.html?idxno=123456)
      </p>
    </div>
    <?php } ?>

    <!-- 내용 -->
    <div class="form-group">
      <label class="form-label">
        내용<span class="required">*</span>
      </label>
      
      <?php if ($bo_table != 'press') { ?>
      <!-- 일반 게시판: 에디터 툴바 제공 -->
      <div class="editor-toolbar">
        <button type="button" class="editor-btn" onclick="insertTag('strong')" title="굵게">
          <i class="fa-solid fa-bold"></i>
        </button>
        <button type="button" class="editor-btn" onclick="insertTag('em')" title="기울임">
          <i class="fa-solid fa-italic"></i>
        </button>
        <button type="button" class="editor-btn" onclick="insertTag('u')" title="밑줄">
          <i class="fa-solid fa-underline"></i>
        </button>
        <button type="button" class="editor-btn" onclick="insertLink()" title="링크">
          <i class="fa-solid fa-link"></i>
        </button>
        <button type="button" class="editor-btn" onclick="insertImage()" title="이미지">
          <i class="fa-solid fa-image"></i>
        </button>
        <button type="button" class="editor-btn" onclick="insertHeading()" title="제목">
          <i class="fa-solid fa-heading"></i>
        </button>
      </div>
      <?php } ?>
      
      <textarea name="wr_content" id="wr_content" class="form-textarea" 
                placeholder="<?php echo $bo_table == 'press' ? '간단한 요약 또는 소개글을 입력하세요 (선택사항)' : '내용을 입력하세요'; ?>" 
                <?php echo $bo_table == 'press' ? '' : 'required'; ?>><?php echo $is_edit ? $write['wr_content'] : ''; ?></textarea>
      
      <?php if ($bo_table != 'press') { ?>
      <p class="form-help">
        <i class="fa-solid fa-circle-info"></i> 
        HTML 태그를 사용할 수 있습니다. (예: &lt;strong&gt;굵게&lt;/strong&gt;, &lt;br&gt; 줄바꿈 등)
      </p>
      <?php } ?>
    </div>

    <!-- 파일 첨부 -->
    <div class="form-group">
      <label class="form-label">
        <i class="fa-solid fa-paperclip"></i> 첨부파일
        <?php if ($bo_table == 'gallery' || $bo_table == 'recruit') { ?>
        <span class="required">*</span>
        <?php } ?>
      </label>
      <div class="file-upload-area">
        <?php if ($bo_table == 'gallery' || $bo_table == 'recruit') { ?>
        <p style="color: #dc3545; font-size: 0.9rem; margin-bottom: 15px;">
          <i class="fa-solid fa-exclamation-circle"></i> 
          <strong>대표 이미지를 반드시 첫 번째 파일로 업로드해주세요.</strong>
        </p>
        <?php } ?>
        
        <div class="file-input-wrapper">
          <input type="file" name="bf_file[]" class="file-input" 
                 <?php echo ($bo_table == 'gallery' || $bo_table == 'recruit') ? 'required' : ''; ?>
                 accept="image/*">
        </div>
        <div class="file-input-wrapper">
          <input type="file" name="bf_file[]" class="file-input" accept="image/*">
        </div>
        <div class="file-input-wrapper">
          <input type="file" name="bf_file[]" class="file-input" accept="image/*">
        </div>
        
        <?php if ($is_edit && count($files) > 0) { ?>
        <div class="existing-files">
          <p style="font-size: 0.9rem; color: #666; margin-bottom: 10px;">
            <strong>기존 첨부파일</strong> (삭제할 파일을 선택하세요)
          </p>
          <?php foreach ($files as $file) { ?>
          <div class="existing-file-item">
            <input type="checkbox" name="bf_file_del[]" value="<?php echo $file['bf_no']; ?>" id="file_del_<?php echo $file['bf_no']; ?>">
            <label for="file_del_<?php echo $file['bf_no']; ?>" class="existing-file-name">
              <?php echo htmlspecialchars($file['bf_source']); ?>
              <span class="file-size">
                (<?php echo number_format($file['bf_filesize'] / 1024, 1); ?>KB)
              </span>
            </label>
          </div>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
      <p class="form-help">
        <i class="fa-solid fa-circle-info"></i> 
        최대 3개의 이미지 파일을 첨부할 수 있습니다. (파일당 최대 10MB)
      </p>
    </div>

    <!-- 버튼 -->
    <div class="form-buttons">
      <button type="submit" class="btn btn-submit">
        <i class="fa-solid fa-check"></i>
        <?php echo $is_edit ? '수정하기' : '등록하기'; ?>
      </button>
      <a href="/<?php echo $bo_table; ?>/<?php echo $bo_table; ?>.php" class="btn btn-cancel">
        <i class="fa-solid fa-xmark"></i>
        취소
      </a>
    </div>
  </form>
</div>

<script>
// 폼 제출 전 검증
document.getElementById('fwrite').addEventListener('submit', function(e) {
    const boTable = '<?php echo $bo_table; ?>';
    const subject = document.querySelector('input[name="wr_subject"]').value.trim();
    const content = document.querySelector('textarea[name="wr_content"]').value.trim();
    
    if (!subject) {
        alert('제목을 입력해주세요.');
        e.preventDefault();
        return false;
    }
    
    // 언론보도가 아닌 경우 내용 필수
    if (boTable !== 'press' && !content) {
        alert('내용을 입력해주세요.');
        e.preventDefault();
        return false;
    }
    
    // 언론보도의 경우 링크 필수
    if (boTable === 'press') {
        const link = document.querySelector('input[name="wr_link1"]').value.trim();
        if (!link) {
            alert('외부 링크 URL을 입력해주세요.');
            e.preventDefault();
            return false;
        }
    }
    
    // 갤러리, 모집공고는 이미지 필수
    if ((boTable === 'gallery' || boTable === 'recruit') && !<?php echo $is_edit ? 'true' : 'false'; ?>) {
        const fileInput = document.querySelector('input[name="bf_file[]"]');
        if (!fileInput.files || !fileInput.files[0]) {
            alert('대표 이미지를 업로드해주세요.');
            e.preventDefault();
            return false;
        }
    }
    
    return true;
});

// 간단한 에디터 기능
function insertTag(tag) {
    const textarea = document.getElementById('wr_content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    if (selectedText) {
        const newText = `<${tag}>${selectedText}</${tag}>`;
        textarea.value = textarea.value.substring(0, start) + newText + textarea.value.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + tag.length + 2, start + tag.length + 2 + selectedText.length);
    } else {
        alert('텍스트를 먼저 선택해주세요.');
    }
}

function insertLink() {
    const textarea = document.getElementById('wr_content');
    const url = prompt('링크 URL을 입력하세요:', 'https://');
    
    if (url) {
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selectedText = textarea.value.substring(start, end) || '링크 텍스트';
        
        const newText = `<a href="${url}" target="_blank">${selectedText}</a>`;
        textarea.value = textarea.value.substring(0, start) + newText + textarea.value.substring(end);
        textarea.focus();
    }
}

function insertImage() {
    const textarea = document.getElementById('wr_content');
    const url = prompt('이미지 URL을 입력하세요:', 'https://');
    
    if (url) {
        const start = textarea.selectionStart;
        const newText = `<img src="${url}" alt="이미지" style="max-width: 100%; height: auto;">`;
        textarea.value = textarea.value.substring(0, start) + newText + textarea.value.substring(start);
        textarea.focus();
    }
}

function insertHeading() {
    const textarea = document.getElementById('wr_content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    if (selectedText) {
        const newText = `<h3>${selectedText}</h3>`;
        textarea.value = textarea.value.substring(0, start) + newText + textarea.value.substring(end);
        textarea.focus();
    } else {
        alert('제목으로 만들 텍스트를 먼저 선택해주세요.');
    }
}
</script>

<?php
// 푸터 포함
include_once($_SERVER['DOCUMENT_ROOT']."/includes/footer.php");
?>