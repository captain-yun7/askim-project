<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	'adm_menu' => array(
		'auth' => array(
			'name' => '기본설정',
			'default' => 'language_reg',
			'low_menu' => array(
                array(
					'name' => '사용 언어설정',
					'segment' => 'language_reg',
				), array(
					'name' => '국가 정보 설정',
					'segment' => 'country_manage',
				), array(
					'name' => '스킨 설정',
					'segment' => 'manage_skin',
				), array(
					'name' => '기본 정보 설정',
					'segment' => 'conf_reg',
				), array(
					'name' => '검색엔진 최적화(SEO)',
					'segment' => 'search_engine_opt',
				), array(
					'name' => '약관 및 개인정보정책',
					'segment' => 'terms_list',
				), array(
					'name' => '메뉴 설정',
					'segment' => 'menu_manage',
				), array(
					'name' => '메인 상품진열',
					'segment' => 'display_main_list',
				), array(
					'name' => '회원 필드세팅',
					'segment' => 'member_field',
				), array(
					'name' => '게시판 관리',
					'segment' => 'board_manage',
				), array(
					'name' => '개발자모드',
					'segment' => 'debug_mode',
				)
			)
		),
		'menu' => array(
			'name' => '메인 설정',
			'default' => 'main_image_slide',
			'low_menu' => array(
				//array(
					//'name' => '메인관리',
					//'segment' => '',
				//),
				array(
					'name' => '메인 슬라이드 설정',
					'segment' => 'main_image_slide',
				)
			)
		),
		'member' => array(
			'name' => '회원',
			'default' => 'member_list',
			'low_menu' => array(
				array(
					'name' => '회원 리스트',
					'segment' => 'member_list',
				),
				array(
					'name' => '회원 등록',
					'segment' => 'member_reg',
				),
				array(
					'name' => '회원 등급관리',
					'segment' => 'member_grade',
				),
				array(
					'name' => '휴면회원 관리',
					'segment' => 'member_dormant_list',
				),
				array(
					'name' => '탈퇴회원 리스트',
					'segment' => 'member_withdrawal_list',
				),
				array(
					'name' => '관리자 등급 리스트',
					'segment' => 'member_auth',
				),
				array(
					'name' => '관리자 등급 등록',
					'segment' => 'member_auth_reg',
				),
			),
		),
		'board' => array(
			'name' => '게시판',
			'default' => 'board_list',
			'low_menu' => array(
				array(
					'name' => '게시글 관리',
					'segment' => 'board_list',
				),
			)
		),
		'goods' => array(
			'name' => '상품',
			'default' => 'goods_list',
			'low_menu' => array(
				array(
					'name' => '상품 필드 세팅',
					'segment' => 'goods_field',
				),
				array(
					'name' => '상품 리스트',
					'segment' => 'goods_list',
				),
				array(
					'name' => '상품 등록',
					'segment' => 'goods_reg',
				),
				array(
					'name' => '카테고리 등록',
					'segment' => 'category_reg',
				),
				array(
					'name' => '카테고리 수정',
					'segment' => 'category_list',
				),
			)
		),
		'popup' => array(
			'name' => '팝업 설정',
			'default' => 'popup_list',
			'low_menu' => array(
				array(
					'name' => '팝업 리스트',
					'segment' => 'popup_list',
				),
				array(
					'name' => '팝업 등록',
					'segment' => 'popup_reg',
				),
			)
		),
	),
);