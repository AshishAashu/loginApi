<?php
namespace App\Http\Controllers\Helper;
class ValidationHelper{
    /********************************************************************
    *                                                                   *
    *    Method to validate "Name" as it only have letters and space.   *
    *                                                                   *
    ********************************************************************/
    public function validateName($name){
        $name = trim($name);
        if(preg_match("/^[A-Za-z ]+$/",$name,$match)){
            return true;
        }
        return false;
    }

    /********************************************************************
    *                                                                   *
    *   Method to validate "Email" as it follow all credential of email.*
    *                                                                   *
    ********************************************************************/

    public function validateEmail($email){
        $email = trim($email);
        if(preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/",$email,$match)){
            return true;
        }
        return false;
    }

    /********************************************************************
    *                                                                   *
    *   Method to validate "Password" as:                               *
    *   1)It have minimum length = 8                                    *
    *   2)It have maximum length = 15                                   *  
    *   3)There should be Minimum One upperCase letter                  * 
    *   4)There should be Minimum One lowerCase letter                  * 
    *   5)There should be Minimum One Number                            * 
    *   6)There should be Minimum One special character                 *    
    *                                                                   *     
    *                                                                   *
    *   3-4 have to implement...                                        *    
    *                                                                   *
    ********************************************************************/
    public function validatePassword($pass){
        $pass = trim($pass);
        if(strlen($pass)>=8 && strlen($pass)<=15)
            return true;
        return false;
    }

    /********************************************************************
    *                                                                   *
    *   Calling of method to validate data as:                          *
    *   for 'name' : Call of "validateName()"                           *
    *   for 'email' : Call of "validateEmail()"                         *
    *   for 'password' : Call of "validatePassword()"                   *
    *                                                                   *
    ********************************************************************/

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

    /********************************************************************
    *                                                                   *
    *   Method to generate token of length[25]                          *
    *       =>calling method when user login                            *
    *                                                                   *
    ********************************************************************/
    public function getLoginToken(){
        $token = '';
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        while(strlen($token)<25){
            $token = $token.$str[mt_rand(0,61)];
        } 
        return $token;
    }
}