// 클립보드 복사(주소복사)
function set_ClipBoard(uri)
{

	uri = uri.replace('&lt;','<');
	uri = uri.replace('&gt;','>');
	window.clipboardData.setData('Text', uri);
	alert('복사되었습니다.');

}


// 즐겨찾기 추가
function set_BookMark(site_url, site_name)
{
	window.external.AddFavorite(site_url, site_name);
}


// 시작페이지
function set_StartPage(site_url)
{
	//<a href="javascript:;" onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('http://www.dadembi.com');">시작페이지 설정</a>
}

function commaNum(num) {
	var len, point, str;

	num = num + "";
	point = num.length % 3
	len = num.length;

	str = num.substring(0, point);
	while (point < len) {
		if (str != "") str += ",";
		str += num.substring(point, point + 3);
		point += 3;
	}
	return str;
}

// 가격에 원단위 컴마를 찍는다.
function set_WonComma(price)
{

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

// 인풋내 콤마 제거
function removeComma(str) {
	if(typeof(str) == 'string') str = str.replace(/,/g, '');
	return str;
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

	//if (email_1.length < 3 ) {
	//	alert("e-mail에 @ 앞자리는 3자리이상 입력하셔야합니다.");
	//	form1.email.focus();
	//	return false;
	//}

	//if (email_2.length < 2 ) {
	//	alert("e-mail에 @ 뒷자리는 2자리이상 입력하셔야합니다.");
	//	form1.email.focus();
	//	return false;
	//}

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

function isYYYYMMDD(y, m, d) {
	switch (m) {
		case 2:     // 2월의 경우
			if (d > 29) return false;
			if (d == 29) {
			     // 2월 29의 경우 당해가 윤년인지를 확인
			        if ((y % 4 != 0) || (y % 100 == 0) && (y % 400 != 0))
			                return false;
			}
		break;
		case 4:     // 작은 달의 경우
		case 6:
		case 9:
		case 11:
		if (d == 31) return false;
	}

	// 큰 달의 경우
	return true;
}
function isNumeric(s) {
	for (i=0; i<s.length; i++) {
		c = s.substr(i, 1);
		if (c < "0" || c > "9") return false;
	}
	return true;
}
function isLeapYear(y) {
	if (y < 100)
		y = y + 1900;
	if ( (y % 4 == 0) && (y % 100 != 0) || (y % 400 == 0) ) {
		return true;
	} else {
		return false;
	}
}
function getNumberOfDate(yy, mm) {
	month = new Array(29,31,28,31,30,31,30,31,31,30,31,30,31);
	if (mm == 2 && isLeapYear(yy)) mm = 0;
	return month[mm];
}
function isSSN(s1, s2) {
	n = 2;
	sum = 0;
	for (i=0; i<s1.length; i++)
		sum += parseInt(s1.substr(i, 1)) * n++;
	for (i=0; i<s2.length-1; i++) {
		sum += parseInt(s2.substr(i, 1)) * n++;
		if (n == 10) n = 2;
	}
	c = 11 - sum % 11;
	if (c == 11) c = 1;
	if (c == 10) c = 0;
	if (c != parseInt(s2.substr(6, 1))) return false;
	else return true;
}

// 주민 등록 번호 체크
function check_ResidentNO_old(str_f_num, str_l_num)
{

	var i3=0
	for (var i=0;i<str_f_num.length;i++)
	{
		var ch1 = str_f_num.substring(i,i+1);
		if (ch1<'0' || ch1>'9') { i3=i3+1 }
	}
	if ((str_f_num == '') || ( i3 != 0 ))
	{
		return (false);
	}

	var i4=0
	for (var i=0;i<str_l_num.length;i++)
	{
		var ch1 = str_l_num.substring(i,i+1);
		if (ch1<'0' || ch1>'9') { i4=i4+1 }
	}
	if ((str_l_num == '') || ( i4 != 0 ))
	{
		return (false);
	}

	if(str_f_num.substring(0,1) < 0)
	{
		return (false);
	}

	if(str_l_num.substring(0,1) > 2)
	{
		return (false);
	}

	if((str_f_num.length > 7) || (str_l_num.length > 8))
	{
		return (false);
	}

	if ((str_f_num == '72') || ( str_l_num == '18'))
	{
		return (false);
	}

	var f1=str_f_num.substring(0,1)
	var f2=str_f_num.substring(1,2)
	var f3=str_f_num.substring(2,3)
	var f4=str_f_num.substring(3,4)
	var f5=str_f_num.substring(4,5)
	var f6=str_f_num.substring(5,6)
	var hap=f1*2+f2*3+f3*4+f4*5+f5*6+f6*7
	var l1=str_l_num.substring(0,1)
	var l2=str_l_num.substring(1,2)
	var l3=str_l_num.substring(2,3)
	var l4=str_l_num.substring(3,4)
	var l5=str_l_num.substring(4,5)
	var l6=str_l_num.substring(5,6)
	var l7=str_l_num.substring(6,7)
	hap=hap+l1*8+l2*9+l3*2+l4*3+l5*4+l6*5
	hap=hap%11
	hap=11-hap
	hap=hap%10
	if (hap != l7)
	{
		return (false);
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
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=WIZ_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function WIZ_swapImage() { //v3.0
  var i,j=0,x,a=WIZ_swapImage.arguments; document.WIZ_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=WIZ_findObj(a[i]))!=null){document.WIZ_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function WIZ_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.WIZ_pgW=innerWidth; document.WIZ_pgH=innerHeight; onresize=WIZ_reloadPage; }}
  else if (innerWidth!=document.WIZ_pgW || innerHeight!=document.WIZ_pgH) location.reload();
}
WIZ_reloadPage(true);

/* 게시판 목록 체크박스 */

// 체크박스 전체선택
function selectAllBbs(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = true;
			}
		}
	}
	return;
}

