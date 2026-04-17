			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td class="left_tit">관리자메인</td></tr>
				<tr> 
					<td> 
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_table2">
							
							<? if($menu_arr["BASIC"]==true){ ?>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'site_info') !== false) echo "class='clickover'"?>><a href="../basic/site_info.php?menucode=BASIC" class="menu">기본설정</a></td>
							</tr>
							<? } ?>
							
							<? if($menu_arr["MEMBER"]==true){ ?>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'member_list') !== false) echo "class='clickover'"?>><a href="../member/member_list.php?menucode=MEMBER" class="menu">회원관리</a></td>
							</tr>
							<? } ?>
						
							<? if($menu_arr["BBS"]==true){ ?>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'bbs_list') !== false) echo "class='clickover'"?>><a href="../bbs/bbs_list.php?menucode=BBS" class="menu">게시판관리</a></td>
							</tr>
							<? } ?>
							
							<? if($menu_arr["LOG"]==true){ ?>
							<tr> 
								<td><a href="https://www.google.co.kr/intl/ko/analytics/" target="_blank" title="구글 웹로그를 제공합니다.">접속관리</a></td>
							</tr>
							<? } ?>
							
						</table>
					</td>
				</tr>
			</table>
			<br>
      <!-- <table width="175" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td align="left"><a href="../db_backup.php"><img src="../image/bt_allbackup.gif" border="0"></a></td>
        </tr>
        <? if($menu_arr["MEMBER"]==true){ ?>
        <tr><td height="5"></td></tr>
        <tr>
          <td align="left"><a href="../db_backup.php?table=wiz_member"><img src="../image/bt_membackup.gif" border="0"></a></td>
        </tr>
        <? } ?>
      </table> -->