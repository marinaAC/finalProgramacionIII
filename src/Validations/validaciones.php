<?php

    namespace App\Validations;

    class Validaciones{

        public static function validName($name)
        {
            $valid = true;
            if (strpos($name, " "))
                $valid = false;
            return $valid;
        }

        public static function validClave($clave){
            $valid = true;
            if(!isset($clave) || strlen($clave)<4){
                $valid = false;
            }
            return $valid;
        }

        public static function validType($type){
            $valid = true;
            if(!isset($type) || !strcmp($type,"alumno") || !strcmp($type,"profesor") || !strcmp($type,"admin") ){
                $valid = false;
            }
            return $valid;
        }

        public static function validOnlyOne($valueRecibed,$arrayValues){
            $valid = true;
            foreach ($arrayValues as $key => $value) {
                var_dump($key);
                 if(strcmp($value,$valueRecibed)){
                     $valid = false;
                 }
            }
            return $valid;
        }


    }

?>