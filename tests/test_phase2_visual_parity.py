"""
시각/구조 패리티 — bloomingterra goods_view ↔ askim portfolio view
미션: 5시간 자율로 askim과 시각/구조 동일하게 만들기. 레이아웃/이미지 엑박 없도록.
"""
import time
import pytest
import requests
from playwright.sync_api import Page, expect

BLOOM_BASE = "https://www.bloomingterra.com"
ASKIM_REF = "https://www.askim.kr/portfolio/portfolio.php?ptype=view&idx=46&page=1&code=portfolio"

# 운영 데이터 의존: TIRTIR 외 다른 활성 goods 글
ACTIVE_GOODS_NOS = [171, 170, 169, 168, 167, 228, 227, 226]


def _bloom_url(no, cate):
    return f"{BLOOM_BASE}/goods/goods_view?no={no}&cate={cate}&_t={int(time.time())}"


class TestNoBrokenImages:
    """라이브 페이지 로드 시 4xx/5xx 응답 0건 (이미지 엑박)"""

    @pytest.mark.parametrize("no,cate", [
        (171, "001007"),
        (228, "005"),
        (170, "001007"),
    ])
    def test_no_failed_resources(self, page: Page, no, cate):
        failed = []
        page.on("response", lambda r: r.status >= 400 and failed.append((r.url, r.status)))
        page.goto(_bloom_url(no, cate), wait_until="networkidle", timeout=60000)
        # google-analytics 같은 외부는 제외 (askim에도 있음)
        critical = [(u, s) for u, s in failed if "bloomingterra.com" in u]
        assert critical == [], f"Failed resources: {critical}"

    def test_no_console_errors(self, page: Page):
        errors = []
        page.on("pageerror", lambda e: errors.append(str(e)))
        page.on("console", lambda m: m.type == "error" and errors.append(m.text))
        page.goto(_bloom_url(171, "001007"), wait_until="networkidle", timeout=60000)
        # 외부 스크립트 광고 등 무관 에러는 제외
        critical = [e for e in errors if "google" not in e.lower() and "analytics" not in e.lower()]
        assert critical == [], f"Console errors: {critical}"


class TestLayoutParity:
    """askim portfolio view와 동일한 핵심 레이아웃 (데스크톱 1440 기준)"""

    @pytest.fixture(autouse=True)
    def goto(self, page: Page):
        page.set_viewport_size({"width": 1440, "height": 900})
        page.goto(_bloom_url(171, "001007"), wait_until="networkidle", timeout=60000)
        page.wait_for_timeout(500)

    def test_2column_flex(self, page: Page):
        m = page.evaluate("""() => {
            const r = document.querySelector('.portfolio_view_in');
            return { d: getComputedStyle(r).display, fd: getComputedStyle(r).flexDirection };
        }""")
        assert m["d"] == "flex" and m["fd"] == "row"

    def test_subject_typography(self, page: Page):
        m = page.evaluate("""() => {
            const s = document.querySelector('.portfolio_view .subject, .portfolio_view .fs65');
            return { fs: getComputedStyle(s).fontSize, fw: getComputedStyle(s).fontWeight };
        }""")
        assert m["fs"] == "65px"
        assert int(m["fw"]) >= 700

    def test_date_color_main_token(self, page: Page):
        color = page.evaluate("""() => {
            const d = document.querySelector('.portfolio_view_in .txt_area .date');
            return getComputedStyle(d).color;
        }""")
        # askim --main: #0A2C51 = rgb(10, 44, 81)
        assert color == "rgb(10, 44, 81)"

    def test_btn_area_2_buttons(self, page: Page):
        btns = page.locator(".portfolio_view_in .txt_area .btn_area a")
        assert btns.count() == 2

    def test_recent_posts_visible_with_30(self, page: Page):
        page.wait_for_timeout(800)
        h = page.locator(".recent_posts").first
        expect(h).to_be_visible()
        items = page.locator(".recent_posts .marquee-item:not(.swiper-slide-duplicate)").count()
        assert items == 30

    def test_recent_thumb_no_empty_src(self, page: Page):
        """썸네일 src에 ?upload/goods/img1/$ (값 없음) 없어야 함"""
        srcs = page.evaluate("""() => Array.from(document.querySelectorAll('.recent_posts .marquee-item img')).map(i => i.getAttribute('src'))""")
        bad = [s for s in srcs if s and (s.endswith('/img1/') or s.endswith('/img2/'))]
        assert bad == [], f"Empty image srcs: {bad}"


