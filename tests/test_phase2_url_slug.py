"""
Phase 2-7 E2E — 블루밍테라 URL Slug (옵션 2: 신규 글 한정)
- DB 사전 조건: no=171에 slug='tirtir-pop-up-store-seongsu' 세팅
- no=170은 slug NULL (기존 글)
- fixture가 테스트 후 slug NULL로 복원
"""
import re
import pymysql
import pytest
import requests

BASE = "https://www.bloomingterra.com"
TEST_NO = 171
TEST_SLUG = "tirtir-pop-up-store-seongsu"
LEGACY_NO = 228  # slug NULL (기존 활성 글, 라이브에서 정상 응답 확인됨)

DB_CFG = dict(
    host="bloomingterra.com",
    user="gcsd33_bloomingterra",
    password="bloomingterra123",
    database="gcsd33_bloomingterra",
    charset="utf8mb4",
    autocommit=True,
)


@pytest.fixture(scope="module", autouse=True)
def seed_slug():
    conn = pymysql.connect(**DB_CFG)
    try:
        with conn.cursor() as cur:
            cur.execute("UPDATE da_goods SET slug=%s WHERE no=%s", (TEST_SLUG, TEST_NO))
            cur.execute("UPDATE da_goods SET slug=NULL WHERE no=%s", (LEGACY_NO,))
        yield
    finally:
        with conn.cursor() as cur:
            cur.execute("UPDATE da_goods SET slug=NULL WHERE no IN (%s, %s)", (TEST_NO, LEGACY_NO))
        conn.close()


class TestSlugRouting:
    def test_slug_url_200(self):
        r = requests.get(f"{BASE}/service/{TEST_SLUG}", timeout=15)
        assert r.status_code == 200

    def test_slug_url_returns_post_content(self):
        r = requests.get(f"{BASE}/service/{TEST_SLUG}", timeout=15)
        assert "TIRTIR" in r.text  # 제목
        assert "portfolio_view_in" in r.text  # Phase 2-1 레이아웃 정상 적용

    def test_unknown_slug_404(self):
        r = requests.get(f"{BASE}/service/no-such-slug-xyz-123", timeout=15)
        assert r.status_code == 404


class TestRedirect:
    def test_query_url_redirects_to_slug(self):
        r = requests.get(
            f"{BASE}/goods/goods_view?no={TEST_NO}&cate=001007",
            timeout=15,
            allow_redirects=False,
        )
        assert r.status_code == 301
        assert r.headers.get("Location") == f"/service/{TEST_SLUG}"

    def test_redirect_single_hop(self):
        """301 → 200 (chain 없음)"""
        r = requests.get(
            f"{BASE}/goods/goods_view?no={TEST_NO}&cate=001007",
            timeout=15,
            allow_redirects=True,
        )
        # history 길이 = redirect 횟수
        assert len(r.history) == 1
        assert r.history[0].status_code == 301
        assert r.status_code == 200

    def test_legacy_post_no_redirect(self):
        """slug NULL인 기존 글은 query URL이 200 응답 (redirect 없음)"""
        r = requests.get(
            f"{BASE}/goods/goods_view?no={LEGACY_NO}&cate=001007",
            timeout=15,
            allow_redirects=False,
        )
        assert r.status_code == 200


class TestCanonical:
    def test_slug_url_canonical_points_to_slug(self):
        r = requests.get(f"{BASE}/service/{TEST_SLUG}", timeout=15)
        m = re.search(r'<link rel="canonical" href="([^"]+)"', r.text)
        assert m
        assert m.group(1) == f"https://bloomingterra.com/service/{TEST_SLUG}"

    def test_legacy_post_canonical_points_to_query(self):
        """기존 글 canonical은 query URL (그대로)"""
        r = requests.get(
            f"{BASE}/goods/goods_view?no={LEGACY_NO}&cate=001007", timeout=15
        )
        m = re.search(r'<link rel="canonical" href="([^"]+)"', r.text)
        assert m
        assert "/service/" not in m.group(1)


class TestSitemap:
    def test_sitemap_uses_slug_url(self):
        import time
        r = requests.get(
            f"{BASE}/sitemap.xml?_t={int(time.time())}",
            timeout=20,
        )
        assert r.status_code == 200
        assert f"<loc>https://bloomingterra.com/service/{TEST_SLUG}</loc>" in r.text
        # query URL이 섞여있지 않아야 함 (slug 글에 한해)
        assert f"goods_view?no={TEST_NO}" not in r.text

    def test_sitemap_legacy_still_query(self):
        import time
        r = requests.get(
            f"{BASE}/sitemap.xml?_t={int(time.time())}",
            timeout=20,
        )
        assert f"goods_view?no={LEGACY_NO}" in r.text


class TestRecentPostsLink:
    def test_recent_post_helper_outputs_query_for_legacy(self):
        """recent_goods 출력 시 slug NULL인 기존 글들은 query URL 형태로 그대로 출력 (URL helper 동작 검증)"""
        r = requests.get(
            f"{BASE}/goods/goods_view?no={LEGACY_NO}&cate=005",
            timeout=15,
        )
        # recent_posts 마크업 안의 anchor 중 query URL 형태가 있어야 함
        assert re.search(r'class="marquee-item[^"]*">\s*<a href="/goods/goods_view\?no=\d+', r.text)
