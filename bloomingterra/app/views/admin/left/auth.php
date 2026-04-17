<h2><?=$this->_adm_menu[$r1]["name"]?></h2>
<ul>
<?php
if($this->session->__get('admin_member')['level'] == 98) :
	$sub = [
		'기본정책' => ['conf_reg' => '기본 정보 설정', 'search_engine_opt' => '검색엔진 최적화(SEO)', 'terms_list' => '약관 및 개인정보정책'],
		'메인 설정' => ['menu_manage' => '메뉴 설정', 'display_main_list' => '메인 상품진열 설정'],
	];
endif;
if($this->session->__get('admin_member')['level'] == 99) :
	$sub = [
		'기본 설정' => ['language_reg' => '사용 언어설정', 'country_manage' => '국가 정보 설정', 'manage_skin' => '스킨 설정'],
		'기본정책' => ['conf_reg' => '기본 정보 설정', 'search_engine_opt' => '검색엔진 최적화(SEO)', 'terms_list' => '약관 및 개인정보정책'],
		'메인 설정' => ['menu_manage' => '메뉴 설정', 'display_main_list' => '메인 상품진열 설정'],
		'회원 / 게시판 설정' => ['member_field' => '회원 필드 세팅', 'board_manage' => '게시판 관리'],
		'개발자 모드' => ['debug_mode' => '개발자 모드']
	];
endif;

foreach($sub as $key => $value) :
	$title_on = array_key_exists($r2, $value) ? " class='on'" : "";
	echo "<li".$title_on."><h3>".$key."</h3>";
	echo "<ul>";
	foreach($value as $sub_url => $sub_name) :
		$sub_active = $this->uri->rsegments[2] == $sub_url ? " class='active'" : "";
		echo "<li".$sub_active."><a href='".$sub_url."'><em>".$sub_name."</em></a></li>";
	endforeach;
	echo "</ul></li>";
endforeach;
?>
</ul>