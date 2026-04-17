<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/oper_info.php"; ?>
<?

// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "status=$status&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day&next_year=$next_year&next_month=$next_month&next_day=$next_day";
$param .= "&searchopt=$searchopt&searchkey=$searchkey";
$param .= "&tax_type=$tax_type";
//--------------------------------------------------------------------------------------------------

if($orderid=="") error("해당 주문번호가 없습니다");

//세금계산서 내용 가져오기
$tax_sql = "select * from wiz_tax where tax_date != '' and orderid='$orderid'";
$tax_result=query($tax_sql) or die("sql error");
$tax_info=sql_fetch_arr($tax_result);

$prd_name="";
$prd_info = explode("^^", $tax_info['prd_info']);
$no = 0;
if($prd_info) {
	for($ii = 0; $ii < count($prd_info); $ii++) {

		if(!empty($prd_info[$ii])) {
			$tmp_prd = explode("^", $prd_info[$ii]);
			if($ii < 1) $prd_name = cut_str($tmp_prd[0], 25);
			$no++;
		}
	}
}
if($no > 1) {
	$prd_name .= " 외 ".($no-1)."건";
}

//전송함수
 require_once 'tax_t_functions.php';

/*인증키 발급  하루마다 다름*/
 $id =$oper_info['tax_id'];
$password =$oper_info['tax_passwd'];
$qr['id'] = urlencode($id);
$qr['password']=urlencode($password);

// URL 생성
$method = 'GETAUTHKEYBYPW'; //인증키 전송메소드

$url="/web/api/".$method."/".$id."/".$password; //전송요청url

// HTTP 요청 인증키 소켓
$data = file_post_contents($url);

//인증키 결과
if( $data!='' ) {
	$r = @explode("|",$data);
	//인증키 받기 성공
	if( $r[0] == 'Y' ) {
		$api_key = $r[1]; //수신받은 인증키

		$url="/web/api/LOGIN"; //전송요청url
		
		/*세금계산서 등록*/

		$qr['a'] = $api_key; //인증키
		$qr['tpf'] = '2'; // 2:세금계산서, 3:거래명세서, 4:견적서
		$qr['open'] = 'n'; // 'n' 전송시 백그라운드로 저장하고 결과를 화면에 출력 합니다.

		// *세금계산서 기본 정보 (필수)
		$qr['is_receive'] = '2'; // 1:매입, 2:매출
		$qr['date'] = date('Y-m-d',strtotime($tax_info['tax_date'])); // 작성일자 (예제는 2009-11-10 을 입력합니다.)
		$qr['tax_type'] = '과세'; // 과세/영세/면세 중 택일
		$qr['type_text'] = '청구'; // 영수/청구 중 택일



		// item 데이터는 품목 갯수만큼 다음의 구성으로 등록 합니다.
		// 아이템 정보 배열에 저장 (배열 내부의 필드 순서 변경 금지)
		// 1건당 최대 10건 등록 가능
		$items = array(
			0 => array(
				'item'=>$prd_name,	// 품목명: *필수
				'isupply'=>$tax_info['supp_price'],	// 공급가: *필수, 세금 제외 금액 입력
				'itax'=>$tax_info['tax_price'],		// 세액: *필수, 과세가 아닌 경우 반드시 0 입력
				'def'=>'',			// 규격: 필수아님, 없을시 공백 입력
				'qty'=>'',			// 수량: 필수아님, 없을시 공백 입력
				'iunit'=>'',		// 단가: 필수아님, 없을시 공백 입력
			),
		);
		foreach( $items as $k=>$row ) $items[$k] = @implode("||",$row);
		$items = @implode("|^*^|",$items);
		$qr['item'] = $items;

		// *수신 거래처 및 수신 담당자 정보 (필수사항)
		// 입력한 거래처의 사업자번호를 기준으로 에 기등록된 거래처는 자동으로 등록 거래처 정보를 사용하며,
		// 존재하지 않으면 문서 등록과 동시에 거래처를 자동으로 등록 합니다.
		$qr['number'] = $tax_info['com_num']; //사업자번호
		$qr['company'] = $tax_info['com_name']; //상호명
		$qr['president'] = $tax_info['com_owner']; //대표자
		$qr['addr'] = $tax_info['com_address']; //사업장 소재지
		$qr['btype'] = $tax_info['com_kind']; //업태
		$qr['bclass'] =  $tax_info['com_class']; //종목
		$qr['name'] = $tax_info['com_owner']; //담당자
		$qr['hp'] =  $tax_info['com_tel']; //담당자; //휴대폰번호
		$qr['email'] =$tax_info['com_email']; // 전자세금계산서 수신 E-mail
		$qr['message'] = ''; // 전자세금계산서 내용에 포함되는 전달 메세지 자유 작성

		


		// 전자세금계산서 부가 정보 (비 필수 사항 / 미전송시 기본값으로 저장됨)
		/*$qr['volume'] = '1'; // 권
		$qr['issue'] = '101'; // 호
		$qr['sequence'] = '11-9999'; // 세금계산서내 일련번호 (최대 6자리 자유 작성, 단순저장용)
		$qr['description'] = '비고없음.';  // 세금계산서내 비고칸 입력 사항
		$qr['payment_type'] = '1';  // 세금계산서내 결제방법 ( 1:현금, 2:수표, 3:어음, 4:외상미수금 )*/

		 // 소켓통신 전자계산서 프리빌에 등록
		$tax_data = tax_regist($url,$qr);

		//등록성공
		if( ereg("^SUCCESS",trim($tax_data)) ) {
			$r = @explode("|",trim($tax_data));
			// ---------------------------------------------------------------------------
			// 문서 등록 번호를 귀하의 시스템에 등록했던 자료와 매핑하여 저장하십시오.
			// 추후 본 번호는 다른 API (문서보기 등) 의 파라미터로 사용됩니다.
			// ---------------------------------------------------------------------------
			//echo '등록한 문서고유번호: '.$r[1];
			$tax_no=$r[1]; //등록한 문서고유번호

			//고유번호 DB 저장
			if($tax_no!=""){
				$sql="update wiz_tax set tax_no='$tax_no' where orderid='$orderid'";
				query($sql) or die("sql error");

				$url="/web/api/PUBLISHNOW"; //발급

				$pu['a']= $api_key; //인증키
				$pu['list_code']=$tax_no; //등록한 문서고유번호
				$pu['return_path'] = "http://".$_SERVER["HTTP_HOST"]."/twcenter/manage/product/tax_t_go_ok.php"; // 공인인증서 인증 하고 나서 발행완료로 업

				$pu_data = tax_regist($url,$pu); //세금계산서 발행
				if( !ereg("ERROR",trim($pu_data)) ) { //세금계산서 발행 성공여부
					echo $pu_data; //성공시 공인인증서 모듈 호출
				}else{
				 	echo '발행요청 오류: '.$pu_data; //발행오류! 등록은 했으니 프리빌에서 전자발행하면 되긴 함
					exit;
				}
			}

		}else{ //등록실패
			echo "세금계산서등록오류".$tax_data;
			exit;
		}

	}else { //인증키수신했으나 오류
		echo '인증키 수신 오류: '.$r[1];
		exit;
	}
}else{ //api 실패
	echo '인증키 수신에 실패하였습니다.';
	exit;
}
 ?>