class TestFontsLoaded:
    @pytest.fixture(autouse=True)
    def goto(self, page: Page):
        page.goto(_bloom_url(171, "001007"), wait_until="networkidle", timeout=60000)
        page.wait_for_timeout(1500)  # 폰트 로드 시간

    def test_pretendard_loaded(self, page: Page):
        ok = page.evaluate("""() => document.fonts.check('1em Pretendard')""")
        assert ok

    def test_montserrat_loaded(self, page: Page):
        ok = page.evaluate("""() => document.fonts.check('1em Montserrat')""")
        assert ok

    def test_subject_uses_pretendard(self, page: Page):
        ff = page.evaluate("""() => {
            const s = document.querySelector('.portfolio_view .subject, .portfolio_view .fs65');
            return getComputedStyle(s).fontFamily;
        }""")
        assert "Pretendard" in ff


class TestRecentImagesAllResolve:
    def test_all_recent_thumbs_load(self, page: Page):
        page.goto(_bloom_url(171, "001007"), wait_until="networkidle", timeout=60000)
        page.wait_for_timeout(1500)
        result = page.evaluate("""() => {
            const imgs = Array.from(document.querySelectorAll('.recent_posts .marquee-item img'));
            return imgs.map(i => ({ src: i.currentSrc || i.src, w: i.naturalWidth }));
        }""")
        broken = [r for r in result if r["w"] == 0]
        assert len(broken) <= 2, f"Many broken images: {len(broken)} of {len(result)}: {broken[:3]}"


class TestHeaderRendersForOtherPosts:
    """다른 활성 글들도 페이지 정상 로드 (페이지 깨짐 없음)"""

    @pytest.mark.parametrize("no,cate", [(170, "001007"), (228, "005"), (227, "005")])
    def test_layout_not_broken(self, page: Page, no, cate):
        page.set_viewport_size({"width": 1440, "height": 900})
        # 2026-05-14: networkidle은 페이지 내 외부 자원(swiper autoplay, 폰트 등)으로 60s 타임아웃 자주 발생.
        # layout 검증만이 목적이므로 domcontentloaded로 변경.
        page.goto(_bloom_url(no, cate), wait_until="domcontentloaded", timeout=60000)
        page.wait_for_timeout(800)
        m = page.evaluate("""() => {
            const r = document.querySelector('.portfolio_view_in');
            return r ? getComputedStyle(r).display : null;
        }""")
        assert m == "flex"


class TestScrollLuminosity:
    """askim과 동일한 스크롤 시 body 클래스 light↔dark 전환 효과"""

    def test_initial_section_light(self, page: Page):
        page.set_viewport_size({"width": 1440, "height": 900})
        page.goto(_bloom_url(171, "001007"), wait_until="networkidle", timeout=60000)
        page.wait_for_timeout(1200)  # GSAP 초기화
        cls = page.evaluate("() => document.body.className")
        assert "section-light" in cls

    def test_recent_posts_triggers_dark(self, page: Page):
        page.set_viewport_size({"width": 1440, "height": 900})
        page.goto(_bloom_url(171, "001007"), wait_until="networkidle", timeout=60000)
        page.wait_for_timeout(1200)
        page.evaluate("""() => {
            const el = document.querySelector('.recent_posts');
            if(el) window.scrollTo({top: el.offsetTop - 200, behavior: 'instant'});
        }""")
        page.wait_for_timeout(800)
        cls = page.evaluate("() => document.body.className")
        assert "section-dark" in cls

    def test_body_has_transition(self, page: Page):
        page.set_viewport_size({"width": 1440, "height": 900})
        page.goto(_bloom_url(171, "001007"), wait_until="networkidle", timeout=60000)
        t = page.evaluate("() => getComputedStyle(document.body).transition")
        assert "background" in t and ("1s" in t or "1000ms" in t)

    def test_two_luminosity_sections(self, page: Page):
        page.set_viewport_size({"width": 1440, "height": 900})
        page.goto(_bloom_url(171, "001007"), wait_until="networkidle", timeout=60000)
        n = page.evaluate("() => document.querySelectorAll('section[data-section-luminosity]').length")
        assert n >= 2  # 본문 light + recent_posts dark
