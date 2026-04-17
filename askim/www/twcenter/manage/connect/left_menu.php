<?
	if($perm_check["LOG"] == false) error("권한이 없습니다.");
?>
			<script language="javascript">
			<!--
			function cpcGuard(){
				alert("시피시가드와 제휴하여 제공되는 서비스입니다. \n\n접속로그와 키워드를 상세히 분석할수있습니다. \n\n설치 연동은 시피시가드에서 무상으로 해드립니다.");
				window.open("http://cpc.web2002.co.kr/","","");
			}
			-->
			</script>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr><td class="left_tit">접속통계</td></tr>
				<tr> 
					<td> 
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_table2">
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'connect_list') !== false) echo "class='clickover'"?>><a href="./connect_list.php?menucode=LOG" class="menu">접속자 분석</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'connect_refer') !== false) echo "class='clickover'"?>><a href="./connect_refer.php?menucode=LOG" class="menu">접속자 경로분석</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'connect_osbrowser') !== false) echo "class='clickover'"?>><a href="./connect_osbrowser.php?menucode=LOG" class="menu">접속자 환경분석</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'connect_ip') !== false) echo "class='clickover'"?>><a href="./connect_ip.php?menucode=LOG" class="menu">접속자 IP 분석</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'connect_keyword') !== false) echo "class='clickover'"?>><a href="./connect_keyword.php?menucode=LOG" class="menu">검색키워드 분석</a></td>
							</tr>

							<? if($site_info["google_an_use"]=="Y"){?>
							<tr class="leftMenu"> 
								<td><a href="https://www.google.co.kr/intl/ko/analytics/" target="_blank" class="menu">구글웹로그</a></td>
							</tr>
							<? } ?>
						</table>
					</td>
				</tr>
			</table>