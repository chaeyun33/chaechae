<?php
// 그누보드 연동
include_once("../gnuboard/common.php");

// 커스텀 설정 - ⭐ 중복 정의 방지
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
      max-width: 800px;
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

    .register-form {
      margin-top: 30px;
    }

    .form-row {
      margin-bottom: 30px;
    }

    .form-row label {
      display: block;
      font-family: 'NexonLv2Gothic', sans-serif;
      color: #2559a8;
      font-size: 1.1rem;
      margin-bottom: 10px;
      font-weight: 600;
    }

    .form-row label .required {
      color: #e74c3c;
      margin-left: 3px;
    }

    .form-row input[type="text"],
    .form-row input[type="password"],
    .form-row input[type="email"],
    .form-row input[type="tel"] {
      width: 100%;
      padding: 15px 20px;
      font-family: 'NexonLv2Gothic light', sans-serif;
      font-size: 1rem;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      box-sizing: border-box;
      transition: all 0.3s ease;
      background-color: #f8f9fa;
    }

    .form-row input:focus {
      outline: none;
      border-color: #2559a8;
      background-color: white;
      box-shadow: 0 0 0 3px rgba(37, 89, 168, 0.1);
    }

    .form-row input::placeholder {
      color: #999;
      font-size: 0.95rem;
    }

    .form-helper {
      font-family: 'NexonLv2Gothic light', sans-serif;
      font-size: 0.9rem;
      color: #666;
      margin-top: 8px;
      display: block;
    }

    .btn-group {
      display: flex;
      gap: 15px;
      margin-top: 50px;
    }

    .btn_submit,
    .btn_cancel {
      flex: 1;
      padding: 18px 40px;
      font-family: 'NexonLv2Gothic', sans-serif;
      font-size: 1.2rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn_submit {
      background: linear-gradient(135deg, #2559a8 0%, #1a4278 100%);
      color: white;
      box-shadow: 0 4px 15px rgba(37, 89, 168, 0.3);
    }

    .btn_submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(37, 89, 168, 0.4);
      background: linear-gradient(135deg, #1a4278 0%, #2559a8 100%);
    }

    .btn_cancel {
      background: #f8f9fa;
      color: #666;
      border: 2px solid #e0e0e0;
    }

    .btn_cancel:hover {
      background: #e0e0e0;
      color: #333;
    }

    .btn_submit:active,
    .btn_cancel:active {
      transform: translateY(0);
    }

    .password-strength {
      margin-top: 8px;
      height: 4px;
      background-color: #e0e0e0;
      border-radius: 2px;
      overflow: hidden;
      display: none;
    }

    .password-strength-bar {
      height: 100%;
      width: 0%;
      transition: all 0.3s ease;
      background-color: #e74c3c;
    }

    .password-strength.visible {
      display: block;
    }

    .password-strength-text {
      font-size: 0.85rem;
      margin-top: 5px;
      display: none;
    }

    .password-strength-text.visible {
      display: block;
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

      .form-row {
        margin-bottom: 25px;
      }

      .form-row label {
        font-size: 1rem;
      }

      .form-row input[type="text"],
      .form-row input[type="password"],
      .form-row input[type="email"],
      .form-row input[type="tel"] {
        padding: 12px 15px;
        font-size: 0.95rem;
      }

      .btn-group {
        flex-direction: column;
        gap: 10px;
      }

      .btn_submit,
      .btn_cancel {
        width: 100%;
        padding: 15px 30px;
        font-size: 1.1rem;
      }
    }
  </style>

  <!-- 회원가입 폼 -->
  <div class="policy-container">
    <h1>회원가입</h1>
    <p class="update-date">✏️ 아래 정보를 입력하여 회원가입을 완료해주세요.</p>

    <form action="/page/register_ok.php" method="post" class="register-form" onsubmit="return validateForm()">

      <div class="form-row">
        <label>아이디 <span class="required">*</span></label>
        <input type="text" name="mb_id" id="mb_id" placeholder="영문, 숫자 조합 4-20자" required>
        <span class="form-helper">영문자와 숫자를 조합하여 4-20자로 입력해주세요.</span>
      </div>

      <div class="form-row">
        <label>비밀번호 <span class="required">*</span></label>
        <input type="password" name="mb_password" id="mb_password" placeholder="영문, 숫자, 특수문자 조합 8자 이상" required oninput="checkPasswordStrength()">
        <div class="password-strength" id="password-strength">
          <div class="password-strength-bar" id="password-strength-bar"></div>
        </div>
        <span class="form-helper password-strength-text" id="password-strength-text"></span>
      </div>

      <div class="form-row">
        <label>비밀번호 확인 <span class="required">*</span></label>
        <input type="password" name="mb_password_confirm" id="mb_password_confirm" placeholder="비밀번호를 다시 입력해주세요" required>
        <span class="form-helper" id="password-match-text"></span>
      </div>

      <div class="form-row">
        <label>이름 <span class="required">*</span></label>
        <input type="text" name="mb_name" id="mb_name" placeholder="실명을 입력해주세요" required>
      </div>

      <div class="form-row">
        <label>닉네임 <span class="required">*</span></label>
        <input type="text" name="mb_nick" id="mb_nick" placeholder="사용하실 닉네임을 입력해주세요" required>
        <span class="form-helper">커뮤니티에서 사용될 닉네임입니다.</span>
      </div>

      <div class="form-row">
        <label>이메일 <span class="required">*</span></label>
        <input type="email" name="mb_email" id="mb_email" placeholder="example@email.com" required>
        <span class="form-helper">비밀번호 찾기 등에 사용됩니다.</span>
      </div>

      <div class="form-row">
        <label>휴대폰</label>
        <input type="tel" name="mb_hp" id="mb_hp" placeholder="010-1234-5678">
        <span class="form-helper">선택사항입니다. 봉사활동 안내에 사용될 수 있습니다.</span>
      </div>

      <div class="btn-group">
        <button type="button" class="btn_cancel" onclick="history.back()">이전으로</button>
        <button type="submit" class="btn_submit">회원가입 완료</button>
      </div>

    </form>
  </div>

<script>
// 비밀번호 강도 체크
function checkPasswordStrength() {
  const password = document.getElementById('mb_password').value;
  const strengthBar = document.getElementById('password-strength-bar');
  const strengthText = document.getElementById('password-strength-text');
  const strengthContainer = document.getElementById('password-strength');
  
  if (password.length === 0) {
    strengthContainer.classList.remove('visible');
    strengthText.classList.remove('visible');
    return;
  }
  
  strengthContainer.classList.add('visible');
  strengthText.classList.add('visible');
  
  let strength = 0;
  
  if (password.length >= 8) strength += 25;
  if (password.match(/[a-z]/)) strength += 25;
  if (password.match(/[A-Z]/)) strength += 25;
  if (password.match(/[0-9]/)) strength += 15;
  if (password.match(/[^a-zA-Z0-9]/)) strength += 10;
  
  strengthBar.style.width = strength + '%';
  
  if (strength < 40) {
    strengthBar.style.backgroundColor = '#e74c3c';
    strengthText.textContent = '⚠️ 약한 비밀번호입니다.';
    strengthText.style.color = '#e74c3c';
  } else if (strength < 70) {
    strengthBar.style.backgroundColor = '#f39c12';
    strengthText.textContent = '⚡ 보통 수준의 비밀번호입니다.';
    strengthText.style.color = '#f39c12';
  } else {
    strengthBar.style.backgroundColor = '#27ae60';
    strengthText.textContent = '✓ 강력한 비밀번호입니다.';
    strengthText.style.color = '#27ae60';
  }
}

// 비밀번호 확인 체크
document.getElementById('mb_password_confirm').addEventListener('input', function() {
  const password = document.getElementById('mb_password').value;
  const confirmPassword = this.value;
  const matchText = document.getElementById('password-match-text');
  
  if (confirmPassword.length === 0) {
    matchText.textContent = '';
    return;
  }
  
  if (password === confirmPassword) {
    matchText.textContent = '✓ 비밀번호가 일치합니다.';
    matchText.style.color = '#27ae60';
  } else {
    matchText.textContent = '✗ 비밀번호가 일치하지 않습니다.';
    matchText.style.color = '#e74c3c';
  }
});

// 폼 유효성 검사
function validateForm() {
  const password = document.getElementById('mb_password').value;
  const confirmPassword = document.getElementById('mb_password_confirm').value;
  const userId = document.getElementById('mb_id').value;
  
  // 아이디 검증
  if (userId.length < 4 || userId.length > 20) {
    alert('아이디는 4-20자로 입력해주세요.');
    return false;
  }
  
  // 비밀번호 검증
  if (password.length < 8) {
    alert('비밀번호는 8자 이상 입력해주세요.');
    return false;
  }
  
  // 비밀번호 일치 확인
  if (password !== confirmPassword) {
    alert('비밀번호가 일치하지 않습니다.');
    return false;
  }
  
  return true;
}
</script>

<?php
// 푸터 포함
include_once('../includes/footer.php');
?>