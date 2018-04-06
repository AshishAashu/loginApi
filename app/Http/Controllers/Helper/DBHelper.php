<?php
namespace App\Http\Controllers\Helper;
use DB;
use App\Http\Controllers\Helper\ValidationHelper;
use stdClass;
use App\User;
class DBHelper{

    public function insertData($data){
        if(DB::table('users')->where('email','=',$data['email'])->doesntExist()){
            $newData = new User;
            foreach($data as $k=>$v){
                $newData->$k = $v;
            }
            $newData->save();
            return true;
        }else{
            return false;
        }
    }

    public static function checkUser($value){        
        $count = DB::table('users')->where('email','=',$value)->exists();
        return $count;    
    }
    /*
        Function to get User Details 
        $on {type: array}
        token {optional}
    */
    public static function getUserDetails($on, $token=null){
        $obj = new stdClass();
        $passdata = array();
        foreach($on as $k=>$v){
            array_push($passdata, $v);
        }
        $q = DBHelper::getRawQuery($on);
        $user = User::whereRaw(trim($q),$passdata)->get();
        $data = new stdClass();
        if(count($user)!=0 ){   
            $user = $user[0];
            if($token != null){    
                $user->remember_token = $token;
                $user->save(); 
            }    
            $data->success = true;
            $data->id=$user->id;
            $data->name=$user->name;
            $data->email=$user->email;
            $data->created_at=$user->created_at;   
        }else{
            $data->success = false;
            $data->msg = "User Profile Not Exists.";
        } 
        return $data;    
    } 

    /*
        Function for "whereRaw()" function parameter pass as query
        ex: "email = ?"
    */
    public static function getRawQuery($on){
        $q = "";
        if(count($on)==1){
            foreach($on as $k=>$v){
                $q = $k." = ?";
            } 
        }else{
            $keys = array_keys($on);
            for($i =0; $i< count($on); $i++){
                $q = $q.$keys[$i]." = ? ";
                if($i< count($on)-1){
                    $q = $q."and ";
                }
            }
        }
        return trim($q);
    }


    public static function updateUserData($on, $data){
        $passdata = array();
        foreach($on as $k=>$v){
            array_push($passdata, $v);
        }
        $q = DBHelper::getRawQuery($on);
        $users = User::whereRaw(trim($q),$passdata)->get();
        if(count($users)==1){
            $user = $users[0];
            foreach($data as $k=>$v){
                $user->$k = $v;
            }
            $user->save();
            return true;
        }
        return false;        
    }

    public static function deleteUserData($on){
        $passdata = array();
        foreach($on as $k=>$v){
            array_push($passdata, $v);
        }
        $q = DBHelper::getRawQuery($on);       
        $count = User::whereRaw($q,$passdata)->count();
        if($count != 0){
            $users = User::withTrashed()->where('id', $on['id'])->delete();  
            return true;
        }
        return false;  
    }

}