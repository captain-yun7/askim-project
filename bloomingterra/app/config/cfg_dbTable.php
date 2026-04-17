<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * @author ethan
 * 2018-03-07 테이블 설정
 * comment 설명 : string
 * type 데이터형 : string
 * constraint 길이 : int
 * unsigned 음수불가 : boolean
 * auto_increment 자동증가 : boolean
 * default 기본값 : string
 * unique 유니크 : boolean
 * null 널 : boolean
 */
$config = array(
	'dbTable' => array(
		'da_member' => array(),
		'da_board' => array( // 게시판 공용기본틀
			'no' => array('comment' => '게시글번호', 'type' => 'int', 'constraint' => '5', 'auto_increment' => true),
			'language' => array('comment' => '언어', 'type' => 'varchar', 'constraint' => '30', 'null' => true),
			'userid' => array('comment' => '아이디', 'type' => 'varchar', 'constraint' => '20', 'null' => true),
			'name' => array('comment' => '작성자', 'type' => 'varchar', 'constraint' => '40', 'null' => true),
			'password' => array('comment' => '비밀번호', 'type' => 'varchar', 'constraint' => '100', 'null' => true),
			'title' => array('comment' => '제목', 'type' => 'varchar', 'constraint' => '100', 'null' => true),
			'content' => array('comment' => '내용', 'type' => 'mediumtext', 'null' => true),
			'fname' => array('comment' => '파일', 'type' => 'varchar', 'constraint' => '100', 'null' => true),
			'oname' => array('comment' => '원본파일', 'type' => 'varchar', 'constraint' => '100', 'null' => true),
			'userip' => array('comment' => '작성자 아이피', 'type' => 'varchar', 'constraint' => '30', 'null' => true),
			'fixed' => array('comment' => '고정순서', 'type' => 'INT', 'constraint' => '5', 'null' => true),
			'cref' => array('comment' => '부모게시 글번호', 'type' => 'INT', 'constraint' => '5', 'null' => true),
			'clevel' => array('comment' => '답글순서', 'type' => 'INT', 'constraint' => '5', 'null' => true),
			'cstep' => array('comment' => '답글레벨순서', 'type' => 'INT', 'constraint' => '5', 'null' => true),
			'hit' => array('comment' => '조회수', 'type' => 'INT', 'constraint' => '11', 'null' => true),
			'regdt' => array('comment' => '작성일', 'type' => 'datetime', 'null' => true),
			'updatedt' => array('comment' => '수정일', 'type' => 'datetime', 'null' => true),
			'origin_code' => array('comment' => '원본게시글코드', 'type' => 'varchar', 'constraint' => '50', 'null' => true),
			'origin_no' => array('comment' => '원본게시글번호', 'type' => 'INT', 'constraint' => '5', 'null' => true),
			'is_secret' => array('comment' => '비밀글유무', 'type' => 'char', 'constraint' => '1', 'null' => true),
			'upload_path' => array('comment' => '파일경로', 'type' => 'varchar', 'constraint' => '100', 'null' => true),
			'email' => array('comment' => '이메일', 'type' => 'varchar', 'constraint' => '50', 'null' => true),
			'mobile' => array('comment' => '휴대폰번호', 'type' => 'varchar', 'constraint' => '14', 'null' => true),
			'answer_status' => array('comment' => '답변상태', 'type' => 'char', 'constraint' => '1', 'null' => true),
			'answer_regdt' => array('comment' => '답변 작성일', 'type' => 'datetime', 'null' => true),
			'answer_updatedt' => array('comment' => '답변 수정일', 'type' => 'datetime', 'null' => true),
			'answer_title' => array('comment' => '답변제목', 'type' => 'varchar', 'constraint' => '100', 'null' => true),
			'answer_content' => array('comment' => '답변내용', 'type' => 'mediumtext', 'null' => true),
			'answer_userid' => array('comment' => '답변자 아이디', 'type' => 'varchar', 'constraint' => '20', 'null' => true),
			'answer_name' => array('comment' => '답변자', 'type' => 'varchar', 'constraint' => '40', 'null' => true),
			'video_url' => array('comment' => '동영상URL', 'type' => 'varchar', 'constraint' => '200', 'null' => true),
			'video_thumbnail_url' => array('comment' => '동영상이미지URL', 'type' => 'varchar', 'constraint' => '200', 'null' => true),
            'preface' => array('comment' => '말머리', 'type' => 'varchar', 'constraint' => '100', 'null' => true),
            'thumbnail_image' => array('comment' => '대표이미지', 'type' => 'varchar', 'constraint' => '100', 'null' => true),
			'extraFieldInfo' => array('comment' => '추가필드 정보', 'type' => 'text', 'null' => true),
			'use_seo' => array('comment' => 'seo 사용 여부', 'type' => 'enum("y","n")', 'default' => 'n', 'null' => false),
			'seo_title' => array('comment' => 'seo title', 'type' => 'varchar', 'constraint' => '255', 'null' => true),
			'seo_author' => array('comment' => 'seo author', 'type' => 'varchar', 'constraint' => '255', 'null' => true),
			'seo_description' => array('comment' => 'seo description', 'type' => 'varchar', 'constraint' => '255', 'null' => true),
			'seo_keywords' => array('comment' => 'seo keywords', 'type' => 'varchar', 'constraint' => '255', 'null' => true),
		),
	),
);