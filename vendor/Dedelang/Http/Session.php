<?php

namespace Dedelang\Http;

class Session{

    public static function start(){

        ini_set('session.use_only_cookies',1);

        (! session_id() ? session_start() : '');

    }

    public static function set($key,$value){

      self::start();

        $_SESSION[$key] = $value;

    }

    public static function get($key, $default = null){

        return array_get($_SESSION, $key, $default);

    }

    public static function has($key){

        self::start();

        return isset($_SESSION[$key]);

    }
    public static function remove($key){

        unset($_SESSION[$key]);

    }
    public static function pull($key){

        $value = $this->get($key);


        $this->remove($key);

        return $value;

    }

    public static function all(){

        return $_SESSION;

    }

    public static function destroy(){

        session_destroy();

        unset($_SESSION);

    }

}
