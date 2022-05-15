<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index() {
        $result=$this->curlRequest($this->apiUrl."article/");
        $resultJson=json_decode($result,true);
        return $resultJson;
    }
    public function experiment(){
        $result=$this->curlRequest($this->apiUrl."experiment/");
        $resultJson=json_decode($result,true);
        return $resultJson;
    }

    public function content(){
        $params=\request()->input();
        $result=$this->curlRequest($this->apiUrl."article/content?id=".$params['id']);
        $resultJson=json_decode($result,true);
        return $resultJson;
    }
    public function a(){
        $params=\request()->input();
        $result=$this->curlRequest($this->apiUrl."article/a?id=".$params['id']);
        $resultJson=json_decode($result,true);
        return $resultJson;
    }
    public function z(){
        $params=\request()->input();
        $result=$this->curlRequest($this->apiUrl."article/z?id=".$params['id']);
        $resultJson=json_decode($result,true);
        return $resultJson;
    }
    public function publish(){
        $params=\request()->input();
        $result=$this->curlRequest($this->apiUrl."leave/index?p=".$params['p']);
        $resultJson=json_decode($result,true);
        return $resultJson;
    }
}
