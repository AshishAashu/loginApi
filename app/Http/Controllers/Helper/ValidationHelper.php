<?php
namespace App\Http\Controllers\Helper;
class ValidationHelper{

    public function validateName($name){
        $name = trim($name);
        if(preg_match("/^[A-Za-z]+$/",$name,$match)){
            return true;
        }
        return false;
    }
    public function validateEmail($email){
        $email = trim($email);
        if(preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/",$email,$match)){
            return true;
        }
        return false;
    }
    public function validatePassword($pass){
        $pass = trim($pass);
        if(strlen($pass)>=8 && strlen($pass)<=15)
            return true;
        return false;
    }

    public function validateData($in_data){
        $keys = array_keys($in_data);
        for($i = 0;$i<count($keys);$i++){
            $k = $keys[$i];
            $flag = 1;
            switch($k){
                case 'name':
                    $flag = $this->validateName($in_data[$k])?:0;
                    break;
                case 'email':
                    $flag = $this->validateEmail($in_data[$k])?:0;
                    break;
                case 'password':
                    $flag = $this->validatePassword($in_data[$k])?:0;
                    break;       
            }
            if($flag != 1){
                return false;
            }
        }
        return true;
    }

    public function getLoginToken(){
        $token = '';
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        while(strlen($token)<25){
            $token = $token.$str[rand(0,61)];
        } 
        return $token;
    }
}