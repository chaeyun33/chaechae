<?php
// 커스텀 설정 (common.php 보다 먼저 정의)
define('_CUSTOM_', true);

// 그누보드 연동
include_once("./gnuboard/common.php");

$base_url = '';  
$page_title = '전북우리사이 - 전북 청년 자원봉사단체';
$body_class = 'main-page';

// 헤더 포함
include_once('./includes/header.php');
?>

<!-- 메인 페이지 전용 CSS -->
<link rel="stylesheet" href="./style/main.css">

<!-- 메인 히어로 슬라이드 -->
<section class="imgslide">
    <img src="./img/슬라이드1.png" alt="전북우리사이 메인 슬라이드" class="active">
    <img src="./img/25-10-19/11.jpg" alt="봉사활동 현장 사진">
    <img src="./img/25-10-19/12.jpg" alt="봉사활동 단체 사진">
    <img src="./img/25-11-14/10.jpg" alt="봉사활동 단체 사진">
    
    <div class="slide-content">
        <div class="slide-title">우리가 하고 싶은 일,<br>우리가 만들어가는 변화</div>
        <p>전북우리사이와 함께하는 자기주도적 자원봉사 활동</p>
        <a href="./intro.php" class="slide-btn">자세히 보기</a>
    </div>
    
    <div class="slide-indicators">
        <span class="active" data-slide="0" aria-label="슬라이드 1"></span>
        <span data-slide="1" aria-label="슬라이드 2"></span>
        <span data-slide="2" aria-label="슬라이드 3"></span>
        <span data-slide="3" aria-label="슬라이드 4"></span>
    </div>
</section>

<!-- MISSION 섹션 -->
<section class="mission">
    <div class="mission-container">
        <div class="mission-text">
            <h2>함께 만들어가는<br>건강한 자원봉사 문화</h2>
            <p>전북우리사이는 청년들이 자발적으로 모여 지역사회에 긍정적인 변화를 만들어가는 자원봉사단체입니다.</p>
            <p>우리는 단순한 봉사활동을 넘어, 청년들이 성장하고 서로 연결되며 지역사회와 함께 발전하는 플랫폼을 만들어갑니다.</p>
        </div>
        
        <div class="mission-gallery">
            <div class="card-stack">
                <div class="gallery-card" data-index="0">
                    <img src="./img/25-11-14/10.jpg" alt="벽화봉사활동">
                    <div class="card-info">
                        <h4>전주시 낙수정 새뜰마을</h4>
                        <p>벽화봉사활동</p>
                    </div>
                </div>
                <div class="gallery-card" data-index="1">
                    <img src="./img/25-10-19/1.jpg" alt="산불안전 캠페인">
                    <div class="card-info">
                        <h4>김제시 금산면</h4>
                        <p>산불안전 캠페인</p>
                    </div>
                </div>
                <div class="gallery-card" data-index="2">
                    <img src="./img/25-10-19/12.jpg" alt="봉사활동 단체사진">
                    <div class="card-info">
                        <h4>봉사활동</h4>
                        <p>단체 기념촬영</p>
                    </div>
                </div>
                <div class="gallery-card" data-index="3">
                    <img src="./img/su/1.jpg" alt="재난예방 캠페인">
                    <div class="card-info">
                        <h4>완주군 산정마을</h4>
                        <p>재난예방 캠페인</p>
                    </div>
                </div>
                <div class="gallery-card" data-index="4">
                    <img src="./img/nak/9.jpg" alt="플로깅 활동">
                    <div class="card-info">
                        <h4>전주시 낙수정</h4>
                        <p>탐방 및 플로깅</p>
                    </div>
                </div>
            </div>
            
            <div class="card-nav">
                <button class="card-btn card-prev" onclick="CardGallery.prev()" aria-label="이전 카드">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <div class="card-counter">
                    <span class="current">1</span> / <span class="total">5</span>
                </div>
                <button class="card-btn card-next" onclick="CardGallery.next()" aria-label="다음 카드">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- 공지사항 & 언론보도 -->
