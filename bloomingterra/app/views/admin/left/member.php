<h2><?=$this->_adm_menu[$r1]["name"]?></h2>
<ul>
<?php
$sub = [
	'회원 관리' => [
		'member_list' => '회원 리스트',
		'member_reg' => '회원 등록',
		'member_grade' => '회원 등급관리',
		'member_dormant_list' => '휴면회원 관리',
		'member_withdrawal_list' => '탈퇴회원 리스트'
	],
	'관리자 관리' => [
		'member_auth' => '관리자 등급 리스트',
		'member_auth_reg' => '관리자 등급 등록'
	]
];

foreach($sub as $key => $value) :
	if($key == "관리자 관리" && $this->session->userdata['admin_member']['level'] < 99) continue;
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