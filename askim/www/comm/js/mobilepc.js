// 브라우저 체크
//NS = navigator.appName.charAt(0) == "N";
//MS = !NS && navigator.userAgent.indexOf("MSIE")>=0;
//MAC = !NS && navigator.userAgent.indexOf("Mac")>=0;

//if(MS == true){ try {document.execCommand("BackgroundImageCache", false, true);} catch(e) {} }

//모바일 관련
var navUA = navigator.userAgent;

var isIPhone = navUA.indexOf("iPhone") >= 0;
var isIPad = navUA.indexOf("iPad") >= 0;
var isWinCE = navUA.indexOf("Windows CE") >= 0;
var isAndroid = navUA.indexOf("Android") >= 0;
var isBlack = navUA.indexOf("BlackBerry") >= 0;
var isNokia = navUA.indexOf("Nokia") >= 0;
var isSony = navUA.indexOf("SonyEricsson") >= 0;
var isLG = navUA.indexOf("lgtelecom") >= 0;
var isBada = navUA.indexOf("bada") >= 0;
var isDolfin = navUA.indexOf("Dolfin") >= 0;
var isMobile = navUA.indexOf("Mobile") >= 0;
var isEtc = false;
var isGtab = false;
var isGphone = false;

frmUrl = document.location.href;

if(frmUrl.indexOf("rsMobile") >= 0){
	varCut = frmUrl.split("?");
	
	if(varCut[1].indexOf("&") >= 0){
		varList = varCut[1].split("&");
		for(var i = 0; i < varList.length; i++){
			varTemp = varList[i].split("=");
			if(varTemp[0] == "rsMobile" && varTemp[1] == "false"){
				isEtc = true;
			}
		}
	}else{
		varList = varCut[1];
		varTemp = varList.split("=");
		if(varTemp[0] == "rsMobile" && varTemp[1] == "false"){
			isEtc = true;
		}
	}
}

//갤럭시탭 갤럭시S 구분
if(isAndroid == true){
	if(navUA.indexOf("SHW-M180S") >= 0){
		isGtab = true;
	}else if(navUA.indexOf("SHW-M110S") >= 0){
		isGphone = true;
	}
}

if(isIPad || isGtab || isEtc == true){
	//alert(navUA);
}else if(isWinCE || isMobile == true){
	document.location.href="/m";
} else {
	//alert("장치가 모바일이 아닙니다");
}

