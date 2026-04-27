"""
Phase 2-4 E2E — bloomingterra Insight 관련 게시글 2개 수동 지정 검증
- DB 사전 조건: no=82가 related_no1=81, related_no2=80을 가리킴 (테스트 setup에서 보장)
- no=81은 related_no가 NULL이므로 related_posts 섹션 비출력 (정상)
- 다른 게시판(notice)은 영향 없음 (이전 phase 테스트에서도 확인)
"""
import pymysql
import pytest
from playwright.sync_api import Page, expect

BASE = "https://www.bloomingterra.com"
DB_CFG = dict(
    host="bloomingterra.com",
    user="gcsd33_bloomingterra",
    password="bloomingterra123",
    database="gcsd33_bloomingterra",
    charset="utf8mb4",
    autocommit=True,
)


@pytest.fixture(scope="module", autouse=True)
def seed_related_posts():
    """no=82에 related 2개 세팅. 테스트 후 NULL로 복원."""
    conn = pymysql.connect(**DB_CFG)
    try:
        with conn.cursor() as cur:
            cur.execute(
                "UPDATE da_board_gallery SET related_no1=%s, related_no2=%s WHERE no=82",
                (81, 80),
            )
            cur.execute(
                "UPDATE da_board_gallery SET related_no1=NULL, related_no2=NULL WHERE no=81"
            )
        yield
    finally:
        # 테스트 후 정리: related 모두 NULL로 (운영 데이터에 영향 없게)
        with conn.cursor() as cur:
            cur.execute(
                "UPDATE da_board_gallery SET related_no1=NULL, related_no2=NULL WHERE no IN (82, 81)"
            )
        conn.close()


class TestRelatedPostsRendered:
    """no=82에 related 설정 → 관련 게시글 섹션 렌더링"""

    @pytest.fixture(autouse=True)
    def goto(self, page: Page):
        page.goto(f"{BASE}/board/board_view?code=gallery&no=82", wait_until="domcontentloaded")

    def test_section_present(self, page: Page):
        expect(page.locator(".related_posts")).to_have_count(1)

    def test_heading_text(self, page: Page):
        heading = page.locator(".related_posts .rp_heading")
        expect(heading).to_be_visible()
        assert "Related" in heading.text_content()

    def test_two_items(self, page: Page):
        items = page.locator(".related_posts .rp_list > li")
        assert items.count() == 2

    def test_first_item_links_to_no_81(self, page: Page):
        href = page.locator(".related_posts .rp_list > li").first.locator("a").get_attribute("href")
        assert href == "/board/board_view?code=gallery&no=81"

    def test_second_item_links_to_no_80(self, page: Page):
        href = page.locator(".related_posts .rp_list > li").nth(1).locator("a").get_attribute("href")
        assert href == "/board/board_view?code=gallery&no=80"

    def test_each_item_has_title_thumb_date(self, page: Page):
        first = page.locator(".related_posts .rp_list > li").first
        expect(first.locator(".rp_title")).to_be_visible()
        expect(first.locator(".rp_thumb img")).to_have_count(1)
        expect(first.locator(".rp_date")).to_be_visible()

    def test_titles_match_db(self, page: Page):
        # 81번 글 제목
        first_title = page.locator(".related_posts .rp_list > li").first.locator(".rp_title").text_content().strip()
        assert "DOOH Ad Examples" in first_title

    def test_grid_layout(self, page: Page):
        # 2열 grid (데스크톱)
        cols = page.evaluate(
            """() => {
                const el = document.querySelector('.related_posts .rp_list');
                return el ? getComputedStyle(el).gridTemplateColumns.split(' ').length : 0;
            }"""
        )
        assert cols == 2


class TestRelatedPostsHidden:
    """no=81에는 related NULL → 섹션 비출력"""

    def test_no_section_when_related_null(self, page: Page):
        page.goto(f"{BASE}/board/board_view?code=gallery&no=81", wait_until="domcontentloaded")
        expect(page.locator(".related_posts")).to_have_count(0)


class TestOtherBoardsUnaffected:
    """다른 게시판은 SQL 에러 없이 정상 동작 (related_no 컬럼 미존재 테이블)"""

    def test_notice_list_loads(self, page: Page):
        response = page.goto(f"{BASE}/board/board_list?code=notice", wait_until="domcontentloaded")
        assert response.status == 200
        # related_posts 섹션은 board_list에 없음 (board_view에만 있음)
        # 페이지 자체가 에러 없이 로드되면 OK

    def test_notice_view_no_related_section(self, page: Page):
        # notice 게시판의 글이 있다면
        page.goto(f"{BASE}/board/board_list?code=notice", wait_until="domcontentloaded")
        first_link = page.locator("a[href*='board_view?code=notice']").first
        if first_link.count() > 0:
            href = first_link.get_attribute("href")
            page.goto(BASE + href, wait_until="domcontentloaded")
            expect(page.locator(".related_posts")).to_have_count(0)
