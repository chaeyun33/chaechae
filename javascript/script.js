/**
 * 전북우리사이 메인 페이지 JavaScript
 * 작성자: 채니
 * 최종 수정: 2025-01-01
 */

// ===========================================
// 1. 페이지 유틸리티
// ===========================================
const PageUtils = {
    /**
     * 메인 페이지인지 확인
     */
    isMainPage() {
        const path = window.location.pathname;
        return path.includes('index.php') || 
               path === '/' || 
               path.endsWith('/');
    },
    
    /**
     * 모바일 환경인지 확인
     */
    isMobile() {
        return window.innerWidth <= 800;
    }
};


// ===========================================
// 2. 헤더 스크롤 효과 (PC 메인 페이지만)
// ===========================================
const HeaderScroll = {
    state: {
        header: null,
        logo: null
    },
    
    init() {
        // PC 환경 && 메인 페이지에서만 동작
        if (PageUtils.isMobile() || !PageUtils.isMainPage()) {
            return;
        }
        
        this.state.header = document.querySelector('header');
        this.state.logo = document.querySelector('.logo img');
        
        if (!this.state.header || !this.state.logo) return;
        
        // 스크롤 이벤트
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                this.state.header.classList.add('scrolled');
                this.state.logo.src = './img/logo.png';
            } else {
                this.state.header.classList.remove('scrolled');
                this.state.logo.src = './img/btlogo.png';
            }
        });
        
        // 초기 상태 설정
        if (window.scrollY > 100) {
            this.state.header.classList.add('scrolled');
            this.state.logo.src = './img/logo.png';
        } else {
            this.state.logo.src = './img/btlogo.png';
        }
    }
};


// ===========================================
// 3. 햄버거 메뉴 (모바일)
// ===========================================
const HamburgerMenu = {
    state: {
        hamburger: null,
        navi: null,
        isOpen: false
    },
    
    init() {
        this.state.hamburger = document.querySelector('.hamburger');
        this.state.navi = document.querySelector('.navi');
        
        if (!this.state.hamburger || !this.state.navi) return;
        
        // 햄버거 버튼 클릭
        this.state.hamburger.addEventListener('click', () => {
            this.state.isOpen = !this.state.isOpen;
            this.state.navi.classList.toggle('show');
        });
        
        // 메뉴 링크 클릭 시 닫기
        const menuLinks = document.querySelectorAll('.navi a');
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (!link.getAttribute('target')) {
                    this.state.navi.classList.remove('show');
                    this.state.isOpen = false;
                }
            });
        });
    }
};


// ===========================================
// 4. 히어로 슬라이드
// ===========================================
const HeroSlide = {
    state: {
        slides: null,
        indicators: null,
        currentIndex: 0,
        timer: null
    },
    
    init() {
        this.state.slides = document.querySelectorAll('.imgslide img');
        this.state.indicators = document.querySelectorAll('.slide-indicators span');
        
        if (this.state.slides.length === 0) return;
        
        // 첫 슬라이드 활성화
        this.state.slides[0].classList.add('active');
        
        // 인디케이터 클릭 이벤트
        this.state.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                this.stopAutoPlay();
                this.showSlide(index);
                // 3초 후 자동 재생 재개
                setTimeout(() => this.startAutoPlay(), 3000);
            });
        });
        
        // 자동 재생 시작
        this.startAutoPlay();
    },
    
    showSlide(index) {
        this.state.slides.forEach(slide => slide.classList.remove('active'));
        this.state.indicators.forEach(indicator => indicator.classList.remove('active'));
        
        this.state.slides[index].classList.add('active');
        this.state.indicators[index].classList.add('active');
        
        this.state.currentIndex = index;
    },
    
    nextSlide() {
        const nextIndex = (this.state.currentIndex + 1) % this.state.slides.length;
        this.showSlide(nextIndex);
    },
    
    startAutoPlay() {
        this.state.timer = setInterval(() => this.nextSlide(), 5000);
    },
    
    stopAutoPlay() {
        if (this.state.timer) {
            clearInterval(this.state.timer);
            this.state.timer = null;
        }
    }
};


