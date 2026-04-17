<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	'auth' => array(
		'99'	=> array(
			'menu'	=> array(
				'main_image_slide', 
			),
			'member'	=> array(
				'member_list', 				'member_reg', 				'member_grade', 				'member_dormant_list', 				'member_withdrawal_list', 				'member_auth', 				'member_auth_reg', 
			),
			'board'	=> array(
				'board_list', 
			),
			'goods'	=> array(
				'goods_field', 				'goods_list', 				'goods_reg', 				'category_reg', 				'category_list', 
			),
			'popup'	=> array(
				'popup_list', 				'popup_reg', 
			),
		),
		'98'	=> array(
			'member'	=> array(
				'member_list', 				'member_reg', 				'member_grade', 				'member_dormant_list', 				'member_withdrawal_list', 				'member_auth', 				'member_auth_reg', 
			),
			'board'	=> array(
				'board_list', 
			),
			'goods'	=> array(
				'goods_list', 				'goods_reg', 				'category_reg', 				'category_list', 
			),
			'popup'	=> array(
				'popup_list', 				'popup_reg', 
			),
		),
	),
);
