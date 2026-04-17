

//롤오버 스크립트
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//


// 새창띄우기
function openwin(theURL,winName,features) { 
  window.open(theURL,winName,features);

}

// 게시판 이미지 확대
function bbsviewImg(code, img, type){
	var url = "/comm/bbs/view_img.php?code=" + code + "&img=" + img + "&type=" + type;
	window.open(url, "viewImg", "height=100, width=100, menubar=no, scrollbars=no, resizable=yes, toolbar=no, status=no");
}

function viewImg_comment(comment_idx){
	var url = "/comm/bbs/view_comment_img.php?comment_idx=" + comment_idx;
	window.open(url, "viewImg_comment", "height=100, width=100, menubar=no, scrollbars=no, resizable=yes, toolbar=no, status=no");
}


// 팝이미지
function popimage(imagesrc,winwidth,winheight){
var look='width='+winwidth+',height='+winheight+','
popwin=window.open("","",look)
popwin.document.open()
popwin.document.write('<title></title><body topmargin=0 leftmargin=0><img onclick="self.close()" src="'+imagesrc+'" style="cursor:hand"></body>')
popwin.document.close()
}


//png코딩
function setPng24(obj) {
	obj.width=obj.height=1;
	obj.className=obj.className.replace(/\bpng24\b/i,'');
	obj.style.filter =
	"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ obj.src +"',sizingMethod='image');"
	obj.src='';
	return '';
}


//탭(다중)공지사항
function showmainBanner(show){
	if(show == "1"){
		mainBanner1.style.display = '';
		mainBanner2.style.display = 'none';
		mainBanner3.style.display = 'none';
	}else if(show == "2"){
		mainBanner1.style.display = 'none';
		mainBanner2.style.display = '';
		mainBanner3.style.display = 'none';

	}else if(show == "3"){
		mainBanner1.style.display = 'none';
		mainBanner2.style.display = 'none';
		mainBanner3.style.display = '';
	}
}
//
//탭(다중)제품 상세정보
function showDetail(show){
	if(show == "1"){
		Detail1.style.display = '';
		Detail2.style.display = 'none';
	}else if(show == "2"){
		Detail1.style.display = 'none';
		Detail2.style.display = '';
	}
}
//


/** 메인페이지 롤링 **/
function slideShow() {

}

/** LNB 레이어 **/
function layeropen(layerName) {
	var layer = document.getElementById("layer");
	var divs = layer.getElementsByTagName("div");
	var display_state;

	for (i = 0 ; i < divs.length ; i++ ) {
		display_state = divs[i].style.display;

		if (display_state == "block") {
			divs[i].style.display = "none";
			break;
		}
	}
	document.getElementById(layerName).style.display = "block";
}

function layerClose(parentObj)
{
	parentObj.style.display = "none";
}

//-- A/S조회 폼체크
function asCheck(frm){

	var as_h = $("#id_as_h").val();
	var as_f = $("#id_as_f").val();

	if(as_h == ""){
		alert("운송장번호를 입력해주세요.");
		$("#id_as_h").focus();
		return false;
	}

	if(as_f == ""){
		alert("핸드폰번호를 '-'를 포함하여 입력해주세요.");
		$("#id_as_f").focus();
		return false;
	}

}

//-- A/S조회 비용결제폼체크
function asCheck2(frm){

	var as_b = $("#id_as_b").val();
	var as_f = $("#id_as_f").val();

	if(as_b == ""){
		alert("일련번호를 입력해주세요.");
		$("#id_as_b").focus();
		return false;
	}

	if(as_f == ""){
		alert("핸드폰번호를 '-'를 포함하여 입력해주세요.");
		$("#id_as_f").focus();
		return false;
	}

}

// 가격에 원단위 컴마를 찍는다.
function won_Comma(price){
	if(price != null){
		var pricelen = price.length;
		var ii = pricelen%3;
		var wonprice = price.substring(0,ii);
		for(;ii<pricelen;ii+=3){
		  wonprice += "," + price.substring(ii,ii+3);
		}
		if((pricelen%3) == 0)
		wonprice = wonprice.substring(1,wonprice.length);
		return wonprice;
	}
}

// 즐겨찾기 추가
function bookMark(site_url, site_name){
	window.external.AddFavorite(site_url, site_name);
}

//-- 이메일 체인지
$(function(){
	//var email_sel = $("#email_select").val();
	$("#s_email_2").attr("readonly",false);
	$("#s_email_4").attr("readonly",false);
});

function change_email(value){
	if(value != ""){
		$("#s_email_2").attr("readonly",true);
		$("#s_email_2").val(value);
	} else {
		$("#s_email_2").attr("readonly",false);
		$("#s_email_2").val('');
	}
}

function change_email2(value){
	if(value != ""){
		$("#s_email_4").attr("readonly",true);
		$("#s_email_4").val(value);
	} else {
		$("#s_email_4").attr("readonly",false);
		$("#s_email_4").val('');
	}
}

