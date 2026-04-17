<div id="leftmenu">
<?php
$r1 = $this->uri->rsegments[1];
$r2 = $this->uri->rsegments[2];

if(isset($this->_adm_menu[$r1])) :
	if(file_exists(__DIR__."/left/".$r1.".php")) :
		include_once __DIR__."/left/".$r1.".php";
	else :
		echo "
			<h2>".$this->_adm_menu[$r1]["name"]."</h2>
			<ul>
				<li class='on'>
					<h3>".$this->_adm_menu[$r1]["name"]."</h3>
					<ul>
		";
		foreach($this->_adm_menu[$r1]['low_menu'] as $key => $value) :
			if(in_array($value['segment'], $this->_adm_auth[$this->_admin_member['level']][$r1])) :
				$sub_active = $this->uri->rsegments[2] == $value['segment'] ? " class='active'" : "";
				echo "<li".$sub_active."><a href='".$value['segment']."'><em>".$value['name']."</em></a></li>";
			endif;
		endforeach;
		echo "</ul>";
	endif;
endif;

if($r1 == "main") :
?>
	<h2>관리자메인</h2>
	<ul class="left_main">
		<li>
			<ul>
				<?php
				if(in_array("conf_reg", $this->_adm_auth[$this->_admin_member['level']]['policy'])) echo "<li><a href='/admin/policy/conf_reg'><em>기본 정책</em></a></li>";
				if(in_array("member_list", $this->_adm_auth[$this->_admin_member['level']]['member'])) echo "<li><a href='/admin/member/member_list'><em>회원 관리</em></a></li>";
				if(in_array("board_list", $this->_adm_auth[$this->_admin_member['level']]['board'])) echo "<li><a href='/admin/board/board_list'><em>게시판 관리</em></a></li>";
				if(in_array("analysis_all", $this->_adm_auth[$this->_admin_member['level']]['advisor'])) echo "<li><a href='/admin/advisor/analysis_all'><em>접속통계</em></a></li>";
				?>
			</ul>
		</li>
	</ul>
<?php
endif;
?>
</div>