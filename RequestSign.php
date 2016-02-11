<?php

class MY_Sign{

	public $sync_secret = array(
		'a' => 'secret_a',
		'b' => 'secret_b',
	);

	public static function get_sign($secret_key, $params = array()) {
		$sercert = $this->sync_secret;
		if(!array_key_exists($secret_key, $sercert)) {
			$err_msg = 'sercert key is not config';
			return $err_msg;
		}
		
		$app_sercert = $sercert[$secret_key];
		$str = '';
		if(!empty($params)){
			ksort($params);
			foreach($params as $key => $param){
				if($str) {
					$str .= $key."=".$param;
				}else {
					$str = $key."=".$param;
				}
			}
		}
		$str .= $app_sercert;
		$sign = md5($str);

		return $sign;
	}

	public function check_sign($secret_key, $params) {
		$sercert = $this->sync_secret;
		if(!array_key_exists($secret_key, $sercert)) {
			$err_msg = 'sercert key not config';
			return $err_msg;
		}
		$app_sercert = $sercert[$secret_key];

		$sign = $params['sign'];

		unset($params['sign']);
		$str = '';
		if(!empty($params)){
			ksort($params);
			foreach($params as $key => $param){
				if($str) {
					$str .= $key."=".$param;
				}else {
					$str = $key."=".$param;
				}
			}
		}
		$str .= $app_sercert;
		$mySign = md5($str);
		if($sign != $mySign)
			return FALSE;

		return TRUE;
	}

}