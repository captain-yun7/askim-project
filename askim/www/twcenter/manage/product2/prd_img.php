<table width="100%" border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td>
			<p class='sub_tit_alt2'>※ 원본이미지를 등록하면 나머지 이미지가 자동생성 됩니다.</p>
			<p class='sub_tit_alt2'>※ 이미지 사이즈는 500x500 사이즈에 맞춰 업로드해주시기 바랍니다.</p>
		</td>
	</tr>
	<tr>
		<td>
		<?
		for($ii = 2; $ii <= $prdimg_max; $ii++) {
		?>
			<span style="vertical-align: middle"><input type="checkbox" name="prdlay_check<?=$ii?>" onClick="if(this.checked==true) prdlay<?=$ii?>.style.display=''; else prdlay<?=$ii?>.style.display='none';"><font color="red"></span>이미지추가<?=$ii?></font>
		<?
		}
		?>
			&nbsp;
			<a href="javascript:setImgsize();"><img src="../image/btn_imgsize.gif" align="absmiddle" border="0"></a>
		</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="75%">
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td width="150" height="25" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;원본 이미지</td>
					<td width="410"class="t_value" colspan="3">
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file">파일 업로드</label>
							<input type="file" name="realimg" id="input-file" class="upload-hidden">
							<div class="sub_tit_alt2"> GIF, JPG, JPEG, PNG 파일만 업로드하세요.</div>
						</div>
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;상품목록</td>
					<td class="t_value" colspan="3">
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file_R">파일 업로드</label>
							<input type="file" name="prdimg_R" id="input-file_R" class="upload-hidden">
							<?
							if(@file($imgpath."/".$prd_info['prdimg_R'])){
							?>
							<input type="checkbox" name="delimg[prdimg_R]" value="<?=$prd_info['prdimg_R']?>">삭제 (<a href="<?=$imgpath?>/<?=$prd_info['prdimg_R']?>?v=<?=time();?>" target="_blank" onMouseOver="document.prdimg1.src='<?=$imgpath?>/<?=$prd_info['prdimg_R']?>?v=<?=time();?>';"><?=$prd_info['prdimg_R']?></a>)
							<?
							}
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;축소</td>
					<td class="t_value" colspan="3">
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file_S1">파일 업로드</label>
							<input type="file" name="prdimg_S1" id="input-file_S1" class="upload-hidden">
							<?
							if(@file($imgpath."/".$prd_info['prdimg_S1'])){
							?>
							<input type="checkbox" name="delimg[prdimg_S1]" value="<?=$prd_info['prdimg_S1']?>">삭제 (<a href="<?=$imgpath?>/<?=$prd_info['prdimg_S1']?>?v=<?=time();?>" target="_blank"   onMouseOver="document.prdimg1.src='<?=$imgpath?>/<?=$prd_info['prdimg_S1']?>?v=<?=time();?>';"><?=$prd_info['prdimg_S1']?></a>)
							<?
							}
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;제품상세</td>
					<td class="t_value" colspan="3">
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file_M1">파일 업로드</label>
							<input type="file" name="prdimg_M1" id="input-file_M1" class="upload-hidden">

							<?
							if(@file($imgpath."/".$prd_info['prdimg_M1'])){
							?>
							<input type="checkbox" name="delimg[prdimg_M1]" value="<?=$prd_info['prdimg_M1']?>">삭제 (<a href="<?=$imgpath?>/<?=$prd_info['prdimg_M1']?>?v=<?=time();?>" target="_blank"  onMouseOver="document.prdimg1.src='<?=$imgpath?>/<?=$prd_info['prdimg_M1']?>?v=<?=time();?>';"><?=$prd_info['prdimg_M1']?></a>)
							<?
							}
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;확대보기</td>
					<td class="t_value" colspan="3">
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file_L1">파일 업로드</label>
							<input type="file" name="prdimg_L1" id="input-file_L1" class="upload-hidden">

							<?
							if(@file($imgpath."/".$prd_info['prdimg_L1'])){
							?>
							<input type="checkbox" name="delimg[prdimg_L1]" value="<?=$prd_info['prdimg_L1']?>">삭제 (<a href="<?=$imgpath?>/<?=$prd_info['prdimg_L1']?>?v=<?=time();?>" target="_blank" onMouseOver="document.prdimg1.src='<?=$imgpath?>/<?=$prd_info['prdimg_L1']?>?v=<?=time();?>';"><?=$prd_info['prdimg_L1']?></a>)
							<?
							}
							?>
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td width="25%" height="100%">
			<table width="100%" height="100%" cellspacing="0" cellpadding="0" class="t_style">
				<tr>
					<td align="center" bgcolor="#ffffff">
					<?
					if(@file($imgpath."/".$prd_info['prdimg_R']))
						echo "<img src='".$imgpath."/".$prd_info['prdimg_R']."?v=".time()."' name='prdimg1' width='100'>";
					else
						echo "<img src='../image/noimg.gif' width='100'>";
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?
for($ii = 2; $ii <= $prdimg_max; $ii++) {
?>
<div id="prdlay<?=$ii?>" style="display:none">
<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td></td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="75%">
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td width="150" height="25" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;원본 이미지</td>
					<td width="410" class="t_value" colspan="3">
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file<?=$ii?>">파일 업로드</label>
							<input type="file" name="realimg<?=$ii?>" id="input-file<?=$ii?>" class="upload-hidden">
						</div>
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;축소</td>
					<td class="t_value" colspan="3">
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file_S<?=$ii?>">파일 업로드</label>
							<input type="file" name="prdimg_S<?=$ii?>" id="input-file_S<?=$ii?>" class="upload-hidden">

							<?
							if(@file($imgpath."/".$prd_info['prdimg_S'.$ii])){
							?>
							<input type="checkbox" name="delimg[prdimg_S<?=$ii?>]" value="<?=$prd_info['prdimg_S'.$ii]?>">삭제 (<a href="<?=$imgpath?>/<?=$prd_info['prdimg_S'.$ii]?>?v=<?=time();?>" target="_blank" onMouseOver="document.prdimg<?=$ii?>.src='<?=$imgpath?>/<?=$prd_info['prdimg_S'.$ii]?>?v=<?=time();?>';"><?=$prd_info['prdimg_S'.$ii]?></a>)
							<?
							}
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;제품상세</td>
					<td class="t_value" colspan="3">
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file_M<?=$ii?>">파일 업로드</label>
							<input type="file" name="prdimg_M<?=$ii?>" id="input-file_M<?=$ii?>" class="upload-hidden">
							<?
							if(@file($imgpath."/".$prd_info['prdimg_M'.$ii])){
							?>
							<input type="checkbox" name="delimg[prdimg_M<?=$ii?>]" value="<?=$prd_info['prdimg_M'.$ii]?>">삭제 (<a href="<?=$imgpath?>/<?=$prd_info['prdimg_M'.$ii]?>?v=<?=time();?>" target="_blank" onMouseOver="document.prdimg<?=$ii?>.src='<?=$imgpath?>/<?=$prd_info['prdimg_M'.$ii]?>?v=<?=time();?>';"><?=$prd_info['prdimg_M'.$ii]?></a>)
							<?
							}
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;확대보기</td>
					<td class="t_value" colspan="3">
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file_L<?=$ii?>">파일 업로드</label>
							<input type="file" name="prdimg_L<?=$ii?>" id="input-file_L<?=$ii?>" class="upload-hidden">
							<?
							if(@file($imgpath."/".$prd_info['prdimg_L'.$ii])){
							?>
							<input type="checkbox" name="delimg[prdimg_L<?=$ii?>]" value="<?=$prd_info['prdimg_L'.$ii]?>">삭제 (<a href="<?=$imgpath?>/<?=$prd_info['prdimg_L'.$ii]?>?v=<?=time();?>" target="_blank" onMouseOver="document.prdimg<?=$ii?>.src='<?=$imgpath?>/<?=$prd_info['prdimg_L'.$ii]?>?v=<?=time();?>';"><?=$prd_info['prdimg_L'.$ii]?></a>)
							<?
							}
							?>
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td width="25%" height="100%">
			<table width="100%" height="100%" cellspacing="0" cellpadding="0" class="t_style">
				<tr>
					<td align="center" bgcolor="#ffffff">
						<?
						if(@file($imgpath."/".$prd_info['prdimg_M'.$ii]))
							echo "<img src='".$imgpath."/".$prd_info['prdimg_M'.$ii]."?v=".time()."' name='prdimg".$ii."' width='100'>";
						else if(@file($imgpath."/".$prd_info['prdimg_S'.$ii]))
							echo "<img src='".$imgpath."/".$prd_info['prdimg_S'.$ii]."?v=".time()."' name='prdimg".$ii."' width='100'>";
						else if(@file($imgpath."/".$prd_info['prdimg_L'.$ii]))
							echo "<img src='".$imgpath."/".$prd_info['prdimg_L'.$ii]."?v=".time()."' name='prdimg".$ii."' width='100'>";
						else
							echo "<img src='../image/noimg.gif' width='100'>";
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>
<?
}
?>