// 체크박스 선택해제
function selectCancelBbs(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].select_checkbox){
			if(document.forms[i].idx != null){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

// 체크박스선택 반전
function selectReverseBbs(form){
	if(form.select_tmp.checked){
		selectAllBbs();
	}else{
		selectCancelBbs();
	}
}

// 체크박스 선택리스트
function selectValueBbs(){
	var i;
	var selbbs = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selbbs = selbbs + document.forms[i].idx.value + "|";
				}
			}
	}
	return selbbs;
}

//선택게시물 삭제
function delBbs(purl, param){

	selbbs = selectValueBbs();

	if(selbbs == ""){
		alert("삭제할 게시물을 선택하세요.");
		return false;
	}else{
		if(confirm("선택한 게시물을 정말 삭제하시겠습니까?")){
			document.location = purl + "?ptype=save&mode=delbbs&selbbs=" + selbbs + "&" + param;
		}
	}
}

//게시물이동
function moveBbs(code){
	selbbs = selectValueBbs();
	if(selbbs == ""){
		alert("이동할 게시물을 선택하세요.");
		return false;
	}else{
		var uri = "/admin/bbs/move.php?code=" + code + "&selbbs=" + selbbs;
		window.open(uri,"moveBbs","width=300,height=80");
	}
}

// 게시물복사
function copyBbs(code){
	selbbs = selectValueBbs();
	if(selbbs == ""){
		alert("복사할 게시물을 선택하세요.");
		return false;
	}else{
		var uri = "/admin/bbs/copy.php?code=" + code + "&selbbs=" + selbbs;
		window.open(uri,"copyBbs","width=300,height=80");
	}
}

// 게시물 순서변경
function orderBbs(code) {
	var uri = "/admin/bbs/order.php?code=" + code;
	window.open(uri,"orderBbs","width=600,height=350,resizable=yes,scrollbars=yes");
}

// 상품 카테고리 레이어
function disableLay(getno){

	if(document.all.displayer != null){
  	if(document.all.displayer.length==null) document.all.displayer.style.display='none';
  	else document.all.displayer[getno].style.display='none';
  }

}

