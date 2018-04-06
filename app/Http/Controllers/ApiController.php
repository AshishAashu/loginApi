<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Helper\ValidationHelper;
use App\Http\Controllers\Helper\DBHelper;
use stdClass;
use App\User;
use Illuminate\Support\Facades\DB;
class ApiController extends Controller
{
    //
    
    public function registerUser(Request $req){
        $in_data = array('name'=>$req->input('name'),
                         'email'=>$req->input('email'),
                         'password'=>$req->input('password')  
                    );
        $keys = array_keys($in_data);
        $obj = new stdClass();
        $vh = new ValidationHelper;         
        $obj->status = $vh->validateData($in_data)?1:0;
        if($obj->status == 1){
            $db = new DBHelper;
            $res = $db->insertData($in_data);
            if($res){
                $obj->status = 1;
                $obj->message = "User created successful.";
            }else{
                $obj->status = 0;
                $obj->message = "Email already exists.";
            }
        }else{
            $obj->status = 0;
            $obj->message = "Data passed is not validated.";
        }
        return response(json_encode($obj))->header('Content-Type', 'JSON');  
    }

    public function loginUser(Request $req){
        $in_data = array('email'=>$req->input('email'),
                         'password'=>$req->input('password')  
                    );            
        $obj = new stdClass();
        $vh = new ValidationHelper;
        $obj->status = $vh->validateData($in_data)?1:0;
        if($obj->status == 1){
            $dbh = new DBHelper;
            $auth_token = $vh->getLoginToken();
            $data = $dbh->getUserDetails(['email'=>$in_data['email'],
                                           'password'=>$in_data['password']],$auth_token);
            if($data->success){
                $obj->auth_token = $auth_token;
                $obj->data = $data;
            }else{
                $obj->status = 0;
                $obj->message = "User profile not exist.";
            }                               
            
        }else{
            $obj->message = "Data Validation Failed... Try again";    
        }         
        return response(json_encode($obj))->header('Content-Type', 'JSON');
    }    

    /*
        Function for update user info.
    */
    public function updateUser(Request $req){
        //check for header is set or not as "TOKEN PROVIDED";
        $obj = new stdClass();
        $auth_token = $req->header("auth_token");
        if(empty($auth_token)){
            $obj->status = 0;
            $obj->message = "Authorization fail due to token.";
        }else{
            if(!isset($req->id)){
                $obj->status = 0;
                $obj->message = "Incomplete Data sent";
            }else{
                $dbh = new DBHelper;
                $on = array('id'=>$req->id,'remember_token'=>$auth_token);
                $keys = $req->all();
                $data = array();
                foreach($keys as $k=>$v){
                    if($k!='id' && $k!='email')
                    $data[$k] = $v;
                }
                $vh = new ValidationHelper;         
                $obj->status = $vh->validateData($data)?1:0;
                if($obj->status == 1 ){
                    if($dbh->updateUserData($on, $data)){
                        $obj->status = 1;
                        $obj->message = "Updated Successfully";
                    }else{
                        $obj->status = 0;
                        $obj->message = "User Profile Not Exist.";
                    }
                }else{
                    $obj->status = 0;
                    $obj->message = "Data Not Validated.";
                }
            }
        }
        return response(json_encode($obj))->header('Content-Type', 'JSON'); 
    }

    public function deleteUser(Request $req){
        $obj = new stdClass();
        $auth_token = $req->header("auth_token");
        $id = $req->header("id");
        $obj->auth_token = $auth_token;
        if(empty($auth_token)){
            $obj->status = 0;
            $obj->message = "Authorization fail due to token.";
        }else{
            if(empty($id)){
                $obj->status = 0;
                $obj->message = "Incomplete Data sent";
            }else{
                $dbh = new DBHelper;
                if($dbh->deleteUserData(['id'=>$id, 'remember_token'=>$auth_token])){
                    $obj->status = 1;
                    $obj->message = "UserProfile Deleted";
                }else{
                    $obj->status = 0;
                    $obj->message = "User Profile not Exist.";
                }
            }
        }
        return response(json_encode($obj))->header('Content-Type', 'JSON'); 
    }

}
