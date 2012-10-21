<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index(){
		// parameter
		$data = array();
		$fb_data = $this->session->userdata('fb_data');
		$data['user_data']['user_fbid'] = $fb_data['me']['id'];
		$data['user_data']['user_name'] = $fb_data['me']['name'];
		$data['user_data']['user_avatar'] = 'https://graph.facebook.com/'.$fb_data['me']['id'].'/picture?type=large';
		$data['user_data']['lat'] = $this->session->userdata('lat');
		$data['user_data']['lon'] = $this->session->userdata('lon');
		
		if(empty($fb_data['uid'])){
			$page['togo'] = 'home';
			$this->page_obj->main_content = $this->load->view('fb_login', $page, true);
		}else{
			// fb api get friend list
			$temp = json_decode(file_get_contents('https://graph.facebook.com/'.$fb_data['me']['id'].'/friends?access_token='.$fb_data['access_token'].'&limit=1000'));
			foreach($temp->data as $index => $i){
				$friend[] = $i->id;
			}
			if(!empty($friend)){
				$sql = "SELECT * FROM action WHERE fbid in (".implode(",",$friend).")";
				$query = $this->db->query($sql);
				if($query->num_rows > 0){
					$data['lists'] = $query->result_array();
					foreach($data['lists'] as $index => $i){
						$data['lists'][$index]['avatar'] = 'https://graph.facebook.com/'.$i['fbid'].'/picture';
					}
				}
			}
			
			$this->page_obj->page_title = 'Lohas 樂活台北城';
			
			//print_r($data);exit();
			
			$this->page_obj->main_content = $this->load->view('page_index', $data, true);
		}
		$this->load->view('template');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */