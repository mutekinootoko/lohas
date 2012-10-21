<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fblogin extends CI_Controller {
	// fb parameter
	var $fbid = array();
	
	var $fb_appid = '368838293199614';
	
	var $fb_secret = '40279705837f2676ce1499bd2d048ef4';
	
	var $logoutUrl = '';
	
	var $loginUrl = '';
	
	public function __construct(){
		parent::__construct();
		
		//print_r($this->session->all_userdata());exit();
		
		$this->load->helper('url');
		$togo = !empty($_GET['togo'])? $_GET['togo']:'home';
		if(!empty($togo)){
			$redir = base_url('index.php/'.$togo);
			$this->session->set_userdata('redir', $redir);
		}
		
		// 取得fb物件 設定
    $config = array(
	    'appId' => $this->fb_appid,
	    'secret' => $this->fb_secret,
	    'fileUpload' => true, // Indicates if the CURL based @ syntax for file uploads is enabled.
    );
    // fb使用者登入權限設定
    $loginConf = array(
	    'redirect_uri' => base_url('index.php/fblogin'),
	    'scope' => 'user_likes, user_photos, email, publish_stream, publish_actions, read_friendlists',
	    'display' => 'page'
    );
    $this->load->library('Facebook', $config);
    $profile = null;
		$code = $_GET['code'];
		if(!empty($code)){
			try {
				$user = $this->facebook->getUser();
	      $profile = $this->facebook->api('/me');
		  } catch (FacebookApiException $e) {
	      error_log($e);
	      $user = NULL;
		  }
		  $this->logoutUrl = $this->facebook->getLogoutUrl();
		}else{
			$this->loginUrl = $this->facebook->getLoginUrl($loginConf);
		}
		if(!empty($code)){
			$this->session->set_userdata('code', $code);
      $fb_data = array(
          'me' => $profile,
          'uid' => $user,
          'access_token' => $this->facebook->getAccessToken(),
          'loginUrl' => $this->loginUrl,
          'logoutUrl' => $this->logoutUrl
      );
  	}else{
  		header('Location:'.$this->facebook->getLoginUrl($loginConf));
  		exit();
  	}
    $this->session->set_userdata('fb_data', $fb_data);
	}
	
	/*
	 *	fblogin
	 */
	public function index(){
		//print_r($this->session->all_userdata());exit();
		
		$this->load->helper('url');
		
		// parameter
		$fb_data = $this->session->userdata('fb_data');
		
		// sql member
		$sql = "SELECT * FROM member WHERE fbid = '".$fb_data['me']['id']."'";
		$query = $this->db->query($sql);
		if($query->num_rows > 0){
			$sql = "UPDATE member SET time_login = NOW(), access_token = '".$fb_data['access_token']."' WHERE fbid = '".$fb_data['me']['id']."'";
		}else{
			$sql = "INSERT INTO member (fbid, time_login, fb_name, access_token) VALUES ('".$fb_data['me']['id']."', NOW(), '".$fb_data['me']['name']."', '".$fb_data['access_token']."')";
		}
		$this->db->query($sql);
		
		$redir = $this->session->userdata('redir');
		redirect($redir, 'refresh');
	}
}