// 이메일 체크
function check_Email(email)
{

	var email_1 = "";
	var email_2 = "";
	var check_point = 0;

	if (email.indexOf("@") < 0 ) {
		alert("e-mail에 @ 가 빠져있습니다.");
		return false;
	}
	if (email.indexOf(".") < 0 ) {
		alert("이메일형식이 잘못되었습니다.");
		return false;
	}

	if (email.indexOf("'") >= 0 ) {
		alert("e-mail에 ' 는 포함할수 없습니다..");
		return false;
	}
	if (email.indexOf("|") >= 0 ) {
		alert("e-mail에 | 는 포함할수 없습니다..");
		return false;
	}
	if (email.indexOf(">") >= 0 ) {
		alert("e-mail에 > 는 포함할수 없습니다..");
		return false;
	}
	if (email.indexOf("<") >= 0 ) {
		alert("e-mail에 < 는 포함할수 없습니다..");
		return false;
	}
	if (email.indexOf(" ") >= 0 ) {
		alert("e-mail에 스페이스는 포함할수 없습니다..");
		return false;
	}

	for(var j = 0 ; j < email.length; j++)
	{
		if(email.substring(j, j + 1) != "@" && check_point == 0)
		{
			email_1 = email_1 + email.substring(j, j + 1)
		}
		else if(email.substring(j, j + 1) == "@" )
		{
			check_point = check_point + 1;
		}
		else
		{
			email_2 = email_2 + email.substring(j, j + 1);
		}
	}

	if (check_point > 1 ) {
		alert("e-mail에 @ 는 1번이상 들어갈수 없습니다.");
		return false;
	}

	if (email_2.indexOf("(") >= 0 ) {
		alert("e-mail에 ( 는 포함할수 없습니다..");
		return false;
	}
	if (email_2.indexOf("(") >= 0 ) {
		alert("e-mail에 ( 는 포함할수 없습니다..");
		return false;
	}
	if (email_2.indexOf(")") >= 0 ) {
		alert("e-mail에 ) 는 포함할수 없습니다..");
		return false;
	}
	if (email_2.indexOf(",") >= 0 ) {
		alert("e-mail에 , 는 포함할수 없습니다..");
		return false;
	}
	if (email_2.indexOf(";") >= 0 ) {
		alert("e-mail에 ; 는 포함할수 없습니다..");
		return false;
	}
	if (email_2.indexOf(":") >= 0 ) {
		alert("e-mail에 : 는 포함할수 없습니다..");
		return false;
	}
	if (email_2.indexOf("/") >= 0 ) {
		alert("e-mail에 / 는 포함할수 없습니다..");
		return false;
	}
	if (email_2.indexOf("[") >= 0 ) {
		alert("e-mail에 [ 는 포함할수 없습니다..");
		return false;
	}
	if (email_2.indexOf("]") >= 0 ) {
		alert("e-mail에 ] 는 포함할수 없습니다..");
		return false;
	}
	if (email_2.indexOf("{") >= 0 ) {
		alert("e-mail에 { 는 포함할수 없습니다..");
		return false;
	}
	if (email_2.indexOf("}") >= 0 ) {
		alert("e-mail에 } 는 포함할수 없습니다..");
		return false;
	}
	if (email_2.indexOf(" ") >= 0 ) {
		alert("e-mail에 스페이스는 포함할수 없습니다..");
		return false;
	}

	return true;

}

function check_Email2(obj,obj2,msg) {

	if (typeof(obj) === 'undefined') return false;
	var objVal  = obj.val().replace(/(^\s*)|(\s*$)/gi, '');

	if (typeof(obj2) === 'undefined') return false;
	var objVal2  = obj2.val().replace(/(^\s*)|(\s*$)/gi, '');

	var EmailType = objVal + "@" + objVal2;

	var email_Reg_pattern = /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
	if (email_Reg_pattern.test(EmailType) == false) {
		alert(msg);
		obj.val('');
		obj2.val('');
		obj.focus();
		return false;
	}

	return true;

}

// 숫자 체크
function check_Num(tocheck)
{

	if (tocheck == null || tocheck == "")
	{
		return false;
	}

	for(var j = 0 ; j < tocheck.length; j++)
	{
		if ( tocheck.substring(j, j + 1) != "0"
			&&   tocheck.substring(j, j + 1) != "1"
			&&   tocheck.substring(j, j + 1) != "2"
			&&   tocheck.substring(j, j + 1) != "3"
			&&   tocheck.substring(j, j + 1) != "4"
			&&   tocheck.substring(j, j + 1) != "5"
			&&   tocheck.substring(j, j + 1) != "6"
			&&   tocheck.substring(j, j + 1) != "7"
			&&   tocheck.substring(j, j + 1) != "8"
			&&   tocheck.substring(j, j + 1) != "9" )
		{
			return false;
		}
	}

	return true;

}

