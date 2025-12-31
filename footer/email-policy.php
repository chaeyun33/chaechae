<?php
// 그누보드 연동
include_once("../gnuboard/common.php");

// 커스텀 설정
if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}
$base_url = '..';  // 상위 폴더로 이동
$page_title = '이메일무단수집거부 - 전북우리사이';
$body_class = '';

// 헤더 포함 (경로 수정)
include_once('../includes/header.php');
?>

<style>
  .policy-container {
    width: 100%;
    max-width: 1220px;
    margin: 120px auto 80px;
    padding: 60px;
    box-sizing: border-box;
    background-color: white;
    border-radius: 15px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
  }

  .policy-container h1 {
    font-family: 'NexonLv2Gothic', sans-serif;
    color: #2559a8;
    font-size: 2.5rem;
    margin-bottom: 20px;
    text-align: center;
    padding-bottom: 30px;
    border-bottom: 3px solid #2559a8;
  }

  .intro {
    background-color: #f8f9fa;
    padding: 30px;
    border-radius: 10px;
    margin: 40px 0;
    border-left: 4px solid #6c757d;
  }

  .intro p {
    font-family: 'NexonLv2Gothic light', sans-serif;
    color: #333;
    font-size: 1.05rem;
    line-height: 1.9;
    margin: 0;
  }

  .policy-container h2 {
    font-family: 'NexonLv2Gothic', sans-serif;
    color: #2559a8;
    font-size: 1.5rem;
    margin-top: 60px;
    margin-bottom: 25px;
    padding-bottom: 12px;
    border-bottom: 2px solid #e0e0e0;
  }

  .policy-container h2:first-of-type {
    margin-top: 40px;
  }

  .policy-container p {
    font-family: 'NexonLv2Gothic light', sans-serif;
    color: #333;
    margin-bottom: 20px;
    font-size: 1.05rem;
    line-height: 1.9;
  }

  .law-section {
    background-color: #f8f9fa;
    padding: 35px;
    border-radius: 10px;
    margin: 30px 0;
    border-left: 4px solid #2559a8;
  }

  .law-title {
    font-family: 'NexonLv2Gothic', sans-serif;
    font-weight: bold;
    color: #2559a8;
    font-size: 1.15rem;
    margin-bottom: 20px;
  }

  .law-article {
    font-family: 'NexonLv2Gothic', sans-serif;
    font-weight: bold;
    color: #333;
    margin: 25px 0 15px 0;
    font-size: 1.05rem;
  }

  .policy-container ul {
    margin: 25px 0;
    padding-left: 30px;
  }

  .policy-container ul li {
    font-family: 'NexonLv2Gothic light', sans-serif;
    color: #555;
    margin-bottom: 15px;
    font-size: 1.05rem;
    line-height: 1.8;
  }

  .penalty-box {
    background-color: #f8f9fa;
    padding: 30px;
    border-radius: 10px;
    margin: 30px 0;
    border-left: 4px solid #6c757d;
  }

  .penalty-box strong {
    color: #333;
    font-family: 'NexonLv2Gothic', sans-serif;
    font-size: 1.15rem;
    display: block;
    margin-bottom: 15px;
  }

  .penalty-box p {
    font-family: 'NexonLv2Gothic light', sans-serif;
    font-size: 1.05rem;
    margin: 0;
    color: #333;
    line-height: 1.8;
  }

  .contact-info {
    background-color: #f8f9fa;
    padding: 30px;
    border-radius: 10px;
    margin: 40px 0 0 0;
    border-left: 4px solid #2559a8;
  }

  .contact-info p {
    margin: 12px 0;
    font-size: 1.05rem;
    color: #555;
  }

  .contact-info strong {
    color: #2559a8;
    font-family: 'NexonLv2Gothic', sans-serif;
    font-size: 1.15rem;
    display: block;
    margin-bottom: 20px;
  }

  @media screen and (max-width: 800px) {
    .policy-container {
      margin: 100px auto 50px;
      padding: 30px 20px;
    }

    .policy-container h1 {
      font-size: 1.8rem;
      padding-bottom: 20px;
    }

    .policy-container h2 {
      font-size: 1.3rem;
      margin-top: 40px;
      margin-bottom: 20px;
    }

    .policy-container p,
    .policy-container ul li {
      font-size: 0.95rem;
    }

    .intro, .law-section, .penalty-box, .contact-info {
      padding: 25px 20px;
      margin: 25px 0;
    }

    .policy-container ul {
      padding-left: 25px;
    }
  }
</style>

<main>
  <div class="policy-container">
    <h1>이메일무단수집거부</h1>

    <div class="intro">
      <p><strong>본 웹사이트에 게시된 이메일 주소가 전자우편 수집 프로그램이나 그 밖의 기술적 장치를 이용하여 무단으로 수집되는 것을 거부하며, 이를 위반시 정보통신망법에 의해 형사 처벌됨을 유념하시기 바랍니다.</strong></p>
    </div>

    <h2>관련 법률</h2>
    <div class="law-section">
      <p class="law-title">정보통신망 이용촉진 및 정보보호 등에 관한 법률</p>
      <p class="law-article">제50조의2 (전자우편주소의 무단 수집행위 등 금지)</p>
      <ul>
        <li><strong>①</strong> 누구든지 인터넷 홈페이지 운영자 또는 관리자의 사전 동의 없이 인터넷 홈페이지에서 자동으로 전자우편주소를 수집하는 프로그램이나 그 밖의 기술적 장치를 이용하여 전자우편주소를 수집하여서는 아니 된다.</li>
        <li><strong>②</strong> 누구든지 제1항의 규정을 위반하여 수집된 전자우편주소를 판매·유통하여서는 아니 된다.</li>
        <li><strong>③</strong> 누구든지 제1항 및 제2항의 규정에 의하여 수집·판매 및 유통이 금지된 전자우편주소임을 알고 이를 정보 전송에 이용하여서는 아니 된다.</li>
      </ul>
    </div>

    <div class="penalty-box">
      <strong>처벌 규정 (제74조)</strong>
      <p>제50조의2를 위반하여 전자우편주소를 수집, 판매, 유통 또는 정보 전송에 이용한 자는 <strong>1년 이하의 징역 또는 1천만원 이하의 벌금</strong>에 처합니다.</p>
    </div>

    <h2>위반 사례</h2>
    <ul>
      <li>자동화된 프로그램(봇)을 이용한 이메일 주소 수집</li>
      <li>웹사이트의 이메일 주소를 무단으로 크롤링하는 행위</li>
      <li>수집된 이메일 주소 목록의 판매 또는 유통</li>
      <li>무단 수집된 이메일로 스팸 메일 발송</li>
    </ul>

    <div class="contact-info">
      <strong>문의 및 신고</strong>
      <p><strong>전북우리사이</strong></p>
      <p>주소: (55011) 전북특별자치도 전주시 완산구 아중로 33, D동 204호(중노송동)</p>
      <p>이메일: jeonbukwoorisai@gmail.com</p>
    </div>
  </div>
</main>
<?php
// 푸터 포함 (경로 수정)
include_once('../includes/footer.php');
?>