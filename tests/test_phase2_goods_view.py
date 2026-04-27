"""
Phase 2 E2E — bloomingterra goods_view 레이아웃 + Recent Posts 검증
대상 페이지: https://www.bloomingterra.com/goods/goods_view?no=171&cate=001007
"""
import pytest
from playwright.sync_api import Page, expect


GOODS_VIEW = "https://www.bloomingterra.com/goods/goods_view?no=171&cate=001007"


@pytest.fixture(autouse=True)
def goto_view(page: Page):
    page.goto(GOODS_VIEW, wait_until="domcontentloaded")


class TestPortfolioLayout:
    def test_portfolio_view_root_exists(self, page: Page):
        expect(page.locator(".portfolio_view")).to_have_count(1)
        expect(page.locator(".portfolio_view_in")).to_have_count(1)

    def test_two_columns_present(self, page: Page):
        expect(page.locator(".portfolio_view_in .txt_area")).to_have_count(1)
        expect(page.locator(".portfolio_view_in .con_area")).to_have_count(1)

    def test_subject_rendered(self, page: Page):
        subject = page.locator(".portfolio_view .subject")
        expect(subject).to_be_visible()
        assert subject.text_content().strip() != ""

    def test_date_rendered(self, page: Page):
        date = page.locator(".portfolio_view .txt_area .date")
        expect(date).to_be_visible()
        # YYYY. MM 패턴
        assert any(c.isdigit() for c in date.text_content())

    def test_info_dl_present(self, page: Page):
        info = page.locator(".portfolio_view_in .txt_area .info dl")
        # Category + Date 최소 2개 + extraFieldData 매핑된 것들
        assert info.count() >= 2

    def test_btn_area_buttons(self, page: Page):
        btn = page.locator(".portfolio_view_in .txt_area .btn_area a")
        # Contact Us + List 2개
        expect(btn).to_have_count(2)
        expect(page.locator(".btn_area .contact_btn")).to_be_visible()
        expect(page.locator(".btn_area .btn_w_ver2")).to_be_visible()

    def test_view_button_navigation(self, page: Page):
        # PREV / LIST / NEXT
        expect(page.locator(".portfolio_view .view_button .page_btn").first).to_be_visible()
        expect(page.locator(".portfolio_view .view_button .list_btn")).to_have_count(1)

    def test_css_loaded(self, page: Page):
        # portfolio_view.css가 적용됐는지: portfolio_view_in이 flex 컨테이너인지
        display = page.evaluate(
            """() => {
                const el = document.querySelector('.portfolio_view_in');
                return el ? getComputedStyle(el).display : null;
            }"""
        )
        assert display == "flex"

    def test_material_symbols_link(self, page: Page):
        link = page.locator('link[href*="Material+Symbols+Outlined"]')
        expect(link).to_have_count(1)


class TestRecentPosts:
    def test_section_present(self, page: Page):
        expect(page.locator(".portfolio_view .recent_posts")).to_have_count(1)

    def test_heading(self, page: Page):
        heading = page.locator(".recent_posts .fs50")
        expect(heading).to_be_visible()
        assert "Recent" in heading.text_content()

    def test_thirty_items(self, page: Page):
        # Swiper loop 모드가 앞뒤로 슬라이드를 복제하므로 duplicate 제외
        items = page.locator(".recent_posts .marquee-item:not(.swiper-slide-duplicate)")
        assert items.count() == 30

    def test_each_item_has_thumb_and_desc(self, page: Page):
        # active 슬라이드(현재 보이는 것) 기준으로 검증
        page.wait_for_timeout(500)
        active = page.locator(".recent_posts .swiper-slide-active")
        expect(active.locator(".thumb img")).to_have_count(1)
        title = active.locator(".desc .fs25").text_content().strip()
        assert title != ""
        loca = active.locator(".desc .loca").text_content().strip()
        assert loca != ""

    def test_swiper_initialized(self, page: Page):
        # swiper init 후 swiper-wrapper에 swiper 인스턴스 attached
        page.wait_for_timeout(800)
        initialized = page.evaluate(
            """() => {
                const wrapper = document.querySelector('.recent_posts .marquee-wrapper');
                return wrapper && wrapper.classList.contains('swiper-container-initialized');
            }"""
        )
        # Swiper 6+: swiper-initialized 클래스 부여
        # Swiper 4-5: swiper-container-initialized
        # 둘 중 하나라도 있으면 OK
        also = page.evaluate(
            """() => {
                const wrapper = document.querySelector('.recent_posts .marquee-wrapper');
                return wrapper && wrapper.classList.contains('swiper-initialized');
            }"""
        )
        assert initialized or also

    def test_pagination_fraction_visible(self, page: Page):
        page.wait_for_timeout(800)
        pagination = page.locator(".recent_posts .marquee-pagination")
        expect(pagination).to_be_visible()
        # fraction current/total 채워졌는지
        text = pagination.text_content().strip()
        assert "/" in text or any(c.isdigit() for c in text)

    def test_navigation_buttons_visible(self, page: Page):
        page.wait_for_timeout(800)
        expect(page.locator(".recent_posts .arrow-prev")).to_be_visible()
        expect(page.locator(".recent_posts .arrow-next")).to_be_visible()

    def test_link_has_correct_href(self, page: Page):
        first_link = page.locator(".recent_posts .marquee-item a").first
        href = first_link.get_attribute("href")
        assert href and href.startswith("/goods/goods_view?no=") and "&cate=" in href

    def test_thumb_img_loads(self, page: Page):
        # 첫 번째 아이템의 이미지가 404 또는 noimg.gif fallback인지
        page.wait_for_load_state("networkidle")
        first_img = page.locator(".recent_posts .marquee-item .thumb img").first
        natural_w = first_img.evaluate("el => el.naturalWidth")
        # 이미지가 로드됐다면 naturalWidth > 0
        assert natural_w > 0


class TestMobileLayout:
    def test_mobile_stacks_columns(self, page: Page):
        page.set_viewport_size({"width": 600, "height": 900})
        page.reload(wait_until="domcontentloaded")
        # 모바일에선 portfolio_view_in이 wrap돼서 txt_area, con_area가 100% width
        wraps = page.evaluate(
            """() => {
                const inEl = document.querySelector('.portfolio_view_in');
                return inEl ? getComputedStyle(inEl).flexWrap : null;
            }"""
        )
        assert wraps == "wrap"
