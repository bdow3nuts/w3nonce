<?php
define( 'PLUGIN_DIR', dirname(__FILE__).'/' );  
require_once( PLUGIN_DIR . 'inc/w3nenvironment.php');
require_once( PLUGIN_DIR . 'inc/w3nnonce.php');
class nonce_devbyw3 {
	function __construct() 
	{
	}
	public function w3test_wp_nonce() 
	{
		$nonce_devbyw3 = new nonce_devbyw3;
		$w3nenvironment = new W3nonceenvironment();
		$w3nonce_object = new W3nonce();
		
		$w3nenvironment->set_userid( '456' );
		echo 'we set user id as: ';
		$userid = $w3nenvironment->w3n_get_current_user();
		echo $userid->UID;
		$w3nonce_object->set_w3nenvironment($w3nenvironment);
		
		$nonce = $w3nonce_object->wp_nonce_create( 'firstaction' );
		echo '<br>we set nonce as: '.$nonce;
		$verify_true = $w3nonce_object->w3_verify_nonce( $nonce, 'firstaction' );
		echo '<br>we check nonce with '.$nonce.' and returns as : '.$verify_true;
		$nonce_devbyw3->checkequalornot( $verify_true, 1 );
		$newnonce = $w3nonce_object->wp_nonce_create( 'firstactionagain' );
		$verify_false_action = $w3nonce_object->w3_verify_nonce( $nonce, 'firstactionagain' );
		$verify_false_actionrec =  $nonce_devbyw3->checkequalornot( $verify_false_action, 1 );
		echo '<br>we check with new nonce '.$newnonce.' and it returns : '.$verify_false_actionrec;
		
	}
	public function checkequalornot($var1,$var2)
	{
		$check = ($var1==$var2)?1:0;
		return $check;
	}
}
nonce_devbyw3::w3test_wp_nonce();