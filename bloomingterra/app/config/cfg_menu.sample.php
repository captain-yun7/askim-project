<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	'cfg_menu' => array(
		'eng' => array(
			'1' => array(
				'name' => 'z',
				'url' => 'zz',
				'use' => 'y',

			),
			'2' => array(
				'name' => 'zzz',
				'url' => 'z',
				'use' => 'y',

			),
			'3' => array(
				'name' => 'zzzz',
				'url' => 'zz',
				'use' => 'y',

			),
			'4' => array(
				'name' => 'zzzz',
				'url' => 'zz',
				'use' => 'y',

			),

		),
		'kor' => array(
			'0' => array(
				'name' => '회원관련',
				'url' => '/member/join_agreement',
				'sort' => '0',
				'use' => 'y',
				'menu' => array(
					'0' => array(
						'name' => '회원가입',
						'url' => '/member/join_agreement',
						'sort' => '0',
						'use' => 'y',

					),
					'1' => array(
						'name' => '로그인',
						'url' => '/member/login',
						'use' => 'y',

					),
					'2' => array(
						'name' => '비밀번호찾기',
						'url' => '/member/find_pw',
						'use' => 'y',

					),
					'3' => array(
						'name' => '아이디찾기',
						'url' => '/member/find_id',
						'use' => 'y',

					),
					'4' => array(
						'name' => '이용약관',
						'url' => '/service/agreement',
						'use' => 'y',

					),
					'5' => array(
						'name' => '개인정보취급방침',
						'url' => '/service/usepolicy',
						'use' => 'y',

					),
					'6' => array(
						'name' => '회원정보수정',
						'url' => '/member/mypage',
						'use' => 'y',

					),
					'7' => array(
						'name' => '비밀번호변경',
						'url' => '/member/change_pw',
						'use' => 'y',

					),
					'8' => array(
						'name' => '회원탈퇴',
						'url' => '/member/withdrawal',
						'use' => 'y',

					),
				),
			),
			'1' => array(
				'name' => '게시판관련',
				'url' => '/board/board_list?code=notice',
				'use' => 'y',
				'menu' => array(
					'0' => array(
						'name' => '공지사항',
						'url' => '/board/board_list?code=notice',
						'sort' => '0',
						'use' => 'y',

					),
					'1' => array(
						'name' => '갤러리게시판',
						'url' => '/board/board_list?code=gallery',
						'use' => 'y',

					),
					'3' => array(
						'name' => '1:1문의',
						'url' => '/board/board_list?code=inquiry',
						'use' => 'y',

					),
				),
			),
			'2' => array(
				'name' => '개별페이지',
				'url' => '/company/',
				'use' => 'y',
				'menu' => array(
					'1' => array(
						'name' => '회사소개',
						'url' => '/company/',
						'use' => 'y',

					),
					'2' => array(
						'name' => '오시는길',
						'url' => '/company/location',
						'use' => 'y',

					),
					'3' => array(
						'name' => '연혁',
						'url' => '/company/history',
						'use' => 'y',

					),
				),
			),
			'3' => array(
				'name' => '제품소개',
				'url' => '/goods/goods_list?cate=001',
				'use' => 'y',
				'menu' => array(
					'1' => array(
						'name' => '제품검색',
						'url' => '/goods/goods_search?search=',
						'use' => 'y',

					),
				),
			),

		),
	),
);