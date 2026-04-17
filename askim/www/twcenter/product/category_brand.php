			<div class="category_area">
				<div class="cate_table">
					<ul>
						<li><a href="<?php echo $_SERVER['PHP_SELF'] ?>" <?php if(!$brand) echo " class='hover'";?>>All Brand</a></li>
						<?php
						$sql_brdlist = "
							select brd.idx
								 , brd.brdname
								 , (select count(*) from wiz_product as prd where brand=brd.idx and showset='Y') as bcount 
							  from wiz_brand as brd 
							 order by brd.idx
						";
						$res_brdlist = query($sql_brdlist);
						while($brdlist = sql_fetch_arr($res_brdlist)) {
							$thisbrandclass = ($brdlist['idx'] == $brand) ? "hover" : "";

						?>
						
						<li><a href="<?php echo $_SERVER['PHP_SELF'] ?>?brand=<?php echo $brdlist['idx']?>" class="<?php echo $thisbrandclass?>"><?php echo $brdlist['brdname']?> (<?php echo $brdlist['bcount']?>)</a></li>
						<?php } ?>
					</ul>
				</div>
			</div>
