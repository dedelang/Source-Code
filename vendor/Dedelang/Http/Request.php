<?php

namespace Dedelang\Http;

class Request{

     public static function get($num){

       return $_GET[$num];

     }

     public static function getAll(){

       return $_GET;

     }

     public static function post($name){
 
       (getenv('NAME_FIELD_SECURITY')==="ON" ? $name=encrypt($name) : '');

       return $_POST[$name];

     }

     public static function postAll(){

       return $_POST;
       
     }

     public static function server($type){

        return $_SERVER[$type];
        
     }
}
