<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index() {
        $indexData=$this->curlRequest($this->apiUrl."index");
        $result=json_decode($indexData,true);
        return $result;
    }
}
