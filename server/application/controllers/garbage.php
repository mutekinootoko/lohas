<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Garbage extends CI_Controller {
	
	public function index($type_mode="1"){
	    
	    $data['user_position'] = file_get_contents("http://lohas.adct.org.tw/index.php/garbage/selectposition/");
	    $data['user_position'] = json_decode($data['user_position']);
	    $data['use_type_mode'] = $type_mode;
	    
	    $this->page_obj->main_content = $this->load->view('garbage', $data, true);
		$this->load->view('template');
	}
	
	public function insertposition(){
    	
	}
	
	public function selectposition(){
    	
    	$sql = "SELECT action_content FROM action WHERE action_type = 'trash' AND action_date = '2012-10-21' ";
    	$query = $this->db->query($sql);
    	$reponse1 = $query->result_array();
    	$reponse1 = array( "user_position"=>$reponse1 );
    	$sql = "SELECT longitude, latitude FROM `trash_truck` WHERE `stop_address` LIKE '%南港%' ";
    	$query = $this->db->query($sql);
    	$reponse2 = $query->result_array();
    	$reponse2 = array( "trash_truck"=>$reponse2 );
    	$reponse = array( "reponse1"=>$reponse1,"reponse2"=>$reponse2 );
    	echo json_encode($reponse);
    	
	}
	
}