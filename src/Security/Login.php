<?php

    namespace App\Security;

    use App\Models\Usuario;

   // require_once './security/Auth.php';
    class Login{
        public $email;
        public $pass;

        public static function ValidCredencial($email,$pass)
        {

            $uss = new Usuario($email,$pass);
            if($uss->FindUss()){
                $prueba = Auth::SignIn($uss);
                var_dump($prueba);
            }
        }
    }


?>