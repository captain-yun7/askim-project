<?
	if($perm_check["POLL"] == false) error("권한이 없습니다.");
?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td class="left_tit">설문관리</td></tr>
				<tr> 
					<td> 
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_table">
							<tr> 
								<th><a href="./pollinfo_list.php?menucode=POLL">설문관리</a></th>
							</tr>
							<tr> 
								<th>목록</th>
							</tr>
							<tr><td class="pad"></td></tr>
							<?
				      $sql = "select code, title from wiz_pollinfo";
							$result = query($sql) or error("sql error");
							$total = sql_fetch_row($result);
							while($row = sql_fetch_arr($result)) {
							?>
							<tr class="leftMenu">
									  <?php if(!isset($code)) $code = ''; ?>
								<td <? if(!strcmp($code, $row['code'])) echo "class='clickover'"?>><a href="./poll_list.php?code=<?=$row['code']?>&menucode=POLL" class="menu"><?=$row['title']?></a></td>
							</tr>
				      <?
				    	}
				    	if($total <= 0){
				    	?>
				    	<tr> 
								<td height="20" style="padding-left:20px"><font color="red">등록된 설문이 없습니다.</font></td>
							</tr>
				    	<?
				    	}
				    	?>
						<tr><td class="line"></td></tr>
						</table>
					</td>
				</tr>
			</table>