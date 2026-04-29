"""
Phase 2-6 E2E — 블루밍테라 동적 sitemap 검증
- /sitemap.xml -> CI Sitemap 컨트롤러 (config/routes.php 매핑)
- 데이터: 정적 + 카테고리 list + goods view + board list/view 모두 포함
- nginx 캐시 회피 위해 query string으로 cache-bust
"""
import re
import time
import pytest
import requests

BASE = "https://www.bloomingterra.com"


@pytest.fixture(scope="module")
def sitemap():
    """cache-bust로 새 응답 강제 → 우리 컨트롤러 동작 확인"""
    r = requests.get(
        f"{BASE}/sitemap.xml?_t={int(time.time())}",
        headers={"User-Agent": "Mozilla/5.0", "Cache-Control": "no-cache"},
        timeout=30,
    )
    return r


class TestBasics:
    def test_status_200(self, sitemap):
        assert sitemap.status_code == 200

    def test_xml_content_type(self, sitemap):
        ct = sitemap.headers.get("content-type", "")
        assert "xml" in ct.lower()

    def test_well_formed_xml(self, sitemap):
        body = sitemap.text
        assert body.startswith("<?xml")
        assert "<urlset" in body
        assert "</urlset>" in body

    def test_robots_announces_sitemap(self):
        r = requests.get(f"{BASE}/robots.txt", timeout=10)
        assert "sitemap.xml" in r.text.lower()


class TestContent:
    def test_home_present(self, sitemap):
        assert f"<loc>{BASE.replace('www.', '')}/</loc>" in sitemap.text or f"<loc>{BASE}/</loc>" in sitemap.text

    def test_company_pages(self, sitemap):
        for p in ["/company/introduce", "/company/history", "/company/location", "/company/work"]:
            assert p in sitemap.text, f"missing {p}"

    def test_goods_list_present(self, sitemap):
        assert "/goods/goods_list" in sitemap.text

    def test_board_list_pages_per_code(self, sitemap):
        # 최소 gallery(Insight)는 등록되어 있어야
        assert "board_list?code=gallery" in sitemap.text

    def test_goods_view_count_at_least_5(self, sitemap):
        n = sitemap.text.count("goods_view?no=")
        assert n >= 5, f"expected >=5 goods view URLs, got {n}"

    def test_board_view_count_at_least_30(self, sitemap):
        """gallery만 30+ 글 있음 (라이브 확인됨)"""
        n = sitemap.text.count("board_view?code=")
        assert n >= 30, f"expected >=30 board view URLs, got {n}"

    def test_total_url_count_ge_100(self, sitemap):
        """이전 정적 sitemap은 8 URL. 동적 후 100+"""
        n = sitemap.text.count("<url>")
        assert n >= 100, f"expected >=100 total URLs, got {n}"

    def test_admin_paths_excluded(self, sitemap):
        for p in ["/admin/", "/_compile/", "/system/", "/data/"]:
            assert p not in sitemap.text, f"forbidden path {p} found"

    def test_lastmod_iso8601(self, sitemap):
        lastmods = re.findall(r"<lastmod>([^<]+)</lastmod>", sitemap.text)
        assert len(lastmods) > 0
        for lm in lastmods[:5]:
            assert re.match(r"^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}", lm), lm


class TestURLsResolve:
    def test_first_goods_view_loads(self, sitemap):
        m = re.search(r"<loc>(https://[^<]+/goods/goods_view\?no=\d+[^<]*)</loc>", sitemap.text)
        assert m
        url = m.group(1).replace("&amp;", "&")
        r = requests.get(url, headers={"User-Agent": "Mozilla/5.0"}, timeout=20)
        assert r.status_code == 200

    def test_first_board_view_loads(self, sitemap):
        m = re.search(r"<loc>(https://[^<]+/board/board_view\?code=[a-z]+&amp;no=\d+)</loc>", sitemap.text)
        assert m
        url = m.group(1).replace("&amp;", "&")
        r = requests.get(url, headers={"User-Agent": "Mozilla/5.0"}, timeout=20)
        assert r.status_code == 200
