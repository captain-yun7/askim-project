<?php 
	if($board_info['mode'] == 'write'  || $board_info['mode'] == 'modify' || $board_info['mode'] == 'answer') {
		include('_form_board_write.php');
	} else if($board_info['mode'] == 'inquire_answer_write' || $board_info['mode'] == 'inquire_answer_modify') {
		include('_form_board_answer_write.php');
	}