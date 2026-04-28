"""
Phase 2-5 E2E — 에스킴 동적 sitemap 검증
- /sitemap.xml 요청이 PHP로 라우팅되어 wiz_bbs portfolio 글 동적 출력
- robots.txt가 sitemap을 참조
- 정적 sitemap(80 URL) 대비 portfolio view 글 누락 없음
"""
import re
from urllib.parse import unquote
import pytest
import requests
from playwright.sync_api import Page

BASE = "https://www.askim.kr"
SITEMAP_URL = f"{BASE}/sitemap.xml"


@pytest.fixture(scope="module")
def sitemap_text():
    r = requests.get(SITEMAP_URL, headers={"User-Agent": "Mozilla/5.0"}, timeout=30)
    return r


class TestSitemapBasics:
    def test_status_200(self, sitemap_text):
        assert sitemap_text.status_code == 200

    def test_xml_content_type(self, sitemap_text):
        ct = sitemap_text.headers.get("content-type", "")
        assert "xml" in ct.lower()

    def test_xml_well_formed(self, sitemap_text):
        body = sitemap_text.text
        assert body.startswith("<?xml")
        assert "<urlset" in body
        assert "</urlset>" in body

    def test_sitemap_announced_in_robots(self):
        r = requests.get(f"{BASE}/robots.txt", timeout=10)
        assert "sitemap.xml" in r.text.lower()


class TestSitemapContent:
    def test_home_present(self, sitemap_text):
        assert f"<loc>{BASE}/</loc>" in sitemap_text.text

    def test_portfolio_list_present(self, sitemap_text):
        assert f"{BASE}/portfolio/portfolio.php</loc>" in sitemap_text.text

    def test_portfolio_view_count_at_least_30(self, sitemap_text):
        """이전 정적 sitemap은 portfolio view 글이 ~12개. 동적 후 30+"""
        view_count = sitemap_text.text.count("ptype=view")
        assert view_count >= 30, f"expected >=30 portfolio view URLs, got {view_count}"

    def test_no_garbage_query_params(self, sitemap_text):
        """이전 정적 sitemap의 ?page=1, code_page=portfolio&pos= 같은 쓰레기 없음"""
        body = sitemap_text.text
        assert "code_page=" not in body
        assert "&page=1&code=" not in body
        assert "pos=" not in body

    def test_lastmod_in_iso_format(self, sitemap_text):
        """portfolio view에 ISO 8601 lastmod (date('c'))"""
        lastmods = re.findall(r"<lastmod>([^<]+)</lastmod>", sitemap_text.text)
        assert len(lastmods) > 0
        # ISO 8601 with timezone: 2025-07-23T16:00:29+00:00
        for lm in lastmods[:5]:
            assert re.match(r"^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}", lm), lm


class TestSitemapURLsResolve:
    def test_first_portfolio_view_loads(self, sitemap_text):
        """sitemap에 있는 첫 portfolio view URL이 200 응답"""
        m = re.search(
            r"<loc>(https://www\.askim\.kr/portfolio/portfolio\.php\?ptype=view&amp;idx=\d+[^<]*)</loc>",
            sitemap_text.text,
        )
        assert m
        url = m.group(1).replace("&amp;", "&")
        r = requests.get(url, headers={"User-Agent": "Mozilla/5.0"}, timeout=20, allow_redirects=True)
        assert r.status_code == 200


class TestSitemapNoStaleURLs:
    """이전 정적 sitemap에는 idx=2~18 정도만. 새 sitemap엔 최신 글 idx=46+ 포함"""

    def test_recent_portfolio_present(self, sitemap_text):
        # idx=46 (TIRTIR 같은 비교적 최신 글)
        assert "idx=46" in sitemap_text.text

    def test_includes_idx_above_20(self, sitemap_text):
        """idx 30+ 의 글이 적어도 1개"""
        ids = [int(m) for m in re.findall(r"idx=(\d+)", sitemap_text.text)]
        assert max(ids) >= 30, f"max idx in sitemap is {max(ids)}"
