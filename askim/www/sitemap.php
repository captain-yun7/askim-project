<?php
/**
 * 동적 sitemap.xml — 에스킴 (askim.kr)
 * - 호출: /sitemap.xml (.htaccess RewriteRule이 sitemap.php로 라우팅)
 * - 데이터: wiz_bbs (portfolio code) + wiz_bbscat (카테고리)
 * - robots.txt 차단 경로(/twcenter/, /upload/, /report*.html 등)는 미포함
 */

header('Content-Type: application/xml; charset=UTF-8');
header('X-Robots-Tag: noindex'); // sitemap 자체는 색인 안 함

require_once $_SERVER['DOCUMENT_ROOT'] . '/twcenter/dbcon.php';

$conn = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
    http_response_code(500);
    echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>';
    exit;
}
mysqli_set_charset($conn, 'utf8mb4');

$base = 'https://www.askim.kr';
$urls = [];

// ── 정적 페이지 (운영 중 색인 가치 있는 곳만)
$urls[] = ['loc' => $base . '/',                          'priority' => '1.0', 'changefreq' => 'weekly'];
$urls[] = ['loc' => $base . '/portfolio/portfolio.php',   'priority' => '0.9', 'changefreq' => 'weekly'];
$urls[] = ['loc' => $base . '/other/privacy.php',         'priority' => '0.3', 'changefreq' => 'yearly'];

// ── 포트폴리오 카테고리 list 페이지
$cat_sql = "SELECT idx FROM wiz_bbscat WHERE code='portfolio' ORDER BY idx ASC";
if ($cat_q = mysqli_query($conn, $cat_sql)) {
    while ($row = mysqli_fetch_assoc($cat_q)) {
        $urls[] = [
            'loc'        => $base . '/portfolio/portfolio.php?ptype=list&code=portfolio&category=' . (int)$row['idx'],
            'priority'   => '0.7',
            'changefreq' => 'weekly',
        ];
    }
    mysqli_free_result($cat_q);
}

// ── 포트폴리오 view 글 (최신순)
$view_sql = "SELECT idx, category, wdate
             FROM wiz_bbs
             WHERE code='portfolio'
               AND (privacy IS NULL OR privacy != 'Y')
             ORDER BY prino DESC";
if ($view_q = mysqli_query($conn, $view_sql)) {
    while ($row = mysqli_fetch_assoc($view_q)) {
        $loc = $base . '/portfolio/portfolio.php?ptype=view&idx=' . (int)$row['idx'];
        if (!empty($row['category'])) {
            $loc .= '&category=' . (int)$row['category'];
        }
        $entry = [
            'loc'        => $loc,
            'priority'   => '0.6',
            'changefreq' => 'monthly',
        ];
        if (!empty($row['wdate']) && is_numeric($row['wdate'])) {
            $entry['lastmod'] = date('c', (int)$row['wdate']);
        }
        $urls[] = $entry;
    }
    mysqli_free_result($view_q);
}

mysqli_close($conn);

// ── 출력
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
    if (!empty($u['lastmod']))    echo '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
    if (!empty($u['changefreq'])) echo '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
    if (!empty($u['priority']))   echo '    <priority>' . $u['priority'] . "</priority>\n";
    echo "  </url>\n";
}
echo '</urlset>' . "\n";
