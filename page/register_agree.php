<?php
// 그누보드 연동
include_once("../gnuboard/common.php");

// 커스텀 설정 - 중복 정의 방지
if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}

$base_url = '';
$page_title = '회원가입 - 전북우리사이';
$body_class = 'register-page';

// 헤더 포함
include_once('../includes/header.php');
?>
<style>
    .policy-container {
      width: 100%;
      max-width: 1220px;
      margin: 120px auto 100px;
      padding: 50px;
      box-sizing: border-box;
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    }

    .policy-container h1 {
      font-family: 'NexonLv2Gothic', sans-serif;
      color: #2559a8;
      font-size: 2.5rem;
      margin-bottom: 15px;
      text-align: center;
      padding-bottom: 25px;
      border-bottom: 4px solid #2559a8;
    }

    .update-date {
      text-align: center;
      color: #666;
      font-size: 1rem;
      margin: 25px 0 50px 0;
      background-color: #e7f3ff;
      padding: 15px;
      border-radius: 8px;
      border-left: 4px solid #2559a8;
    }

    .policy-container h2 {
      font-family: 'NexonLv2Gothic', sans-serif;
      color: #2559a8;
      font-size: 1.5rem;
      margin-top: 50px;
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 2px solid #e0e0e0;
    }

    .policy-container h2:first-of-type {
      margin-top: 0;
    }

    .terms-box {
      width: 100%;
      height: 250px;
      padding: 20px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      background-color: #f8f9fa;
      font-family: 'NexonLv2Gothic light', sans-serif;
      font-size: 0.95rem;
      line-height: 1.8;
      color: #333;
      overflow-y: scroll;
      margin-bottom: 15px;
      box-sizing: border-box;
    }

    .terms-box::-webkit-scrollbar {
      width: 8px;
    }

    .terms-box::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    .terms-box::-webkit-scrollbar-thumb {
      background: #2559a8;
      border-radius: 10px;
    }

    .terms-box::-webkit-scrollbar-thumb:hover {
      background: #1a4278;
    }

    .checkbox-container {
      margin: 20px 0 40px 0;
      padding: 15px 20px;
      background-color: #e7f3ff;
      border-radius: 8px;
      border-left: 4px solid #2559a8;
    }

    .checkbox-container label {
      font-family: 'NexonLv2Gothic', sans-serif;
      font-size: 1.1rem;
      color: #2559a8;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .checkbox-container input[type="checkbox"] {
      width: 20px;
      height: 20px;
      cursor: pointer;
      accent-color: #2559a8;
    }

    .btn_submit {
      width: 100%;
      max-width: 300px;
      display: block;
      margin: 50px auto 0;
      padding: 18px 40px;
      background: linear-gradient(135deg, #2559a8 0%, #1a4278 100%);
      color: white;
      font-family: 'NexonLv2Gothic', sans-serif;
      font-size: 1.2rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(37, 89, 168, 0.3);
    }

    .btn_submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(37, 89, 168, 0.4);
      background: linear-gradient(135deg, #1a4278 0%, #2559a8 100%);
    }

    .btn_submit:active {
      transform: translateY(0);
    }

    @media screen and (max-width: 800px) {
      footer { display: none; }
      #wrap { display: block; }
      .mlogo { float: left; display: block; width: 30%; }
      .mlogo img { width: 100%; height: auto; }
      .mmenu { display: block; }
      .menu { display: none; }
      .navi.show { display: flex; }
      .hamburger { display: flex; }

      .policy-container {
        margin: 100px auto 50px;
        padding: 30px 20px;
      }

      .policy-container h1 {
        font-size: 2rem;
      }

      .policy-container h2 {
        font-size: 1.3rem;
        margin-top: 35px;
      }

      .terms-box {
        height: 200px;
        font-size: 0.9rem;
        padding: 15px;
      }

      .checkbox-container label {
        font-size: 1rem;
      }

      .btn_submit {
        font-size: 1.1rem;
        padding: 15px 30px;
      }
    }
  </style>
  
  <!-- 회원가입 약관 내용 -->
  <div class="policy-container">
    <h1>회원가입 약관 동의</h1>
    <p class="update-date">📋 회원가입을 위해 필수 약관에 동의해주세요.</p>

    <h2>이용약관 (필수)</h2>
    <div class="terms-box">
      <strong>제1조 (목적)</strong><br>
      본 약관은 전북우리사이(이하 "단체"라 함)가 제공하는 서비스의 이용과 관련하여 단체와 회원 간의 권리, 의무 및 책임사항을 규정함을 목적으로 합니다.<br><br>

      <strong>제2조 (정의)</strong><br>
      1. "서비스"란 단체가 제공하는 자원봉사 관련 모든 서비스를 의미합니다.<br>
      2. "회원"이란 본 약관에 동의하고 단체와 이용계약을 체결한 자를 말합니다.<br><br>

      <strong>제3조 (약관의 효력 및 변경)</strong><br>
      1. 본 약관은 회원가입 시 동의함으로써 효력이 발생합니다.<br>
      2. 단체는 필요한 경우 관련 법령을 위배하지 않는 범위에서 본 약관을 변경할 수 있습니다.<br><br>

      <strong>제4조 (서비스의 제공)</strong><br>
      단체는 다음과 같은 서비스를 제공합니다.<br>
      1. 자원봉사 활동 안내 및 참여 기회 제공<br>
      2. 봉사활동 관련 정보 제공<br>
      3. 기타 단체가 정하는 서비스<br><br>

      <strong>제5조 (회원의 의무)</strong><br>
      1. 회원은 본 약관 및 관련 법령을 준수하여야 합니다.<br>
      2. 회원은 정확한 정보를 제공하여야 하며, 변경사항이 있을 경우 즉시 수정하여야 합니다.<br>
      3. 회원은 타인의 개인정보를 도용하거나 부정한 목적으로 서비스를 이용해서는 안 됩니다.
    </div>

    <div class="checkbox-container">
      <label>
        <input type="checkbox" id="agree1">
        <span>이용약관에 동의합니다. (필수)</span>
      </label>
    </div>

    <h2>개인정보 수집 및 이용 (필수)</h2>
    <div class="terms-box">
      <strong>수집하는 개인정보 항목</strong><br>
      - 필수항목: 성명, 생년월일, 연락처(전화번호, 이메일), 주소<br>
      - 선택항목: 봉사활동 관련 특기사항<br><br>

      <strong>개인정보의 수집 및 이용목적</strong><br>
      1. 자원봉사자 모집 및 관리<br>
      2. 봉사활동 참여 확인 및 관리<br>
      3. 봉사활동 관련 공지사항 전달<br><br>

      <strong>개인정보의 보유 및 이용기간</strong><br>
      - 자원봉사자 정보: 탈퇴 시까지 또는 법령에 따른 보존기간<br>
      - 상담 및 문의 정보: 처리 완료 후 1년<br><br>

      <strong>개인정보 제공 동의 거부권 및 불이익</strong><br>
      귀하는 개인정보 제공 동의를 거부할 권리가 있으나, 필수항목에 대한 동의를 거부할 경우 회원가입 및 서비스 이용이 제한될 수 있습니다.<br><br>

      <strong>개인정보의 파기</strong><br>
      단체는 개인정보 보유기간의 경과, 처리목적 달성 등 개인정보가 불필요하게 되었을 때에는 지체없이 해당 개인정보를 파기합니다.<br>
      - 전자적 파일: 복구 및 재생되지 않도록 안전하게 삭제<br>
      - 종이 문서: 분쇄기로 분쇄하거나 소각<br><br>

      <strong>개인정보 보호책임자</strong><br>
      - 이메일: jeonbukwoorisai@gmail.com<br>
      - 주소: (55011) 전북특별자치도 전주시 완산구 아중로 33, D동 204호
    </div>

    <div class="checkbox-container">
      <label>
        <input type="checkbox" id="agree2">
        <span>개인정보 수집 및 이용에 동의합니다. (필수)</span>
      </label>
    </div>

    <button onclick="goNext()" class="btn_submit">다음 단계로 이동</button>
  </div>

<script>
function goNext(){
  const agree1 = document.getElementById('agree1');
  const agree2 = document.getElementById('agree2');
  
  if(!agree1.checked || !agree2.checked){
    alert("필수 약관에 모두 동의해야 합니다.");
    return;
  }
  location.href = "register.php";
}
</script>

<?php
// 푸터 포함 - 경로 수정!
include_once('../includes/footer.php');
?>