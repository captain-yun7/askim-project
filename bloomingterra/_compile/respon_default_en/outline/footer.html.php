<?php /* Template_ 2.2.8 2026/02/19 16:57:44 /gcsd33_bloomingterra/www/data/skin/respon_default_en/outline/footer.html 000003160 */ ?>
<!-- ** layout정리190513  ** -->
					<!-- #서브 컨텐츠 끝 -->
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]!="index_"&&$TPL_VAR["CI"]->uri->uri_string!="service/index2"){?>
				</div><!-- .contents -->
				<!-- 컨텐츠 끝 .contents -->

			</div><!-- .contents_box -->

		</div><!-- .contents_wrap -->
	</div><!-- .warpper -->
<?php }?>
<!-- ** layout정리190513  ** -->

<?php if($TPL_VAR["footer_hidden"]!='y'){?>
	<footer id="footer">
		<div class="w_custom">
			<div class="ft_logo">
				<div class="logo"><img src="/data/skin/respon_default_en/images/skin/hd_logo.png" alt="hd_logo"></div>
				<ul class="ft_sns">
					<li><a href="https://www.instagram.com/bloomingterra_com/" target="_blank"><img src="/data/skin/respon_default_en/images/skin/ft_sns01.png" alt="insta"></a></li>
					<li><a href="https://www.youtube.com/@BloomingTerra" target="_blank"><img src="/data/skin/respon_default_en/images/skin/ft_sns02.png" alt="youtube"></a></li>
					<li><a href="https://www.linkedin.com/company/bloomingterra/" target="_blank"><img src="/data/skin/respon_default_en/images/skin/ft_sns03.svg" alt="youtube"></a></li>
				</ul>
			</div>
			<div class="info_box">
				<div class="info">
					<p><?php echo $TPL_VAR["cfg_site"]["compName"]?></p>
					<span>Address. <?php echo $TPL_VAR["cfg_site"]["address"]?></span> <br>
					<!-- <span>Tel. <a href="callto:<?php echo $TPL_VAR["cfg_site"]["compPhone"]?>"><?php echo $TPL_VAR["cfg_site"]["compPhone"]?></a></span> -->
					<span>Email. <a href="mailto:info@bloomingterra.com">info@bloomingterra.com</a></span>
					<!-- <span>(TIN) <?php echo $TPL_VAR["cfg_site"]["compSerial"]?></span> -->
				</div>
				<p class="copy">&copy; Copyright by 2023 <?php echo $TPL_VAR["cfg_site"]["nameEng"]?>. All Rights Reserved. <a href="//www.designart.co.kr/new/index.php" target="_blank">Designed by designart</a></p>
			</div>
		</div>
	</footer><!-- #footer 하단영역 -->
<?php }?>
	<div id="popup_contents">
<?php $this->print_("popup_open",$TPL_SCP,1);?>

	</div>
</article><!-- #wrap 전체영역 -->
<script>
$(document).ready(function(){
		//애니메이션
				AOS.init({
						offset: 0,
						debounceDelay: 50,
						throttleDelay: 99,
						easing: 'ease-in-quart',
				});
				onElementHeightChange(document.body, function(){
						AOS.refresh();
				});
				function onElementHeightChange(elm, callback) {
						var lastHeight = elm.clientHeight
						var newHeight;

						(function run() {
								newHeight = elm.clientHeight;      
								if (lastHeight !== newHeight) callback();
								lastHeight = newHeight;
								if (elm.onElementHeightChangeTimer) {
										clearTimeout(elm.onElementHeightChangeTimer); 
								}
								elm.onElementHeightChangeTimer = setTimeout(run, 200);
						})();
				}
});
$(window).on('load', function () {
		//애니메이션
				AOS.refresh(true);
});
</script>
<iframe name="ifr_processor" id="ifr_processor" title="&nbsp;" width="0" height="0" frameborder="0" style="display:none;"></iframe>
</body>
</html>