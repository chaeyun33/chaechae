<?php
// 그누보드 연동
include_once("../gnuboard/common.php");

// 커스텀 설정
if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}

$base_url = '..';
$page_title = '개인정보처리방침 - 전북우리사이';
$body_class = '';

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

  .policy-container h3 {
    font-family: 'NexonLv2Gothic', sans-serif;
    color: #333;
    font-size: 1.2rem;
    margin-top: 30px;
    margin-bottom: 15px;
  }

  .policy-container p {
    font-family: 'NexonLv2Gothic light', sans-serif;
    color: #333;
    margin-bottom: 20px;
    font-size: 1.05rem;
    line-height: 1.9;
  }

  .policy-container ul {
    margin: 20px 0;
    padding-left: 30px;
  }

  .policy-container ul li {
    font-family: 'NexonLv2Gothic light', sans-serif;
    color: #555;
    margin-bottom: 12px;
    font-size: 1.05rem;
    line-height: 1.8;
  }

  .info-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background-color: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
  }

  .info-table th,
  .info-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
  }

  .info-table th {
    background-color: #2559a8;
    color: white;
    font-family: 'NexonLv2Gothic', sans-serif;
    font-weight: 600;
  }

  .info-table td {
    font-family: 'NexonLv2Gothic light', sans-serif;
    color: #333;
  }

  .highlight-box {
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 20px;
    margin: 25px 0;
    border-radius: 8px;
  }

  .highlight-box p {
    margin: 0;
    color: #856404;
  }

  .contact-info {
    background: linear-gradient(135deg, #f8f9fa 0%, #e7f3ff 100%);
    padding: 25px;
    border-radius: 10px;
    margin: 25px 0;
    border-left: 5px solid #2559a8;
  }

  .contact-info p {
    margin: 10px 0;
    font-size: 1.05rem;
  }

  .contact-info strong {
    color: #2559a8;
    font-family: 'NexonLv2Gothic', sans-serif;
    font-size: 1.15rem;
    display: block;
    margin-bottom: 15px;
  }

  @media screen and (max-width: 800px) {
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

    .policy-container h3 {
      font-size: 1.1rem;
    }

    .policy-container p,
    .policy-container ul li {
      font-size: 0.95rem;
    }

    .contact-info {
      padding: 20px;
    }

    .info-table th,
    .info-table td {
      padding: 10px;
      font-size: 0.9rem;
    }
  }
</style>

<div id="wrap">
  <div class="policy-container">
    <h1>개인정보처리방침</h1>
    <p class="update-date">📅 시행일자: 2025년 1월 1일</p>

    <h2>제1조 (개인정보의 처리 목적)</h2>
    <p>전북우리사이(이하 '단체'라 함)는 다음의 목적을 위하여 개인정보를 처리합니다. 처리하고 있는 개인정보는 다음의 목적 이외의 용도로는 이용되지 않으며, 이용 목적이 변경되는 경우에는 개인정보 보호법 제18조에 따라 별도의 동의를 받는 등 필요한 조치를 이행할 예정입니다.</p>
    
    <h3>1. 회원가입 및 관리</h3>
    <ul>
      <li>회원 가입의사 확인, 회원제 서비스 제공에 따른 본인 식별·인증</li>
      <li>회원자격 유지·관리, 서비스 부정이용 방지</li>
      <li>각종 고지·통지, 고충처리</li>
    </ul>

    <h3>2. 자원봉사 활동 관리</h3>
    <ul>
      <li>자원봉사자 모집 및 관리</li>
      <li>봉사활동 참여 확인 및 이력 관리</li>
      <li>봉사활동 관련 공지사항 전달</li>
      <li>봉사시간 인증 및 확인서 발급</li>
    </ul>

    <h3>3. 민원사무 처리</h3>
    <ul>
      <li>민원인의 신원 확인, 민원사항 확인</li>
      <li>사실조사를 위한 연락·통지</li>
      <li>처리결과 통보</li>
    </ul>

    <h2>제2조 (개인정보의 처리 및 보유기간)</h2>
    <p>단체는 법령에 따른 개인정보 보유·이용기간 또는 정보주체로부터 개인정보를 수집 시에 동의받은 개인정보 보유·이용기간 내에서 개인정보를 처리·보유합니다.</p>
    
    <table class="info-table">
      <thead>
        <tr>
          <th>처리 목적</th>
          <th>보유 기간</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>회원 가입 및 관리</td>
          <td>회원 탈퇴 시까지 (단, 관계 법령 위반에 따른 수사·조사 등이 진행 중인 경우에는 해당 수사·조사 종료 시까지)</td>
        </tr>
        <tr>
          <td>자원봉사 활동 관리</td>
          <td>봉사활동 종료 후 5년</td>
        </tr>
        <tr>
          <td>상담 및 문의</td>
          <td>처리 완료 후 1년</td>
        </tr>
      </tbody>
    </table>

    <div class="highlight-box">
      <p><strong>⚠️ 법령에 따른 보존:</strong> 전자상거래 등에서의 소비자보호에 관한 법률, 통신비밀보호법 등 관계법령의 규정에 의하여 보존할 필요가 있는 경우 관계법령에서 정한 일정한 기간 동안 개인정보를 보관합니다.</p>
    </div>

    <h2>제3조 (처리하는 개인정보의 항목)</h2>
    <p>단체는 다음의 개인정보 항목을 처리하고 있습니다.</p>

    <h3>1. 회원가입 및 관리</h3>
    <ul>
      <li><strong>필수항목:</strong> 아이디, 비밀번호, 성명, 닉네임, 연락처(휴대전화번호, 이메일)</li>
      <li><strong>선택항목:</strong> 프로필 사진, 관심분야</li>
      <li><strong>자동수집항목:</strong> 서비스 이용기록, 접속 로그, 접속 IP 정보, 쿠키</li>
    </ul>

    <h3>2. 자원봉사 활동</h3>
    <ul>
      <li><strong>필수항목:</strong> 성명, 생년월일, 연락처</li>
      <li><strong>선택항목:</strong> 봉사활동 관련 특기사항, 경력사항</li>
    </ul>

    <h2>제4조 (개인정보의 제3자 제공)</h2>
    <p>단체는 정보주체의 개인정보를 제1조(개인정보의 처리 목적)에서 명시한 범위 내에서만 처리하며, 정보주체의 동의, 법률의 특별한 규정 등 개인정보 보호법 제17조 및 제18조에 해당하는 경우에만 개인정보를 제3자에게 제공합니다.</p>

    <h2>제5조 (개인정보처리의 위탁)</h2>
    <p>단체는 원활한 개인정보 업무처리를 위하여 다음과 같이 개인정보 처리업무를 위탁하고 있습니다.</p>
    <ul>
      <li><strong>수탁업체:</strong> 그누보드 호스팅 서비스 제공업체</li>
      <li><strong>위탁업무:</strong> 웹사이트 시스템 운영 및 유지보수</li>
      <li><strong>보유기간:</strong> 위탁계약 종료 시까지</li>
    </ul>

    <h2>제6조 (정보주체의 권리·의무 및 행사방법)</h2>
    <p>정보주체는 단체에 대해 언제든지 다음 각 호의 개인정보 보호 관련 권리를 행사할 수 있습니다.</p>
    <ul>
      <li>개인정보 열람 요구</li>
      <li>오류 등이 있을 경우 정정 요구</li>
      <li>삭제 요구</li>
      <li>처리정지 요구</li>
    </ul>
    <p>권리 행사는 개인정보 보호법 시행규칙 별지 제8호 서식에 따라 서면, 전자우편 등을 통하여 하실 수 있으며, 단체는 이에 대해 지체없이 조치하겠습니다.</p>

    <h2>제7조 (개인정보의 안전성 확보조치)</h2>
    <p>단체는 개인정보의 안전성 확보를 위해 다음과 같은 조치를 취하고 있습니다.</p>
    <ul>
      <li><strong>관리적 조치:</strong> 내부관리계획 수립·시행, 정기적 직원 교육</li>
      <li><strong>기술적 조치:</strong> 개인정보처리시스템 등의 접근권한 관리, 접근통제시스템 설치, 고유식별정보 등의 암호화, 보안프로그램 설치</li>
      <li><strong>물리적 조치:</strong> 전산실, 자료보관실 등의 접근통제</li>
    </ul>

    <h2>제8조 (개인정보의 파기)</h2>
    <p>단체는 개인정보 보유기간의 경과, 처리목적 달성 등 개인정보가 불필요하게 되었을 때에는 지체없이 해당 개인정보를 파기합니다.</p>
    
    <h3>파기 절차</h3>
    <p>정보주체로부터 동의받은 개인정보 보유기간이 경과하거나 처리목적이 달성되었음에도 불구하고 다른 법령에 따라 개인정보를 계속 보존하여야 하는 경우에는, 해당 개인정보를 별도의 데이터베이스(DB)로 옮기거나 보관장소를 달리하여 보존합니다.</p>

    <h3>파기 방법</h3>
    <ul>
      <li><strong>전자적 파일:</strong> 복구 및 재생되지 않도록 기술적 방법을 이용하여 완전하게 삭제</li>
      <li><strong>종이 문서:</strong> 분쇄기로 분쇄하거나 소각</li>
    </ul>

    <h2>제9조 (개인정보 자동 수집 장치의 설치·운영 및 거부에 관한 사항)</h2>
    <p>단체는 이용자에게 개별적인 맞춤서비스를 제공하기 위해 이용정보를 저장하고 수시로 불러오는 '쿠키(cookie)'를 사용합니다.</p>
    
    <h3>쿠키의 사용 목적</h3>
    <ul>
      <li>회원과 비회원의 접속 빈도나 방문 시간 등을 분석</li>
      <li>이용자의 취향과 관심분야를 파악 및 자취 추적</li>
      <li>각종 이벤트 참여 정도 및 방문 회수 파악 등을 통한 타겟 마케팅 및 개인 맞춤 서비스 제공</li>
    </ul>

    <h3>쿠키 설정 거부 방법</h3>
    <p>이용자는 쿠키 설치에 대한 선택권을 가지고 있습니다. 따라서 웹브라우저에서 옵션을 설정함으로써 모든 쿠키를 허용하거나, 쿠키가 저장될 때마다 확인을 거치거나, 모든 쿠키의 저장을 거부할 수 있습니다.</p>
    <p>단, 쿠키 설치를 거부할 경우 웹 사용이 불편해지며 로그인이 필요한 일부 서비스 이용에 어려움이 있을 수 있습니다.</p>

    <h2>제10조 (개인정보 보호책임자)</h2>
    <p>단체는 개인정보 처리에 관한 업무를 총괄해서 책임지고, 개인정보 처리와 관련한 정보주체의 불만처리 및 피해구제 등을 위하여 아래와 같이 개인정보 보호책임자를 지정하고 있습니다.</p>
    
    <div class="contact-info">
      <strong>📋 개인정보 보호책임자</strong>
      <p><strong>단체명:</strong> 전북우리사이</p>
      <p>📧 <strong>이메일:</strong> jeonbukwoorisai@gmail.com</p>
      <p>📍 <strong>주소:</strong> (55011) 전북특별자치도 전주시 완산구 아중로 33, D동 204호</p>
    </div>

    <p>정보주체는 단체의 서비스를 이용하시면서 발생한 모든 개인정보 보호 관련 문의, 불만처리, 피해구제 등에 관한 사항을 개인정보 보호책임자에게 문의하실 수 있습니다. 단체는 정보주체의 문의에 대해 지체없이 답변 및 처리해드릴 것입니다.</p>

    <h2>제11조 (개인정보 처리방침 변경)</h2>
    <p>이 개인정보 처리방침은 2025년 1월 1일부터 적용되며, 법령 및 방침에 따른 변경내용의 추가, 삭제 및 정정이 있는 경우에는 변경사항의 시행 7일 전부터 공지사항을 통하여 고지할 것입니다.</p>

    <h2>제12조 (권익침해 구제방법)</h2>
    <p>정보주체는 개인정보침해로 인한 구제를 받기 위하여 개인정보분쟁조정위원회, 한국인터넷진흥원 개인정보침해신고센터 등에 분쟁해결이나 상담 등을 신청할 수 있습니다.</p>
    
    <ul>
      <li><strong>개인정보침해신고센터</strong><br>
        전화: (국번없이) 118<br>
        웹사이트: privacy.kisa.or.kr</li>
      
      <li><strong>개인정보분쟁조정위원회</strong><br>
        전화: (국번없이) 1833-6972<br>
        웹사이트: kopico.go.kr</li>
      
      <li><strong>대검찰청 사이버범죄수사단</strong><br>
        전화: (국번없이) 1301<br>
        웹사이트: spo.go.kr</li>
      
      <li><strong>경찰청 사이버안전국</strong><br>
        전화: (국번없이) 182<br>
        웹사이트: cyberbureau.police.go.kr</li>
    </ul>
  </div>
</div>

<?php
// 푸터 포함
include_once('../includes/footer.php');
?>