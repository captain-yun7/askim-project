"""
Phase 1 SEO E2E 검증 — askim.kr / bloomingterra.com
수정 항목: OG 태그, Twitter Card, Schema.org, robots.txt, sitemap.xml, canonical
"""
import re
import json
import pytest
from playwright.sync_api import Page, expect


ASKIM = "https://www.askim.kr"
BLOOM = "https://bloomingterra.com"


# ──────────────────────────────────────────────
# 헬퍼
# ──────────────────────────────────────────────

def get_meta(page: Page, selector: str) -> str:
    el = page.query_selector(selector)
    return el.get_attribute("content") if el else ""


def get_og(page: Page, prop: str) -> str:
    return get_meta(page, f'meta[property="og:{prop}"]')


def get_tw(page: Page, name: str) -> str:
    return get_meta(page, f'meta[name="twitter:{name}"]')


def get_ld_json(page: Page) -> list[dict]:
    scripts = page.query_selector_all('script[type="application/ld+json"]')
    result = []
    for s in scripts:
        try:
            result.append(json.loads(s.inner_text()))
        except Exception:
            pass
    return result


# ──────────────────────────────────────────────
# 에스킴 (askim.kr)
# ──────────────────────────────────────────────

class TestAskimSEO:
    @pytest.fixture(autouse=True)
    def goto_main(self, page: Page):
        page.goto(ASKIM, wait_until="domcontentloaded")

    def test_title_exists(self, page: Page):
        assert page.title() != "", "title 태그가 비어있음"

    def test_description(self, page: Page):
        desc = get_meta(page, 'meta[name="description"]')
        assert len(desc) > 10, f"description 없거나 너무 짧음: '{desc}'"

    def test_no_nonstandard_metas(self, page: Page):
        for name in ["subject", "publisher", "Classification"]:
            el = page.query_selector(f'meta[name="{name}"]')
            assert el is None, f"비표준 meta[name={name}] 여전히 존재"

    def test_og_type(self, page: Page):
        assert get_og(page, "type") == "website"

    def test_og_title(self, page: Page):
        assert len(get_og(page, "title")) > 0, "og:title 없음"

    def test_og_url(self, page: Page):
        url = get_og(page, "url")
        assert url.startswith("https://"), f"og:url 이상함: {url}"

    def test_og_site_name(self, page: Page):
        assert get_og(page, "site_name") != "", "og:site_name 없음"

    def test_og_description(self, page: Page):
        assert len(get_og(page, "description")) > 0, "og:description 없음"

    def test_og_locale(self, page: Page):
        assert get_og(page, "locale") == "ko_KR", "og:locale가 ko_KR이 아님"

    def test_og_image(self, page: Page):
        img = get_og(page, "image")
        assert img.startswith("http"), f"og:image URL 이상함: {img}"

    def test_og_image_dimensions(self, page: Page):
        assert get_og(page, "image:width") == "1200", "og:image:width != 1200"
        assert get_og(page, "image:height") == "630", "og:image:height != 630"

    def test_twitter_card(self, page: Page):
        assert get_tw(page, "card") == "summary_large_image", "twitter:card 없거나 값 이상"

    def test_twitter_title(self, page: Page):
        assert len(get_tw(page, "title")) > 0, "twitter:title 없음"

    def test_twitter_description(self, page: Page):
        assert len(get_tw(page, "description")) > 0, "twitter:description 없음"

    def test_twitter_image(self, page: Page):
        img = get_tw(page, "image")
        assert img.startswith("http"), f"twitter:image URL 이상함: {img}"

    def test_canonical(self, page: Page):
        el = page.query_selector('link[rel="canonical"]')
        assert el is not None, "canonical 없음"
        href = el.get_attribute("href")
        assert "askim.kr" in href, f"canonical 도메인 이상: {href}"

    def test_schema_org(self, page: Page):
        schemas = get_ld_json(page)
        org = next((s for s in schemas if s.get("@type") == "Organization"), None)
        assert org is not None, "Organization Schema.org 없음"
        assert org.get("url") == "https://www.askim.kr", "schema url 이상"
        assert org.get("logo", "").startswith("http"), "schema logo 없음"
        assert len(org.get("sameAs", [])) > 0, "sameAs 비어있음"

    def test_robots_txt(self, page: Page):
        resp = page.goto(f"{ASKIM}/robots.txt")
        assert resp.status in (200, 403), f"robots.txt 응답 이상: {resp.status}"

    def test_sitemap_xml(self, page: Page):
        resp = page.goto(f"{ASKIM}/sitemap.xml")
        assert resp.status == 200, f"sitemap.xml 응답 이상: {resp.status}"
        assert "urlset" in page.content() or "sitemapindex" in page.content()


