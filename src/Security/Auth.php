<?php
    namespace App\Security;
    use Firebase\JWT\JWT;
    
    class Auth{
        private static $key = "prueba";
        private static $encrypt = ['HS256'];

        public static function SignIn($data)
        {
            $time = time();
            $token = array(
                'exp' => $time + (60*60),
                'data' => $data
            );
    
            return JWT::encode($token, self::$key);
        }

        public static function GetData($token)
        {
            return JWT::decode(
                $token,
                self::$key,
                self::$encrypt
            )->data;
        }
    }


?>