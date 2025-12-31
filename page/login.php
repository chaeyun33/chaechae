<?php
// 그누보드 연동
include_once("../gnuboard/common.php");

// 이미 로그인된 경우 메인으로 리다이렉트
if ($is_member) {
    goto_url('/');
}

// 커스텀 설정 - 중복 정의 방지
if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}

$base_url = '';
$page_title = '로그인 - 전북우리사이';
$body_class = 'login-page';

// 헤더 포함
include_once('../includes/header.php');

// 카카오 로그인 URL 직접 생성
$kakao_rest_api_key = 'a7d604d5ea37690cd056095003adf0ff';  // ← 이 부분 수정
$kakao_redirect_uri = 'https://jeonbukwoorisai.co.kr/gnuboard/bbs/kakao_callback.php';

$kakao_params = array(
    'client_id' => $kakao_rest_api_key,
    'redirect_uri' => $kakao_redirect_uri,
    'response_type' => 'code'
);

$kakao_login_url = 'https://kauth.kakao.com/oauth/authorize?' . http_build_query($kakao_params);

// 로그인 후 이동할 URL
$url = isset($_GET['url']) ? $_GET['url'] : '/';
?>

<style>
    .policy-container {
      width: 100%;
      max-width: 500px;
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

    .login-form {
      margin-top: 30px;
    }

    .form-row {
      margin-bottom: 25px;
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
    .form-row input[type="password"] {
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

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 20px 0 30px 0;
      font-family: 'NexonLv2Gothic light', sans-serif;
      font-size: 0.95rem;
    }

    .form-options label {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      color: #666;
    }

    .form-options input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: #2559a8;
    }

    .find-links {
      display: flex;
      gap: 15px;
    }

    .find-links a {
      color: #2559a8;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .find-links a:hover {
      color: #1a4278;
      text-decoration: underline;
    }

    .btn_submit {
      width: 100%;
      padding: 18px 40px;
      font-family: 'NexonLv2Gothic', sans-serif;
      font-size: 1.2rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
      background: linear-gradient(135deg, #2559a8 0%, #1a4278 100%);
      color: white;
      box-shadow: 0 4px 15px rgba(37, 89, 168, 0.3);
      margin-bottom: 15px;
    }

    .btn_submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(37, 89, 168, 0.4);
      background: linear-gradient(135deg, #1a4278 0%, #2559a8 100%);
    }

    .btn_submit:active {
      transform: translateY(0);
    }

    .divider {
      text-align: center;
      margin: 30px 0;
      position: relative;
      color: #999;
      font-family: 'NexonLv2Gothic light', sans-serif;
      font-size: 0.9rem;
    }

    .divider::before,
    .divider::after {
      content: '';
      position: absolute;
      top: 50%;
      width: 40%;
      height: 1px;
      background-color: #e0e0e0;
    }

    .divider::before {
      left: 0;
    }

    .divider::after {
      right: 0;
    }

    .btn-kakao {
      width: 100%;
      padding: 18px 40px;
      background: #FEE500;
      color: #000000;
      border: none;
      border-radius: 10px;
      font-size: 1.2rem;
      font-family: 'NexonLv2Gothic', sans-serif;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      text-decoration: none;
      box-shadow: 0 4px 15px rgba(254, 229, 0, 0.3);
      margin-bottom: 15px;
    }

    .btn-kakao:hover {
      background: #FDD835;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(254, 229, 0, 0.4);
    }

    .btn-kakao svg {
      width: 24px;
      height: 24px;
      flex-shrink: 0;
    }

    .register-link {
      text-align: center;
      padding: 20px;
      background-color: #f8f9fa;
      border-radius: 10px;
      border: 2px dashed #e0e0e0;
      margin-top: 20px;
    }

    .register-link p {
      font-family: 'NexonLv2Gothic light', sans-serif;
      color: #666;
      margin-bottom: 15px;
      font-size: 1rem;
    }

    .register-link a {
      display: inline-block;
      padding: 12px 30px;
      font-family: 'NexonLv2Gothic', sans-serif;
      font-size: 1rem;
      color: #2559a8;
      text-decoration: none;
      border: 2px solid #2559a8;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .register-link a:hover {
      background-color: #2559a8;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(37, 89, 168, 0.3);
    }

    .error-message {
      background-color: #fff3f3;
      border-left: 4px solid #e74c3c;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-family: 'NexonLv2Gothic light', sans-serif;
      color: #e74c3c;
      font-size: 0.95rem;
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
        margin-bottom: 20px;
      }

      .form-row label {
        font-size: 1rem;
      }

      .form-row input[type="text"],
      .form-row input[type="password"] {
        padding: 12px 15px;
        font-size: 0.95rem;
      }

      .form-options {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
      }

      .btn_submit {
        padding: 15px 30px;
        font-size: 1.1rem;
      }

      .btn-kakao {
        padding: 16px;
        font-size: 1.1rem;
      }
    }
</style>

<div class="policy-container">
  <h1>로그인</h1>
  <p class="update-date">👋 전북우리사이에 오신 것을 환영합니다!</p>

  <?php if(isset($_GET['error'])): ?>
    <div class="error-message">
      ⚠️ 아이디 또는 비밀번호가 일치하지 않습니다.
    </div>
  <?php endif; ?>

  <form name="flogin" action="<?php echo G5_BBS_URL; ?>/login_check.php" method="post" class="login-form">
    <input type="hidden" name="url" value="<?php echo $url; ?>">

    <div class="form-row">
      <label for="login_id">아이디 <span class="required">*</span></label>
      <input type="text" name="mb_id" id="login_id" placeholder="아이디를 입력하세요" required autofocus>
    </div>

    <div class="form-row">
      <label for="login_pw">비밀번호 <span class="required">*</span></label>
      <input type="password" name="mb_password" id="login_pw" placeholder="비밀번호를 입력하세요" required>
    </div>

    <div class="form-options">
      <label>
        <input type="checkbox" name="auto_login" value="1">
        자동 로그인
      </label>
      <div class="find-links">
        <a href="/page/password_lost.php">비밀번호 찾기</a>
      </div>
    </div>

    <button type="submit" class="btn_submit">로그인</button>
    
    <div class="divider">또는</div>

    <!--a href="<?php echo $kakao_login_url; ?>" class="btn-kakao">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
        <path d="M12 3C6.372 3 1.8 6.702 1.8 11.2c0 2.827 1.875 5.307 4.688 6.72-.195.708-.635 2.443-.736 2.825-.124.467.172.461.363.335.148-.098 2.406-1.634 3.338-2.269.447.062.902.094 1.347.094 5.628 0 10.2-3.702 10.2-8.264S17.628 3 12 3z" fill="currentColor"/>
      </svg>
      카카오 계정으로 로그인
  </a-->
  </form>

  <div class="register-link">
    <p>아직 회원이 아니신가요?</p>
    <a href="/page/register.php">회원가입 하기</a>
  </div>
</div>

<script>
document.getElementById('login_pw').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    document.flogin.submit();
  }
});
</script>

<?php
include_once('../includes/footer.php');
?>