<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Env;

class Controller extends BaseController
{
    protected $apiUrl='';
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function _initialize(){
        $this->apiUrl=env("API_URL");
    }

}
