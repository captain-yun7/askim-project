"""
Phase 2-3 E2E — bloomingterra Insight (board code=gallery) 웹진 스타일 검증
대상: list = /board/board_list?code=gallery
대상: view = /board/board_view?code=gallery&no=82
다른 게시판 (notice 등) 비영향도 함께 검증
"""
import pytest
from playwright.sync_api import Page, expect


BASE = "https://www.bloomingterra.com"
INSIGHT_LIST = f"{BASE}/board/board_list?code=gallery"
INSIGHT_VIEW = f"{BASE}/board/board_view?code=gallery&no=82"
OTHER_LIST = f"{BASE}/board/board_list?code=notice"


class TestInsightList:
    @pytest.fixture(autouse=True)
    def goto(self, page: Page):
        page.goto(INSIGHT_LIST, wait_until="domcontentloaded")

    def test_wrapper_class_applied(self, page: Page):
        expect(page.locator(".insight_webzine")).to_have_count(1)

    def test_css_loaded(self, page: Page):
        link = page.locator('link[href*="insight_board.css"]')
        expect(link).to_have_count(1)

    def test_grid_layout(self, page: Page):
        # gallery_list가 grid 컨테이너로 렌더링되는지 (CSS 적용 확인)
        display = page.evaluate(
            """() => {
                const el = document.querySelector('.insight_webzine .gallery_list');
                return el ? getComputedStyle(el).display : null;
            }"""
        )
        assert display == "grid"

    def test_cards_have_thumb_and_info(self, page: Page):
        first = page.locator(".insight_webzine .gallery_list > li").first
        expect(first.locator(".thumb img")).to_have_count(1)
        expect(first.locator(".info dt")).to_be_visible()
        expect(first.locator(".info > span")).to_be_visible()

    def test_card_link_format(self, page: Page):
        link = page.locator(".insight_webzine .gallery_list .cont .link").first
        href = link.get_attribute("href")
        assert href and href.startswith("/board/board_view?code=gallery&no=")

    def test_thumb_radius(self, page: Page):
        radius = page.evaluate(
            """() => {
                const el = document.querySelector('.insight_webzine .gallery_list .thumb');
                return el ? getComputedStyle(el).borderRadius : null;
            }"""
        )
        # 8px 라운드
        assert radius and radius != "0px"

    def test_search_button_styled(self, page: Page):
        # 검색 버튼이 pill 형태로 변경됐는지
        bg = page.evaluate(
            """() => {
                const el = document.querySelector('.insight_webzine .search_btn');
                return el ? getComputedStyle(el).backgroundColor : null;
            }"""
        )
        # 메인 컬러(#000) 적용
        assert bg in ("rgb(0, 0, 0)", "rgba(0, 0, 0, 1)")

    def test_material_symbols_loaded(self, page: Page):
        link = page.locator('link[href*="Material+Symbols+Outlined"]')
        expect(link).to_have_count(1)


class TestInsightView:
    @pytest.fixture(autouse=True)
    def goto(self, page: Page):
        page.goto(INSIGHT_VIEW, wait_until="domcontentloaded")

    def test_wrapper_class_applied(self, page: Page):
        expect(page.locator(".insight_webzine_view")).to_have_count(1)

    def test_css_loaded(self, page: Page):
        link = page.locator('link[href*="insight_board.css"]')
        expect(link).to_have_count(1)

    def test_subject_visible(self, page: Page):
        subject = page.locator(".insight_webzine_view .subject")
        expect(subject).to_be_visible()
        assert subject.text_content().strip() != ""

    def test_subject_static_position(self, page: Page):
        # 기존엔 absolute 중앙 → 새 디자인은 static (좌측 정렬)
        position = page.evaluate(
            """() => {
                const el = document.querySelector('.insight_webzine_view .subject');
                return el ? getComputedStyle(el).position : null;
            }"""
        )
        assert position == "static"

    def test_content_max_width_narrow(self, page: Page):
        # 본문 가독성 위한 max-width 880 적용
        max_w = page.evaluate(
            """() => {
                const el = document.querySelector('.insight_webzine_view .sub_board');
                return el ? getComputedStyle(el).maxWidth : null;
            }"""
        )
        assert max_w == "880px"

    def test_content_typography(self, page: Page):
        # cont 본문 font-size 17px
        size = page.evaluate(
            """() => {
                const el = document.querySelector('.insight_webzine_view .view_content .cont');
                return el ? parseInt(getComputedStyle(el).fontSize) : 0;
            }"""
        )
        # 데스크톱에서 17px (모바일 16/15)
        assert size >= 16

    def test_navigation_buttons(self, page: Page):
        # PREV/LIST/NEXT
        expect(page.locator(".insight_webzine_view .view_button .page_btn").first).to_be_visible()
        expect(page.locator(".insight_webzine_view .view_button .list_btn")).to_have_count(1)


class TestOtherBoardNotAffected:
    """code=gallery 외 다른 게시판은 insight 클래스/CSS 적용 안 됨 보장"""

    @pytest.fixture(autouse=True)
    def goto(self, page: Page):
        page.goto(OTHER_LIST, wait_until="domcontentloaded")

    def test_no_insight_wrapper(self, page: Page):
        expect(page.locator(".insight_webzine")).to_have_count(0)

    def test_no_insight_css(self, page: Page):
        link = page.locator('link[href*="insight_board.css"]')
        expect(link).to_have_count(0)


class TestMobileLayout:
    def test_list_mobile_one_column(self, page: Page):
        page.set_viewport_size({"width": 600, "height": 900})
        page.goto(INSIGHT_LIST, wait_until="domcontentloaded")
        # 모바일은 1 컬럼
        cols = page.evaluate(
            """() => {
                const el = document.querySelector('.insight_webzine .gallery_list');
                if (!el) return null;
                return getComputedStyle(el).gridTemplateColumns.split(' ').length;
            }"""
        )
        assert cols == 1
