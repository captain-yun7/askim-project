<?php /* Template_ 2.2.8 2026/02/19 16:57:44 /gcsd33_bloomingterra/www/data/skin/respon_default_en/layout/popup.html 000006087 */ ?>
<script>
var noShow = function(checkbox){
	var no = checkbox.value;
	if(checkbox.checked){
		Cookie.setCookie("popup_"+no, 'y', 1);
	}else{
		Cookie.setCookie("popup_"+no, 'y', -1);
	}
};
var winWidth = $(window).width();
<?php if(isset($TPL_VAR["popup_list"]['popup_list'])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["popup_list"]['popup_list'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
<?php if(get_cookie('popup_'.$TPL_V1["no"],true)!='y'){?>
			var popupWidth = 0, popupHeight = 0, popupToppx = 0, popupLeftpx = 0;
			var isMobile = false;
			var agentInitial = '';
<?php if($TPL_V1["popupform"]=="responsive"){?>
				if(winWidth >= Number(<?php echo $TPL_V1["recognition_pc"]?>)){
					popupWidth = Number(<?php echo $TPL_V1["width_responsive_pc"]?>);
					popupHeight = Number(<?php echo $TPL_V1["height_responsive_pc"]?>);
					popupToppx = Number(<?php echo $TPL_V1["toppx_responsive_pc"]?>);
					popupLeftpx = Number(<?php echo $TPL_V1["leftpx_responsive_pc"]?>);
					agentInitial = 'pc';
				} else if(winWidth >= Number(<?php echo $TPL_V1["recognition_tablet"]?>)){
					popupWidth = Number(<?php echo $TPL_V1["width_responsive_tablet"]?>);
					popupHeight = Number(<?php echo $TPL_V1["height_responsive_tablet"]?>);
					popupToppx = Number(<?php echo $TPL_V1["toppx_responsive_tablet"]?>);
					popupLeftpx = Number(<?php echo $TPL_V1["leftpx_responsive_tablet"]?>);
					agentInitial = 't';
				}else {
					popupWidth = Number(<?php echo $TPL_V1["width_responsive_mobile"]?>);
					popupHeight = Number(<?php echo $TPL_V1["height_responsive_mobile"]?>);
					popupToppx = Number(<?php echo $TPL_V1["toppx_responsive_mobile"]?>);
					popupLeftpx = Number(<?php echo $TPL_V1["leftpx_responsive_mobile"]?>);
					isMobile = true;
					agentInitial = 'm';
				}
<?php }else{?>
<?php if($TPL_VAR["isMobile"]==true){?>
					popupWidth = Number(<?php echo $TPL_V1["width_mobile"]?>);
					popupHeight = Number(<?php echo $TPL_V1["height_mobile"]?>);
					popupToppx = Number(<?php echo $TPL_V1["toppx_mobile"]?>);
					popupLeftpx = Number(<?php echo $TPL_V1["leftpx_mobile"]?>);
					isMobile = true;
<?php }else{?>
					popupWidth = Number(<?php echo $TPL_V1["width_pc"]?>);
					popupHeight = Number(<?php echo $TPL_V1["height_pc"]?>);
					popupToppx = Number(<?php echo $TPL_V1["toppx_pc"]?>);
					popupLeftpx = Number(<?php echo $TPL_V1["leftpx_pc"]?>);
<?php }?>
<?php }?>
<?php if($TPL_V1["type"]=='1'){?>
				var layer_html = '';
				if (isMobile){
					layer_html += '<div id="popup_<?php echo $TPL_V1["no"]?>" class="layer-popup '+ (agentInitial ? 'layer_'+agentInitial : '') +'" style="width:'+popupWidth+'%;top:'+popupToppx+'px;z-index:<?php echo  2000+$TPL_V1["no"]?>;">';
				}else{
					layer_html += '<div id="popup_<?php echo $TPL_V1["no"]?>" class="layer-popup '+ (agentInitial ? 'layer_'+agentInitial : '') +'" style="width:'+popupWidth+'px;min-height:'+popupHeight+'px;top:'+popupToppx+'px;left:'+popupLeftpx+'px;z-index:<?php echo  2000+$TPL_V1["no"]?>;">';
				}

				layer_html += '<div><h4><?php echo htmlspecialchars_decode($TPL_V1["title"])?></h4></div>';
				layer_html += '<div class="pop_content yui3-cssreset">';
				layer_html += '<?php echo str_replace("'","",htmlspecialchars_decode($TPL_V1["content"]))?>';
				layer_html += '</div>';
				layer_html += '<div class="pop_closed">';
				layer_html += '<input type = "checkbox" value="<?php echo $TPL_V1["no"]?>" id="<?php echo $TPL_V1["no"]?>" onChange="noShow(this);"/><label for="<?php echo $TPL_V1["no"]?>">Do Not Show for 24 hours</label>';
				layer_html += '<a href ="javascript://" onclick="$(this).closest(\'.layer-popup\').hide();">Close</a>';
				layer_html += '</div>';
				layer_html += '</div>';

				$('#popup_contents').append(layer_html);
<?php }else{?>
				var addressHeight = window.screenY + window.outerHeight - window.innerHeight;//주소 표시줄 높이
				
				var popup_options = 'width='+popupWidth+'px,height='+popupHeight+'px,top='+(popupToppx+addressHeight)+',left='+(window.screenX+popupLeftpx)+',toolbar=no,status=no,resizable=no,scrollbars=yes,location=no,menubar=no';
				var popup_html = '<div class="pop_content yui3-cssreset"><?php echo addslashes(htmlspecialchars_decode($TPL_V1["content"]))?></div>';
				popup_html += '<style>';
				popup_html += 'body {margin:0;}';
				popup_html += '.pop_content {margin:0;padding:0;}';
				popup_html += '.pop_content img, .pop_content table, .pop_content div {max-width:100%;max-height:100%;}';
				popup_html += '.pop_closed {text-align:right;padding:4px 12px;box-sizing:border-box;line-height:20px;font-size:12px;vertical-align:middle;}';
				popup_html += '.pop_closed input[type="checkbox"] + label {font-size:12px;}';
				popup_html += '.pop_closed a {vertical-align:top;display:inline-block;line-height:20px;margin-left:12px;position:relative;z-index:1;width:20px;height:20px;font-size:0;}';
				popup_html += '.pop_closed a:before, .pop_closed a:after {display:inline-block;content:"";width:24px;height:1px;background:#000;position:absolute;left:0;top:50%;}';
				popup_html += '.pop_closed a:before {transform:rotate(45deg) }';
				popup_html += '.pop_closed a:after {transform:rotate(-45deg) }';
				popup_html += '</style>';
				popup_html += '<div class="pop_closed">';
				popup_html += '		<label><input type="checkbox" value="<?php echo $TPL_V1["no"]?>" onChange="opener.noShow(this);">Do Not Show for 24 hours</label>';
				popup_html += '		<a href="javascript://" onclick="self.close();">Close</a>';
				popup_html += '</div>';
				var popup_<?php echo $TPL_V1["no"]?> = window.open('', 'popup_<?php echo $TPL_V1["no"]?>', popup_options); 
				popup_<?php echo $TPL_V1["no"]?>.document.body.innerHTML = popup_html;

				var popup_header_html = '<title><?php echo addslashes(htmlspecialchars_decode($TPL_V1["title"]))?></title>';
				popup_<?php echo $TPL_V1["no"]?>.document.head.innerHTML = popup_header_html;
<?php }?>
<?php }?>
<?php }}?>
<?php }?>
</script>