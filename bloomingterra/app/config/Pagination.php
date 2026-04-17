<?php
$config['base_url'] = current_url();
$config['num_links'] = 5;

$config['full_tag_open'] = '<div class="paging">';
$config['full_tag_close'] = '</div>';

$config['enable_query_strings'] = true;
$config['use_page_numbers'] = true;
$config['page_query_string'] = true;

if(!defined("_MOBILE")) {
	$config['first_tag_open'] = '<span class="arrow first">';
	$config['first_tag_close'] = '</span>';
	$config['last_tag_open'] = '<span class="arrow last">';
	$config['last_tag_close'] = '</span>';
	$config['prev_tag_open'] = '<span class="arrow prev">';
	$config['prev_tag_close'] = '</span>';
	$config['next_tag_open'] = '<span class="arrow next">';
	$config['next_tag_close'] = '</span>';

	if(defined("_IS_ADMIN_LOGIN")) {
		$config['cur_tag_open'] = '<span><a class="page_num on" href="javascript://"><b>';
		$config['cur_tag_close'] = '</b></a></span>';

		$config['num_tag_open'] = '<span>';
		$config['num_tag_close'] = '</span>';
	} else {
		$config['cur_tag_open'] = '<span class="page_num on"><a href="javascript://">';
		$config['cur_tag_close'] = '</a></span>';

		$config['num_tag_open'] = '<span class="page_num">';
		$config['num_tag_close'] = '</span>';
	}
} else {
	$config['prev_tag_open'] = '<span class="arrow prev">';
	$config['prev_tag_close'] = '</span>';
	$config['next_tag_open'] = '<span class="arrow next">';
	$config['next_tag_close'] = '</span>';

	$config['first_link'] = false;
	$config['last_link'] = false;
	$config['prev_link'] = '<img src="../../lib/admin/images/paging_prev.gif" alt="이전">';
	$config['next_link'] = '<img src="../../lib/admin/images/paging_next.gif" alt="다음">';

	$config['cur_tag_open'] = '<a href="javascript://" class="on">';
	$config['cur_tag_close'] = '</a>';
}