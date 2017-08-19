<?php
class W3nonce 
{
	private $w3environment;
	private $nonce_lifetime = 86400;
	function __construct() {
	}
	private function hash_hmac( $w3algo, $w3data, $key, $raw_output = false ) {

		$packs = array (
				'md5' => 'H32',
				'sha1' => 'H40' 
		);
		
		if ( ! isset( $packs[ $w3algo ] ) )
			return false;
		
		$pack = $packs[ $w3algo ];
		
		if ( strlen( $key ) > 64 )
			$key = pack( $pack, $w3algo( $key ) );
		
		$key = str_pad( $key, 64, chr( 0 ) );
		
		$ipad = ( substr( $key, 0, 64 ) ^ str_repeat( chr( 0x36 ), 64 ) );
		$opad = ( substr( $key, 0, 64 ) ^ str_repeat( chr( 0x5C ), 64 ) );
		
		$hmac = $w3algo( $opad . pack( $pack, $w3algo( $ipad . $w3data ) ) );
		
		if ( $raw_output )
			return pack( $pack, $hmac );
		return $hmac;
	}
	private function w3check_equals_hash( $a, $b ) {

		$a_length = strlen( $a );
		if ( $a_length !== strlen( $b ) ) {
			return false;
		}
		$result = 0;
		for( $i = 0; $i < $a_length; $i ++ ) {
			$result |= ord( $a[ $i ] ) ^ ord( $b[ $i ] );
		}
		
		return $result === 0;
	}
	private function w3_nonce_tick() {

		return ceil( time() / ( $this->nonce_lifetime / 2 ) );
	}
	private function _http_build_query( $data, $prefix = null, $sep = null, $key = '', $urlencode = true ) {

		$ret = array ();
		
		foreach ( ( array ) $data as $k => $v ) {
			if ( $urlencode )
				$k = urlencode( $k );
			if ( is_int( $k ) && $prefix != null )
				$k = $prefix . $k;
			if ( ! empty( $key ) )
				$k = $key . '%5B' . $k . '%5D';
			if ( $v === null )
				continue;
			elseif ( $v === false )
				$v = '0';
			
			if ( is_array( $v ) || is_object( $v ) )
				array_push( $ret, _http_build_query( $v, '', $sep, $k, $urlencode ) );
			elseif ( $urlencode )
				array_push( $ret, $k . '=' . urlencode( $v ) );
			else
				array_push( $ret, $k . '=' . $v );
		}
		
		if ( null === $sep )
			$sep = ini_get( 'arg_separator.output' );
		
		return implode( $sep, $ret );
	}
	private function w3_create_hash( $data, $scheme = 'auth' ) {

		$salt = $this->w3environment->salt;
		return hash_hmac( 'md5', $data, $salt );
	}
	public function w3_verify_nonce( $nonce, $action ) {

		$nonce = ( string ) $nonce;
		$user = $this->w3environment->w3n_get_current_user();
		$w3userid = ( int ) $user->UID;
		
		if ( empty( $nonce ) ) {
			return false;
		}
		$action = ( string ) $action;
		if ( empty( $action ) ) {
			$action="new";
		}
		
		
		$token = $this->w3environment->w3n_get_session_token();
		$i = $this->w3_nonce_tick();
		$expected = substr( $this->w3_create_hash( $i.'|'.$action.'|'.$w3userid.'|'.$token,'nonce'), -5, 5 );
		if ( $this->w3check_equals_hash( $expected, $nonce ) ) {
			return 1;
		}
		$expected = substr( $this->w3_create_hash( ( $i - 1 ) . '|' . $action . '|' . $w3userid . '|' . $token, 'nonce' ), - 12, 10 );
		if ( $this->w3check_equals_hash( $expected, $nonce ) ) {
			return 2;
		}
		return false;
	}
	public function set_w3nenvironment( $w3n_environment ) {
		$this->w3environment = $w3n_environment;
	}
	public function wp_nonce_create($action) 
	{
		if (empty($action)){$action="new";}
		$user = $this->w3environment->w3n_get_current_user();
		$w3userid = ( int ) $user->UID;
		$token = $this->w3environment->w3n_get_session_token();
		$i = $this->w3_nonce_tick();
		return substr( $this->w3_create_hash($i.'|'.$action.'|'.$w3userid.'|'.$token,'nonce'),-5,5);
	}

}
?>