// 주민 등록 번호 체크
function check_ResidentNO(str_f_num, str_l_num)
{
	var juminno = str_f_num + str_l_num;

	if(juminno=="" || juminno==null || juminno.length!=13) {
		alert("주민등록번호를 적어주세요.");
		return false;
	}
	var jumin1 = juminno.substr(0,6);
	var jumin2 = juminno.substr(6,7);
	var yy         = jumin1.substr(0,2);     // 년도
	var mm     = jumin1.substr(2,2);     // 월
	var dd     = jumin1.substr(4,2);     // 일
	var genda = jumin2.substr(0,1);     // 성별
	var msg, ss, cc;

	// 숫자가 아닌 것을 입력한 경우
	if (!isNumeric(jumin1)) {
		alert("주민등록번호 앞자리를 숫자로 입력하세요.");
		return false;
	}
	// 길이가 6이 아닌 경우
	if (jumin1.length != 6) {
		alert("주민등록번호 앞자리를 다시 입력하세요.");
		return false;
	}
	// 첫번째 자료에서 연월일(YYMMDD) 형식 중 기본 구성 검사
	if (yy < "00" || yy > "99" ||
	mm < "01" || mm > "12" ||
	dd < "01" || dd > "31") {
		alert("주민등록번호 앞자리를 다시 입력하세요.");
		return false;
	}
	// 숫자가 아닌 것을 입력한 경우
	if (!isNumeric(jumin2)) {
		alert("주민등록번호 뒷자리를 숫자로 입력하세요.");
		return false;
	}
	// 길이가 7이 아닌 경우
	if (jumin2.length != 7) {
		alert("주민등록번호 뒷자리를 다시 입력하세요.");
		return false;
	}
	// 성별부분이 1 ~ 4 가 아닌 경우
	if (genda < "1" || genda > "4") {
		alert("주민등록번호 뒷자리를 다시 입력하세요.");
		return false;
	}
	// 연도 계산 - 1 또는 2: 1900년대, 3 또는 4: 2000년대
	cc = (genda == "1" || genda == "2") ? "19" : "20";
	// 첫번째 자료에서 연월일(YYMMDD) 형식 중 날짜 형식 검사
	if (isYYYYMMDD(parseInt(cc+yy), parseInt(mm), parseInt(dd)) == false) {
		alert("주민등록번호 앞자리를 다시 입력하세요.");
		return false;
	}
	// Check Digit 검사
	if (!isSSN(jumin1, jumin2)) {
		alert("입력한 주민등록번호를 검토한 후, 다시 입력하세요.");
		return false;
	}

	return true;

}

