<?
	if($wiz_admin['designer'] != "Y") error("권한이 없습니다.");
?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr><td class="left_tit">환경설정</td></tr>
		<tr> 
			<td> 
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_table">
					<tr>
					  <th><a href="./basic_config.php">기본설정</a></th>
					</tr>
					<tr><td class="pad"></td></tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'basic_config') !== false) echo "class='clickover'"?>><a href="./basic_config.php" class="menu">기본설정</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'basic_api_config') !== false) echo "class='clickover'"?>><a href="./basic_api_config.php" class="menu">외부 API 연동</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'log_config') !== false) echo "class='clickover'"?>><a href="./log_config.php" class="menu">로그분석</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'popup_config') !== false) echo "class='clickover'"?>><a href="./popup_config.php" class="menu">팝업관리</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'poll_config') !== false) echo "class='clickover'"?>><a href="./poll_config.php" class="menu">설문조사</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'pollmain_config') !== false) echo "class='clickover'"?>><a href="./pollmain_config.php" class="menu">메인설문</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'form_config') !== false) echo "class='clickover'"?>><a href="./form_config.php" class="menu">폼메일</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'sms_config') !== false) echo "class='clickover'"?>><a href="./sms_config.php" class="menu">SMS발송</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'schedule_config') !== false) echo "class='clickover'"?>><a href="./schedule_config.php" class="menu">일정관리</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'banner_config') !== false) echo "class='clickover'"?>><a href="./banner_config.php" class="menu">디자인관리</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'page_config') !== false) echo "class='clickover'"?>><a href="./page_config.php" class="menu">페이지관리</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'levelcheck_config') !== false) echo "class='clickover'"?>><a href="./levelcheck_config.php" class="menu">페이지접근권한</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'counter_config') !== false) echo "class='clickover'"?>><a href="./counter_config.php" class="menu">카운터</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'search_config') !== false) echo "class='clickover'"?>><a href="./search_config.php" class="menu">전체검색</a></td>
					</tr>
					<tr><td class="line"></td></tr>
					<tr>
					  <th><a href="./basic_service_config.php">부가서비스</a></th>
					</tr>
					<tr><td class="pad"></td></tr>

					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'basic_service_config') !== false) echo "class='clickover'"?>><a href="./basic_service_config.php" class="menu"><strong><font color="#2DA7FE">부가서비스 관리</font></strong></a></td>
					</tr>

					<tr><td class="line"></td></tr>
					<tr>
					  <th><a href="./bbs_config.php">게시판</a></th>
					</tr>
					<tr><td class="pad"></td></tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'bbs_config') !== false) echo "class='clickover'"?>><a href="./bbs_config.php" class="menu">게시판</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'bbsmain_config') !== false) echo "class='clickover'"?>><a href="./bbsmain_config.php" class="menu">메인게시물</a></td>
					</tr>
					<tr><td class="line"></td></tr>

					<tr>
					  <th><a href="./member_config.php">회원</a></th>
					</tr>
					<tr><td class="pad"></td></tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'member_config') !== false) echo "class='clickover'"?>><a href="./member_config.php" class="menu">회원관리</a></td>
					</tr>
					<tr><td class="line"></td></tr>
					

					<tr>
					  <th><a href="./prd_config.php">상품</a></th>
					</tr>
					<tr><td class="pad"></td></tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'prd_config') !== false) echo "class='clickover'"?>><a href="./prd_config.php" class="menu">상품관리</a></td>
					</tr>
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'prdmain_config') !== false) echo "class='clickover'"?>><a href="./prdmain_config.php" class="menu">메인상품</a></td>
					</tr>
					<tr><td class="line"></td></tr>
					
					<!-- <tr> 
						<td><a href="http://anywiz.co.kr/solution/wizhome.php" target="_blank"><b>유료추가기능</b></a></td>
					</tr> -->
					<tr>
					  <th><a href="./prd_config.php">기타기능</a></th>
					</tr>
					<tr><td class="pad"></td></tr>
					<!--
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'message_config') !== false) echo "class='clickover'"?>><a href="./message_config.php" class="menu">쪽지관리</a></td>
					</tr>
					-->
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'point_config') !== false) echo "class='clickover'"?>><a href="./point_config.php" class="menu">포인트관리</a></td>
					</tr>
					<!--
					<tr class="leftMenu"> 
						<td <? if(strpos($PHP_SELF, 'mini_config') !== false) echo "class='clickover'"?>><a href="./mini_config.php" class="menu">미니홈피관리</a></td>
					</tr>
					-->
				</table>
			</td>
		</tr>
	</table>