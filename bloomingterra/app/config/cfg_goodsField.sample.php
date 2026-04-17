<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	'goodsField' => array(
		'name' => array(
			'kor'	=> array(
				'no'	=> '상품번호',
				'name'	=> '상품명',
				'category'	=> '카테고리코드',
				'yn_state'	=> '노출유무',
				'regdt'	=> '등록일',
				'moddt'	=> '수정일',
				'upload_path'	=> '파일업로드 경로',
				'upload_oname'	=> '업로드 원본파일',
				'img2'	=> '썸네일',
				'img1'	=> '대표 이미지',
				'detail_img'	=> '상세 이미지',
				'info'	=> '내용',
				'upload_fname'	=> '첨부파일',
				'ex1'	=> 'ex1',
				'ex2'	=> 'ex2',
				'ex3'	=> 'ex3',
				'ex4'	=> 'ex4',
				'ex5'	=> 'ex5',
				'ex6'	=> 'ex6',
				'ex7'	=> 'ex7',
				'ex8'	=> 'ex8',
				'ex9'	=> 'ex9',
				'ex10'	=> 'ex10',
				'ex11'	=> 'ex11',
				'ex12'	=> 'ex12',
				'ex13'	=> 'ex13',
				'ex14'	=> 'ex14',
				'ex15'	=> 'ex15',
				'ex16'	=> 'ex16',
				'ex17'	=> 'ex17',
				'ex18'	=> 'ex18',
				'ex19'	=> 'ex19',
				'ex20'	=> 'ex20',
			),
			'eng'	=> array(
				'no'	=> 'no',
				'name'	=> 'name',
				'category'	=> 'category',
				'yn_state'	=> 'state',
				'regdt'	=> 'regdt',
				'moddt'	=> 'moddt',
				'upload_path'	=> 'upload_path',
				'upload_oname'	=> 'upload_oname',
				'img2'	=> 'somenail',
				'img1'	=> 'img1',
				'detail_img'	=> 'detail_img',
				'info'	=> 'info',
				'upload_fname'	=> 'upload_fname',
				'ex1'	=> 'ex1',
				'ex2'	=> 'ex2',
				'ex3'	=> 'ex3',
				'ex4'	=> 'ex4',
				'ex5'	=> 'ex5',
				'ex6'	=> 'ex6',
				'ex7'	=> 'ex7',
				'ex8'	=> 'ex8',
				'ex9'	=> 'ex9',
				'ex10'	=> 'ex10',
				'ex11'	=> 'ex11',
				'ex12'	=> 'ex12',
				'ex13'	=> 'ex13',
				'ex14'	=> 'ex14',
				'ex15'	=> 'ex15',
				'ex16'	=> 'ex16',
				'ex17'	=> 'ex17',
				'ex18'	=> 'ex18',
				'ex19'	=> 'ex19',
				'ex20'	=> 'ex20',
			),
			'chn'	=> array(
				'no'	=> '产品编号',
				'name'	=> ' 产品名称',
				'category'	=> ' 分类代码',
				'yn_state'	=> ' 曝光时间',
				'regdt'	=> ' 报名日期',
				'moddt'	=> '已修改',
				'upload_path'	=> '文件上传路径',
				'upload_oname'	=> '上传原始文件',
				'img2'	=> '썸네일',
				'img1'	=> '대표이미지',
				'detail_img'	=> '상세 이미지 중국어 ',
				'info'	=> '内容内容',
				'upload_fname'	=> '附件文件',
				'ex1'	=> ' ex1',
				'ex2'	=> ' ex2',
				'ex3'	=> ' ex3',
				'ex4'	=> ' ex4',
				'ex5'	=> ' ex5',
				'ex6'	=> ' ex6',
				'ex7'	=> ' ex7',
				'ex8'	=> 'ex',
				'ex9'	=> 'ex',
				'ex10'	=> 'ex',
				'ex11'	=> ' ex11',
				'ex12'	=> ' ex12',
				'ex13'	=> 'ex13',
				'ex14'	=> 'ex14',
				'ex15'	=> ' ex15',
				'ex16'	=> ' ex16',
				'ex17'	=> ' ex17',
				'ex18'	=> ' ex18',
				'ex19'	=> ' ex19',
				'ex20'	=> 'ex20',
			),
			'jpn'	=> array(
				'no'	=> '商品番号',
				'name'	=> '商品名',
				'category'	=> 'カテゴリーコード',
				'yn_state'	=> '露出の有無',
				'regdt'	=> '登録日',
				'moddt'	=> '更新日',
				'upload_path'	=> 'ファイルのアップロードパス',
				'upload_oname'	=> 'アップロード元のファイル',
				'img2'	=> 'サムネイル',
				'img1'	=> '대표이미지',
				'detail_img'	=> '상세 이미지5155',
				'info'	=> '内容',
				'upload_fname'	=> ' 添付ファイル',
				'ex1'	=> 'ex1',
				'ex2'	=> 'ex2',
				'ex3'	=> 'ex3',
				'ex4'	=> 'ex4',
				'ex5'	=> 'ex5',
				'ex6'	=> 'ex6',
				'ex7'	=> 'ex7',
				'ex8'	=> 'ex8',
				'ex9'	=> 'ex9',
				'ex10'	=> 'ex10',
				'ex11'	=> 'ex11',
				'ex12'	=> 'ex12',
				'ex13'	=> 'ex13',
				'ex14'	=> 'ex14',
				'ex15'	=> 'ex15',
				'ex16'	=> 'ex16',
				'ex17'	=> 'ex17',
				'ex18'	=> 'ex18',
				'ex19'	=> 'ex19',
				'ex20'	=> 'ex20',
			),
		),
		'use' => array(
			'kor'	=> array(
				'img2'	=> 'checked',
				'img1'	=> 'checked',
				'detail_img'	=> 'checked',
				'info'	=> 'checked',
				'upload_fname'	=> 'checked',
			),
			'eng'	=> array(
				'img2'	=> 'checked',
				'img1'	=> 'checked',
				'detail_img'	=> 'checked',
				'info'	=> 'checked',
				'upload_fname'	=> 'checked',
			),
			'chn'	=> array(
				'img2'	=> 'checked',
				'img1'	=> 'checked',
				'detail_img'	=> 'checked',
				'info'	=> 'checked',
				'upload_fname'	=> 'checked',
			),
			'jpn'	=> array(
				'img2'	=> 'checked',
				'img1'	=> 'checked',
				'detail_img'	=> 'checked',
				'info'	=> 'checked',
				'upload_fname'	=> 'checked',
			),
		),
		'require' => array(
			'kor'	=> array(
				'img2'	=> 'checked',
			),
			'eng'	=> array(
				'img2'	=> 'checked',
				'img1'	=> 'checked',
				'detail_img'	=> 'checked',
			),
			'chn'	=> array(
				'img2'	=> 'checked',
				'img1'	=> 'checked',
			),
			'jpn'	=> array(
				'img2'	=> 'checked',
				'img1'	=> 'checked',
				'detail_img'	=> 'checked',
			),
		),
		'multi' => array(
			'name'	=> 'checked',
			'info'	=> 'checked',
			'ex1'	=> 'checked',
			'ex2'	=> 'checked',
			'ex3'	=> 'checked',
			'ex4'	=> 'checked',
			'ex5'	=> 'checked',
			'ex6'	=> 'checked',
			'ex7'	=> 'checked',
			'ex8'	=> 'checked',
			'ex9'	=> 'checked',
			'ex10'	=> 'checked',
			'ex11'	=> 'checked',
			'ex12'	=> 'checked',
			'ex13'	=> 'checked',
			'ex14'	=> 'checked',
			'ex15'	=> 'checked',
			'ex16'	=> 'checked',
			'ex17'	=> 'checked',
			'ex18'	=> 'checked',
			'ex19'	=> 'checked',
			'ex20'	=> 'checked',
		),
		'option' => array(
			'kor'	=> array(
				'img2'	=> array(
					'type' => 'file',
					'file_type' => 'image',
					'width' => '1000',
					'height' => '',
					'item' => array(
					),
				),
				'img1'	=> array(
					'type' => 'file',
					'file_type' => 'image',
					'width' => '1000',
					'height' => '',
					'item' => array(
					),
				),
				'detail_img'	=> array(
					'type' => 'file',
					'file_type' => 'image',
					'width' => '1600',
					'height' => '',
					'item' => array(
					),
				),
				'info'	=> array(
					'type' => 'editor',
					'item' => array(
					),
				),
				'upload_fname'	=> array(
					'type' => 'file',
					'file_type' => 'document',
					'item' => array(
					),
				),
				'ex1'	=> array(
					'type' => 'editor',
					'item' => array(
					),
				),
				'ex2'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex3'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex4'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex5'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex6'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex7'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex8'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex9'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex10'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex11'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex12'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex13'	=> array(
					'type' => 'editor',
					'item' => array(
					),
				),
				'ex14'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex15'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex16'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex17'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex18'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex19'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex20'	=> array(
					'type' => '',
					'item' => array(
					),
				),
			),
			'eng'	=> array(
				'img2'	=> array(
					'type' => 'file',
					'file_type' => 'image',
					'width' => '500',
					'height' => '',
					'item' => array(
					),
				),
				'img1'	=> array(
					'type' => 'file',
					'file_type' => 'image',
					'width' => '500',
					'height' => '',
					'item' => array(
					),
				),
				'detail_img'	=> array(
					'type' => 'file',
					'file_type' => 'image',
					'width' => '500',
					'height' => '500',
					'item' => array(
					),
				),
				'info'	=> array(
					'type' => 'editor',
					'item' => array(
					),
				),
				'upload_fname'	=> array(
					'type' => 'file',
					'file_type' => 'document',
					'item' => array(
					),
				),
				'ex1'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex2'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex3'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex4'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex5'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex6'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex7'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex8'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex9'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex10'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex11'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex12'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex13'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex14'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex15'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex16'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex17'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex18'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex19'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex20'	=> array(
					'type' => '',
					'item' => array(
					),
				),
			),
			'chn'	=> array(
				'img2'	=> array(
					'type' => 'file',
					'file_type' => 'image',
					'width' => '500',
					'height' => '',
					'item' => array(
					),
				),
				'img1'	=> array(
					'type' => 'file',
					'file_type' => 'image',
					'width' => '500',
					'height' => '',
					'item' => array(
					),
				),
				'detail_img'	=> array(
					'type' => 'file',
					'file_type' => 'image',
					'width' => '500',
					'height' => '500',
					'item' => array(
					),
				),
				'info'	=> array(
					'type' => 'editor',
					'item' => array(
					),
				),
				'upload_fname'	=> array(
					'type' => 'file',
					'file_type' => 'document',
					'item' => array(
					),
				),
				'ex1'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex2'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex3'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex4'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex5'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex6'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex7'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex8'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex9'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex10'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex11'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex12'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex13'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex14'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex15'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex16'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex17'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex18'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex19'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex20'	=> array(
					'type' => '',
					'item' => array(
					),
				),
			),
			'jpn'	=> array(
				'img2'	=> array(
					'type' => 'file',
					'file_type' => 'image',
					'width' => '500',
					'height' => '',
					'item' => array(
					),
				),
				'img1'	=> array(
					'type' => 'file',
					'file_type' => 'image',
					'width' => '500',
					'height' => '',
					'item' => array(
					),
				),
				'detail_img'	=> array(
					'type' => 'file',
					'file_type' => 'image',
					'width' => '500',
					'height' => '500',
					'item' => array(
					),
				),
				'info'	=> array(
					'type' => 'editor',
					'item' => array(
					),
				),
				'upload_fname'	=> array(
					'type' => 'file',
					'file_type' => 'document',
					'item' => array(
					),
				),
				'ex1'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex2'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex3'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex4'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex5'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex6'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex7'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex8'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex9'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex10'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex11'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex12'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex13'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex14'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex15'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex16'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex17'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex18'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex19'	=> array(
					'type' => '',
					'item' => array(
					),
				),
				'ex20'	=> array(
					'type' => '',
					'item' => array(
					),
				),
			),
		),
	),
);