// ===========================================
// 5. 갤러리 카드 스와이프
// ===========================================
const CardGallery = {
    state: {
        cards: null,
        currentIndex: 0,
        totalCards: 0,
        startX: 0,
        isDragging: false
    },
    
    init() {
        this.state.cards = document.querySelectorAll('.gallery-card');
        const cardStack = document.querySelector('.card-stack');
        const counterCurrent = document.querySelector('.card-counter .current');
        const counterTotal = document.querySelector('.card-counter .total');
        
        this.state.totalCards = this.state.cards.length;
        
        if (this.state.totalCards === 0) return;
        
        // 카운터 표시
        if (counterTotal) {
            counterTotal.textContent = this.state.totalCards;
        }
        
        // 초기 위치 설정
        this.updateCards();
        
        // 마우스 이벤트
        if (cardStack) {
            cardStack.addEventListener('mousedown', (e) => {
                this.state.startX = e.clientX;
                this.state.isDragging = true;
            });
            
            cardStack.addEventListener('mousemove', (e) => {
                if (!this.state.isDragging) return;
                const diff = e.clientX - this.state.startX;
                if (Math.abs(diff) > 50) {
                    diff > 0 ? this.prev() : this.next();
                    this.state.isDragging = false;
                }
            });
            
            cardStack.addEventListener('mouseup', () => {
                this.state.isDragging = false;
            });
            
            // 터치 이벤트
            cardStack.addEventListener('touchstart', (e) => {
                this.state.startX = e.touches[0].clientX;
            });
            
            cardStack.addEventListener('touchend', (e) => {
                const endX = e.changedTouches[0].clientX;
                const diff = endX - this.state.startX;
                if (Math.abs(diff) > 50) {
                    diff > 0 ? this.prev() : this.next();
                }
            });
        }
        
        // 카운터 업데이트
        if (counterCurrent) {
            counterCurrent.textContent = this.state.currentIndex + 1;
        }
    },
    
    updateCards() {
        this.state.cards.forEach((card, index) => {
            const pos = index - this.state.currentIndex;
            
            if (pos < 0) {
                // 지나간 카드
                card.style.transform = 'translateX(-150%) scale(0.8) rotateZ(-15deg)';
                card.style.opacity = '0';
                card.style.zIndex = index;
            } else if (pos === 0) {
                // 현재 카드
                card.style.transform = 'translateX(0) scale(1) rotateY(0deg)';
                card.style.opacity = '1';
                card.style.zIndex = this.state.totalCards;
            } else {
                // 다음 카드들
                const offset = pos * 20;
                const scale = 1 - (pos * 0.05);
                const rotate = pos * 3;
                
                card.style.transform = `translateX(${offset}px) scale(${scale}) rotateY(${rotate}deg)`;
                card.style.opacity = pos <= 2 ? '1' : '0';
                card.style.zIndex = this.state.totalCards - pos;
            }
        });
        
        // 카운터 업데이트
        const counterCurrent = document.querySelector('.card-counter .current');
        if (counterCurrent) {
            counterCurrent.textContent = this.state.currentIndex + 1;
        }
    },
    
    next() {
        if (this.state.currentIndex < this.state.totalCards - 1) {
            this.state.cards[this.state.currentIndex].style.transform = 'translateX(-150%) scale(0.8) rotateZ(-15deg)';
            this.state.cards[this.state.currentIndex].style.opacity = '0';
            this.state.currentIndex++;
            this.updateCards();
        }
    },
    
    prev() {
        if (this.state.currentIndex > 0) {
            this.state.cards[this.state.currentIndex].style.transform = 'translateX(150%) scale(0.8) rotateZ(15deg)';
            this.state.cards[this.state.currentIndex].style.opacity = '0';
            this.state.currentIndex--;
            this.updateCards();
        }
    }
};


// ===========================================
// 6. Instagram 위젯
// ===========================================
const InstagramWidget = {
    init() {
        if (document.getElementById('EmbedSocialHashtagScript')) {
            return;
        }
        
        const script = document.createElement('script');
        script.id = 'EmbedSocialHashtagScript';
        script.src = 'https://embedsocial.com/cdn/ht.js';
        script.async = true;
        document.head.appendChild(script);
    }
};


// ===========================================
// 7. 전역 함수 (HTML에서 직접 호출용)
// ===========================================
window.CardGallery = CardGallery;


// ===========================================
// 8. 초기화
// ===========================================
document.addEventListener('DOMContentLoaded', () => {
    // 메인 페이지 표시
    if (PageUtils.isMainPage()) {
        document.body.classList.add('main-page');
    }
    
    // 모듈 초기화
    HeaderScroll.init();
    HamburgerMenu.init();
    HeroSlide.init();
    CardGallery.init();
    InstagramWidget.init();
    
    console.log('✅ 전북우리사이 로드 완료');
});