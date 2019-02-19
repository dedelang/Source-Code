<?php

namespace Dedelang\Engine\Security;

class Attack{

	protected static $payloadXSS = ['alert','SRC',
							'javascript',
							'http-equiv',
							'<script','<body','<input','onload','autofocus','write','<img','style'];

	protected static $payloadSql = ["'",'"','#',' -','--',' --',"'%20--",'union','select','or%201=1','order by',"'-"];

	protected static $payloadDirectorytraversal = ['../'.'/////','\..','etc/issue','etc/passwd',
																								'etc/shadow','robot',
																								'etc/group','robot.txt',
																								'etc/hosts','.php',
																								'etc/mot','.js'];

	protected static $cmd_injection = ['exec(','shell_exec','cmd','passthru','proc_open','expect_open',
																		 'eval','ssh2_exec','popen','proc_open','ping%CommonProgramFiles',
																		 'ping%PROGRAMFILES','cmd_by',
																		 "w'h'o'am'i",'w"h"o"am"i','powershell',
																		 "@^p^o^w^e^r^shell",'sleep${IFS}9',
																	 	 'system'];


	private static function GET(){

		(isset($_GET['url']) ? self::process_get() : '');

	}

	private static function process_get(){
		

		foreach (self::$payloadXSS as $value) {

			$res = strpos(strtolower($_GET['url']), $value);

			($res || in_array($_GET['url'],self::$payloadXSS) ? self::display_error('XSS (Cross site scripting)') : '');


		}

		foreach (self::$payloadSql as $value) {

			$res = strpos(strtolower($_GET['url']), $value);

			($res || in_array($_GET['url'],self::$payloadSql) ? self::display_error('SQL Injection') : '');


		}

		foreach (self::$payloadDirectorytraversal as $value) {

			$res = strpos(strtolower($_GET['url']), $value);

			($res || in_array($_GET['url'],self::$payloadDirectorytraversal) ? self::display_error('Attack Directory traversal') : '');


		}

		foreach (self::$cmd_injection as $value) {

			$res = strpos(strtolower($_GET['url']), $value);

			($res || in_array($_GET['url'],self::$cmd_injection) ? self::display_error('Command Injection') : '');

			 
		}
		
	}

	private static function POST(){
		
		($_SESSION['token']!=$_POST['csrf']? self::display_error('Changing CSRF value token') : '');

			foreach (self::$payloadXSS as $valuepayload) {

				foreach ($_POST as $key => $value) {

					$res = strpos(strtolower($value), $valuepayload);

					($res || in_array($value,self::$payloadXSS) ? self::display_error('XSS (Cross site scripting)') : '');

				}

			}

			foreach (self::$payloadSql as $valuesql) {

				foreach ($_POST as $value) {

					$res = strpos(strtolower($value), $valuesql);

					($res || in_array($value,self::$payloadSql) ? self::display_error('SQL Injection') : '');

				}
		}
	}



	public static function run(){

		$method = strtolower($_SERVER['REQUEST_METHOD']);

		($method==='post'? self::POST() : (($method==='get'? self::GET() : self::display_error('Changing Request Method'))));

	}

	public static function display_error($type_attack){

		include("vendor/Dedelang/View/Template/findings.php");

		die();
	}

}

 ?>
