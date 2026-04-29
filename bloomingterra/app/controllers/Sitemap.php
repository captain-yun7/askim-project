<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dynamic sitemap controller — bloomingterra.com
 * Route: /sitemap.xml -> Sitemap::index() (config/routes.php에 매핑)
 *
 * 데이터:
 *  - 정적 페이지 (홈, 회사 소개, 서비스 약관 등)
 *  - 카테고리 list 페이지 (da_category)
 *  - goods view (da_goods)
 *  - board list / board view (da_board_manage + da_board_<code>)
 *
 * robots.txt 차단 경로(/admin/, /app/, /system/, /_compile/, /data/, /lib/admin/)는 미포함.
 */
class Sitemap extends FRONT_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function index() {
		$base = 'https://bloomingterra.com';
		$urls = [];

		// ── 정적 페이지
		$urls[] = ['loc' => $base . '/',                     'priority' => '1.0', 'changefreq' => 'weekly'];
		$urls[] = ['loc' => $base . '/company/introduce',    'priority' => '0.7', 'changefreq' => 'monthly'];
		$urls[] = ['loc' => $base . '/company/history',      'priority' => '0.5', 'changefreq' => 'yearly'];
		$urls[] = ['loc' => $base . '/company/location',     'priority' => '0.5', 'changefreq' => 'yearly'];
		$urls[] = ['loc' => $base . '/company/work',         'priority' => '0.5', 'changefreq' => 'yearly'];
		$urls[] = ['loc' => $base . '/goods/goods_list',     'priority' => '0.9', 'changefreq' => 'weekly'];
		$urls[] = ['loc' => $base . '/service/agreement',    'priority' => '0.3', 'changefreq' => 'yearly'];

		// ── 카테고리 list 페이지 (3자리 top categories)
		$cat_q = $this->db->select('category')
		                  ->where('yn_use', 'y')
		                  ->order_by('category', 'ASC')
		                  ->get('da_category');
		if ($cat_q) {
			foreach ($cat_q->result_array() as $row) {
				$cate = $row['category'];
				if (!preg_match('/^[0-9]+$/', $cate)) continue;
				$urls[] = [
					'loc'        => $base . '/goods/goods_list?cate=' . $cate,
					'priority'   => strlen($cate) === 3 ? '0.8' : '0.6',
					'changefreq' => 'weekly',
				];
			}
		}

		// ── goods view (활성 상태인 글만)
		$goods_q = $this->db->select('no, category, regdt, moddt')
		                    ->where('yn_state', 'y')
		                    ->order_by('no', 'DESC')
		                    ->get('da_goods');
		if ($goods_q) {
			foreach ($goods_q->result_array() as $row) {
				$loc = $base . '/goods/goods_view?no=' . (int)$row['no'];
				if (!empty($row['category'])) $loc .= '&cate=' . $row['category'];
				$lastmod = !empty($row['moddt']) ? $row['moddt'] : $row['regdt'];
				$entry = [
					'loc'        => $loc,
					'priority'   => '0.6',
					'changefreq' => 'monthly',
				];
				if (!empty($lastmod)) {
					$entry['lastmod'] = date('c', strtotime($lastmod));
				}
				$urls[] = $entry;
			}
		}

		// ── board list + board view (게시판별)
		$board_q = $this->db->select('code, name')
		                    ->where('yn_display_list', 'y')
		                    ->order_by('sort', 'ASC')
		                    ->get('da_board_manage');
		if ($board_q) {
			foreach ($board_q->result_array() as $brow) {
				$code = $brow['code'];
				if (!preg_match('/^[a-z_]+$/i', $code)) continue;

				// list 페이지
				$urls[] = [
					'loc'        => $base . '/board/board_list?code=' . $code,
					'priority'   => '0.8',
					'changefreq' => 'weekly',
				];

				// view 페이지 (각 게시판 테이블)
				$table = 'da_board_' . $code;
				if (!$this->db->table_exists($table)) continue;
				$post_q = $this->db->select('no, regdt, updatedt')
				                   ->where('language', 'kor')
				                   ->where("(is_secret IS NULL OR is_secret != 'y')", null, false)
				                   ->order_by('no', 'DESC')
				                   ->get($table);
				if (!$post_q) continue;
				foreach ($post_q->result_array() as $prow) {
					$entry = [
						'loc'        => $base . '/board/board_view?code=' . $code . '&no=' . (int)$prow['no'],
						'priority'   => '0.6',
						'changefreq' => 'monthly',
					];
					$lastmod = !empty($prow['updatedt']) ? $prow['updatedt'] : $prow['regdt'];
					if (!empty($lastmod)) {
						$entry['lastmod'] = date('c', strtotime($lastmod));
					}
					$urls[] = $entry;
				}
			}
		}

		// ── 출력
		$this->output
		     ->set_content_type('application/xml; charset=UTF-8')
		     ->set_header('X-Robots-Tag: noindex');

		$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
		foreach ($urls as $u) {
			$xml .= "  <url>\n";
			$xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
			if (!empty($u['lastmod']))    $xml .= '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
			if (!empty($u['changefreq'])) $xml .= '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
			if (!empty($u['priority']))   $xml .= '    <priority>' . $u['priority'] . "</priority>\n";
			$xml .= "  </url>\n";
		}
		$xml .= '</urlset>' . "\n";

		$this->output->set_output($xml);
	}
}