function displayLay(getno){

	if(document.all.displayer != null){
	  for(i=0; i<document.all.displayer.length; i++){
	          document.all.displayer[i].style.display='none';
	  }
	  if(document.all.displayer.length==null) document.all.displayer.style.display='block';
	  else document.all.displayer[getno].style.display='block';
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

function check_all(f)
{
	var chk = document.getElementsByName("checks[]");
	for (i=0; i<chk.length; i++)
	chk[i].checked = f.chkall.checked;
}

function check_val(act)
{
	var f = document.pgfrm;
	var act_url = "/admin/manage/as/as_save.php";

	if (act == "update") // 선택수정
	{
		var sel=[];

		$('input:checkbox:checked').each(function(){
			sel.push($(this).val());
		});
		var check_id = sel.join('|');
		f.idx.value = check_id;
		f.action = act_url;
		str = "수정";
	}
	else if (act == "delete") // 선택삭제
	{
		var sel=[];
		$('input:checkbox:checked').each(function(){
			sel.push($(this).val());
		});
		var check_id = sel.join('|');

		f.idx.value = check_id;
		f.action = act_url;
		str = "삭제";
	}
	else if (act == "copy") // 선택복사
	{
		var sel=[];

		$('input:checkbox:checked').each(function(){
			sel.push($(this).val());
		});
		var check_id = sel.join('|');
		f.idx.value = check_id;
		f.action = act_url;
		str = "복사";
	}
	else
		return;


	var chk = document.getElementsByName("checks[]");
	var bchk = false;

	for (i=0; i<chk.length; i++)
	{
		if (chk[i].checked)
			bchk = true;
	}

	if (!bchk) 
	{
		alert(str + "할 AS리스트를 선택하세요.");
		return;
	}

	if (act == "delete")
	{
		if (!confirm("선택한 자료를 정말 삭제 하시겠습니까?"))
			return;
	}

	f.mode.value = act;
	f.submit();
}

function check_val2(num)
{
	var chk = document.getElementsByName("checks[]");
	chk[num].checked = true;
}

function check_down(act)
{
	var f = document.pgfrm;
	var act_url = "/admin/manage/as/sel_download.php";
	var act_url2 = "/admin/manage/as/sel_print.php";

	if (act == "download")
	{
		var sel=[];
		$('input:checkbox:checked').each(function(){
			sel.push($(this).val());
		});
		var check_id = sel.join('|');

		f.idx.value = check_id;
		f.action = act_url;
		str = "다운로드";
	}
	else if (act == "print")
	{
		var sel=[];
		$('input:checkbox:checked').each(function(){
			sel.push($(this).val());
		});
		var check_id = sel.join('|');

		f.idx.value = check_id;
		f.action = act_url2;
		str = "송장출력";
	}
	else
		return;


	var chk = document.getElementsByName("checks[]");
	var bchk = false;

	for (i=0; i<chk.length; i++)
	{
		if (chk[i].checked)
			bchk = true;
	}

	if (!bchk) 
	{
		alert(str + "할 AS리스트를 선택하세요.");
		return;
	}

	f.submit();

}

function servicechg(idx,num)
{
	var url = "as_service.php?idx="+idx+"&srv_idx="+num;
	var win = "servicechg";
	
	window.open(url, win, "fullscreen=0,toolbar=0,scrollbars=0,location=0,status=0,menubar=0,resizable=0,width=400,height=300,top=0,left=0");
}

function asUpdate(check_id)
{
	var f = document.pgfrm;
	var act_url = "/admin/manage/as/as_save.php";

	f.idx.value = check_id;
	f.mode.value = "update";
	f.date_check.value = "date_check";
	f.action = act_url;
	f.submit();
}

function asDefer(check_id)
{
	var f = document.pgfrm;
	var act_url = "/admin/manage/as/as_save.php";

	f.idx.value = check_id;
	f.mode.value = "update";
	f.date_check.value = "date_defer";
	f.action = act_url;
	f.submit();
}

function asDelete(check_id)
{
	var f = document.pgfrm;
	var act_url = "/admin/manage/as/as_save.php";

	f.idx.value = check_id;
	f.mode.value = "delete";
	f.action = act_url;

	q = confirm("선택한 자료를 정말 삭제하시겠습니까?")
	if (q == true)
	{
		f.submit();
	}
	else
	{
		f.mode.value = "no_delete";
		return false;
	}
	
}

function btnchange(href)
{
	if(confirm("처리결과를 변경하시겠습니까?")) 
		document.location.href = href;
}

function csv_submit()
{
	var f = document.csvfrm;
	f.action = "./as_file_update.php";
	f.submit();
}

function csv_submit_out()
{
	var f = document.csvfrm_out;
	f.action = "./as_file_update_out.php";
	f.submit();
}

function stats_submit(act) 
{
	var f = document.searchForm;
	f.action = act;
	f.submit();
}

function sms_submit(href) 
{
	if(confirm("문자를 발송하시겠습니까?")) 
		document.location.href = href;
}


function sms_submit_t(href,date_idx,dilnum_idx) 
{

	//출고송장 비어있는지 확인
	var dilnum = document.getElementById(dilnum_idx).value;
	//alert(dilnum);
	if(dilnum==""){
		alert("출고송장이 비어있습니다.");
		return false;
	}
	

	var date = document.getElementById(date_idx).value;

	
	//출고일 날짜 형식 
	var outdate = date;
	var outdate_split = outdate.split('-');

	var outdate_split_1=outdate_split[0];
	var outdate_split_2=outdate_split[1];
	var outdate_split_3=outdate_split[2];

	if(outdate.length=='10'){
		if(outdate_split_1.length=='4'){

			if(outdate_split_2.length=='2'){

				if(outdate_split_2<13 && outdate_split_2>0){

					if(outdate_split_3.length=='2'){
						if(outdate_split_3<32 && outdate_split_3>0){
							if(confirm("문자를 발송하시겠습니까?")) 
								document.location.href = href;
						}else{
							alert("출고일이 잘못 표기되었습니다");
							return false;
						}
					}else{
						alert("출고일이 잘못 표기되었습니다");
						return false;
					}

				}else{
					alert("출고일이 잘못 표기되었습니다");
					return false;
				}

			}else{
				alert("출고일이 잘못 표기되었습니다");
				return false;
			}

		}else{
		  alert("출고일이 잘못 표기되었습니다");
		  return false;
		}
	}else if(outdate.length=='0'){
		alert("출고일이 비어있습니다.");
		return false;
	}else{
	  alert("출고일이 잘못 표기되었습니다");
	  return false;
	}
}



$(function() {
	var fileTarget = $('.filebox .upload-hidden');

	fileTarget.on('change', function(){		// 값이 변경되면
		if(window.FileReader){				// modern browser
			var filename = $(this)[0].files[0].name;
		}
		else {  // old IE
			var filename = $(this).val().split('/').pop().split('\\').pop();  // 파일명만 추출
		}

		// 추출한 파일명 삽입
		$(this).siblings('.upload-name').val(filename);
	});
});

$(function() {
	var imgTarget = $('.preview-image .upload-hidden');

	imgTarget.on('change', function(){
		var parent = $(this).parent();
		parent.children('.upload-display').remove();

		if(window.FileReader){
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
			$(this)[0].select();
			$(this)[0].blur();
			var imgSrc = document.selection.createRange().text;
			parent.prepend('<div class="upload-display"><div class="upload-thumb-wrap"><img class="upload-thumb"></div></div>');

			var img = $(this).siblings('.upload-display').find('img');
			img[0].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(enable='true',sizingMethod='scale',src=\""+imgSrc+"\")";
		}
	});

});

/** 트리구조 **/
$(function(){
	var tree_menu	= $('#tree_menu');
	var TREE_OPEN	= '/admin/manage/image/ic_plus.gif';
	var TREE_CLOSE	= '/admin/manage/image/ic_minus.gif';

	var FOLDER_OPEN	   = '<img src="/admin/manage/image/ic_folder_o.gif" style="vertical-align:middle">';
	var FOLDER_CLOSE   = '<img src="/admin/manage/image/ic_folder_c.gif" style="vertical-align:middle">';

	tree_menu.find('li:has("ul")').prepend('<a href="#" class="controller"><img src="' + TREE_CLOSE + '" /></a> ');
	tree_menu.find('li:last-child').addClass('end');

	$('.controller').click(function(){
		var temp_el = $(this).parent().find('>ul');
		var temp_el_vv = $(this).parent().find('span').attr('id');

		if (temp_el.css('display') == 'none'){
			temp_el.slideDown(100);
			$(this).find('img').attr('src', TREE_CLOSE);
			$("span#"+temp_el_vv).html(FOLDER_OPEN);
			return false;
		} else {
			temp_el.slideUp(100);
			$(this).find('img').attr('src', TREE_OPEN);
			$("span#"+temp_el_vv).html(FOLDER_CLOSE);
			return false;
		}
	});

	function tree_init(status){
		if (status == 'close'){
			tree_menu.find('ul').hide();
			$('a.controller').find('img').attr('src', TREE_OPEN);
		} else if (status == 'open'){
			tree_menu.find('ul').show();
			$('a.controller').find('img').attr('src', TREE_CLOSE);
		}
	}
	tree_init('open');

});

function moveCode(catcode,depthno,prior){
/*	$.ajax({
		type:"post"
		, async: false
		, url:  "/admin/manage/product/prd_category.php"
		, data : {"mode":"update","catcode":catcode,"depthno":depthno,"prior":prior}
		, success: function(data) {
			var href = "/admin/manage/product/prd_category.php?catcode="+catcode+"&depthno="+depthno+"&prior="+prior;
			$("#detailcategoryList").load(href);
			//document.location.href = "/admin/manage/product/prd_category.php?catcode="+catcode+"&depthno="+depthno+"&prior="+prior;
		}	
		, error: function(){
		}
	});
*/

	$.get("/admin/manage/product/prd_category.php?&mode=update&catcode="+catcode+"&depthno="+depthno+"&prior="+prior, function(data){
		var href = "/admin/manage/product/prd_category.php?catcode="+catcode+"&depthno="+depthno+"&prior="+prior;
		$("#detailcategoryList").load(href);
	});
}

function moveCode2(catcode){
	var href = "/admin/manage/product/prd_category.php?catcode="+catcode;
	$("#detailcategoryList").load(href);
}

function moveCategory(mode,posi,catcode,depthno){
/*	$.ajax({
		type:"post"
		, url:  "/admin/manage/product/category_save.php"
		, data : {"mode":mode,"posi":posi,"catcode":catcode,"depthno":depthno}
		, success: function(data) {
			var result = data.split("|");
			if(result[0] == "ok"){
				var href = "/admin/manage/product/prd_category.php?catcode="+catcode+"&depthno="+depthno+"&prior="+prior;
				$("#detailcategoryList").load(href);
				//document.location.href = "/admin/manage/product/prd_category.php?mode="+result[1]+"&catcode="+result[2]+"&depthno="+result[3];
			}
		}	
		, error: function(){
		}
	})*/
	$.get("/admin/manage/product/category_save.php?&mode="+mode+"&posi="+posi+"&catcode="+catcode+"&depthno="+depthno, function(data){
		if(data){
			var result = data.split("|");
			var href = "/admin/manage/product/prd_category.php?mode="+result[1]+"&catcode="+result[2]+"&depthno="+result[3];
			$("#detailcategoryList").load(href);
		}
	});

}

function SmoveCode(catcode,depthno,prior){
	$.get("/admin/manage/product2/prd_cat.php?&mode=update&catcode"+catcode+"&depthno="+depthno+"&prior="+prior, function(data){
		var href = "/admin/manage/product2/prd_cat.php?catcode="+catcode+"&depthno="+depthno+"&prior="+prior;
		$("#detailcategoryList").load(href);
	});
}

function SmoveCode2(catcode){
	var href = "/admin/manage/product2/prd_cat.php?catcode="+catcode;
	$("#detailcategoryList").load(href);
}

function SmoveCategory(mode,posi,catcode,depthno){
	$.get("/admin/manage/product2/cat_save.php?mode="+mode+"&posi="+posi+"&catcode="+catcode+"&depthno="+depthno, function(data){
		if(data){
			var result = data.split("|");
			var href = "/admin/manage/product2/prd_cat.php?mode="+result[1]+"&catcode="+result[2]+"&depthno="+result[3];
			$("#detailcategoryList").load(href);
		}
	});

}

function brandCode(idx){
	$.get("/admin/manage/product/prd_brand.php?&mode=update&idx="+idx, function(data){
		var href = "/admin/manage/product/prd_brand.php?mode=update&idx="+idx
		$("#detailcategoryList").load(href);
	});
}

function brandCode2(){
	var href = "/admin/manage/product/prd_brand.php";
	$("#detailcategoryList").load(href);

}

function BrandmoveCategory(mode,posi,idx){
	$.get("/admin/manage/product/brand_save.php?&mode="+mode+"&posi="+posi+"&idx="+idx, function(data){
		if(data){
			var result = data.split("|");
			var href = "/admin/manage/product/prd_brand.php?mode="+result[1]+"&idx="+result[2];
			$("#detailcategoryList").load(href);
		}
	});

}

$(function(){

	$(document).on("keyup", "input:text[Onlynum]", function() {
		regExp = /[^0-9]/gi;
		v = $(this).val();
		if(regExp.test(v)){
			alert("숫자만 가능합니다.");
			$(this).val(v.replace(regExp,"") );
		}
	});

});
