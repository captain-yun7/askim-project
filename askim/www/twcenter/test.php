<?
	$last_visit_time  = date("Y-m-d", strtotime("-11months"));	// 11개월전(sms발송)

	$sql = "
		select idx, id, name, hphone, email, visit, visit_time, wdate
		  from wiz_member 
		 where dchange_type != 'Y'
		 and ( 
					(visit_time != '0000-00-00 00:00:00' and visit_time is not null and visit_time <= '".$last_visit_time." 00:00:00') 
					or (visit_time = '0000-00-00 00:00:00' and wdate <= '".$last_visit_time." 00:00:00')
				)
		 and send_dormail != 'Y'
	";
	echo $sql;
?>