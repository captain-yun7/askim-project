<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH."/models/Board_model.php");

class Admin_Board_model extends Board_model {
	private $_auth; // 관리자 권한

	public function initialize($code = null) {
		parent::initialize($code);
		$this->_auth = array_keys($this->_adm_auth);
	}

	public function board_write($data = null, $mode = null) {
		if(!isset($mode)) {
			return false;
		}

        //$this->_admin_member - 관리자 세션        
        //$this->_adm_auth - 관리자 종류와 권한

        // 2020-06-25 게시글 수정권한을 가진 관리자는 모든 게시글을 수정할 수 있어야 합니다.
        if($mode == 'modify'){
            // $isOwner 로그인한 관리자 = 게시글 작성자?
            $isOwner = (empty($data['write_userid']) === false && $data['write_userid'] == $this->_admin_member['userid']);

            // $permissionBoard 로그인한 관리자가 게시글 관리 권한이 있는가?
            $permissionBoard = (in_array('board_list',$this->_adm_auth[$this->_admin_member['level']]['board']));

            if( !$isOwner && !$permissionBoard ) {
                msg('본인이 작성한 글이 아니며, 게시글 관리 권한이 없습니다.', -1);
            }
        }

       /******************************************
        *   @deprecated
        *   게시글 관리 권한이 있는 관리자는 모든 게시글을 수정할 수 있어야합니다. 
        *   2020-06-25
        *
        *            // 글 수정일시 관리자가 작성한 글이 아니면 수정할 수 없도록 기능 추가
        *            if($mode == 'modify'){
        *                // 작성자가 관리자가 아닐 시
        *                if(!empty($data['write_userid'])){
        *                    $this->db->select("level");
        *                    $this->db->where("userid", $data['write_userid']);
        *                    $this->db->from('da_member');
        *
        *                    $result = $this->db->get()->result_array();
        *
        *                    if(!empty($result[0]['level'])){
        *                        if(in_array($result[0]['level'], array_keys($this->_adm_auth))){
        *                            msg('관리자가 작성한 글 이외에는 게시글 수정이 불가능합니다.', -1);
        *                        }
        *                    }
        *                }
        *            }
        ******************************************/
		return parent::board_write($data, $mode);
	}
	/*
	 * @override
	 * 게시판 nav정보 가져오기
	 *
	 *	@return array
	 */
	public function get_board_manege($arr_where = null, $array_like = null, $limit = null, $offset = null, $arr_orderby = null) {
		return parent::get_board_manege($arr_where, $array_like, $limit, $offset, $arr_orderby);
	}

	//john 추가 - 게시판 리스트정보 모두 가져오기
	/*
	 * 게시판 모든 리스트정보 가져오기
	 *
	 *	@pamra int $limit 갯수
	 *	@pamra int $offset 시작
	 *
	 *	@return array
	 */
	public function get_list_board_all($limit = null, $offset = null, $day = null) {
		$this->db->from("da_board_manage");

		$result = $this->db->get();
		if($result->result_id->num_rows) {
			$board_manage_list = $result->result_array();
		}
		$sql = "";
		foreach($board_manage_list as $key => $val) {
			$sql .= "SELECT '" . $val['name'] . "' as code, title, name, regdt";
			$sql .= " FROM " . 'da_board_' . $val['code'];
			if($day) {
				$sql .= " WHERE regdt > date_add(now(),interval -".$day." day) ";
			}
			if($key < count($board_manage_list)-1) {
				$sql .= " UNION ALL ";
			}
		}

		$sql .= " ORDER BY regdt DESC ";

		if(!is_null($limit)  && !is_null($offset)) {
			$sql.= " LIMIT " . $offset . ", ".$limit;
		}

		$result = $this->db->query($sql);
		if($result->result_id->num_rows) {
			$board_list["board_list"] = $result->result_array();
		}

		$sql = "SELECT SUM(cnt) FROM ( ";
		foreach($board_manage_list as $key => $val) {
			$sql .= " SELECT count(*) as cnt";
			$sql .= " FROM " . 'da_board_' . $val['code'];
			if($day) {
				$sql .= " WHERE regdt > date_add(now(),interval -".$day." day) ";
			}
			if($key < count($board_manage_list)-1) {
				$sql .= " UNION ALL ";
			}
		}

		$sql .= " ) A";

		$result = $this->db->query($sql);
		if($result->result_id->num_rows) {
			$board_list["total_rows"] = $result->result_array();
		}
		$board_list["total_rows"] = $board_list["total_rows"][0]["SUM(cnt)"];
		return $board_list;
	}
}