<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/oper_info.php"; ?>
<? include_once "../../inc/prd_info.php"; ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td width="15%" height="25" class="t_name">관련상품</td>
					<td colspan="10" align="center">
						<table width="100%" border="0" cellspacing="1" cellpadding="2">
							<tr><td height="5"></td></tr>
							<tr>
								<td width="100%" align="center">
									<table width="99%" height="10" border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td width="33%">
												<input type="button" value="관련상품등록" class="base_btm blue" onclick="addReation()">
											</td>
										<tr><td height="5"></td></tr>
										</tr>
									</table>
									<table width="99%" border="0" cellpadding="0" cellspacing="0">
										<tr class="t_th">
											<th width="5%"><input type="checkbox" id="checkAll" onclick="selectAll()"></th>
											<th width="5%">번호</th>
											<th width="10%">이미지</th>
											<th>상품명</th>
											<th width="10%">상품가격</th>
											<th width="10%">적립금</th>
											<th width="10%">진열여부</th>
											<th width="15%">기능</th>
										</tr>
										<tr><td class="t_rd" colspan=20></td></tr>
										<?
										$rel_sql = "
											
											select 
											
												wr.idx               ,
												wp.prdcode           ,
												wp.prdname           ,
												wp.prdimg_R          ,
												wp.sellprice         ,
												wp.reserve           ,
												wp.showset
												
											from
											
												wiz_prdrelation wr  ,
												wiz_product wp 
												
											where 
											
												wr.prdcode = '$prdcode' and 
												wr.relcode = wp.prdcode
												group by wr.relcode
												
											";
										$rel_result = query($rel_sql);
										$no = 1;
										while($rel_row = sql_fetch_obj($rel_result)){

											$showset = ($rel_row->showset == 'Y') ? "<font color='red'>상품진열</font>" : "미진열";
											if($rel_row->prdimg_R && @file(WIZHOME_DATA_PATH."/prdimg/".$rel_row->prdimg_R)) $prdimg = "/twcenter/data/prdimg/".$rel_row->prdimg_R;
											else  $prdimg = "/twcenter/images/noimage.gif";

										?>
										<tr>
											<td align="center" height="55"><input type="checkbox" name="select_checkbox" id="select_checkbox" value="<?=$rel_row->idx?>"></td>
											<td align="center"><?=$no?></td>
											<td align="center"><a href="prd_input.php?mode=update&prdcode=<?=$rel_row->prdcode?>" target="_blank"><img src="<?=$prdimg?>" width="50" height="50" border="0" align="absmiddle"></a></td>
											<td align="left" style="padding: 0 0 0 10px"><?=$rel_row->prdname?></td>
											<td align="center" style="padding:0 0 0 15px"><?=number_format($rel_row->sellprice)?>원</th>
											<td align="center" style="padding:0 0 0 15px"><?=number_format($rel_row->reserve)?>원</th>
											<td align="center"><?=$showset?></td>
											<td align="center"><input type="button" value="삭제" class="suboptdel" onclick="relDel(<?=$rel_row->idx?>)"></td>
										</tr>
										<tr><td colspan="20" class="t_line"></td></tr>
										<?
										$no++;
										}
										?>
									</table>
									<table width="99%" height="10" border="0" cellpadding="0" cellspacing="0">
										<tr><td height="5"></td></tr>
										<tr>
											<td width="33%">
												<input type="button" value="선택삭제" class="base_btm gray" onclick="prdDelete()">
											</td>
										</tr>
									</table>
								
								</td>
							</tr>
							<tr><td height="5"></td></tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>


