<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	'cfg_menu' => array(
		'chn' => array(
			'0' => array(
				'use' => 'y',
				'name' => '중_갤러리',
				'url' => '/board/board_list?code=gallery',
				'sort' => '0',

			),
			'1' => array(
				'use' => 'y',
				'name' => '중문_1:1문의',
				'url' => '/board/board_write?code=inquiry',
				'menu' => array(
					'1' => array(
						'use' => 'y',
						'name' => '목록페이지',
						'url' => '/board/board_list?code=inquiry',

					),
				),
			),
			'2' => array(
				'use' => 'y',
				'name' => '중문_비디오',
				'url' => '/board/board_list?code=video',

			),
			'5' => array(
				'use' => 'y',
				'name' => '중) 대메뉴',
				'url' => '/board/board_list?code=add',
				'menu' => array(
					'1' => array(
						'use' => 'y',
						'name' => '중) 서브',
						'url' => '/board/board_list?code=add',

					),
				),
			),
			'9' => array(
				'use' => 'y',
				'name' => '중_카테고리',
				'url' => '/goods/goods_list?cate=001',

			),
			'10' => array(
				'use' => 'y',
				'name' => '중_개별',
				'url' => '/company/about',
				'menu' => array(
					'1' => array(
						'use' => 'y',
						'name' => '중_회사소개',
						'url' => '/company/about',

					),
					'2' => array(
						'use' => 'y',
						'name' => '중_연혁',
						'url' => '/company/history',

					),
					'3' => array(
						'use' => 'y',
						'name' => '중_오는길',
						'url' => '/company/location',

					),
					'4' => array(
						'use' => 'y',
						'name' => '중_추가페이지1',
						'url' => '/test/test1',

					),
					'5' => array(
						'use' => 'y',
						'name' => '중_추가페이지2',
						'url' => '/test/test2',

					),
					'6' => array(
						'use' => 'y',
						'name' => '중_추가페이지3',
						'url' => '/test/test3',

					),
				),
			),
			'12' => array(
				'use' => 'y',
				'name' => '중_추가필드',
				'url' => '/board/board_list?code=add',
				'menu' => array(
					'1' => array(
						'use' => 'y',
						'name' => '0320 중문3-2',
						'url' => '/board/board_list?code=notice',

					),
				),
			),
			'13' => array(
				'use' => 'y',
				'name' => '테스트',
				'url' => '/board/board_list?code=testtesttest',

			),

		),
		'eng' => array(
			'0' => array(
				'use' => 'y',
				'name' => 'SERVICE',
				'url' => '/goods/goods_list?cate=001',
				'sort' => '0',

			),
			'1' => array(
				'use' => 'y',
				'name' => 'INSIGHT',
				'url' => '/board/board_list?code=gallery',

			),
			'2' => array(
				'use' => 'y',
				'name' => 'CONTACT',
				'url' => '/board/board_write?code=inquiry',

			),

		),
		'jpn' => array(
			'0' => array(
				'use' => 'y',
				'name' => '일_갤러리',
				'url' => '/board/board_list?code=gallery',
				'sort' => '0',

			),
			'1' => array(
				'use' => 'y',
				'name' => '일문_1:1문의',
				'url' => '/board/board_write?code=inquiry',
				'menu' => array(
					'2' => array(
						'use' => 'n',
						'name' => '목록페이지',
						'url' => '/board/board_list?code=inquiry',

					),
				),
			),
			'2' => array(
				'use' => 'y',
				'name' => '일문_비디오',
				'url' => '/board/board_list?code=video',

			),
			'3' => array(
				'use' => 'y',
				'name' => '일문_추가',
				'url' => '/board/board_list?code=add',
				'menu' => array(
					'1' => array(
						'use' => 'y',
						'name' => '일문_공지사항',
						'url' => '/board/board_list?code=notice',

					),
				),
			),
			'4' => array(
				'use' => 'y',
				'name' => '일문_카테고리',
				'url' => '/goods/goods_list?cate=001',

			),
			'5' => array(
				'use' => 'y',
				'name' => '일문_개별페이지',
				'url' => '/company/about',
				'menu' => array(
					'1' => array(
						'use' => 'y',
						'name' => '회사소개',
						'url' => '/company/about',

					),
					'2' => array(
						'use' => 'y',
						'name' => '오시는길',
						'url' => '/company/location',

					),
					'3' => array(
						'use' => 'y',
						'name' => '연혁',
						'url' => '/company/history',

					),
				),
			),
			'7' => array(
				'use' => 'y',
				'name' => '일) 대메뉴',
				'url' => '/goods/goods_list?cate=001',

			),
			'8' => array(
				'use' => 'y',
				'name' => '테스트',
				'url' => '/board/board_list?code=testtesttest',

			),

		),
		'kor' => array(
			'2' => array(
				'use' => 'y',
				'name' => 'SERVICE',
				'url' => '/goods/goods_list?cate=001',

			),
			'3' => array(
				'use' => 'y',
				'name' => 'INSIGHT',
				'url' => '/board/board_list?code=gallery',

			),
			'7' => array(
				'use' => 'y',
				'name' => 'CONTACT',
				'url' => '/board/board_write?code=inquiry',

			),

		),
	),
);