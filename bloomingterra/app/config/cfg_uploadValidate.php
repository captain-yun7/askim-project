<?php
$config = array(
	// mine 배열
	'cfg_uploadValidate' => array(
		'mine'		=> array(
			'document'	=> array('application/msword','application/CDFV2-corrupt','application/pdf','text/plain', 'application/x-hwp', 'application/unknown','application/haansofthwp','application/vndopenxmlformats-officedocumentwordprocessingmldocument','application/vndms-powerpoint','application/vndopenxmlformats-officedocumentpresentationmlpresentation','application/vndms-excel','application/vndopenxmlformats-officedocumentspreadsheetmlsheet','application/rtf','application/x-zip-compressed','application/x-7z-compressed'),
			'image' 	=> array('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif', 'image/GIF'),
			'excel'		=> array('application/excel','application/vndms-excel','application/msexcel','application/x-msexcel','application/x-ms-excel','application/x-excel','application/x-dos_ms_excel','application/xls','application/x-xls','application/vndopenxmlformats-officedocumentspreadsheetmlsheet','application/octet-stream','application/vndmsexcel','application/csv','application/haansoftxls','application/softgrid-xls','application/msexcell','application/haansoftxlsx','application/docxconverter'),
			'video'		=> array('application/mp4','video/mp4','video/mp4v-es','video/mpeg4'),
			'all' => array('application/msword','application/CDFV2-corrupt','application/pdf','text/plain', 'application/x-hwp', 'application/unknown','application/haansofthwp','application/vndopenxmlformats-officedocumentwordprocessingmldocument','application/vndms-powerpoint','application/vndopenxmlformats-officedocumentpresentationmlpresentation','application/vndms-excel','application/vndopenxmlformats-officedocumentspreadsheetmlsheet','application/rtf','application/x-zip-compressed','application/x-7z-compressed','image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif', 'image/GIF','application/excel','application/vndms-excel','application/msexcel','application/x-msexcel','application/x-ms-excel','application/x-excel','application/x-dos_ms_excel','application/xls','application/x-xls','application/vndopenxmlformats-officedocumentspreadsheetmlsheet','application/octet-stream','application/vndmsexcel','application/csv','application/haansoftxls','application/softgrid-xls','application/msexcell','application/haansoftxlsx','application/docxconverter','application/mp4','video/mp4','video/mp4v-es','video/mpeg4'),
			'sitemap'	=> array('application/xml'),
		),
		// 확장자 배열
		'extension' => array(
			'all' => array('doc', 'docx', 'hwp', 'txt', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', '7z', 'jpeg', 'jpg', 'png', 'bmp', 'gif', 'mp4', 'mpeg4'),
			'image'		=> array('jpeg','jpg','png','bmp','gif'),
			'document' 	=> array('doc','docx','hwp','txt','pdf','xls','xlsx','cell','ppt','pptx','show','rtf','zip','7z'),
			'sitemap'	=> array('xml'),
			'favicon'		=> array('ico'),
			'snsImage'		=> array('jpeg','jpg','png','bmp','gif'),
			/*
			'excel'		=> array('xls','xlsx','cell'),
			'video'		=> array('mp4','mpeg4'),
			*/
		)
	)
);

