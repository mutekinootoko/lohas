<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Active extends CI_Controller {
	/*
	 *	area
	 */
	public function index($lat = '25.057469', $lon ='121.614371'){
		$this->session->set_userdata('lat', $lat);
		$this->session->set_userdata('lon', $lon);
		$fb_data = $this->session->userdata('fb_data');
		if(empty($fb_data['uid'])){
			$page['togo'] = 'active';
			$this->page_obj->main_content = $this->load->view('fb_login', $page, true);
		}else{
			$data = $this->api_get_area();
			
			//print_r($data);exit();
			
			$this->page_obj->main_content = $this->load->view('page-active_1', $data, true);
		}
		$this->load->view('template');
	}
	/*
	 *	name
	 */
	public function name($area = ''){
		$fb_data = $this->session->userdata('fb_data');
		if(empty($fb_data['uid'])){
			$page['togo'] = 'active';
			$this->page_obj->main_content = $this->load->view('fb_login', $page, true);
		}else{
			$data = $this->api_get_name($area);
			$data['area'] = urldecode($area);
			
			//print_r($data);exit();
			
			$this->page_obj->main_content = $this->load->view('page-active_2', $data, true);
		}
		$this->load->view('template');
	}
	/*
	 *	info
	 */
	public function info($name = '', $date = ''){
		$fb_data = $this->session->userdata('fb_data');
		if(empty($fb_data['uid'])){
			$page['togo'] = 'active';
			$this->page_obj->main_content = $this->load->view('fb_login', $page, true);
		}else{
			$data = $this->api_get_name_info($name, $date);
			if(empty($date)){
				$data['date'] = date('Y-m-d');
			}else{
				$data['date'] = $date;
			}
			$data['code'] = $name;
			
			//print_r($data);exit();
			
			$this->page_obj->main_content = $this->load->view('page-active_3', $data, true);
		}
		$this->load->view('template');
	}
	/*
	 *	get area
	 */
	private function api_get_area(){
		// parameter
		$data = array();
		
		// get fb_data
		$fb_data = $this->session->userdata('fb_data');
		$data['user_data']['user_fbid'] = $fb_data['me']['id'];
		$data['user_data']['user_name'] = $fb_data['me']['name'];
		$data['user_data']['user_avatar'] = 'https://graph.facebook.com/'.$fb_data['me']['id'].'/picture?type=large';
		$data['user_data']['lat'] = $this->session->userdata('lat');
		$data['user_data']['lon'] = $this->session->userdata('lon');
		
		// sql group by area
		$sql = "SELECT s_division as area FROM activity_canter GROUP BY s_division";
		$query = $this->db->query($sql);
		if($query->num_rows > 0){
			$data['area'] = $query->result_array();
		}
		
		//print_r($data);exit();
		
		return $data;
	}
	/*
	 *	get area 中的 s_org_name
	 */
	private function api_get_name($area = ''){
		// parameter
		$data = array();
		
		// get fb_data
		$fb_data = $this->session->userdata('fb_data');
		$data['user_data']['user_fbid'] = $fb_data['me']['id'];
		$data['user_data']['user_name'] = $fb_data['me']['name'];
		$data['user_data']['user_avatar'] = 'https://graph.facebook.com/'.$fb_data['me']['id'].'/picture?type=large';
		$data['user_data']['lat'] = $this->session->userdata('lat');
		$data['user_data']['lon'] = $this->session->userdata('lon');
		
		// sql
		$sql = "SELECT s_address_code, s_org_name as name FROM activity_canter WHERE s_division = '".urldecode($area)."'";
		$query = $this->db->query($sql);
		if($query->num_rows > 0){
			$data['name'] = $query->result_array();
		}
		
		//print_r($data);exit();
		
		return $data;
	}
	/*
	 *	get name 中的 infomation
	 */
	private function api_get_name_info($code = '', $date = ''){
		// parameter
		$data = array();
		if(empty($date)){
			$date = date('Y-m-d');
		}
		
		// get fb_data
		$fb_data = $this->session->userdata('fb_data');
		$data['user_data']['user_fbid'] = $fb_data['me']['id'];
		$data['user_data']['user_name'] = $fb_data['me']['name'];
		$data['user_data']['user_avatar'] = 'https://graph.facebook.com/'.$fb_data['me']['id'].'/picture?type=large';
		$data['user_data']['lat'] = $this->session->userdata('lat');
		$data['user_data']['lon'] = $this->session->userdata('lon');
		
		// fb api get friend list
		$temp = json_decode(file_get_contents('https://graph.facebook.com/'.$fb_data['me']['id'].'/friends?access_token='.$fb_data['access_token'].'&limit=1000'));
		foreach($temp->data as $index => $i){
			$friend[] = $i->id;
		}
		
		// sql
		$sql = "SELECT * FROM activity_canter WHERE s_address_code = '".$code."'";
		$query = $this->db->query($sql);
		if($query->num_rows > 0){
			$data['info'] = $query->row_array();
			$sql = "SELECT * FROM activity_item WHERE s_address_code = '".$code."' AND item_date = '".$date."'";
			$query = $this->db->query($sql);
			if($query->num_rows > 0){
				$data['list'] = $query->result_array();
				foreach($data['list'] as $index => $i){
					$sql = "SELECT * FROM action WHERE source_id = '".$i['item_pk']."' AND action_type = 'active' AND fbid in(".implode(",",$friend).")";
					$query = $this->db->query($sql);
					if($query->num_rows > 0){
						$data['list'][$index]['friends'] = $query->result_array();
					}
					$data['list'][$index]['avatar'] = 'https://graph.facebook.com/'.$i['fbid'].'/picture';
				}
			}
			/*
			$sql = "SELECT * 
							FROM activity_item as ai
							LEFT JOIN action as ac ON ac.source_id = ai.item_pk
							WHERE ai.s_address_code = '".$code."' AND ac.action_type = 'active' AND ac.time_add = '".$date." 00:00:00"."' AND ac.fbid in (".implode(",",$friend).")";
			$query = $this->db->query($sql);
			if($query->num_rows > 0){
				$data['friend_list'] = $query->result_array();
			}
			*/
		}
		
		//print_r($data);exit();
		
		return $data;
	}
	/*
	 *	add activity
	 */
	function api_add_activity(){
		// post parameter
		$temp = (string) $this->input->post('pk');
		$title = (string) $this->input->post('title');
		$tmp = explode(",",$temp);
		$item_pk = $tmp[0];
		$address_code = $tmp[1];
		$date = $tmp[2];
		
		// parameter
		$result = array('success' => 'N', 'msg' => '');
		$fb_data = $this->session->userdata('fb_data');
		$sql = "SELECT * FROM activity_canter WHERE s_address_code = '".$address_code."'";
		$query = $this->db->query($sql);
		if($query->num_rows > 0){
			$temp = $query->row_array();
		}
		
		if($item_pk == 0){
			// insert activity
			$sql = "INSERT INTO activity_item (fbid, title, s_address_code, item_date) VALUES ('".$fb_data['uid']."', '".mysql_real_escape_string($title)."', '".$address_code."', '".$date."')";
			$this->db->query($sql);
			$item_pk = $this->db->insert_id();
		}
		$sql = "INSERT INTO action (fbid, action_content, action_date, action_type, source_id, time_add) VALUES ('".$fb_data['uid']."', '去".$temp['s_org_name'].mysql_real_escape_string($title)."', '".$date."', 'active', '".$item_pk."', NOW())";
		if($this->db->query($sql)){
			$result['success'] = 'Y';
		}
		
		header('Content-Type: text/plain; charset=utf-8');
		echo json_encode($result);
	}
}