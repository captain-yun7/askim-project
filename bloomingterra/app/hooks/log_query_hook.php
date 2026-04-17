<?php
class LogQueryHook {
	function log_queries() {
		$CI =& get_instance();
		$times = $CI->db->query_times;
		$dbs = array();
		$output = NULL;
		$queries = $CI->db->queries;
		if(count($queries) == 0) {
			$output .= "no queries";
		} else {
			foreach($queries as $key => $query) {
				$output .= "[".date("Y-m-d H:i:s")."]".str_replace("\n", " ", $query)."\n";
			}
			$took = round(doubleval($times[$key]), 3);
			$output .= "===[took:".$took.", ".$CI->input->server("REMOTE_ADDR")."]\n\n";
		}

		$CI->load->helper("file");
		if(!write_file(APPPATH."/logs/queries-".date("Y-m-d").".log", $output, "a+")) {
			log_message("debug", "Unable to write query the file");
		}
	}
}