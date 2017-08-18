<?php
class W3nonceenvironment {
	public $salt = '$2y$10$.vGA1O9wmRjrwAVXD98HNOgsNpDczlqm3Jq7KnEd1rVAGv3Fykk1a';
	public $options = array('blog_charset' => 'utf8');
	public $userid = '123';
	
	public function set_userid($userid)
	{
		$this->userid = $userid;
	}
	
	public function w3n_get_current_user()
	{
		$user = array('UID' => $this->userid);
		return (object) $user;
	}
	public function w3n_get_session_token()
	{
		return 'asdfgqwerty';
	}
	public function getw3n_option($option){
		return $this->options[$option];
	}
	public function get_request_uri(){
		return '/request/uri';
	}
}