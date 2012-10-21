<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_get extends CI_Controller {
	public function index(){
		echo 'Aontent API!';
	}
	
	public function activity_canter(){
		$data = json_decode(file_get_contents('http://www.api.cloud.taipei.gov.tw/CSCP_API/swd/swl/04/11'));
		foreach($data as $d){
			$detail = json_decode(file_get_contents('http://www.api.cloud.taipei.gov.tw/CSCP_API/swd/swl/org/'.$d->s_address_code));
			//print_r($detail[0]->s_org_name);
			
			$center = array();
			$center['s_address_code'] = $d->s_address_code;
			$center['s_org_name'] = $detail[0]->s_org_name;
			$center['s_division'] = $detail[0]->s_division;
			$center['s_contact_name'] = $detail[0]->s_contact_name;
			$center['s_zone_code'] = $detail[0]->s_zone_code;
			$center['s_address'] = $detail[0]->s_address;
			$center['s_phone'] = $detail[0]->s_phone;
			$center['s_org_introduction'] = $detail[0]->s_org_introduction;
			$center['s_lat'] = $detail[0]->s_lat;
			$center['s_lon'] = $detail[0]->s_lon;
			$str = $this->db->insert_string('activity_canter', $center);
			$this->db->query($str);
			echo $str."<br />";
			
		}
	}
	
	public function sell(){
		//$data = simplexml_load_string(file_get_contents('http://taipei.kijiji.com.tw/f-SearchAdRss?CatId=500739&Location=50001'));
		$data = simplexml_load_string(file_get_contents('http://taipei.kijiji.com.tw/f-SearchAdRss?CatId=500737&Location=1400001'));
		$items = $data->channel->item;
		foreach($items as $i){
			$center = array();
			$center['title'] = $i->title."";
			$center['link'] = $i->link."";
			$center['description'] = $i->description."";
			$center['pubDate'] = date('Y-m-d H:i:s', strtotime($i->pubDate));
			$center['guid'] = $i->guid."";
			$tmp = explode(" ( ", $i->title);
			$tmp2 = explode(" )", $tmp[COUNT($tmp)-1]);
			$center['area'] = $tmp2[0];
			$str = $this->db->insert_string('sell', $center);
			$this->db->query($str);
			echo $str."<br />";
		}
	}
}