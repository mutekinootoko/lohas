<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Controller {
	public function index($lat='0',$lon='0'){
		$this->session->set_userdata('lat', $lat);
		$this->session->set_userdata('lon', $lon);
		$fb_data = $this->session->userdata('fb_data');
		if(empty($fb_data['uid'])){
			$page['togo'] = 'products';
			$this->page_obj->main_content = $this->load->view('fb_login', $page, true);
		}else{
			$data['items'] = $this->api_sell_list();
			$this->page_obj->main_content = $this->load->view('shopping', $data, true);
			// todo maincontent
			// print_r($fb_data);
		}
		$this->load->view('template');
	}
	// 商品優惠列表, 不分日期
	public function api_sell_list(){
		if(empty($date)){
			$date = date('Y-m-d');
		}
		$items = array();
		$fb_data = $this->session->userdata('fb_data');
		$sql = "SELECT sell.*, action.action_pk FROM sell LEFT JOIN action ON (action.action_type = 'sell' AND action.source_id = sell.sell_pk AND action.fbid = '".$fb_data['me']['id']."')";
		$sql .= " WHERE sell.private='N' OR (sell.private='Y' AND sell.fbid = '".$fb_data['me']['id']."')";
		$query = $this->db->query($sql);
		$items = $query->result_array();
		$profile = $this->session->userdata('fb_data');
		//print_r($profile);exit();
		$user['user_fbid'] = $profile['me']['id'];
		$user['user_name'] = $profile['me']['name'];
		$user['user_avatar'] = 'https://graph.facebook.com/'.$user['user_fbid'].'/picture?type=large';
		$result['user_data'] = $user;
		$result['items'] = $items;
		//print_r($result['items']);exit();
		//echo json_encode($result);
		return $items;
	}
	// 商品詳細資訊
	public function api_sell_detail($sell_pk){
		$items = array();
		$fb_data = $this->session->userdata('fb_data');
		$sql = "SELECT sell.*, action.action_pk FROM sell LEFT JOIN action ON (action.action_type = 'sell' AND action.source_id = sell.sell_pk AND fbid = '".$fb_data['me']['id']."') WHERE sell_pk = ".$sell_pk;
		//echo $sql;exit();
		$query = $this->db->query($sql);
		$item = $query->row_array();
		
		$profile = $this->session->userdata('fb_data');
		//print_r($profile);exit();
		$user['user_fbid'] = $profile['me']['id'];
		$user['user_name'] = $profile['me']['name'];
		$user['lat'] = $profile['me']['name'];
		$user['lon'] = $profile['me']['name'];
		$user['user_avatar'] = 'https://graph.facebook.com/'.$user['user_fbid'].'/picture?type=large';
		$result['user_data'] = $user;
		$result['detail'] = $item;
		echo json_encode($result);
	}
	// 商品加入追蹤
	public function sell_booking($sell_pk,$date=''){
		$this->api_sell_booking($sell_pk,date('Y-m-d'));
		header("Location:/index.php/home/");
		exit();
	}
	// 商品加入追蹤
	public function api_sell_booking($sell_pk,$date){
		$date = empty($date)? date('Y-m-d'):urldecode($date);
		$result = array('success'=>'N', 'msg'=>'');
		$items = array();
		$fb_data = $this->session->userdata('fb_data');
		$sql = "SELECT action_pk FROM action WHERE action_type = 'sell' AND source_id = ".$sell_pk." AND fbid = '".$fb_data['me']['id']."'";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$sql = "DELETE FROM action WHERE action_type = 'sell' AND source_id = ".$sell_pk." AND fbid = '".$fb_data['me']['id']."'";
			$this->db->query($sql);
			$result['success'] = 'Y';
			$result['msg'] = '已移除';
		}else{
			$sql = "SELECT * FROM sell WHERE sell_pk = ".$sell_pk;
			$query = $this->db->query($sql);
			$product = $query->row_array();
			
			$sql = "INSERT INTO action (fbid,action_content,action_date,action_type,source_id,time_add) VALUES ";
			$sql .= "('".$fb_data['me']['id']."','[優惠]".$product["title"]."','".$date."','sell',".$sell_pk.",NOW())";
			//echo $sql;exit();
			$this->db->query($sql);
			$result['success'] = 'Y';
			$result['msg'] = '已新增';
		}
		//echo json_encode($result);
	}
	// 新增私有商品
	public function api_sell_new(){
		$sell['title'] = trim($this->input->post("title"));
		$profile = $this->session->userdata('fb_data');
		$sell['fbid'] = $profile['me']['id'];
		$sell['private'] = 'Y';
		$str = $this->db->insert_string('sell', $sell);
		$this->db->query($str);
		$sell_pk = $this->db->insert_id();
		
		$action["fbid"] = $profile['me']['id'];
		$action["action_content"] = "[私有優惠]".trim($this->input->post("title"));
		$action['action_date'] = trim($this->input->post("date"));
		$action['action_date'] = empty($action['action_date'])? date('Y-m-d'):$action['action_date'];
		$action['action_type'] = 'sell';
		$action['source_id'] = $sell_pk;
		$action['time_add'] = date("Y-m-d H:i:s");
		$str = $this->db->insert_string('sell', $action);
		$this->db->query($str);
		
		$result['success'] = 'Y';
		$result['msg'] = '已新增';
		echo json_encode($result);
	}
}