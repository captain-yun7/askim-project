<?
	if($perm_check["SCHEGUAL"] == false) error("권한이 없습니다.");
?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td class="left_tit">일정관리</td></tr>
				<tr> 
					<td> 
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_table">
							<tr> 
								<th><a href="./sch_list.php?menucode=SCHEGUAL">일정관리</a></th>
							</tr>
							<tr> 
								<th>목록</th>
							</tr>
							<tr><td class="pad"></td></tr>
							<?
				      $sql = "select * from wiz_bbsinfo where type='SCH'";
			        $result = query($sql) or error("sql error");
							$total = sql_fetch_row($result);
							while($row = sql_fetch_arr($result)) {
								if($row['skin'] == "fullCalendar") { 
									$purl = "list2.php";
								} else {
									$purl = "list.php";
								}
							?>
							<tr class="leftMenu">
								<td <? if(strpos("list.php", $PHP_SELF) !== false && !strcmp($code, $row['code'])) echo "class='clickover'"?>><a href="./<?=$purl?>?code=<?=$row['code']?>&menucode=SCHEGUAL" class="menu"><?=$row['title']?></a></td>
							</tr>
				      <?
				    	}
				    	if($total <= 0){
				    	?>
				    	<tr> 
								<td height="20" style="padding-left:20px"><font color="red">등록된 일정이 없습니다.</font></td>
							</tr>
				    	<?
				    	}
				    	?>
						</table>
					</td>
				</tr>
			</table>