<article class="art2">
    <div class="art2-grid">
        <!-- 공지사항 (그누보드 연동) -->
        <div class="note">
            <h2>공지사항<a href="./notice/notice.php"><i class="fa-solid fa-plus fa-xs"></i></a></h2>
            <ul>
                <?php
                // 공지사항 최신 3개 가져오기
                $sql = "SELECT wr_id, wr_subject, wr_datetime 
                        FROM {$g5['write_prefix']}notice 
                        WHERE wr_is_comment = 0 
                        ORDER BY wr_datetime DESC 
                        LIMIT 3";
                $result = sql_query($sql);
                
                if (sql_num_rows($result) > 0) {
                    while ($row = sql_fetch_array($result)) {
                        $subject = htmlspecialchars($row['wr_subject']);
                        $date = date('Y-m-d', strtotime($row['wr_datetime']));
                        $link = './notice/note.php?bo_table=notice&wr_id='.$row['wr_id'];
                        
                        echo '<li>';
                        echo '<a href="'.$link.'">';
                        echo '<h3>'.$subject.'</h3>';
                        echo '<span>'.$date.'</span>';
                        echo '</a>';
                        echo '</li>';
                    }
                } else {
                    echo '<li><a href="#"><h3>등록된 공지사항이 없습니다.</h3><span>-</span></a></li>';
                }
                ?>
            </ul>
        </div>
        
        <!-- 언론보도 (그누보드 연동) -->
        <div class="note">
            <h2>언론보도<a href="./news/news.php"><i class="fa-solid fa-plus fa-xs"></i></a></h2>
            <ul>
                <?php
                // 언론보도 최신 3개 가져오기
                $sql_press = "SELECT wr_id, wr_subject, wr_datetime, wr_link1 
                              FROM {$g5['write_prefix']}press 
                              WHERE wr_is_comment = 0 
                              ORDER BY wr_datetime DESC 
                              LIMIT 3";
                $result_press = sql_query($sql_press);
                
                if (sql_num_rows($result_press) > 0) {
                    while ($row = sql_fetch_array($result_press)) {
                        $subject = htmlspecialchars($row['wr_subject']);
                        $date = date('Y-m-d', strtotime($row['wr_datetime']));
                        
                        // 외부 링크가 있으면 외부 링크로, 없으면 상세 페이지로
                        if (!empty($row['wr_link1'])) {
                            $link = htmlspecialchars($row['wr_link1']);
                            $target = ' target="_blank" rel="noopener noreferrer"';
                        } else {
                            $link = './news/note.php?bo_table=press&wr_id='.$row['wr_id'];
                            $target = '';
                        }
                        
                        echo '<li>';
                        echo '<a href="'.$link.'"'.$target.'>';
                        echo '<h3>'.$subject.'</h3>';
                        echo '<span>'.$date.'</span>';
                        echo '</a>';
                        echo '</li>';
                    }
                } else {
                    echo '<li><a href="#"><h3>등록된 언론보도가 없습니다.</h3><span>-</span></a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</article>

<!-- Instagram 섹션 -->
<article class="art2 news-layout instagram-section">
    <h2 class="instagram-title">Instagram</h2>
    
    <div class="instagram-wrapper">
        <div class='embedsocial-hashtag' data-ref="d6843ffcdeb6872d4a411338d16168aa131e52f9"></div>
    </div>
</article>

<!-- 지원기관 -->
<aside>
    <div class="organ">
        <div class="organlist">
            <div class="slide">
                <div class="organbox"><a href="https://www.khs.go.kr/main.html" target="_blank" rel="noopener noreferrer"><img src="./img/Korea Heritage Service-logo.jpg" alt="국가유산청"></a></div>
                <div class="organbox"><a href="https://www.jeonju.go.kr/" target="_blank" rel="noopener noreferrer"><img src="./img/jeon.png" alt="전주시청"></a></div>
                <div class="organbox"><a href="https://www.jeonbuk.go.kr/index.jeonbuk" target="_blank" rel="noopener noreferrer"><img src="./img/jb.png" alt="전북특별자치도"></a></div>
                <div class="organbox"><a href="https://www.jbnu.ac.kr/web/index.do" target="_blank" rel="noopener noreferrer"><img src="./img/jbsc.png" alt="전북대학교"></a></div>
                <div class="organbox"><a href="https://www.khs.go.kr/main.html" target="_blank" rel="noopener noreferrer"><img src="./img/gj.png" alt="국가유산지킴이"></a></div>
            </div>
            <div class="slide">
                <div class="organbox"><a href="https://www.khs.go.kr/main.html" target="_blank" rel="noopener noreferrer"><img src="./img/Korea Heritage Service-logo.jpg" alt="국가유산청"></a></div>
                <div class="organbox"><a href="https://www.jeonju.go.kr/" target="_blank" rel="noopener noreferrer"><img src="./img/jeon.png" alt="전주시청"></a></div>
                <div class="organbox"><a href="https://www.jeonbuk.go.kr/index.jeonbuk" target="_blank" rel="noopener noreferrer"><img src="./img/jb.png" alt="전북특별자치도"></a></div>
                <div class="organbox"><a href="https://www.jbnu.ac.kr/web/index.do" target="_blank" rel="noopener noreferrer"><img src="./img/jbsc.png" alt="전북대학교"></a></div>
                <div class="organbox"><a href="https://www.khs.go.kr/main.html" target="_blank" rel="noopener noreferrer"><img src="./img/gj.png" alt="국가유산지킴이"></a></div>
            </div>
            <div class="slide">
                <div class="organbox"><a href="https://www.khs.go.kr/main.html" target="_blank" rel="noopener noreferrer"><img src="./img/Korea Heritage Service-logo.jpg" alt="국가유산청"></a></div>
                <div class="organbox"><a href="https://www.jeonju.go.kr/" target="_blank" rel="noopener noreferrer"><img src="./img/jeon.png" alt="전주시청"></a></div>
                <div class="organbox"><a href="https://www.jeonbuk.go.kr/index.jeonbuk" target="_blank" rel="noopener noreferrer"><img src="./img/jb.png" alt="전북특별자치도"></a></div>
                <div class="organbox"><a href="https://www.jbnu.ac.kr/web/index.do" target="_blank" rel="noopener noreferrer"><img src="./img/jbsc.png" alt="전북대학교"></a></div>
                <div class="organbox"><a href="https://www.khs.go.kr/main.html" target="_blank" rel="noopener noreferrer"><img src="./img/gj.png" alt="국가유산지킴이"></a></div>
            </div>
        </div>
    </div>
</aside>

<style>
/* Instagram 섹션 스타일 */
.news-grid {
    display: none !important;
}

.instagram-section {
    width: 100%;
    padding: 60px 0;
    background: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.instagram-title {
    text-align: center;
    margin-bottom: 40px;
}

.instagram-wrapper {
    max-width: 1500px;
    width: 100%;
    margin: 0 auto;
    padding: 0 20px;
}

.embedsocial-hashtag {
    width: 100% !important;
    max-width: 1500px !important;
    margin: 0 auto !important;
}

.embedsocial-hashtag > div {
    max-width: 1500px !important;
}

/* EmbedSocial 모달 최우선 표시 */
body > div[class*="embedsocial"],
body > div[id*="embedsocial"],
div[class*="es-modal"],
div[class*="es-lightbox"],
div[class*="es-popup"],
.embedsocial-modal,
.embedsocial-lightbox,
.embedsocial-popup {
    z-index: 999999 !important;
    position: fixed !important;
}

.es-modal-backdrop,
.embedsocial-backdrop {
    z-index: 999998 !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
}

/* 모바일 반응형 */
@media (max-width: 800px) {
    .instagram-section {
        padding: 40px 0;
    }
    
    .instagram-wrapper {
        padding: 0 10px;
    }
    
    .instagram-title {
        margin-bottom: 30px;
        font-size: 1.5rem;
    }
}
</style>

<?php
// 푸터 포함
include_once('./includes/footer.php');
?>

<!-- 외부 스크립트 로드 -->
<script src="https://embedsocial.com/cdn/ht.js" id="EmbedSocialHashtagScript"></script>
<script src="./javascript/script.js"></script>