                  <table width="100%" border="0" cellspacing="2" cellpadding="0">
					          <tr>
					        	  <td>원본이미지를 등록하면 나머지 이미지가 자동생성 됩니다.</td>
					        	</tr>
					        	<tr>
					        	  <td>
												<?
												for($ii = 2; $ii <= $prdimg_max; $ii++) {
												?>
					        	    <input type="checkbox" name="prdlay_check<?=$ii?>" onClick="if(this.checked==true) prdlay<?=$ii?>.style.display=''; else prdlay<?=$ii?>.style.display='none';"><font color="red">이미지추가<?=$ii?></font>
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
					                <td width="410"class="t_value" colspan="3"><input type="file" name="realimg" size="12" size="12" class="input"> [GIF, JPG, PNG]</td>
					              </tr>
					              <tr>
					                <td height="25" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;상품목록</td>
					                <td class="t_value" colspan="3">
					                <input type="file" name="prdimg_R" size="12" size="12" class="input">
					                <?
					                if(@file($imgpath."/".$prd_info['prdimg_R'])){
					                ?>
					                <input type="checkbox" name="delimg[]" value="<?=$prd_info['prdimg_R']?>">삭제 (<a href="../../data/product/<?=$prd_info['prdimg_R']?>" target="_blank" onMouseOver="document.prdimg1.src='../../data/product/<?=$prd_info['prdimg_R']?>';"><?=$prd_info['prdimg_R']?></a>)
					                <?
					                }
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td height="25" align="left" class="t_name">
					                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;축소</td>
					                <td class="t_value" colspan="3">
					                <input type="file" name="prdimg_S1" size="12" size="12" class="input">
					                <?
					                if(@file($imgpath."/".$prd_info['prdimg_S1'])){
					                ?>
					                <input type="checkbox" name="delimg[]" value="<?=$prd_info['prdimg_S1']?>">삭제 (<a href="../../data/product/<?=$prd_info['prdimg_S1']?>" target="_blank"   onMouseOver="document.prdimg1.src='../../data/product/<?=$prd_info['prdimg_S1']?>';"><?=$prd_info['prdimg_S1']?></a>)
					                <?
					                }
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td height="25" align="left" class="t_name">
					                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;제품상세</td>
					                <td class="t_value" colspan="3">
					                <input type="file" name="prdimg_M1" size="12" class="input">
					                <?
					                if(@file($imgpath."/".$prd_info['prdimg_M1'])){
					                ?>
					                <input type="checkbox" name="delimg[]" value="<?=$prd_info['prdimg_M1']?>">삭제 (<a href="../../data/product/<?=$prd_info['prdimg_M1']?>" target="_blank"  onMouseOver="document.prdimg1.src='../../data/product/<?=$prd_info['prdimg_M1']?>';"><?=$prd_info['prdimg_M1']?></a>)
					                <?
					                }
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td height="25" align="left" class="t_name">
					                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;확대보기</td>
					                <td class="t_value" colspan="3">
					                <input type="file" name="prdimg_L1" size="12" class="input">
					                <?
					                if(@file($imgpath."/".$prd_info['prdimg_L1'])){
					                ?>
					                <input type="checkbox" name="delimg[]" value="<?=$prd_info['prdimg_L1']?>">삭제 (<a href="../../data/product/<?=$prd_info['prdimg_L1']?>" target="_blank" onMouseOver="document.prdimg1.src='../../data/product/<?=$prd_info['prdimg_L1']?>';"><?=$prd_info['prdimg_L1']?></a>)
					                <?
					                }
					                ?>
					                </td>
					              </tr>
					            </table>
					            </td>
					            <td width="25%" height="100%">
					            <table width="100%" height="100%" cellspacing="1" cellpadding="2" class="t_style">
					              <tr>
					                <td align="center" bgcolor="#ffffff">
					                <?
					                if(@file($imgpath."/".$prd_info['prdimg_R']))
					                	echo "<img src='../../data/product/".$prd_info['prdimg_R']."' name='prdimg1' width='100'>";
					                else
					                	echo "No Image";
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
					                <td width="410" class="t_value" colspan="3"><input type="file" name="realimg<?=$ii?>" size="12" class="input"></td>
					              </tr>
					              <tr>
					                <td height="25" align="left" class="t_name">
					                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;축소</td>
					                <td class="t_value" colspan="3">
					                <input type="file" name="prdimg_S<?=$ii?>" size="12" class="input">
					                <?
					                if(@file($imgpath."/".$prd_info[prdimg_S.$ii])){
					                ?>
					                <input type="checkbox" name="delimg[]" value="<?=$prd_info[prdimg_S.$ii]?>">삭제 (<a href="../../data/product/<?=$prd_info[prdimg_S.$ii]?>" target="_blank" onMouseOver="document.prdimg<?=$ii?>.src='../../data/product/<?=$prd_info[prdimg_S.$ii]?>';"><?=$prd_info[prdimg_S.$ii]?></a>)
					                <?
					                }
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td height="25" align="left" class="t_name">
					                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;제품상세</td>
					                <td class="t_value" colspan="3">
					                <input type="file" name="prdimg_M<?=$ii?>" size="12" class="input">
					                <?
					                if(@file($imgpath."/".$prd_info[prdimg_M.$ii])){
					                ?>
					                <input type="checkbox" name="delimg[]" value="<?=$prd_info[prdimg_M.$ii]?>">삭제 (<a href="../../data/product/<?=$prd_info[prdimg_M.$ii]?>" target="_blank" onMouseOver="document.prdimg<?=$ii?>.src='../../data/product/<?=$prd_info[prdimg_M.$ii]?>';"><?=$prd_info[prdimg_M.$ii]?></a>)
					                <?
					                }
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td height="25" align="left" class="t_name">
					                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;확대보기</td>
					                <td class="t_value" colspan="3">
					                <input type="file" name="prdimg_L<?=$ii?>" size="12" class="input">
					                <?
					                if(@file($imgpath."/".$prd_info[prdimg_L.$ii])){
					                ?>
					                <input type="checkbox" name="delimg[]" value="<?=$prd_info[prdimg_L.$ii]?>">삭제 (<a href="../../data/product/<?=$prd_info[prdimg_L.$ii]?>" target="_blank" onMouseOver="document.prdimg<?=$ii?>.src='../../data/product/<?=$prd_info[prdimg_L.$ii]?>';"><?=$prd_info[prdimg_L.$ii]?></a>)
					                <?
					                }
					                ?>
					                </td>
					              </tr>
					            </table>
					          </td>
					          <td width="25%" height="100%">
					            <table width="100%" height="100%" cellspacing="1" cellpadding="2" class="t_style">
					              <tr>
					                <td align="center" bgcolor="#ffffff">
					                <?
					                if(@file($imgpath."/".$prd_info[prdimg_M.$ii]))
					                	echo "<img src='../../data/product/".$prd_info[prdimg_M.$ii]."' name='prdimg".$ii."' width='100'>";
					                else if(@file($imgpath."/".$prd_info[prdimg_S.$ii]))
					                	echo "<img src='../../data/product/".$prd_info[prdimg_S.$ii]."' name='prdimg".$ii."' width='100'>";
					                else if(@file($imgpath."/".$prd_info[prdimg_L.$ii]))
					                	echo "<img src='../../data/product/".$prd_info[prdimg_L.$ii]."' name='prdimg".$ii."' width='100'>";
					                else
					                	echo "No Image";
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