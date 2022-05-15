<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(){
        $data=\request()->input();
        $returnJson['data']=[];
        if(empty($data['account']) || empty($data['pwd'])){
            $returnJson['status']=500;
            return $returnJson;
        }

        $returnJson['data']=$data;
        $returnJson['code']=1;
        $returnJson['status']=200;
        return $returnJson;
    }
}
