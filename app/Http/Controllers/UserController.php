<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
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
                $returnJson['status']=500;
                $returnJson['data']['msg']='账号或密码不能为空～';
                return $returnJson;
            }
            //验证账号是否存在
            $userInfo=DB::table("carver_user")->where(['user_account'=>$data['account']])->first();
            if(!$userInfo){
                DB::rollBack();
                $returnJson['status']=500;
                $returnJson['data']['msg']='账号不存在～';
                return $returnJson;
            }

            //账号是否被锁定🔒
            if($userInfo->is_lock){
                DB::rollBack();
                $returnJson['status']=500;
                $returnJson['data']['msg']='你的账号已经被锁定～';
                return $returnJson;
            }

            //$userData=password_hash($userInfo['user_pwd'], PASSWORD_DEFAULT);
            //验证密码
            $userVeryPwd=password_verify($data['pwd'],$userInfo->user_pwd);
            if(!$userVeryPwd){
                DB::rollBack();
                $returnJson['status']=500;
                $returnJson['data']['msg']='密码不正确～';
                return $returnJson;
            }

            $upUserInfo['ip']=$request->ip();
            $upUserInfo['country']=$this->ipForCountry($request->ip())['country'];
            DB::table("carver_user")->where("id",$userInfo->id)->update($upUserInfo);
            DB::commit();
            $returnJson['data']=$data;
            $returnJson['data']['code']=1;
            $returnJson['status']=200;
            return $returnJson;


        }catch (\Exception $e){
            $returnJson['data']['code']=0;
            $returnJson['status']=500;
            $returnJson['data']['msg']='系统错误～';
            return $returnJson;
        }

    }
}