# ──────────────────────────────────────────────
# 블루밍테라 (bloomingterra.com)
# ──────────────────────────────────────────────

class TestBloomingterraSEO:
    @pytest.fixture(autouse=True)
    def goto_main(self, page: Page):
        page.goto(BLOOM, wait_until="domcontentloaded")

    def test_title_exists(self, page: Page):
        assert page.title() != "", "title 태그 비어있음"

    def test_description_tag_exists(self, page: Page):
        """description 태그가 마크업에 존재하는지 (값은 관리자 입력 필요)"""
        el = page.query_selector('meta[name="description"]')
        assert el is not None, "description meta 태그 자체가 없음"

    @pytest.mark.xfail(reason="관리자 패널에서 SEO description 입력 필요", strict=False)
    def test_description_has_content(self, page: Page):
        desc = get_meta(page, 'meta[name="description"]')
        assert len(desc) > 0, "description이 비어있음 — 관리자 패널에서 입력 필요"

    def test_og_type(self, page: Page):
        assert get_og(page, "type") == "website"

    def test_og_site_name(self, page: Page):
        assert get_og(page, "site_name") == "블루밍테라", f"og:site_name 이상: {get_og(page, 'site_name')}"

    def test_og_locale(self, page: Page):
        assert get_og(page, "locale") == "ko_KR", "og:locale가 ko_KR이 아님"

    def test_og_url(self, page: Page):
        url = get_og(page, "url")
        assert "bloomingterra.com" in url, f"og:url 이상: {url}"

    def test_og_image_dimensions_in_template(self, page: Page):
        """이미지 설정 시 width/height 1200x630이 출력되는지 — og:image 있을 때만 렌더링"""
        img = get_og(page, "image")
        if img:
            width = get_og(page, "image:width")
            height = get_og(page, "image:height")
            assert width == "1200", f"og:image:width != 1200 (현재: {width})"
            assert height == "630", f"og:image:height != 630 (현재: {height})"
        else:
            pytest.skip("og:image 미설정 — 관리자 패널에서 이미지 등록 후 재검증 필요")

    def test_twitter_card(self, page: Page):
        assert get_tw(page, "card") == "summary_large_image", "twitter:card 없거나 이상"

    @pytest.mark.xfail(reason="관리자 패널에서 og_title 입력 필요", strict=False)
    def test_twitter_title(self, page: Page):
        assert len(get_tw(page, "title")) > 0, "twitter:title 비어있음 — og_title 입력 필요"

    def test_canonical(self, page: Page):
        el = page.query_selector('link[rel="canonical"]')
        assert el is not None, "canonical 없음"
        href = el.get_attribute("href")
        assert "bloomingterra.com" in href, f"canonical 이상: {href}"

    def test_schema_org(self, page: Page):
        schemas = get_ld_json(page)
        org = next((s for s in schemas if s.get("@type") == "Organization"), None)
        assert org is not None, "Organization Schema.org 없음"
        assert org.get("url") == "https://bloomingterra.com", f"schema url 이상: {org.get('url')}"
        assert org.get("logo", "").startswith("http"), "schema logo 없음"

    def test_robots_txt(self, page: Page):
        resp = page.goto(f"{BLOOM}/robots.txt")
        assert resp.status == 200, f"robots.txt 응답 이상: {resp.status}"

    def test_sitemap_xml(self, page: Page):
        resp = page.goto(f"{BLOOM}/sitemap.xml")
        assert resp.status == 200, f"sitemap.xml 응답 이상: {resp.status}"
        assert "urlset" in page.content() or "sitemapindex" in page.content()
