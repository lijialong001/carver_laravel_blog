<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $data['name']=11;
        $data['pwd']=000;
        $data['status']=200;
        $data['code']=1;
        return $data;
    }
}
