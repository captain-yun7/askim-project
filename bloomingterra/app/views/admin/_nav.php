<? if($this->uri->rsegments[1] != "main") : ?>
	<? if(!$this->_admin_member["super"] && !isset($this->_adm_auth[$this->_admin_member["level"]][$this->uri->rsegments[1]])) : ?>
			<? msg("권한이 없습니다.", -1); ?>
	<? endif ?>
<? endif ?>
<div id="header">
	<div class="header_cont">
		<div class="hd_top">
			<div class="hd_top_box">
				<h1 class="logo"><a href="/admin/main"><em><?=$this->_cfg_site["kor"]["nameKor"]?></em></a></h1>
				<dl>
					<dd><a href="<?=base_url()?>" target="_blank" class="home"><img src="/lib/admin/images/icon_home.png" alt=""></a></dd>
					<dd class="admin_member"><img src="/lib/admin/images/new/icon_name.gif" alt="">&nbsp;<span><?=$this->_admin_member["name"]?></span>님</dd>
					<dd><a href="/admin/logout"><img src="/lib/admin/images/new/icon_logout.gif" alt="">로그아웃</a></dd>
					<!-- <dd><a href="#"><img src="/lib/admin/images/new/icon_modify.gif" alt="">정보변경</a></dd> -->
					<!--dd style="position:relative;z-index:1;">
						<em>외부 접속통계</em>
						<span class="hd_layers"><span>
							<a href="https://searchadvisor.naver.com/start" target="_blank">네이버 접속통계 바로가기</a><a href="https://accounts.google.com" target="_blank">구글 접속통계 바로가기</a><br/>
							별도서비스로 고객센터에 문의하세요.
						</span></span>
					</dd-->
				</dl>
			</div>
		</div>
		<div class="nav">
			<ul>
			<?php
			foreach($this->_adm_menu as $key => $value) :
				if($this->_admin_member["super"] || isset($this->_adm_auth[$this->_admin_member["level"]][$key])) :
					if($this->_admin_member['level'] == 98) :
						if($value['name'] == "기본설정") $value['default'] = "conf_reg";
					endif;
					$nav_active = $this->uri->rsegments[1] == $key ? " active" : "";
					echo "<li><a href='/admin/".$key."/".$value['default']."' class='".$nav_active."'>".$value['name']."</a></li>";
				endif;
			endforeach;
			?>
			</ul>
		</div>
	</div><!--header_cont-->
</div><!--header-->