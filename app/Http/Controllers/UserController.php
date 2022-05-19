<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        parent::_initialize();
    }
    /**
     * @return mixed
     * @desc 用户登录
     */
    public function login(){
        try {
            DB::beginTransaction();
            $request=\request();
            $data=$request->input();

            $returnJson['data']['msg']='';
            if(empty($data['account']) || empty($data['pwd'])){
                DB::rollBack();
                return returnJson(500,'账号或密码不能为空～',0);
            }
            //验证账号是否存在
            $userInfo=DB::table("carver_user")->where(['user_account'=>$data['account']])->first();
            if(!$userInfo){
                DB::rollBack();
                return returnJson(500,'账号不存在～',0);
            }

            //账号是否被锁定🔒
            if($userInfo->is_lock){
                DB::rollBack();
                return returnJson(500,'你的账号已经被锁定～',0);
            }

            //$userData=password_hash($userInfo['user_pwd'], PASSWORD_DEFAULT);
            //验证密码
            $userVeryPwd=password_verify($data['pwd'],$userInfo->user_pwd);
            if(!$userVeryPwd){
                DB::rollBack();
                return returnJson(500,'密码不正确～',0);
            }

            $upUserInfo['ip']=$request->ip();
            $upUserInfo['country']=ipForCountry($request->ip())['country'];
            DB::table("carver_user")->where("id",$userInfo->id)->update($upUserInfo);
            $data['uid']=$userInfo->id;
            DB::commit();
            return returnJson(200,'',1,$data);


        }catch (\Exception $e){
            return returnJson(500,'系统错误～',0);
        }

    }


    /**
     * @return mixed
     * @desc 用户注册
     */
    public function register(){
        try {
            DB::beginTransaction();
            $request=\request();
            $data=$request->input();

            $returnJson['data']['msg']='';
            if(empty($data['user_account']) || empty($data['user_pwd'])){
                DB::rollBack();
                return returnJson(500,'账号或密码不能为空～',0);
            }
            //验证账号是否存在
            $userInfo=DB::table("carver_user")->where(['user_account'=>$data['user_account']])->first();
            if($userInfo){
                DB::rollBack();
                return returnJson(500,'该账号已经被占用～',0);
            }

            $addUserInfo['user_account']=$data['user_account'];
            $addUserInfo['user_name']=$data['user_account'];
            $addUserInfo['user_pwd']=password_hash($data['user_pwd'], PASSWORD_DEFAULT);
            $addUserInfo['ip']=$request->ip();
            $addUserInfo['country']=ipForCountry($request->ip())['country'];
            $addUserInfo['add_time']=time();
            DB::table("carver_user")->insert($addUserInfo);

            DB::commit();
            return returnJson(200,'',1,$data);


        }catch (\Exception $e){
            return returnJson(500,'系统错误～',0);
        }

    }
}
