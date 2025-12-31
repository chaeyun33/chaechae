<?php
// 그누보드 연동
include_once("./gnuboard/common.php");

// 커스텀 설정
if (!defined('_CUSTOM_')) {
    define('_CUSTOM_', true);
}
$base_url = '';  // 루트 경로
$page_title = '소개 - 전북우리사이';
$body_class = 'intro-page';

// 헤더 포함
include_once('./includes/header.php');
?>

<!-- intro 페이지 전용 CSS -->
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    background: #fff;
    overflow-x: hidden;
  }

  /* ===== HERO 섹션 - 풀스크린 ===== */
  .hero {
    position: relative;
    width: 100vw;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin-bottom: 0;
  }

  .hero img {
    position: absolute;
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.4);
  }

  .hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    max-width: 900px;
    padding: 0 30px;
  }

  .hero-content h1 {
    font-size: clamp(40px, 6vw, 80px);
    font-weight: 600;
    color: #fff;
    margin-bottom: 30px;
    letter-spacing: -0.03em;
    line-height: 1.2;
    animation: fadeUp 1s ease-out;
  }

  .hero-content p {
    font-size: clamp(18px, 2vw, 24px);
    font-weight: 400;
    color: rgba(255, 255, 255, 0.9);
    letter-spacing: 0.08em;
    animation: fadeUp 1s ease-out 0.2s both;
  }

  @keyframes fadeUp {
    from {
      opacity: 0;
      transform: translateY(40px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* ===== 스크롤 인디케이터 ===== */
  .scroll-indicator {
    position: absolute;
    bottom: 40px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
    animation: bounce 2s infinite;
  }

  .scroll-indicator i {
    color: rgba(255, 255, 255, 0.7);
    font-size: 32px;
  }

  @keyframes bounce {
    0%, 100% { transform: translateX(-50%) translateY(0); }
    50% { transform: translateX(-50%) translateY(10px); }
  }

  .stat-item {
    text-align: center;
  }

  .stat-number {
    font-size: clamp(40px, 5vw, 64px);
    font-weight: 400;
    color: #191919;
    margin-bottom: 10px;
  }

  .stat-label {
    font-size: 14px;
    font-weight: 400;
    color: #999;
    letter-spacing: 0.1em;
    text-transform: uppercase;
  }

  /* ===== GALLERY 섹션 ===== */
  .gallery {
    padding: 150px 0;
    background: #fafafa;
  }

  .gallery-header {
    text-align: center;
    margin-bottom: 80px;
    padding: 0 20px;
  }

  .gallery-header h2 {
    font-size: clamp(36px, 4vw, 56px);
    font-weight: 500;
    color: #191919;
    margin-bottom: 20px;
    letter-spacing: -0.02em;
  }

  .gallery-header p {
    font-size: clamp(16px, 1.5vw, 20px);
    font-weight: 400;
    color: #666;
  }

  .gallery-slider {
    width: 100%;
    overflow: hidden;
    padding: 40px 0;
  }

  .gallery-track {
    display: flex;
    gap: 30px;
    padding: 0 max(20px, calc((100vw - 1400px) / 2));
    overflow-x: scroll;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    cursor: grab;
  }

  .gallery-track::-webkit-scrollbar {
    display: none;
  }

  .gallery-track.dragging {
    cursor: grabbing;
    scroll-snap-type: none;
  }

  .gallery-item {
    flex: 0 0 400px;
    height: 550px;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    scroll-snap-align: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    user-select: none;
    -webkit-user-drag: none;
  }

  .gallery-item:hover img {
    transform: scale(1.08);
  }

  .gallery-item::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 50%;
    background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
    opacity: 0;
    transition: opacity 0.4s ease;
  }

  .gallery-item:hover::after {
    opacity: 1;
  }

  /* ===== 반응형 - 태블릿 ===== */
  @media (max-width: 1024px) {
    .mission-container {
      grid-template-columns: 1fr;
      gap: 60px;
    }

    .mission-stats {
      grid-template-columns: repeat(3, 1fr);
      gap: 30px;
    }

    .gallery-item {
      flex: 0 0 350px;
      height: 480px;
    }
  }

</style>

<!-- HERO -->
<section class="hero">
  <img src="./img/nak/1.jpg" alt="전북우리사이 청년 자원봉사 활동 모습">
  <div class="hero-content">
    <h1>청년의 연결과 나눔으로<br>지역사회를 변화시킵니다</h1>
    <p>전북우리사이 청년자원봉사단</p>
  </div>
  <div class="scroll-indicator">
    <i class="fas fa-chevron-down"></i>
  </div>
</section>

<!-- GALLERY -->
<section class="gallery">
  <div class="gallery-header">
    <h2>우리의 활동</h2>
    <p>청년들과 함께한 의미있는 순간들</p>
  </div>
  <div class="gallery-slider">
    <div class="gallery-track" id="galleryTrack">
      <div class="gallery-item">
        <img src="./img/gal/9.jpg" alt="가드너즈 활동" draggable="false">
      </div>
      <div class="gallery-item">
        <img src="./img/nak/9.jpg" alt="낙수정 봉사활동" draggable="false">
      </div>
      <div class="gallery-item">
        <img src="./img/gal/6.jpg" alt="가드너즈 활동" draggable="false">
      </div>
      <div class="gallery-item">
        <img src="./img/gal/2.jpg" alt="가드너즈 활동" draggable="false">
      </div>
      <div class="gallery-item">
        <img src="./img/nak/5.jpg" alt="낙수정 봉사활동" draggable="false">
      </div>
    </div>
  </div>
</section>

<script>
  // 갤러리 드래그 슬라이더
  const track = document.getElementById('galleryTrack');
  let isDown = false;
  let startX;
  let scrollLeft;

  track.addEventListener('mousedown', (e) => {
    isDown = true;
    track.classList.add('dragging');
    startX = e.pageX - track.offsetLeft;
    scrollLeft = track.scrollLeft;
  });

  track.addEventListener('mouseleave', () => {
    isDown = false;
    track.classList.remove('dragging');
  });

  track.addEventListener('mouseup', () => {
    isDown = false;
    track.classList.remove('dragging');
  });

  track.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - track.offsetLeft;
    const walk = (x - startX) * 1.5;
    track.scrollLeft = scrollLeft - walk;
  });

  // 터치 지원
  let touchStartX = 0;
  let touchScrollStart = 0;

  track.addEventListener('touchstart', (e) => {
    touchStartX = e.touches[0].clientX;
    touchScrollStart = track.scrollLeft;
  }, { passive: true });

  track.addEventListener('touchmove', (e) => {
    const touchCurrent = e.touches[0].clientX;
    const diff = (touchStartX - touchCurrent);
    track.scrollLeft = touchScrollStart + diff;
  }, { passive: true });
</script>

<?php
// 푸터 포함
include_once('./includes/footer.php');
?>