<?
	if($perm_check["FORMMAIL"] == false) error("권한이 없습니다.");
?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td class="left_tit">폼메일관리</td></tr>
				<tr> 
					<td> 
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_table">
							<tr> 
								<th class="non">목록</th>
							</tr>
							<tr><td class="pad"></td></tr>
							<?
				      $sql = "select * from wiz_forminfo where code != ''";
							$result = query($sql) or error("sql error");
							$total = sql_fetch_row($result);
							while($row = sql_fetch_arr($result)){
							?>
							<tr class="leftMenu">
									  <?php if(!isset($code)) $code = ''; ?>
								<td <? if(strpos($code, $row['code']) !== false) echo "class='clickover'"?>><a href="./form_list.php?code=<?=$row['code']?>&menucode=FORMMAIL" class="menu"><?=$row['title']?></a></td>
							</tr>
				      <?
				    	}
				    	if($total <= 0){
				    	?>
						<tr class="leftMenu">
							<td height="20" style="padding-left:20px"><font color=red>등록된 폼메일이<br>없습니다.</font></td>
						</tr>
				    	<?
				    	}
				    	?>
						<tr><td class="line"></td></tr>
						</table>
					</td>
				</tr>
			</table>