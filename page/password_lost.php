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
$page_title = '비밀번호 찾기 - 전북우리사이';
$body_class = 'password-lost-page';

// 헤더 포함
include_once('../includes/header.php');
?>

<style>
    .policy-container {
      width: 100%;
      max-width: 600px;
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
      margin: 25px 0 30px 0;
      background-color: #e7f3ff;
      padding: 15px;
      border-radius: 8px;
      border-left: 4px solid #2559a8;
    }

    .contact-box {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      border: 2px solid #2559a8;
      border-radius: 15px;
      padding: 40px;
      margin: 30px 0;
      text-align: center;
    }

    .contact-box h2 {
      font-family: 'NexonLv2Gothic', sans-serif;
      color: #2559a8;
      font-size: 1.8rem;
      margin-bottom: 20px;
    }

    .contact-box p {
      font-family: 'NexonLv2Gothic light', sans-serif;
      color: #666;
      font-size: 1.1rem;
      line-height: 1.8;
      margin-bottom: 10px;
    }

    .contact-info {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin: 30px 0;
    }

    .contact-item {
      background-color: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      display: flex;
      align-items: center;
      gap: 20px;
      transition: all 0.3s ease;
    }

    .contact-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 15px rgba(37, 89, 168, 0.2);
    }

    .contact-icon {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #2559a8 0%, #1a4278 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      color: white;
      flex-shrink: 0;
    }

    .contact-details {
      text-align: left;
      flex: 1;
    }

    .contact-label {
      font-family: 'NexonLv2Gothic', sans-serif;
      color: #2559a8;
      font-size: 1rem;
      margin-bottom: 5px;
      font-weight: 600;
    }

    .contact-value {
      font-family: 'NexonLv2Gothic light', sans-serif;
      color: #333;
      font-size: 1.3rem;
      font-weight: 500;
    }

    .contact-value a {
      color: #333;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .contact-value a:hover {
      color: #2559a8;
    }

    .info-box {
      background-color: #fff9e6;
      border-left: 4px solid #f39c12;
      padding: 20px;
      border-radius: 8px;
      margin: 30px 0;
      font-family: 'NexonLv2Gothic light', sans-serif;
      color: #666;
      font-size: 0.95rem;
      line-height: 1.6;
    }

    .info-box strong {
      display: block;
      margin-bottom: 10px;
      color: #333;
      font-size: 1.05rem;
    }

    .info-box ul {
      margin: 10px 0 0 0;
      padding-left: 20px;
    }

    .info-box li {
      margin: 8px 0;
    }

    .divider {
      text-align: center;
      margin: 40px 0;
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

    .login-link {
      text-align: center;
      padding: 25px;
      background-color: #f8f9fa;
      border-radius: 10px;
      border: 2px dashed #e0e0e0;
    }

    .login-link p {
      font-family: 'NexonLv2Gothic light', sans-serif;
      color: #666;
      margin-bottom: 15px;
      font-size: 1rem;
    }

    .login-link a {
      display: inline-block;
      padding: 12px 30px;
      font-family: 'NexonLv2Gothic', sans-serif;
      font-size: 1rem;
      color: #2559a8;
      text-decoration: none;
      border: 2px solid #2559a8;
      border-radius: 8px;
      transition: all 0.3s ease;
      margin: 0 5px;
    }

    .login-link a:hover {
      background-color: #2559a8;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(37, 89, 168, 0.3);
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

      .contact-box {
        padding: 25px 20px;
      }

      .contact-box h2 {
        font-size: 1.5rem;
      }

      .contact-box p {
        font-size: 1rem;
      }

      .contact-item {
        flex-direction: column;
        text-align: center;
      }

      .contact-details {
        text-align: center;
      }

      .contact-value {
        font-size: 1.1rem;
      }

      .login-link a {
        display: block;
        margin: 10px 0;
      }
    }
  </style>

  <!-- 비밀번호 찾기 -->
  <div class="policy-container">
    <h1>비밀번호 찾기</h1>
    <p class="update-date">🔐 비밀번호를 잊으셨나요? 아래 연락처로 문의해주세요</p>

    <div class="contact-box">
      <h2>📞 관리자 연락처</h2>
      <p>비밀번호  필요하신 경우<br>아래 연락처로 문의해주시면 빠르게 도와드리겠습니다.</p>
    </div>

    <div class="contact-info">
      <div class="contact-item">
        <div class="contact-icon">📧</div>
        <div class="contact-details">
          <div class="contact-label">이메일 문의</div>
          <div class="contact-value">
            <a href="mailto:admin@jeonbukwoorisai.com">jeonbukwoorisai@gmail.com</a>
          </div>
        </div>
      </div>

      <div class="contact-item">
        <div class="contact-icon">⏰</div>
        <div class="contact-details">
          <div class="contact-label">운영 시간</div>
          <div class="contact-value">평일 09:00 - 18:00</div>
        </div>
      </div>
    </div>

    <div class="info-box">
      <strong>📌 비밀번호 재설정 안내</strong>
      <ul>
        <li>위 연락처로 <strong>아이디</strong>와 <strong>가입 시 이메일</strong>을 알려주세요.</li>
        <li>본인 확인 후 가입하실 때 입력하셨던 비밀번호를 알려드립니다.</li>
        <li>평일 운영시간 내에 빠르게 처리해드리겠습니다.</li>
      </ul>
    </div>

    <div class="divider">또는</div>

    <div class="login-link">
      <p>비밀번호가 기억나셨나요?</p>
      <a href="/page/login.php">로그인 하기</a>
      <a href="/page/register.php">회원가입 하기</a>
    </div>
  </div>

<?php
// 푸터 포함
include_once('../includes/footer.php');
?>