<?php
use Dedelang\Engine\Validate as Vali;


if (! function_exists('e_msg')) {

	function e_msg($field){

		return Vali::msg($field);

 	}
 }

if (! function_exists('r_value')) {

	function r_value($field){

		return Vali::value($field);

	 }
	 
}
 



if (! function_exists('pre')) {

   function pre($var)

   {
       echo '<pre>';

       print_r($var);

       echo '</pre>';

   }
}
if(! function_exists('encrypt')){

  function encrypt( $string, $action = 'e' ) {

	    $secret_key = 'bB,}RW=[H-TrQ!5j8T\Tc{S&w2#CDF$%^@v';

	    $secret_iv = 'S8~G?F"GAP<dVHhatC>*xy@^]5qT\Tc{S&w2#CDF$%^@v';

	    $output = false;

	    $encrypt_method = "AES-256-CBC";

	    $key = hash( 'md5', $secret_key );

	    $iv = substr( hash( 'md5', $secret_iv ), 0, 16 );

	    if( $action == 'e' ) {

	        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );

	    }
	    else if( $action == 'd' ){

	        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );

	    }

	    return $output;
	}
}

if(! function_exists('get_array')){

    function get_array($array,$key,$default = null){

        return isset($array[$key]) ? $array[$key] : $default;

    }

}

if(! function_exists('response_time')){

	function response_time(){

		return (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']);
		
	}
}



if(! function_exists('_e')){

    function _e($value){

       return htmlspecialchars($value);

    }
}

if(! function_exists('display_error_attack')){
	
	function display_error_attack($type_attack){

		include("vendor/Dedelang/View/Template/findings.php");

		die();
	}
}

 