function check_Pass(text, s, e, t){

	var pass    = text;
	var number  = pass.search(/[0-9]/g);
	var alpha   = pass.search(/[a-z]/ig);
	var alpha2  = pass.search(/[A-Z]/ig);
	var nonchar = pass.search(/[~!_@#$%^&*]/gi);
	var regexp  = /[0-9a-zA-Z~!_@#$%^&*]/;
	var permit  = "~!_@#$%^&*";
	
	if(t == "Y") {
		var msg = "영문+숫자+특수문자조합";
	} else {
		var msg = "영문+숫자조합";
	}

	if(pass.length < s || pass.length > e){
		alert("비밀번호는 "+msg+"으로 "+s+"~"+e+"자리만 가능합니다.");
		return false;
	}

	if(pass.search(/\s/) != -1){
		alert("비밀번호는 공백없이 입력해주세요.");
		return false;
	}

	if(t == "Y") {
		if(number < 0 || alpha < 0){
			alert("비밀번호는 "+msg+"으로 "+s+"~"+e+"자리만 가능합니다.");
			return false;
		} else if(nonchar < 0) {
			alert("특수문자를 한개이상 입력해주세요.\n( 허용특수문자 : "+permit+" )");
			return false;
		} else if(nonchar > 0 ) {
			for( var i=0; i<pass.length; i++){
				if(pass.charAt(i) != " " && regexp.test(pass.charAt(i)) == false ){
					alert(pass.charAt(i) + " 는 등록이 불가능한 문자입니다");
					return false;
				}
			}
		}
	} else {
		if(number < 0 || alpha < 0) {
			alert("비밀번호는 "+msg+"으로 "+s+"~"+e+"자리만 가능합니다.");
			return false;
		}
	}

	return true;

}

// 특수문자가있는지 체크
function check_Char(id_text)
{
	var alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	var numeric = '1234567890';
	var nonkorean = alpha+numeric+'_';

	var i ;
	for ( i=0; i < id_text.length; i++ )  {
		if( nonkorean.indexOf(id_text.substring(i,i+1)) < 0) {
			break ;
		}
	}

	if ( i != id_text.length ) {
		return false ;
	}
	else{
		return true ;
	}

	return true;

}

// 특수문자 체크
function check_nonChar(id_text)
{
	var nonchar = '~`!@#$%^&*()-_=+\|<>?,./;:"';
	var numeric = '1234567890';
	var nonkorean = nonchar+numeric;

	var i ;
	for ( i=0; i < id_text.length; i++ )  {
		if( nonkorean.indexOf(id_text.substring(i,i+1)) > 0) {
			break ;
		}
	}

	if ( i != id_text.length ) {
		return false ;
	}
	else{
		return true ;
	}

	return false;
}

//사업자등록번호 체크 함수
function check_BizRegNo(bizID)
{
	var checkID = new Array(1, 3, 7, 1, 3, 7, 1, 3, 5, 1);
	var i, Sum=0, c2, remander;

	bizID = bizID.replace(/-/gi,''); // "-"가 있을시에도 체크 가능

	for (i=0; i<=7; i++){
		Sum += checkID[i] * bizID.charAt(i);
	}

	c2 = "0" + (checkID[8] * bizID.charAt(8));
	c2 = c2.substring(c2.length - 2, c2.length);

	Sum += Math.floor(c2.charAt(0)) + Math.floor(c2.charAt(1));

	remander = (10 - (Sum % 10)) % 10 ;

	if(bizID.length != 10){
		return false;
	}else if (Math.floor(bizID.charAt(9)) != remander){
		return false;
	}else{
		return true;
	}
}

// 이미지 롤오버
function WIZ_jumpMenu(targ,selObj,restore){ //v3.0
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}

function WIZ_swapImgRestore() { //v3.0
	var i,x,a=document.WIZ_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function WIZ_preloadImages() { //v3.0
	var d=document; if(d.images){ if(!d.WIZ_p) d.WIZ_p=new Array();
	var i,j=d.WIZ_p.length,a=WIZ_preloadImages.arguments; for(i=0; i<a.length; i++)
	if (a[i].indexOf("#")!=0){ d.WIZ_p[j]=new Image; d.WIZ_p[j++].src=a[i];}}
}

function WIZ_findObj(n, d) { //v4.01
	var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
			d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);
		}
	if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=WIZ_findObj(n,d.layers[i].document);
	if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function WIZ_swapImage() { //v3.0
	var i,j=0,x,a=WIZ_swapImage.arguments; document.WIZ_sr=new Array; for(i=0;i<(a.length-2);i+=3)
	if ((x=WIZ_findObj(a[i]))!=null){document.WIZ_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function WIZ_reloadPage(init) {  //reloads the window if Nav4 resized
	if (init==true) with (navigator) {
		if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
				document.WIZ_pgW=innerWidth; document.WIZ_pgH=innerHeight; onresize=WIZ_reloadPage;
			}
	} else if (innerWidth!=document.WIZ_pgW || innerHeight!=document.WIZ_pgH) {
		location.reload();
	}
}
WIZ_reloadPage(true);

//트위터로 연결
function snsTwitter(msg,url){
	var href = "http://twitter.com/home?status=" + encodeURIComponent(msg) + " " + encodeURIComponent(url);
	window.open(href, 'twitter', '');
}

//페이스북으로 연결
function snsFacebook(msg,url){
	var href = "http://www.facebook.com/sharer.php?u=" + encodeURIComponent(url) + "&t=" + encodeURIComponent(msg);
	window.open(href, 'facebook', '');
}
// 미투데이
function snsMe2day(msg,url,tag){
	var href = "http://me2day.net/posts/new?new_post[body]=\"" + encodeURIComponent(msg) + "\":"+encodeURIComponent(url) + "&new_post[tags]=" + encodeURIComponent(tag);
  window.open(href, 'me2day', '');
}

// 페이지 이동 ($_SERVER['HTTP_REFERER'] 유지)
function goURL(url) {
	var anchor = document.createElement("a");
	if(!anchor.click) {
		//Providing a logic for Non IE
		window.location = url;
		return;
	}
	anchor.setAttribute("href", url);
	anchor.style.display = "none";
	//document.getElementById("body").appendChild(anchor);
	document.body.appendChild(anchor);
	anchor.click();
}

//function test(){
//	device.gps.status();
//}

$(function(){

	//-- 1
	$(document).on("blur keydown keypress keyup", "input:text[Onlynum]", function() {
		regExp = /[^0-9]/gi;
		v = $(this).val();
		if(regExp.test(v)){
			$(this).css("ime-mode","disabled");
			$(this).val(v.replace(regExp,""));
		}
	});

	//-- 2
	$(".Onum").on("blur keydown keypress keyup", function() {
		regExp = /[^0-9]/gi;
		v = $(this).val();
		if(regExp.test(v)){
			$(this).css("ime-mode","disabled");
			$(this).val(v.replace(regExp,""));
		}
	});

});

/* 오른쪽 마우스 막기 */
/*
$(document).bind("contextmenu", function(e){
	return false;
});
*/

/* 휴대폰 및 전화번호 체크 */
function check_Hphone2(obj,msg) {

	var regExp = /^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/;
	if (regExp.test(obj) == false) {
		alert(msg);
		return false;
	}

	return true;

}

function check_Tphone2(obj,type) {

	if(type == "T") {
		var msg = '올바른 전화번호를 입력하세요.';
	} else {
		var msg = '올바른 팩스번호를 입력하세요.';
	}
	var regExp = /^\d{2,3}-\d{3,4}-\d{4}$/;
	if (regExp.test(obj) == false) {
		alert(msg);
		return false;
	}

	return true;

}

function check_Tphone3(obj,msg) {

	var regExp = /^\d{2,3}-\d{3,4}-\d{4}$/;
	if (regExp.test(obj) == false) {
		alert(msg);
		return false;
	}

	return true;

}

function check_Comnum(obj,msg) {

	var regExp = /^\d{3}-\d{2}-\d{5}$/;
	if (regExp.test(obj) == false) {
		alert(msg);
		return false;
	}

	return true;

}

$(document).on('change', '.preview-image .upload-hidden', function(){
	var parent = $(this).parent();
	parent.children('.upload-display').remove();
	var filename;

	if(window.FileReader){
		$(this).siblings('.upload-name').val($(this)[0].files[0].name);
		//image 파일만
		if (!$(this)[0].files[0].type.match(/image\//)) return;

		var reader = new FileReader();
		reader.onload = function(e){
			var src = e.target.result;
			parent.prepend('<div class="upload-display"><div class="upload-thumb-wrap"><img src="'+src+'" class="upload-thumb"></div></div>');
		}
		reader.readAsDataURL($(this)[0].files[0]);
	}
	else {
		$(this).siblings('.upload-name').val($(this).val().split('/').pop().split('\\').pop());
		$(this)[0].select();
		$(this)[0].blur();
		var imgSrc = document.selection.createRange().text;
		parent.prepend('<div class="upload-display"><div class="upload-thumb-wrap"><img class="upload-thumb"></div></div>');

		var img = $(this).siblings('.upload-display').find('img');
		img[0].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(enable='true',sizingMethod='scale',src=\""+imgSrc+"\")";
	}
	
});

$(function() {

	$(document).on('submit', '#loginFrm', function(e) {

		e.preventDefault();

		var frmid = $("[name=id]").val();
		var frmpw = $("[name=passwd]").val();

		if(frmid == ""){
			alert("아이디를 입력하세요");
			$("[name=id]").focus();
			return false;
		}
		if(frmpw == ""){
			alert("비밀번호를 입력하세요");
			$("[name=passwd]").focus();
			return false;
		}

		if($("input:checkbox[name=secure_login]").val() != undefined) {
			if($("input:checkbox[name=secure_login]").is(":checked") == false){
				$("#loginFrm")[0].submit();
			}
		}

		var _params = "";
		_params += "prev=" + $("[name=prev]").val();
		_params += "&prev_code_page=" + $("[name=prev_code_page]").val();
		_params += "&product_idx=" + $("[name=product_idx]").val();
		_params += "&auto_login=" + $("input:checkbox[name=auto_login]").val();
		_params += "&id=" + $("[name=id]").val();
		_params += "&passwd=" + $.md5($("[name=passwd]").val());

		$.ajax({
			type: 'POST'
			,url: '/twcenter/member/dormancy_check.php'
			,cache: false
			,data: _params
			,dataType: 'json'
		}).done(function(data){
			if(data.result == '9999') {
				var url = data.url + "?seq=" + data.seq;
				window.open(url,'휴면로그인','width=600, height=500');
			} else if(data.result == '9998') {
				alert(data.msg);
				return false;
			} else if(data.result == '100') {
				$("#loginFrm")[0].submit();
			}
		}).fail(function(request, status, error){
			//console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		});

	});

});

/* -- -------------------------------------------------------------------------- -- *\
 * 아이디 및 비밀번호 변경 구조변경
\* -- -------------------------------------------------------------------------- -- */
function idfindCheck(){

	var frm = document.idfindFrm;
	var chkval = frm.t_rtype.value;
	if(chkval == "") {
		alert("아이디 찾을 방법을 선택하세요.");
		return false;
	}

	if(frm.name.value == ""){
		alert("이름을 입력하세요.");
		frm.name.focus();
		return false;
	}

	console.log("1");

	if(chkval == "email") {
		if($("input[name='email']").val() == ""){
			alert("이메일을 입력하세요.");
			$("input[name='email']").focus();
			return false;
		}
	} else {
		var hphone0 = $.trim($("#id_hphone1").val());
		var hphone1 = $.trim($("#id_hphone2").val());
		var hphone2 = $.trim($("#id_hphone3").val());

		var str_hphone = hphone0 + "-" + hphone1 + "-" + hphone2;
		if(!check_Hphone2(str_hphone, "올바른 휴대전화번호를 입력하세요.")) return false;
	}
	
	console.log("2");

	var _params = "t_rtype=" + chkval;
		_params += "&search=" + frm.search.value;
		_params += "&subsearch=idsrh";
		_params += "&submode=" + frm.submode.value;
	    _params += "&name=" + frm.name.value;
	    _params += "&email=" + $("input[name='email']").val();
	    _params += "&hphone=" + str_hphone;

	console.log("3");
	$.ajax({
		type: 'POST'
		,url: '/twcenter/member/idpw.php'
		,cache: false
		,data: _params
		,dataType: 'json'
	}).done(function(data){

		console.log("4");
		
		if(data.result == '0000') {
			$("#conIdShow").show();
			$("#conId").hide();
			$("#setid").text(data.setid);
			$("#setemail").text(data.email);
			$("[name=sendemail]").val(data.oEmail);
			$("#sethp").text(data.hp);
			$("[name=sendhp]").val(data.oHp);
			$("[name=sendname]").val(data.name);
			$("#entry").text(data.entry);
			if(chkval == "email") {
				$("#send_id_email").show();
				$("#send_id_hp").hide();
			} else if (chkval == "sms") {
				$("#send_id_email").hide();
				$("#send_id_hp").show();
			}
		} else if(data.result == '9999') {
			alert(data.msg);
			return false;
		}
	}).fail(function(request, status, error){
	});
	
}

function pwfindCheck(){

	var frm = document.pwfindFrm;
	var chkval = frm.pt_rtype.value;
	if(chkval == "") {
		alert("아이디 찾을 방법을 선택하세요.");
		return false;
	}

	if(chkval == "") {
		alert("아이디 찾을 방법을 선택하세요.");
		return false;
	}
	if(frm.uid.value == ""){
		alert("아이디를 입력하세요.");
		frm.uid.focus();
		return false;
	}
	if(frm.uname.value == ""){
		alert("이름을 입력하세요.");
		frm.uname.focus();
		return false;
	}

	if(chkval == "email") {
		if(frm.emailv.value == ""){
			alert("이메일을 입력하세요.");
			frm.emailv.focus();
			return false;
		}
	} else {
		var hphone0 = $.trim($("#p_hphone1").val());
		var hphone1 = $.trim($("#p_hphone2").val());
		var hphone2 = $.trim($("#p_hphone3").val());

		var str_hphone = hphone0 + "-" + hphone1 + "-" + hphone2;
		if(!check_Hphone2(str_hphone, "올바른 휴대전화번호를 입력하세요.")) return false;
	}

	if(frm.certchk.value != "Y") {
		alert("인증번호를 발급 받으셔야합니다.");
		return false;
	}

	if(frm.cert_number.value == ""){
		alert("인증번호를 입력해주세요.");
		return false;
	}

	var rchk_radio = document.getElementsByName('prtype');
	var sel_type = null;
	for(var i=0;i<rchk_radio.length;i++){
		if(rchk_radio[i].checked == true){ 
			sel_type = rchk_radio[i].value;
		}
	}

	if(sel_type == null){
		alert("비밀번호 정보수신 형태를 선택하세요."); 
		return false;
	}

	var _params = "t_rtype=" + chkval;
		_params += "&search=" + frm.search.value;
		_params += "&subsearch=pwsrh";
		_params += "&submode=" + frm.submode.value;
	    _params += "&name=" + frm.uname.value;
	    _params += "&email=" + frm.emailv.value;
	    _params += "&hphone=" + str_hphone;
	    _params += "&id=" + frm.uid.value;
	    _params += "&cert_number=" + frm.cert_number.value;

	$.ajax({
		type: 'POST'
		,url: '/twcenter/member/idpw.php'
		,cache: false
		,data: _params
		,dataType: 'json'
	}).done(function(data){
		if(data.result == '0000') {
			$("#conPwShow").show();
			$("#conPw").hide();
			$("#usetid").text(data.setid);
			$("[name=sendid]").val(data.setid);
		} else if(data.result == '9999') {
			alert(data.msg);
			return false;
		}
	}).fail(function(request, status, error){
	});
	

}

function dofindCheck(frm){
	if(frm.name.value == ""){
		alert("이름을 입력하세요.");
		frm.name.focus();
		return false;
	}
	if(frm.emaild.value == ""){
		alert("이메일을 입력하세요.");
		frm.emaild.focus();
		return false;
	}
}

//탭(다중)
function showIdpw(show){
	if(show == "1"){
		conId.style.display = '';
		conPw.style.display = 'none';
		$("#conId").show();
		$("#conIdShow").hide();
		$("#conPw").hide();
		$("#conPwShow").hide();
	}else if(show == "2"){
		conId.style.display = 'none';
		conPw.style.display = '';
		$("#conId").hide();
		$("#conIdShow").hide();
		$("#conPw").show();
		$("#conPwShow").hide();
	}
}

$(function() {

	$("[name=rtype]").on("click", function() {
		var rtype_val = $('input[name=rtype]:checked').val();
		if(rtype_val == 'sms') {
			$("#hpShow").show();
			$("#mailShow").hide();
			$("[name=t_rtype]").val(rtype_val);
		} else {
			$("#hpShow").hide();
			$("#mailShow").show();
			$("[name=t_rtype]").val(rtype_val);
		}
	});

	$("[name=prtype]").on("click", function() {
		var rtype_val = $('input[name=prtype]:checked').val();
		if(rtype_val == 'sms') {
			$("#phpShow").show();
			$("#pmailShow").hide();
			$("[name=pt_rtype]").val(rtype_val);
		} else {
			$("#phpShow").hide();
			$("#pmailShow").show();
			$("[name=pt_rtype]").val(rtype_val);
		}
	});

});

function sendRes() {
	var frm = $("form[name=idfindFrm]");

	var _params = "t_rtype=" + $("[name=t_rtype]", frm).val();
		_params += "&search=" + $("[name=search]", frm).val();
		_params += "&subsearch=idsend";
		_params += "&submode=" + $("[name=submode]", frm).val();
		_params += "&email=" + $("[name=sendemail]", frm).val();
		_params += "&hphone=" + $("[name=sendhp]", frm).val();
		_params += "&name=" + $("[name=sendname]", frm).val();

	$.ajax({
		type: 'POST'
		,url: '/twcenter/member/idpw.php'
		,cache: false
		,data: _params
		,dataType: 'json'
	}).done(function(data){
		if(data.result == '0000') {
			alert(data.msg);
			document.location = data.gourl;
		} else if(data.result == '9999') {
			alert(data.msg);
			return false;
		}
	}).fail(function(request, status, error){
	});

}

function certNumCheck(t) {

	var frm = document.pwfindFrm;

	if(t == "E") {
		if($("[name=emailv]").val() == "") {
			alert("이메일주소를 입력해주세요.");
			frm.emailv.focus();
			return false;
		}
	} else {
		if($("#p_hphone1").val() == "" || $("#p_hphone2").val() == "" || $("#p_hphone3").val() == "") {
			alert("휴대폰번호를 입력해주세요.");
			return false;
		}
	}

	var hphone0 = $.trim($("#p_hphone1").val());
	var hphone1 = $.trim($("#p_hphone2").val());
	var hphone2 = $.trim($("#p_hphone3").val());

	var str_hphone = hphone0 + "-" + hphone1 + "-" + hphone2;
	var _params = "gubun=" + t;
		_params += "&uid=" + $("[name=uid]").val();
		_params += "&uname=" + $("[name=uname]").val();
		_params += "&email=" + $("[name=emailv]").val();
		_params += "&hphone=" + str_hphone;

	$.ajax({
		type: 'POST'
		,url: '/twcenter/member/cert_check.php'
		,cache: false
		,data: _params
		,dataType: 'json'
	}).done(function(data){
		if(data.result == '0000') {
			alert(data.msg);
			$("[name=certchk]").val('Y');
			$("#send_btn2").hide(); /*2020-03-19 재전송기능*/
			$("#re_send2").show();
		} else if(data.result == '9999') {
			alert(data.msg);
			return false;
		}
	}).fail(function(request, status, error){
		console.log(request);
		console.log(status);
		console.log(error);
	});

}

function newPasswdChg(t) {

	var sdigit  = $("[name=sdigit]").val();
	var edigit  = $("[name=edigit]").val();
	var spechk  = $("[name=spechk]").val();
	var id      = $("[name=sendid]").val();
	var new_pw  = $("[name=new_passwd]").val();
	var new_pw2 = $("[name=new_passwd2]").val();

	if(!check_Pass($.trim(new_pw), sdigit, edigit, spechk)){
		$("[name=new_passwd]").focus();
		return false;
	}

	if(id == new_pw) {
		alert("아이디와 동일하게 비밀번호를 설정하셨습니다.");
		new_pw  = "";
		new_pw2 = "";
		$("[name=new_passwd]").focus();
		return false;
	}

	if(new_pw.search(/\s/) != -1){
		alert("비밀번호는 공백없이 입력해주세요.");
		$("[name=new_passwd]").focus();
		return false;
	}

	var dup_Reg = /(\w)\1\1\1/;
	if(dup_Reg.test(new_pw)){
		alert("비밀번호에 같은 문자를 4번이상 사용하실 수 없습니다.");
		$("[name=new_passwd]").focus();
		return false;
	}

	if(new_pw2 == ""){
		alert("비밀번호를 재입력해주세요.");
		$("[name=new_passwd2]").focus();
		return false;
	}

	if(!check_Pass($.trim(new_pw2), sdigit, edigit, spechk)){
		$("[name=new_passwd2]").focus();
		return false;
	}

	if(new_pw != new_pw2){
		alert("비밀번호가 일치하지 않습니다");
		$("[name=new_passwd]").focus();
		return false;
	}

	var _params = "gubun=" + t;
		_params += "&uid=" + $("[name=sendid]").val();
		_params += "&new_passwd=" + $.md5($("[name=new_passwd]").val());
		_params += "&new_passwd2=" + $.md5($("[name=new_passwd2]").val());

	$.ajax({
		type: 'POST'
		,url: '/twcenter/member/cert_check.php'
		,cache: false
		,data: _params
		,dataType: 'json'
	}).done(function(data){
		if(data.result == '0000') {
			alert(data.msg);
			document.location = data.gourl;
		} else if(data.result == '9999') {
			alert(data.msg);
			return false;
		}
	}).fail(function(request, status, error){
	});

}

function search() {

	var searchkey     = $("[name=total_searchkey]").val();

	if(searchkey.length < 1) {
		alert("검색어를 입력하세요.");
		$("[name=total_searchkey]").select();
		$("[name=total_searchkey]").focus();
		return false;
	}

	var cnt = 0;
	for(var i=0; i<searchkey.length; i++) {
		if (searchkey.charAt(i) == ' ')
			cnt++;
	}

	if(cnt > 1) {
		alert("형식에 맞지 않은 검색어입니다.");
		$("[name=total_searchkey]").val('');
		$("[name=total_searchkey]").focus();
		return false;
	}

	return true;
}

function bbssearch() {

	var searchkey     = $("[name=searchkey]").val();

	if(searchkey.length < 2) {
		alert("검색어를 입력하세요.");
		$("[name=searchkey]").select();
		$("[name=searchkey]").focus();
		return false;
	}

	var cnt = 0;
	for(var i=0; i<searchkey.length; i++) {
		if (searchkey.charAt(i) == ' ')
			cnt++;
	}

	if(cnt > 1) {
		alert("형식에 맞지 않은 검색어입니다.");
		$("[name=searchkey]").val('');
		$("[name=searchkey]").focus();
		return false;
	}

	return true;
}

function prdsearch() {

	var prdname     = $("[name=prdname]").val();
	var sellprice   = $("[name=sellprice]").val();
	var sellprice2  = $("[name=sellprice2]").val();
	var prdcom      = $("[name=prdcom]").val();
	var reserve     = $("[name=reserve]").val();
	var reserve2    = $("[name=reserve2]").val();

	var prdname_cnt    = 0;
	var sellprice_cnt  = 0;
	var sellprice2_cnt = 0;
	var prdcom_cnt     = 0;
	var reserve_cnt    = 0;
	var reserve2_cnt   = 0;

	for(var i=0; i<prdname.length; i++) {
		if (prdname.charAt(i) == ' ')
			prdname_cnt++;
	}

	for(var i=0; i<sellprice.length; i++) {
		if (sellprice.charAt(i) == ' ')
			sellprice_cnt++;
	}

	for(var i=0; i<sellprice2.length; i++) {
		if (sellprice2.charAt(i) == ' ')
			sellprice2_cnt++;
	}

	for(var i=0; i<prdcom.length; i++) {
		if (prdcom.charAt(i) == ' ')
			prdcom_cnt++;
	}

	for(var i=0; i<reserve.length; i++) {
		if (reserve.charAt(i) == ' ')
			reserve_cnt++;
	}

	for(var i=0; i<reserve2.length; i++) {
		if (reserve2.charAt(i) == ' ')
			reserve2_cnt++;
	}

	if(prdname_cnt > 1 || sellprice_cnt > 1 || sellprice2_cnt > 1 || prdcom_cnt > 1 || reserve_cnt > 1 || reserve2_cnt > 1) {
		alert("형식에 맞지 않은 검색어입니다.");
		return false;
	}

	return true;

}

/* 배송사별 배송상태확인 팝업 */
function delivery_status_popup(url,delnum) {
	var gourl = url + delnum
	window.open(gourl,"delivery_status_popup","height=520, width=600, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");

}

function number_format(x) {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

$(document).on("keyup keydown blur", ".numCk", function(){
	$(this).val($(this).val().replace(/[^0-9]/g,""));
});

$(document).on("keyup keydown blur", ".num_alphaCk", function(){
	$(this).val($(this).val().replace(/[^A-Za-z0-9]/g,""));
});
/*
작업자명	: 이상민
작업일시	: 2020-05-22
작업내용	: 이니시스 신용카드/현금영수증 링크버튼 추가
*/
function open_receipt(param){
	window.open("https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid="+param, "receipt_pop", "width=445,height=750,status=no");
}