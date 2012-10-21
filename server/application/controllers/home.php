<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	/*
	 *	home index
	 */
	public function index($lat = '25.057469', $lon ='121.614371'){
		$lat = '25.057469';
		$lon = '121.614371';
		$this->session->set_userdata('lat', $lat);
		$this->session->set_userdata('lon', $lon);
		$fb_data = $this->session->userdata('fb_data');
		if(empty($fb_data['uid'])){
			$page['togo'] = 'home';
			$this->page_obj->main_content = $this->load->view('fb_login', $page, true);
		}else{
			$date = date('Y-m-d');
			$data = $this->api_todo_list($date);
			
			//print_r($data);exit();
			
			$this->page_obj->main_content = $this->load->view('page-home', $data, true);
		}
		$this->load->view('template');
	}
	/*
	 *	todo list
	 */
	private function api_todo_list($date = ''){
		// parameter
		$data = array();
		$friend = array();
		
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
		
		// sql action list
		$sql = "SELECT * FROM action WHERE fbid = '".$fb_data['me']['id']."' ORDER BY action_pk DESC"; /*."' AND time_add = '".$date." 00:00:00"."'"*/
		$query = $this->db->query($sql);
		if($query->num_rows > 0){
			$data['todo'] = $query->result_array();
			foreach($data['todo'] as $index => $i){
				$sql = "SELECT * FROM action WHERE action_type = '".$i['action_type']."' AND source_id = '".$i['source_id']."' AND fbid in (".implode(",",$friend).")";
				$query = $this->db->query($sql);
				if($query->num_rows > 0){
					$data['todo'][$index]['friends'] = $query->result_array();
					foreach($data['todo'][$index]['friends'] as $indexj => $j){
						$data['todo'][$index]['friends'][$indexj]['avatar'] = 'https://graph.facebook.com/'.$j['fbid'].'/picture';
					}
				}
			}
		}
		
		return $data;
	}
	/*
	 *	api checkin
	 */
	function api_checkin(){
		// post parameter
		$action_pk = (int) $this->input->post('pk');
		
		// parameter
		$result = array('success' => 'N', 'msg' => '');
		
		$sql = "UPDATE action SET checkin = 'Y' WHERE action_pk = ".$action_pk;
		if($this->db->query($sql)){
			$result['success'] = 'Y';
		}
		
		header('Content-Type: text/plain; charset=utf-8');
		echo json_encode($result);
	}
}