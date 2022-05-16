<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::_initialize();
    }

    public function index() {
        $indexData=curlRequest($this->apiUrl."index");
        $result=json_decode($indexData,true);
        return $result;
    }

    public function uploadImg(Request $request){

        try {

            if (!$request->hasFile('file')) {
                returnJson(500,'无法获取上传文件');
            }

            $fileData= $request->file('file');

            if ($fileData) {

                //获取文件的扩展名
                $extensionName=$fileData->getClientOriginalExtension();

                //获取文件的绝对路径，但是获取到的在本地不能打开
                $path=$fileData->getRealPath();

                //要保存的文件名 时间+扩展名
                $filename=date('Y-m-d') . '/' . uniqid() .'.'.$extensionName;
                //保存文件   配置文件存放文件的名字 ，文件名，路径
                $bool= Storage::disk('public')->put($filename,file_get_contents($path));
                if(!$bool){
                    $data['status']=500;
                    $data['filepath']='';
                }
                $data['status']=200;
                $data['filepath']=$filename;

                return $data;

            }else{
                returnJson(500,'系统错误～');
            }
        }catch (\Exception $e){
            returnJson(500,'系统错误～');
        }

    }
}
